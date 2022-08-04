<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status_code' => 200,
            'message' => 'All Products',
            'donnees' => $products,
        ]);
    }
    //public function create(){}

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'description' => 'string',
            'price' => 'numeric',
            'service_id' => 'integer'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->image = $request->image;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->service_id = $request->service_id;

        ///// SAVE A IMAGE ////

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . "." . $extension;

            $destinationPath = public_path('/img/services');
            $requestImage->move($destinationPath, $imageName);

            $product->image = $imageName;
        } else {
            $product->image = null;
        }

        $product->save();

        return response()->json([
            'status_code' => 200,
            "message" => "Success - Product created",
            "produits" => $product,
        ], 201);
    }

    public function show(int $id)
    {
        $product = Product::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Success - Product found',
            'donnees' => $product
        ]);
    }

    public function edit(int $id)
    {
        $product = Product::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Edit product',
            'donnees' => $product,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string',
            'price' => 'numeric',
            'status' => 'in:actif, inactif|string',
            'service_id' => 'integer'
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response([
            'status_code' => 200,
            'message' => 'Product updated',
        ]);
    }

    public function destroy(int $id)
    {

        $product = Product::findOrFail($id);
        if ($product) {
            $product->delete();
            return response([
                'status_code' => 200,
                'message' => 'success delete product'
            ], 200);
        } else {
            return response([
                'message' => 'The product don\'t exist'
            ], 200);
        }
    }
}
