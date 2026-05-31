<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales_items extends Model
{
    use HasFactory;

    /**
     * Get the sales that owns the sales_items
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sales()
    {
        return $this->belongsTo(sales::class, 'sale_id', 'id');
    }
}
