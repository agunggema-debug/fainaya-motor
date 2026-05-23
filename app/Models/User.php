<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    // Relasi jika user bertindak sebagai mekanik di transaksi servis
    public function mechanicServices(): HasMany
    {
        return $this->hasMany(Service::class, 'mechanic_id');
    }

    // Relasi jika user bertindak sebagai kasir penyelesai transaksi
    public function cashierServices(): HasMany
    {
        return $this->hasMany(Service::class, 'cashier_id');
    }
}