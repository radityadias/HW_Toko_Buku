<?php

namespace App\Http\Controllers;

use App\Models\SalesModel;
use  App\Models\CategoryModel;
use App\Models\BooksModel;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /* Fungsi mengambil data buku  */
    public function getBooks(){
        $books = BooksModel::with('category')->get();

        return view('user', compact('books'));
    }

    /* Fungsi mencari data buku sesuai judul */
    public function getSearchBooks($title = null){

          // Trim whitespace and check if the title is empty or null
          $title = trim($title);


        if (empty($title)) {
            return redirect()->back()->with('error', 'Search term cannot be empty.');
        }

        try {
            // Search books by title if it's not empty
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

    /* Fungsi mengambil data penjualan dari tabel sales */
    public function getTransactions(){
        $transactions = SalesModel::with('customers', 'books')->get();
        return view('customers', compact('transactions'));
    }

    /* Fungsi menyimpan data penjualan ke database */
    public function storeTransactions(Request $request){
        $request->validate([
            'customer_id' => 'required',
            'total_price' => 'required',
        ]);

        // Create the sale
        $sale = new SalesModel;

        $sale->customer_id = $request->input('customer_id');
        $sale->total_price = $request->input('total_price');
        $sale->sale_date = now();
        $sale->save();
        $books = $request->input('books');
        $saleIdLocate = SalesModel::find($sale->sale_id);
        $bookData = [];
        foreach ($books as $book) {
        // Tambahkan setiap book_id dan quantity ke array
        $bookData[$book['id_book']] = [
            'quantity' => $book['quantity']
        ];
        }

        $saleIdLocate->books()->attach($bookData);

        return response()->json(['message' => 'Checkout behasil dilakukan'], 200);
    }

    /* Fungsi update data sales */
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

        return view('user', compact('sale'));
    }

    /* Fungsi hapus data sales */
    public function deleteTransaction($id){
        $sale = SalesModel::findOrFail($id);
        $sale->books()->detach(); // Detach the books before deleting the sale
        $sale->delete();

        return redirect()->back();
    }
}
