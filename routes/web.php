<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\CategoriesController;
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
    
});