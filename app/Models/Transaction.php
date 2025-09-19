<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Single Responsibility: Handles transaction data and transaction logic
 * Open/Closed: Can be extended for new transaction types
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_member_id',
        'to_member_id', 
        'from_account_id',
        'to_account_id',
        'amount',
        'currency',
        'exchange_rate',
        'type',
        'description',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4'
    ];

    /**
     * Relationships following Single Responsibility
     */
    public function fromMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class, 'from_member_id');
    }

    public function toMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class, 'to_member_id');
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    /**
     * Business Logic: Calculate converted amount
     * Interface Segregation: Specific calculation method
     */
    public function getConvertedAmount(): float
    {
        if (!$this->exchange_rate) {
            return $this->amount;
        }

        return $this->currency === 'USD' 
            ? $this->amount * $this->exchange_rate
            : $this->amount / $this->exchange_rate;
    }
}