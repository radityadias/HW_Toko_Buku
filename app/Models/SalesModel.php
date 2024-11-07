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
        'total_price'
    ];

    public function customer(){
        return $this -> belongsTo(CustomersModel::class);
    }
}
