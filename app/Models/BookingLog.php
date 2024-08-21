<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'action',
        'changes',
        'user_id',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    /**
     * Get the booking that owns the log.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who made the change.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
