<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\models\BooksModel;
use app\models\CategoriesModel;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout
    public function showCheckoutPage()
    {
        return view('checkout'); // Tampilkan halaman checkout tanpa data awal
    }

    // Menerima data cart dari JavaScript dan kirimkan ke view
    public function processCheckout(Request $request)
    {
        // Ambil data cart dari request
        $cart = $request->input('cart'); // Data cart dalam bentuk array
        $totalAmount = $request->input('totalAmount');
        // Kembalikan view checkout dengan data cart
        return view('checkout', compact('cart', 'totalAmount'));
    }
    public function decreaseStocks(Request $request){
          // Ambil data keranjang dari request (harus dalam bentuk array)
    $cart = $request->input('cart');

    // Pastikan $cart adalah array dan bukan null
    if (is_array($cart)) {
        // Looping melalui setiap item dalam keranjang
        foreach ($cart as $item) {
            // Ambil id buku dan kuantitas dari item keranjang
            $bookId = $item['book_id']; // Misal, id buku
            $quantity = $item['quantity']; // Kuantitas yang ingin dikurangi dari stok

            // Ambil buku berdasarkan id
            $book = BooksModel::find($bookId);

            if ($book) {
                // Kurangi stok buku
                $book->stock -= $quantity;

                // Pastikan stok tidak kurang dari 0
                if ($book->stock < 0) {
                    $book->stock = 0;
                }

                // Simpan perubahan
                $book->save();
            }

        }
           return response()->json(['message' => 'Stok berhasil dikurangi.'], 200);
    }
         else{
             return response()->json(['message' => 'Data keranjang tidak valid.'], 400);
         }
    }
}
