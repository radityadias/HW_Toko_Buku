<?php

namespace App\Http\Controllers;

use App\Models\BooksModel;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getCategories(){
        $category = CategoryModel::all();

        return view('admin', compact('category'));
    }

    public function delCategories($id){
        $cate = CategoryModel::where('category_id', '=', $id);

        $cate -> delete();

        return redirect()->back();
    }

    public function storeCategories(Request $request){
        $request -> validate([
            'name' => 'required',
        ]);

        CategoryModel::create([
            'name' => $request ->input('name'),
        ]);

        return redirect()->back();
    }

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
