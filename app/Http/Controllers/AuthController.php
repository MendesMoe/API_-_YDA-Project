<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use \App\Models\LoginToken;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;

class AuthController extends Controller
{

    // methode d'inscription
    public function newUser(Request $request)
    {

        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|min:8|',

        ]);

        $utilisateur = new User();

        $utilisateur->firstname = $request->firstname;
        $utilisateur->lastname = $request->lastname;
        $utilisateur->birthday = $request->birthday;
        $utilisateur->phone = $request->phone;
        $utilisateur->email =   $request->email;
        $utilisateur->password  = Hash::make('12345678');
        $utilisateur->role = $request->role;
        $utilisateur->firm_id = $request->firm_id;
        $utilisateur->remember_token = Str::random(10);

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $requestImageAvatar = $request->avatar;
            $extension = $requestImageAvatar->extension();
            $imageAvatarName = md5($requestImageAvatar->getClientOriginalName() . strtotime('now')) . "." . $extension;

            $destinationPath = public_path('/img/avatar');
            $requestImageAvatar->move($destinationPath, $imageAvatarName);

            $utilisateur->avatar = $imageAvatarName;
        } else {
            $utilisateur->avatar = null;
        }

        $utilisateur->save();

        return response()->json([
            'msg' => 'Utilisateur creation reussie',
            'status_code' => 200,
            'utilisateur' => $utilisateur
        ]);
    }

    // methode d'authentification

    public function sendMagicLink(Request $request) //update password
    {
        try {
            $data = $request->validate(['email' => ['required', 'email', 'exists:users,email']]);
            $user = User::whereEmail($data['email'])->first();
            $user->sendLoginLink(); //sendCreatePasswordLink()
            //dd($user);
            //return view('auth.login', compact('user'));
            //session()->flash('success', 'Email envoyé');
            //return redirect()->back();

            return response()->json([
                'status_code' => 200,
            ]);
        } catch (Exception $e) {
            return response([
                'status_code' => 500,
                'message' => 'erreur'

            ]);
        }
    }

    public function verifyToken(Request $request, $token)
    {
        $token = LoginToken::whereToken(hash('sha256', $token))->firstOrFail();
        abort_unless($token->isValid(), 401);
        $token->consume();

        //User::login($token->user);
        $user = $token->user;
        Auth::login($token->user);

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'user' => $user,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);

        //return view('auth.login', compact('user'));
    }
    /**
     * Method qui permet de réaliser le login des utilisateurs existants en DB
     * @param Request $request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'email|required',
            'password' => 'required|min:8'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status_code' => 500,
                'message' => 'non autorisé',
                'data' => $credentials,

            ]);
        }

        $user = User::where('email', $request->email)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;
        $role = $user->role;
        $id = $user->id;
        $name = $user->firstname . " " . $user->lastname;


        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'role' => $role,
            'id' => $id,
            'name' => $name,

        ]);
    }

    public function logout() // function qui ne sert a rien pour une API, seulement le token suffit
    {
        Auth::logout();
        return response()->json([
            'status_code' => 200,
            'message' => 'logout',

        ]);
    }

    public function majPassword(Request $request, int $id)
    {
        try {
            $request->validate([

                'password' => [
                    'required',
                    'string',
                    'min:8',
                ]
            ]);
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);

            $user->update();

            return response([
                'status_code' => 200,
                'message' => 'mise a jour du password réussie',
                'donnees' => $user
            ]);
        } catch (Exception $e) {
            return response([
                'status_code' => 500,
                'message' => 'erreur'

            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
}
