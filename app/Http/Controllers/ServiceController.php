<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = new Service();
        $service->name = $request->name;
        $service->image = $request->image;
        $service->email = $request->email;
        $service->phone = $request->phone;
        $service->description_1 = $request->description_1;
        $service->description_2 = $request->description_2;
        $service->type_id = $request->type_id;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . "." . $extension;

            $destinationPath = public_path('/img/services');
            $requestImage->move($destinationPath, $imageName);

            $service->image = $imageName;
        } else {
            $service->image = null;
        }



        $service->save();

        return response()->json([
            'status_code' => 200,
            "message" => "creation de service réussi",
            "image" => $imageName,
            "services" => $service,

        ], 200);
        //return view('image', compact('imageName'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$service = Service::all();
        $service = Service::whereId($id)->with('products')->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Service et produits associés',
            'donnees' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($request->all());
        return response([
            'status_code' => 200,
            'message' => 'mise a jour du produit réussie',
            'donnees' => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response([
            'status_code' => 200,
            'message' => 'suppression réussie ainsi que les produits associés'
        ], 200);
    }
}
