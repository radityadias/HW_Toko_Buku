<?php

namespace App\Http\Controllers;

use App\Models\BooksModel;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /* Fungi menampilkan data kategori */
    public function getCategories(){
        $category = CategoryModel::all();

        return view('admin', compact('category'));
    }

    /* Fungsi hapus kategori sesuai category_id yang dipilih */
    public function delCategories($id){
        $cate = CategoryModel::where('category_id', '=', $id);

        $cate -> delete();

        return redirect()->back();
    }

    /* Fungsi menyimpan data kategori ke database */
    public function storeCategories(Request $request){
        $request -> validate([
            'name' => 'required',
        ]);

        CategoryModel::create([
            'name' => $request ->input('name'),
        ]);

        return redirect()->back();
    }

    /* Fungsi update kategori */
    public function updateCategories(Request $request, $id){
        $request -> validate([
            'name' => 'required',
        ]);

        $cate = CategoryModel::findOrFail($id);

        $cate->name = $request->name;

        $cate->save();

        return redirect()->back();
    }
}
