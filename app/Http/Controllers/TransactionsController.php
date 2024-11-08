<?php

namespace App\Http\Controllers;

use App\Models\SalesModel;
use  App\Models\CategoryModel;
use App\Models\BooksModel;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function getBooks(){
        $books = BooksModel::with('category')->get();

        return view('user', compact('books'));
    }

    public function getSearchBooks($title){
        if (empty($title)) {
            return redirect()->back()->with('error', 'Search term cannot be empty.');
        }

        try {
            $books = BooksModel::with('category')->where('title', 'LIKE','%'.$title.'%')->get();
            $cate = CategoryModel::all();

            // Check if books were found
            if ($books->isEmpty()) {
                throw new \Exception('No books with title: ' . $title);
            }

            return view('user', compact('books', 'cate'));
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->back()->with('error', ' ' . $e->getMessage());
        }
    }

    public function getTransactions(){
        $transactions = SalesModel::with('customers', 'books')->get();
        return view('customers', compact('transactions'));
    }

    public function storeTransactions(Request $request){
        $request->validate([
            'customer_id' => 'required',
            'total_price' => 'required',
            'book_ids' => 'required|array', // Expecting an array of book IDs
        ]);

        // Create the sale
        $sale = SalesModel::create([
            'customer_id' => $request->customer_id,
            'total_price' => $request->total_price,
            'sale_date' => now(), // Set the current date
        ]);

        // Attach books to the sale
        $sale->books()->attach($request->book_ids);

        return view('customers', compact('sale'));
    }

    public function updateTransaction(Request $request, $id){
        $request->validate([
            'customer_id' => 'required',
            'total_price' => 'required',
            'book_ids' => 'required|array',
        ]);

        $sale = SalesModel::findOrFail($id);
        $sale->update([
            'customer_id' => $request->customer_id,
            'total_price' => $request->total_price,
            'sale_date' => now(),
        ]);

        // Sync the books
        $sale->books()->sync($request->book_ids);

        return view('customers', compact('sale'));
    }

    public function deleteTransaction($id){
        $sale = SalesModel::findOrFail($id);
        $sale->books()->detach(); // Detach the books before deleting the sale
        $sale->delete();

        return redirect()->back();
    }
}
