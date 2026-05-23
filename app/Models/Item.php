<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = ['item_code', 'name', 'stock', 'min_stock', 'purchase_price', 'sell_price'];

    // Mengetahui item ini keluar di detail transaksi mana saja
    public function serviceDetails(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }
}