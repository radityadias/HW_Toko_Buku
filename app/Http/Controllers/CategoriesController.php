<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getCategories(){
        $cate = CategoryModel::all();

        return view('admin', compact('cate'));
    }
}
