<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'passenger_name',
        'passenger_email',
        'number_seats',
        'total_cost',
        'status',
    ];

    /**
     * Get the flight that owns the booking.
     */
    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    /**
     * Get the logs for the booking.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(BookingLog::class);
    }

    /**
     * Log changes to the booking.
     */
    public function logChange(string $action, array $changes = []): void
    {
        $this->logs()->create([
            'action' => $action,
            'changes' => json_encode($changes),
            'user_id' => auth()->id() ?? null,
        ]);
    }
}
