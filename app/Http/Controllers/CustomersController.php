<?php

namespace App\Http\Controllers;

use App\Models\CustomersModel;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index(){
        return view("user");


    }

    public function storeCustomers(Request $request, ){
        $request -> validate([
            'name' => 'required',
        ]);

        CustomersModel::create([
            'name' => $request->name,
        ]);

        return redirect()->back();
    }
    public function delCustomers($id){
        $customers = CustomersModel::where('customer_id', '=', $id);

        $customers -> delete();

        return redirect()->back();
    }

    public function updateCustomers(Request $request, $id){
        $request -> validate([
            'name' => 'required',
        ]);

        $customers = CustomersModel::findOrFail($id);

        $customers->name = $request->name;

        $customers->save();

        return redirect()->back();

    }
}
