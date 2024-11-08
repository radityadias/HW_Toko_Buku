<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('admin')->group(function()
{
    Route::get('/', [BooksController::class, 'sortBooks']) -> name('books.sort');
    Route::get('/{title}', [BooksController::class, 'getSearchBooks']) -> name('books.search');
    Route::get('/filter/{genre}', [BooksController::class, 'getFilterBooks']) -> name('books.filter');
    Route::post('/books', [BooksController::class, 'storeBooks']) ->name('books.store');
    Route::delete('/books/{book_id}', [BooksController::class, 'delBooks']) -> name('books.delete');  
    Route::put('/books/{book_id}', [BooksController::class, 'updateBooks']) -> name('books.update');  
    Route::delete('/{category_id}', [CategoriesController::class, 'delCategories']) -> name('categories.delete');
    Route::post('/categories', [CategoriesController::class, 'storeCategories']) -> name('categories.store');
});

Route::get('/', [UserController::class, 'index']);