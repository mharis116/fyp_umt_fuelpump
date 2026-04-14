<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sup_ledger extends Model
{
    use HasFactory;
    protected $fillable = ['cr','dr','desc','type','pur_id','sup_id','type','isdeleted','adjustment','date'];
}
