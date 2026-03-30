<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_banned'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_banned' => 'boolean',
    ];

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isBanned(): bool {
        return $this->is_banned;
    }
}
