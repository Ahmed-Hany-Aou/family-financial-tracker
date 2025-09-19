<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Single Responsibility: Handles Account data and balance operations
 * Open/Closed: Extensible for new account types without modification
 */
class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_member_id',
        'name',
        'type',
        'usd_balance',
        'egp_balance',
        'is_isolated',
        'isolation_until',
        'description'
    ];

    protected $casts = [
        'usd_balance' => 'decimal:2',
        'egp_balance' => 'decimal:2',
        'is_isolated' => 'boolean',
        'isolation_until' => 'date'
    ];

    /**
     * Relationship: Account belongs to a family member
     */
    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }

    /**
     * Relationship: Account has many transactions (as source)
     */
    public function transactionsFrom(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    /**
     * Relationship: Account has many transactions (as destination)
     */
    public function transactionsTo(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    /**
     * Business Logic: Get total balance in USD equivalent
     * Single Responsibility: This method only calculates USD equivalent
     */
    public function getTotalBalanceInUsd(float $exchangeRate = 48.32): float
    {
        return $this->usd_balance + ($this->egp_balance / $exchangeRate);
    }

    /**
     * Business Logic: Check if account can be used for transactions
     * Interface Segregation: Specific method for transaction validation
     */
    public function canTransact(): bool
    {
        if ($this->is_isolated && $this->isolation_until > now()) {
            return false;
        }
        
        return $this->familyMember->is_active;
    }

    /**
     * Business Logic: Check if sufficient balance for withdrawal
     */
    public function hasSufficientBalance(float $amount, string $currency): bool
    {
        return $currency === 'USD' 
            ? $this->usd_balance >= $amount
            : $this->egp_balance >= $amount;
    }
}