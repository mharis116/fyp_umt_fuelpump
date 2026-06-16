<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $fillable = [
    //     'date','invoice_no','cost_amount','retail_amount','desc','total_qty','adjustment','customer_id'
    // ];

    /**
     * Get all of the sales_items for the sales
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales_items()
    {
        return $this->hasMany(sales_items::class, 'sale_id', 'id');
    }
}
