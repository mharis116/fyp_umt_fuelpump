<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'date','invoice_no','cost_amount','retail_amount','desc','total_qty','adjustment','customer_id'
    ];
}
