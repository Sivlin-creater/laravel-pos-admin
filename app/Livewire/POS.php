<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Services\ExchangeRateService;

class POS extends Component
{
    //Properties
    public $items;
    public $customers;
    public $paymentMethods;

    public $search = '';
    public $cart = [];

    //Properties for Checkout
    public $customer_id;
    public $payment_method_id;
    public $paid_amount = 0;
    public $discount_amount = 0;

    public function mount() {
        //Load All
        $this->items = Item::whereHas('inventory', function(Builder $query) {
            $query->where('quantity', '>', 0);
        })
            ->with('inventory')
            ->where('status', 'active')
            ->get();

            //Load Customers & Payment Methods
            $this->customers = Customer::all();
            $this->paymentMethods = PaymentMethod::all();
    }

    protected function rate(): float{
        return app(ExchangeRateService::class)->getUsdToKhrRate();
    }
    
    //Computed: Filtered Items or Search
    public function getFilteredItemsProperty() {
        if(!$this->search) {
            return $this->items;
        }

        return $this->items->filter(fn ($item) =>
            str_contains(strtolower($item->name), strtolower($this->search)) ||
            str_contains(strtolower($item->sku), strtolower($this->search))
        );
    }

    //Cart Logic
    public function addToCart($itemId){

        if(!auth()->user()->canSell()) {
            return;
        }

        $rate = $this->rate();
        $item = $this->items->firstWhere('id', $itemId);

        if (!$item) return;

        // Prevent overselling
        $stock = $item->inventory->quantity ?? 0;
        $currentQty = $this->cart[$itemId]['quantity'] ?? 0;

        if ($currentQty >= $stock) {
            session()->flash('error', 'Not enough stock.');
            return;
        }

        $this->cart[$itemId] = [
            'id' => $item->id,
            'name' => $item->name,
            'sku' => $item->sku,
            'price' => $item->selling_price,
            'price_khr' => $item->selling_price * $rate,
            'quantity' => $currentQty + 1,
        ];
    }

    public function removeFromCart($itemId) {
        if(!auth()->user()->canSell()) {
            return;
        }

        unset($this->cart[$itemId]);
    }

    //Total Computed
    public function getSubtotalProperty() {
        return collect($this->cart)->sum(fn($i) =>
            $i['price'] * $i['quantity']
        );
    }
     public function getSubtotalKhrProperty()
    {
        $rate = $this->rate();
        return $this->subtotal * $rate;
    }
    
    public function getTaxProperty() {
        return $this->subtotal * 0.15;
    }
    public function getTaxKhrProperty()
    {
        $rate = $this->rate();
        return $this->tax * $rate;
    }

    public function getTotalBeforeDiscountProperty() {
        return $this->subtotal + $this->tax;
    }

    public function getTotalProperty() {
        return max(
            $this->totalBeforeDiscount - $this->discount_amount,
            0
        );
    }
    public function getTotalKhrProperty()
    {
        $rate = $this->rate();
        return $this->total * $rate;
    }

    public function getChangeProperty() {
        return max(
            $this->paid_amount - $this->total,
            0
        );
    }
    public function getChangeKhrProperty() {
        $rate = $this->rate();
        return $this->change * $rate;
    }

    //Check Out
    public function checkout() {
        if(!auth()->user()->canSell()) {
            abort(403, 'Unauthorized action.');
        }

        if(empty($this->cart)) {
            session()->flash('error', 'Cart is empty.');
            return;
        }
        if(!$this->payment_method_id) {
            session()->flash('error', 'Select payment method.');
            return;
        }
        if($this->paid_amount < $this->total) {
            session()->flash('error', 'Insufficient payment.');
            return;
        }

        DB::transaction(function () {
            //Create Sale
            $sale = Sale::create([
                'customer_id' => $this->customer_id ?: null,
                'payment_method_id' => $this->payment_method_id,
                'subtotal' => $this->subtotal,
                'tax' => $this->tax,
                'discount' => $this->discount_amount,
                'total' => $this->total,
                'paid_amount' => $this->paid_amount,
                'change_amount' => $this->change,
                'user_id' => auth()->id(),
            ]);

            //Save Items
            foreach ($this->cart as $cartItem) {
                SalesItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $cartItem['id'],
                    'selling_price' => $cartItem['price'],
                    'quantity' => $cartItem['quantity'],
                    'total' => $cartItem['price'] * $cartItem['quantity'],
                ]);

                //Deduct Inventory
                // $inventory = Item::find($cartItem['id'])->inventory;
                // $inventory->decrement('quantity', $cartItem['quantity']);
            }
        });

        session()->flash('success', 'Sale completed successfully.');

        //Reset POS
        $this->reset([
            'cart',
            'paid_amount',
            'discount_amount',
            'customer_id',
            'payment_method_id',
        ]);
    }

    public function render()
    {
        return view('livewire.p-o-s');
    }
}
