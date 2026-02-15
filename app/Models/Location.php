<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Hierarchy;
class Location extends Model
{
    use HasFactory;
    // protected $connection = 'client'; // same as your other tables
    // protected $table = 'locations';
    // protected $fillable = [
    //     'name',
    //     'code',
    //     'address',
    // ];
    protected $guarded = [];
    public function hierarchies()
    {
        return $this->hasMany(Hierarchy::class, 'location_id');
    }
}
