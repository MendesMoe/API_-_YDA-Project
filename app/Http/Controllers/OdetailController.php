<?php

namespace App\Http\Controllers;

use App\Models\Odetail;
use Illuminate\Http\Request;

class OdetailController extends Controller
{

    public function index()
    {
        $odetail = Odetail::all();
        return response()->json([
            'status_code' => 200,
            'message' => ' liste des orders',
            'donnees' => $odetail,
        ]);
    }

    //public function create() {}

    //public function store(Request $request) {}

    public function show(int $id)
    {
        $odetail = Odetail::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'details ont été trouvés',
            'tab_firms' => $odetail
        ]);
    }

    public function edit(int $id)
    {
        $odetail = Odetail::whereId($id)->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Edit de odetail',
            'donnees' => $odetail,
        ]);
    }

    public function update(Request $request, $id)
    {
        $odetail = Odetail::findOrFail($id);
        $odetail->update($request->all());
        return response([
            'status_code' => 200,
            'message' => 'mise a jour du odetail',
            'donnees' => $odetail
        ]);
    }

    public function destroy(int $id)
    {
        $odetail = Odetail::findOrFail($id);
        if ($odetail) {
            $odetail->delete();
            return response([
                'status_code' => 200,
                'message' => 'success delete odetail'
            ], 200);
        } else {
            return response([
                'message' => 'The odetail don\'t exist'
            ], 200);
        }
    }
}
