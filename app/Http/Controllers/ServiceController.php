<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;
use \App\Http\Middleware;

class ServiceController extends Controller
{
    public function __construct()
    {
        //$this->middleware('admin')->only('show', 'edit');
    }

    public function index()
    {
        $service = Service::all();
        return response()->json([
            'status_code' => 200,
            'message' => ' liste des services',
            'donnees' => $service,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Illuminate\Http\Request $request)
    {
        $request->validate([

            'name' => 'required|unique:services|max:99',
            'description' => 'string',
            'price' => 'numeric',
            'image' => 'dimensions:min_width=100,min_height=200',
        ]);

        $service = new Service();
        $service->name = $request->name;
        $service->image = $request->image;
        $service->email = $request->email;
        $service->phone = $request->phone;
        $service->description_1 = $request->description_1;
        $service->description_2 = $request->description_2;
        $service->type_id = $request->type_id;

        $service->status = $request->status;

        //////// SAVE A IMAGE ////////

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

    public function show($id)
    {
        $service = Service::whereId($id)->with('products')->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Service et produits associés',
            'donnees' => $service,
        ]);
    }

    public function edit($id)
    {
        $service = Service::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Edit du service',
            'donnees' => $service,
        ]);
    }

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

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $deleted = $service->delete();

        if ($deleted) {
            $product = Product::where('service_id', $service->id);
            $product->delete();
        }
        return response([
            'status_code' => 200,
            'message' => 'suppression réussie ainsi que les produits associés'
        ], 200);
    }
}
