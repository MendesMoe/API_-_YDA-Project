<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Odetail;
use App\Models\User;
use App\Models\Product;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        $order = Order::with('odetails')->get();
        return response()->json([
            'status_code' => 200,
            'message' => ' liste des orders',
            'donnees' => $order,
        ]);
    }

    //public function create() {}

    public function store(Request $request)
    {
        //dd($request->user_id);
        //dd($request->products);

        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->comments = 'teste-lundi-midi';
        //$order->firm_id = User::getFirmId($request->user_id);
        $order->status = "en attente";
        $order->save();
        //dd($order);
        $products = $request->products;
        //dd($request->products);
        foreach ($products as $product) {
            $odetail = new Odetail();
            $odetail->product_id = $product['id'];
            $odetail->price_product = Product::getPrice($odetail->product_id);
            $odetail->qtty = $product['quantity'];
            $odetail->order_id = $order->id;
            $odetail->name = $product['name'];
            $odetail->comments = $product['comment'];
            $odetail->total_odetail = $odetail->qtty * $odetail->price_product;

            $odetail->save();
        }

        $order->total = Odetail::where('order_id', $order->id)->sum('total_odetail');
        $order->save();


        return response()->json([
            'status_code' => 200,
            "message" => "new order + odetail ok",
            "order" => $order,
        ], 201);
    }

    public function show($id)
    {

        $order = Order::whereId($id)->with('odetails')->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'orders et odetails ont été trouvés',
            'tab_firms' => $order
        ]);
    }

    public function edit($id)
    {
        $order = Order::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Edit de order',
            'donnees' => $order,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status != "terminée") {
            $order->update($request->all());

            return response([
                'status_code' => 200,
                'message' => 'success update order',
                'donnees' => $order,
            ]);
        } else {
            return response([
                'message' => 'La commande est en cours ou terminée',
                'order->status' => $order->status,
            ]);
        }
    }

    public function destroy($id)
    {

        $order = Order::findOrFail($id);
        $deleted = $order->delete();

        if ($deleted) {
            $odetail = Odetail::where('order_id', $order->id);
            $odetail->delete();
        }
        return response([
            'status_code' => 200,
            'message' => 'suppression réussie ainsi que les odetails associés'
        ], 200);
    }
}
