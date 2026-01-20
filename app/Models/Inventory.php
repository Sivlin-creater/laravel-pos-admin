<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'item_id',
        'quantity',
    ];

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function getStockStatusAttribute(): string {
        $qty = $this->quantity ?? 0;

        return match(true){
            $qty === 0 => 'Out of Stock',
            $qty <= 10 => 'Low Stock',
            default => 'In Stock',
        };
    }
}

