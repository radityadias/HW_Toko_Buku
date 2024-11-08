<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\BooksModel;
use App\models\CategoriesModel;

class CheckoutController extends Controller
{
    public function showCheckoutPage()
    {
        return view('checkout');     }

    public function processCheckout(Request $request)
    {
        $cart = $request->input('cart', []);
        $totalAmount = $request->input('totalAmount', 0);

        if (empty($cart)) {
            return view('checkout', [
                'cart' => [],
                'totalAmount' => 0
            ]);
        }

        // Proses checkout normal
        return view('checkout', [
            'cart' => $cart,
            'totalAmount' => $totalAmount
        ]);
    }
   public function reduceStock(Request $request){
    try {
        $cart = $request->input('cart');

        if (!is_array($cart) || empty($cart)) {
            return response()->json(['message' => 'Data keranjang tidak valid.'], 400);
        }

        DB::beginTransaction();

        foreach ($cart as $item) {
            // Validasi input
            // $validator = Validator::make($item, [
            //     'id_buku' => 'required|exists:books,id',
            //     'quantity' => 'required|integer|min:1'
            // ]);

            // if ($validator->fails()) {
            //     DB::rollBack();
            //     return response()->json([
            //         'message' => 'Validasi gagal',
            //         'errors' => $validator->errors()
            //     ], 400);
            // }

            $bookId = $item['book_id'];
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

        return response()->json(['message' => 'Stok berhasil dikurangi.'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
