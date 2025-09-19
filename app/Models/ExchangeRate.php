<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Single Responsibility: Manages exchange rates
 * Open/Closed: Extensible for new currencies
 */
class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency',
        'to_currency', 
        'rate',
        'source',
        'is_active'
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_active' => 'boolean'
    ];

    /**
     * Business Logic: Get current USD to EGP rate
     * Single Responsibility: Only handles current rate retrieval
     */
    public static function getCurrentUsdToEgpRate(): float
    {
        return static::where('from_currency', 'USD')
            ->where('to_currency', 'EGP')
            ->where('is_active', true)
            ->latest()
            ->value('rate') ?? 48.32;
    }
}