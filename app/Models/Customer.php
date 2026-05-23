<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'license_plate',
        'motorcycle_type',
        'frame_number',
        'engine_number',
        'engine_capacity'
    ];

    // Riwayat petualangan/servis motor pelanggan di bengkel
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}