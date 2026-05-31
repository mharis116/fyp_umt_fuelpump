<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchaseItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the purchase that owns the purchaseItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo(purchases::class, 'pur_id', 'id');
    }
}
