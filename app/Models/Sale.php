<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'payment_method_id',
        'subtotal',
        'tax',
        'total',
        'paid_amount',
        'discount',
        'change_amount',
        'user_id',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }

    public function salesItems(){
        return $this->hasMany(SalesItem::class);
    }
}
