<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Admin;

use App\Models\Firm;
use App\Models\User;
use Illuminate\Http\Request;
use \App\Http\Middleware;
use App\Http\Requests\StoreFirm;

class FirmController extends Controller
{
    public function __construct()
    {
        //$this->middleware('admin')->only(['store', 'destroy']);
        //$this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
    }
    /*
    * Method qui permet d'afficher toutes les entreprises existantes (Desktop) et seulement celles dont les utilisateurs possedent une commande en attente (Mobile)
    */
    public function index()
    {
        // Réponse différente pour Desktop et APP Mobile. Soucis d'optimization
        //if ($this->device === "desktop") {
        //  $firms = Firm::with('users.orders.odetails')->get();
        //return  response()->json($firms, 200);
        //}
        //$firms = Firm::has('users.orders')->get();

        $firms = Firm::has('users.orders')->with('users.orders')->get();

        foreach ($firms as $firm) {
            //pour chaque firm
            $usersWithoutOrders = [];
            for ($m = 0; $m < $firm->users->count(); $m++) {
                //pour chaque user
                $ordersOnHold = $firm->users[$m]->orders->filter(function ($order) {
                    return ($order->status != "terminee" && $order->status != "annule");
                });

                for ($j = 0; $j < $firm->users[$m]->orders->count(); $j++) {
                    if (isset($ordersOnHold[$j])) {
                        $firm->users[$m]->orders[$j] = $ordersOnHold[$j];
                    } else {
                        unset($firm->users[$m]->orders[$j]);
                    }
                }

                if ($firm->users[$m]->orders->isEmpty()) {

                    $usersWithoutOrders[] = $m;
                }
            }
            foreach ($usersWithoutOrders as $index) {
                unset($firm->users[$index]);
            }
        }

        return  response()->json([$firms, 200, "funcao index"]); //Dois-je envoyer la réponse en json ?
    }

    public function store(StoreFirm $request)
    {
        $input = $request->all();
        $firm = Firm::create($input);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $requestImage = $request->logo;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . "." . $extension;

            $destinationPath = public_path('/img/logos');
            $requestImage->move($destinationPath, $imageName);

            $firm->logo = $imageName;
        } else {
            $firm->logo = null;
        }
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImageNews = $request->image;
            $extension = $requestImageNews->extension();
            $imageNewsName = md5($requestImageNews->getClientOriginalName() . strtotime('now')) . "." . $extension;

            $destinationPath = public_path('/img/news');
            $requestImageNews->move($destinationPath, $imageNewsName);

            $firm->image = $imageNewsName;
        } else {
            $firm->image = null;
        }

        $firm->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Firm crée',
            'donnees' => $firm,
        ]);
    }
    /*
    * Method qui permet d'afficher une entreprise avec ses relations
    */
    public function show($id)
    {
        if ($this->device === "desktop") {
            $firm = Firm::whereId($id)
                ->with('users.orders.odetails')
                ->get();

            return response()->json([
                'status_code' => 200,
                'message' => 'La firm, users, orders et odetails ssociés ont été trouvés',
                'tab_firms' => $firm,
                'DEVICE' => $this->device,
            ]);
        }
        $firm = Firm::where('id', $id)
            ->with('users')
            ->has('users.orders')
            ->get();

        return  response()->json($firm, 200);
    }

    public function edit($id)
    {
        $firm = Firm::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Affichage du user',
            'donnees' => $firm,
        ]);
    }

    public function update(StoreFirm $request, $id)
    {
        $firm = Firm::findOrFail($id);

        $firm->update($request->all());

        return response([
            'status_code' => 200,
            'message' => 'mise a jour de la firm réussie',
            'donnees' => $firm
        ]);
    }

    public function destroy($id)
    {
        $firm = Firm::findOrFail($id);
        $deleted = $firm->delete();

        if ($deleted) {
            $user = User::where('firm_id', $firm->id);
            $user->delete();
        }

        return response([
            'status_code' => 200,
            'message' => 'suppression réussie'
        ], 200);
    }
}
