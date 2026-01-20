<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    /** @use HasFactory<\Database\Factories\SalesItemFactory> */
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'item_id',
        'quantity',
        'selling_price',
        'total',
    ];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }

    protected static function booted() {
        static::created(function ($salesItem) {
            $item = $salesItem->item;
            if($item && $item->inventory) {
                if($item->inventory->quantity >= $salesItem->quantity) {
                    $item->inventory->decrement('quantity', $salesItem->quantity);
                } else {
                    throw new \Exception("Not enough stock for item: {$item->name}");
                }
            }
        });

        static::deleted(function ($salesItem) {
            $item = $salesItem->item;
            if($item && $item->inventory) {
                $item->inventory->increment('quantity', $salesItem->quantity);
            }
        });
    }
}
