<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HierarchyLevel extends Model
{
    // protected $connection = 'client';
    protected $guarded = [];


    /**
     * Get the next level dynamically
     */
    public function next()
    {
        return self::where('level', '>', $this->level)->orderBy('level', 'asc')->first();
    }

    /**
     * Get the previous level dynamically
     */
    public function previous()
    {
        return self::where('level', '<', $this->level)->orderBy('level', 'desc')->first();
    }

    public static function dropdown(){
        return self::get();
    }

}
