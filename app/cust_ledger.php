<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cust_ledger extends Model
{
    use HasFactory;
    protected $fillable = ['cr','dr','desc','type','sale_id','customer_id','type','isdeleted','adjustment','date'];
}
