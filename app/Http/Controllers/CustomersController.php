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
        // dd(session());
        return redirect()->back();
        // return response()->json(['customer_id' => $newCustomer->customer_id]);
        // $request -> validate([
        //     'name' => 'required',
        // ]);
        // $findCustomer = CustormersModel::where('name', $request->name)->first();
        // if ($findCustomer)
        // {
        //     $customerId = $findCustomer->customer_id;
        //     return view('checkout', compact('customerId'));
        // }
        // CustomersModel::create([
        //     'name' => $request->name,
        // ]);
        // $latestCustomerid = CustomersModel::latest()->pluck('customer_id')->first();


        // return view('checkout', compact('latestCustomerid'));
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
