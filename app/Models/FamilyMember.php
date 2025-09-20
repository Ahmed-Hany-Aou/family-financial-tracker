<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;


/**
 * Single Responsibility: This model only handles FamilyMember data and relationships
 * Open/Closed: Can be extended without modifying existing code
 */
class FamilyMember extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'position_id',
        'permission_level',
        'password',
        'is_active',
        'family_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: A family member can have many accounts
     * Following Single Responsibility - each method has one purpose
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'family_member_id');
        
    }

    /**
     * Relationship: A family member can have many transactions (sent)
     */
    public function transactionsSent(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_member_id');
    }

    /**
     * Relationship: A family member can have many transactions (received)
     */
    public function transactionsReceived(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_member_id');
    }

    /**
     * Business Logic: Check if member can perform write operations
     * Interface Segregation: Specific method for specific functionality
     */
    public function canWrite(): bool
    {
        return $this->permission_level === 'read_write' && $this->is_active;
    }

    /**
     * Business Logic: Check if member is father
     */
    public function isFather(): bool
    {
        return $this->position_id === 1; // father position ID
    }

    /**
     * Relationship: Family member belongs to a family
     */
    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }

    /**
     * Relationship: Family member belongs to a position
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function role()
{
    return $this->belongsTo(Role::class, 'role_id');
}
    public function canBeDeleted(): bool
{
    return $this->accounts()->count() === 0;
}
}