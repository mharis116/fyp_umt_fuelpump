<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenses extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','exp_type_id','desc','date' 
    ];
}
