<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function()
{
    Route::get('/', [BooksController::class, 'sortBooks']) -> name('books.sort');
    Route::get('/{title}', [BooksController::class, 'getSearchBooks']) -> name('books.search');
    Route::post('/books', [BooksController::class, 'storeBooks']) ->name('books.store');
    Route::delete('/books/{book_id}', [BooksController::class, 'delBooks']) -> name('books.delete');
    Route::put('/books/{book_id}', [BooksController::class, 'updateBooks']) -> name('books.update');

    Route::delete('/{category_id}', [CategoriesController::class, 'delCategories']) -> name('categories.delete');
    Route::post('/categories', [CategoriesController::class, 'storeCategories']) -> name('categories.store');
});
Route::prefix('checkout')->group(function(){
Route::get('/', [CheckoutController::class, 'showCheckoutPage'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
// Route::post('/reduce-stocks', [CheckoutController::class, 'reduceStock'])->name('reduce.stock');
});
