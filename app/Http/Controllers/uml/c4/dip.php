<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dip extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'pro_id' , 'qty' , 'change_in_qty' , 'date','sighn','desc'
    // ];

    protected $casts = [
        'date' => 'datetime',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $guarded = [];
}
