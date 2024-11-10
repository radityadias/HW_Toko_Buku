<?php

namespace App\Http\Controllers;

use App\Models\BooksModel;
use App\Models\CategoryModel;
use App\Models\SalesModel;
use App\Models\CustomersModel;
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
        $transactions = SalesModel::with('customer', 'books')->get();
        $customers = CustomersModel::all();

        $cate = CategoryModel::all();
        $transactions = SalesModel::with('customer', 'books')->get();

        if ($sortColumn == 'category_name') {
            $books = BooksModel::join('categories', 'books.category_id', '=', 'categories.category_id')
                ->orderBy('name', $sortDirection)
                ->select('books.*')
                ->get();
        } else {
            $books = BooksModel::orderBy($sortColumn, $sortDirection)->get();
        }
        return view('admin', compact('books', 'cate', 'transactions', 'customers'));
    }

    public function getSearchBooks($title){

        $title = trim($title ?? '');

        if (empty($title)) {
            return redirect()->back()->with('error', 'Search term cannot be empty.');
        }

        try {
            $books = BooksModel::with('category')->where('title', 'LIKE','%'.$title.'%')->get();
            $cate = CategoryModel::all();
            $transactions = SalesModel::with('customer', 'books')->get();
            $customers = CustomersModel::all();
            // Check if books were found
            if ($books->isEmpty()) {
                throw new \Exception('No books with title: ' . $title);
            }

            return view('admin', compact('books', 'cate', 'transactions', 'customers'));
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->back()->with('error', ' ' . $e->getMessage());
        }
    }

    public function getFilterBooks($genre){

        if (empty($genre)) {
            throw new \Exception('No books with Genre: ' . $genre);
        }
        try{
        $books = BooksModel::with('category')->where('category_id', '=', $genre)->get();
        $cate = CategoryModel::all();
        $transactions = SalesModel::with('customer', 'books')->get();
        $customers = CustomersModel::all();
            return view('admin', compact('books', 'cate', 'transactions', 'customers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', ' ' . $e->getMessage());
        }
    }

    public function delBooks($id){
        $books = BooksModel::where('book_id', $id);

        $books -> delete();

        return redirect()->back();
    }

    public function storeBooks(Request $request){
        try {
            $request->validate([
                'title' => 'required',
                'author' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required',
                'category_id' => 'required',
            ], [
                'title.required' => 'The title field is required.',
                'author.required' => 'The author field is required.',
                'price.required' => 'The price field is required.',
                'price.numeric' => 'The price must be a number.',
                'stock.required' => 'The stock field is required.',
                'stock.integer' => 'The stock must be an integer.',
                'category_id.required' => 'The category field is required.',
                'category_id.exists' => 'The selected category is invalid.',
            ]);

            BooksModel::create([
                'title' => $request->input('title'),
                'author' => $request->input('author'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'category_id' => $request->input('category_id'),
            ]);

            return redirect()->back()->with('success', 'Book added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the book. Please try again.');
        }
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
