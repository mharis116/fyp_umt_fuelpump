<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Hierarchy extends Model
{
    // protected $connection = 'client';
    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'hierarchy_id');
    }


    // public function location()
    // {
    //     return $this->belongsTo(Location::class);
    // }

    public function hierarchyLevel()
    {
        return $this->belongsTo(HierarchyLevel::class);
    }

    public function children()
    {
        return $this->hasMany(Hierarchy::class, 'parent_id')->with(['location', 'children.location']);
    }
    public function parent()
    {
        return $this->belongsTo(Hierarchy::class, 'parent_id');
    }

    public function auditSubmissions()
    {
        return $this->hasMany(AuditSubmission::class);
    }

    public function getDisplayNameAttribute()
    {
        if ($this->location && $this->location->name) {
            return $this->location->name;
        }
        return $this->name ?? 'Unnamed Hierarchy';
    }

}
