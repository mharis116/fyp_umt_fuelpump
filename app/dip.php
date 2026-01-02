<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dip extends Model
{
    use HasFactory;
    protected $fillable = [
        'pro_id' , 'qty' , 'change_in_qty' , 'date','sighn','desc'
    ];
}
