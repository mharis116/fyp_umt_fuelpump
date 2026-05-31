<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchases extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Get all of the purchase_items for the purchaseItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchase_items()
    {
        return $this->hasMany(purchaseItem::class, 'pur_id', 'id');
    }
}
