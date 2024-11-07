<?php

namespace App\Http\Controllers;

use App\Models\BooksModel;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    // public function getBooks(){
    //     $books = BooksModel::with('category')->get();
    //     $cate = CategoryModel::all();


    //     return view('admin', compact('books', 'cate'));
    // }

    public function sortBooks(Request $request){
        $sortColumn = $request->input('sort_by', 'title');
        $sortDirection = $request->input('sort_direction', 'asc');
        $cate = CategoryModel::all();

        if ($sortColumn == 'category_name') {
            $books = BooksModel::join('categories', 'books.category_id', '=', 'categories.category_id')
                ->orderBy('name', $sortDirection)
                ->select('books.*')
                ->get();
        } else {
            $books = BooksModel::orderBy($sortColumn, $sortDirection)->get();
        }
        return view('admin', compact('books', 'cate'));
    }

    public function getSearchBooks($title){
        $books = BooksModel::with('category')->where('title', '=', $title)->get();
        $cate = CategoryModel::all();

        return view('admin', compact('books', 'cate'));
    }

    public function delBooks($id){
        $books = BooksModel::where('book_id', $id);

        $books -> delete();

        return redirect()->back();
    }

    public function storeBooks(Request $request){
        $request -> validate([
            'title' => 'required',
            'author' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
        ]);

        BooksModel::create([
            'title' => $request ->input('title'),
            'author' => $request ->input('author'),
            'price' => $request ->input('price'),
            'stock' => $request ->input('stock'),
            'category_id' => $request ->input('category_id'),
        ]);

        return redirect()->back();
    }

    public function updateBooks(Request $request, $id){
        $request -> validate([
            'title' => 'required',
            'author' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
        ]); 

        $books = BooksModel::findOrFail($id);

        $books->title = $request->title;
        $books->author = $request->author;
        $books->price = $request->price;
        $books->stock = $request->stock;
        $books->category_id = $request->category_id;

        $books->save();

        return redirect()->back();

    }
}
