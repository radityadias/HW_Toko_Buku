<?php

namespace App\Http\Controllers;

use App\Models\CustomersModel;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index(){
        return view("user");
    }

    public function storeCustomers(Request $request){
        $request -> validate([
            'name' => 'required',
        ]);

        CustomersModel::create([
            'name' => $request->name,
        ]);

        return redirect('checkout');
    }
}
