<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function sale(){
        return $this -> belongsTo(SalesModel::class, 'customer_id', 'customer_id');
    }
}
