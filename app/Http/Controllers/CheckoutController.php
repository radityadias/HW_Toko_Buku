<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\BooksModel;
use App\models\CategoriesModel;

class CheckoutController extends Controller
{

    /* Fungsi menampilkan data data buku */
    public function showCheckoutPage()
    {
        $books = BooksModel::all();
        return view('checkout', compact('books'));     }

    /* Fungsi melempar data ke view Checout */
    public function processCheckout(Request $request)
    {
        $books = BooksModel::all();
        $cart = $request->input('cart', []);
        $totalAmount = $request->input('totalAmount', 0);

        if (empty($cart)) {
            return view('checkout', [
                'cart' => [],
                'totalAmount' => 0,
                'books' => $books
            ]);
        }

        // Proses checkout normal
        return view('checkout', [
            'cart' => $cart,
            'totalAmount' => $totalAmount,
            'books' => $books
        ]);
    }

    /* Funsi mengurangi stok sesuai buku yang dibeli */
   public function reduceStock(Request $request){
    try {
        if (!$request->customerId)
        {
            return response()->json([
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage()
        ], 500);
        }
        $cart = $request->input('cart');

        if (!is_array($cart) || empty($cart)) {
            return response()->json(['message' => 'Data keranjang tidak valid.'], 400);
        }

        DB::beginTransaction();

        foreach ($cart as $item) {
            // Validasi input
            $validator = Validator::make($item, [
                'id_book' => 'required|exists:books,book_id',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            $bookId = $item['id_book'];
            $quantity = $item['quantity'];

            // Gunakan pessimistic locking untuk menghindari race condition
            $book = BooksModel::lockForUpdate()->find($bookId);

            if (!$book) {
                DB::rollBack();
                return response()->json(['message' => "Buku dengan ID $bookId tidak ditemukan."], 404);
            }

            // Cek stok cukup
            if ($book->stock < $quantity) {
                DB::rollBack();
                return response()->json([
                    'message' => "Stok buku {$book->title} tidak mencukupi.",
                    'available_stock' => $book->stock
                ], 400);
            }

            // Kurangi stok
            $book->stock -= $quantity;
            $book->save();
        }

        // Commit transaksi
        DB::commit();

        return response()->json(['message' => 'Checkout behasil dilakukan'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
