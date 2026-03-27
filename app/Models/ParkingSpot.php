<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model {
    use HasFactory;

    protected $fillable = ['name', 'location', 'status', 'price_per_hour', 'type'];

    // Relatie: een parkeerplaats heeft veel reserveringen
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    // Scope: alleen beschikbare plekken
    public function scopeAvailable($query) {
        return $query->where('status', 'beschikbaar');
    }

    public function isBeschikbaar(): bool {
        return $this->status === 'beschikbaar';
    }
}
