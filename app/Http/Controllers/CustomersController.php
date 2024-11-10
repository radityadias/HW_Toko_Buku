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
        // Check if the customer already exists
        $findCustomer = CustomersModel::where('name', $request->name)->first();
        if ($findCustomer) {
            $customerId = $findCustomer->customer_id;
        // If customer already exists, return the existing customer_id
        }
        // If customer does not exist, create a new one
        else
        {
        $newCustomer = CustomersModel::create([
            'name' => $request->name,
        ]);
        $customerId = $newCustomer->customer_id;
        }
        session(['customerId' => $customerId]);
        // dd($customerId);
        // Return the new customer ID as a JSON response
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
