<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fuelBackup extends Model
{
    use HasFactory;
    protected $fillable = [
        'qty',
        'fqty',
        'desc',
        'pro_id',
        'sku',
        'stock_capacity' ,
        'pur_id'
    ];
}
