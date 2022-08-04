<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('Admin')->except(['index', 'show', 'update']);
    }

    public function index()
    {
        $user = User::all();
        return response()->json([
            'status_code' => 200,
            'message' => 'liste des Users',
            'donnees' => $user,
        ]);
    }

    public function show(int $id)
    {
        $user = User::whereId($id)->with('orders.odetails')->get();
        // $user->firm->name

        return response()->json([
            'status_code' => 200,
            'message' => 'Données du user + orders+ odetails',
            'donnees' => $user,
        ]);
    }

    public function getCustomersByCompany($id)
    {
        $users = User::where('firm_id', $id)->has('orders')->with('orders.odetails')->get();
        $res = array();
        $u = $users->filter(function ($item) {
            $ordersOnHold = $item->orders->filter(function ($order) {
                return ($order->status != "terminee" && $order->status != "annule");
            });

            for ($j = 0; $j <= sizeof($item->orders); $j++) {
                if (isset($ordersOnHold[$j])) {
                    $item->orders[$j] = $ordersOnHold[$j];
                } else {
                    unset($item->orders[$j]);
                }
            }

            return !($item->orders->isEmpty());
        });
        //$u->toArray();

        // dd($u);
        //dd($u->toArray());
        if ($u) {
            foreach ($u as $user) {
                # code...
                $res[] = $user;
            }
            return response()->json([
                'status_code' => 200,
                'message' => 'Données des users by company, only with orders - for mobile',
                'donnees' => $res,
            ]);
        }
        return response()->json([
            'status_code' => 404,
            'message' => 'erreur avec getCustomersByCompany'
        ]);
    }

    public function edit(int $id)
    {
        $user = User::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Affichage du user',
            'donnees' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $requestImageAvatar = $request->avatar;
                $extension = $requestImageAvatar->extension();
                $imageAvatarName = md5($requestImageAvatar->getClientOriginalName() . strtotime('now')) . "." . $extension;

                $destinationPath = public_path('/img/avatar');
                $requestImageAvatar->move($destinationPath, $imageAvatarName);

                $user->avatar = $imageAvatarName;
            } else {
                $user->avatar = null;
            }

            $user->update($request->all());

            return response([
                'status_code' => 200,
                'message' => 'mise a jour du profil user réussie',
                'donnees' => $user
            ]);
        } catch (Exception $e) {
            return response([
                'status_code' => 500,
                'message' => 'erreur'

            ]);
        }
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response([
            'status_code' => 200,
            'message' => 'suppression réussie du user'
        ], 200);
    }
}
