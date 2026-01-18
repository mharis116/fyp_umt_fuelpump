<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exp_type extends Model
{
    use HasFactory;
    protected $fillable = ['name','type','desc'];
}
