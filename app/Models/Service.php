<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['customer_id', 'mechanic_id', 'cashier_id', 'queue_number', 'status', 'total_price'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Mengambil semua sub-tindakan/sub-item dalam satu quest servis ini
    public function details(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }
}