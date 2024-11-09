<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'total_price',
        // 'quantity',
        'customer_id'
    ];

    public function customer(){
        return $this -> belongsTo(CustomersModel::class, 'customer_id', 'customer_id');
    }

    public function books(){
        return $this -> belongsToMany(BooksModel::class, 'book_sale', 'sale_id', 'book_id');
    }
}
