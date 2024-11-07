<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function books(){
        return $this -> hasMany(BooksModel::class, 'category_id', 'category_id');
    }
}
