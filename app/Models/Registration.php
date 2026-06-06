<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $fillable = [
        'training_id',
        'client_id',
        'seats',
        'amount',
        'status',
        'notes',
        'bundle_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function getAmountPaidAttribute(): int
    {
        return (int) $this->payments()->where('status', 'completed')->sum('amount');
    }

    public function getBalanceDueAttribute(): int
    {
        return max(0, (int) $this->amount - $this->amount_paid);
    }

    public function getPaymentStatusAttribute(): string
    {
        $paid = $this->amount_paid;
        if ($paid === 0) {
            return 'unpaid';
        }
        if ($paid >= (int) $this->amount) {
            return 'paid';
        }
        return 'partial';
    }
}