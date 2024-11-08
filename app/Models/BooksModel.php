<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BooksModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'book_id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'author',
        'price',
        'stock',
        'category_id',
        'sale_id',

    ];

    public function category(){
        return $this -> belongsTo(CategoryModel::class, 'category_id', 'category_id');
    }

    public function sales(){
        return $this->belongsToMany(SalesModel::class, 'book_sale', 'book_id', 'sale_id');
    }
}
