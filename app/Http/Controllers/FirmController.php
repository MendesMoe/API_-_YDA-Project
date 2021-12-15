<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use Illuminate\Http\Request;

class FirmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $firms = Firm::all();
        return  response()->json($firms, 200);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
        $firm = new Firm();
        $firm->name = $request->name;
        $firm->address = $request->address;
        $firm->phone = $request->phone;
        $firm->email = $request->email; //email du manager
        $firm->color = $request->color;
        $firm->siret = $request->siret;
        $firm->subscription = $request->subscription;

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

        $firm->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Firm crée',
            'donnees' => $firm,
        ]);
    }
    public function show($id)
    {
        $firm = Firm::whereId($id)->with('users.orders.odetails')->get();
        //$firm = Firm::findOrFail($id);

        return response()->json([
            'status_code' => 200,
            'message' => 'La firm, users, orders et odetails ssociés ont été trouvés',
            'tab_firms' => $firm,
        ]);
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

    public function update(Request $request, $id)
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
        //
        $firm = Firm::findOrFail($id);
        $firm->delete();

        return response([
            'status_code' => 200,
            'message' => 'suppression réussie'
        ], 200);
    }
}
