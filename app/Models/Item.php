<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'sku',
        'original_price',
        'selling_price',
        'status',
    ];

    public function inventory(){
        return $this->hasOne(Inventory::class);
    }

    public function saleItems(){
        return $this->hasMany(SalesItem::class);
    }

    public function getStockStatusAttribute(): string {
        $inventory = $this->inventory;
        if(!$inventory || $inventory->quantity === 0){
            return 'Out of Stock';
        } else if ($inventory->quantity <= 5){
            return 'Low Stock';
        }
        return 'In Stock';
    }

    public function getImageUrlAttribute(): string {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/no-image.png');
    }
}

