<?php

namespace App\Http\Controllers;

use App\Models\CustomersModel;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    
    /* Fungsi simpan data user ke database */
    public function storeCustomers(Request $request, ){

        $request->validate([
        'name' => 'required',
        ]);
            $findCustomer = CustomersModel::where('name', $request->name)->first();
        if ($findCustomer) {
            $customerId = $findCustomer->customer_id;
        }
        else
        {
            $newCustomer = CustomersModel::create([
            'name' => $request->name,
        ]);
            $customerId = $newCustomer->customer_id;
        }
        
        session()->flash('customerId', $customerId);
        return redirect()->back();
    }

    /* Fungsi delete user seuai customer_id yang dipilih */
    public function delCustomers($id){
        $customers = CustomersModel::where('customer_id', '=', $id);

        $customers -> delete();

        return redirect()->back();
    }

    /* Fungsi update data user */
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
