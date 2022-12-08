<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Order;
use App\Models\Odetail;
use App\Models\User;
use App\Models\Product;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;

class OrderController extends Controller
{

    public function index()
    {
        $order = Order::with('odetails')->get();

        return response()->json([
            'status_code' => 200,
            'message' => ' liste des orders with odetails',
            'donnees' => $order,
        ]);
    }

    public function store(Request $request)
    {
        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->comments = 'livraison au bureau';
        //$order->firm_id = User::getFirmId($request->user_id);
        //$order->firm_id = $request->user()->firm_id;
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

        if ($order->status != Order::TERMINEE && $order->status != Order::ANNULEE) {

            $order->update($request->all());

            return response([
                'status_code' => 200,
                'message' => 'success update order',
                'donnees' => $order,
            ]);
        } else {
            return response([
                'message' => 'La commande est terminée ou annulé',
                'order->status' => $order->status,
            ]);
        }
    }
    /**
     * @param Request $request
     */
    public function changeStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);

        $success = $order->update([
            'status' => $request->newStatus
        ]);

        if ($success) {
            return response()->json([
                'status_code' => 200,
                'message' => 'success update order',
                'newStatus' => $order->status,
            ], 201);
        }
        return response()->json([
            'status_code' => 400,
            'message' => 'Le status n\'a pas pu etre modifiée',
            'orderstatus' => $order->status,
        ], 404);
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

    public function getCATotalEnEuros()
    {
        $totalEuros = Order::sum('total');

        return response([
            'status_code' => 200,
            'message' => 'Le CA total en Euros',
            'total' => $totalEuros
        ], 200);
    }

    public function getOrdersQtty()
    {
        $totalOrders = Order::count();

        return response([
            'status_code' => 200,
            'message' => 'La quantité de commandes',
            'total' => $totalOrders
        ], 200);
    }

    public function getCAPreviousMonth()
    {
        $previousMonth = $this->getPreviousMonth();

        $caPreviousMonth = Order::whereMonth('created_at', '=', $previousMonth)
            ->sum('total');

        return response([
            'status_code' => 200,
            'message' => 'Le CA pour le mois precedent',
            'total' => $caPreviousMonth
        ], 200);
    }

    private function getPreviousMonth()
    {
        return date("m", strtotime("first day of previous month"));
    }

    public function getCAByMonth()
    {
        $mois = [
            "01" => "Janvier",
            "02" => "Fevrier",
            "03" => "Mars",
            "04" => "Avril",
            "05" => "Mai",
            "06" => "Juin",
            "07" => "Juillet",
            "08" => "Aout",
            "09" => "Septembre",
            "10" => "Octobre",
            "11" => "Novembre",
            "12" => "Decembre"
        ];

        $caResult = [];
        foreach ($mois as $key => $value) {
            $caResult[$value] = Order::whereMonth('created_at', '=', $key)
                ->sum('total');
        }
        return $caResult;
    }

    public function getCAByCompany()
    {
        $caByFirm = [];
        $firms = Firm::select('name', 'id')->distinct()->get();

        //dd($firms);

        foreach ($firms as $firm) {
            $caByFirm[$firm->name] = Order::where('id', $firm->id)
                ->sum('total');
        }

        return $caByFirm;
    }

    public function teste()
    {
        $firms = Firm::all();

        foreach ($firms as $firm) {
            $next = Firm::where('id', '>', $firm->id)
                ->oldest('id')
                ->first();

            ($next) ? $firm->next = $next->id : $firm->next = null;


            $previous = Firm::where('id', '<', $firm->id)
                ->latest('id')
                ->first();

            ($previous) ? $firm->previous = $previous->id : $firm->previous = null;
        }

        return view("teste", compact("firms"));
    }
}
