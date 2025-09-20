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
        'phone',
        'dob',
        'gender',
        'personal_id',
        'photo',
        'address',
        'emergency_name',
        'emergency_phone',
        'notes',
        'position_id',
        'role_id',  // This was missing
        'permission_level',
        'password',
        'is_active',
        'family_id',
        'email_verified_at',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
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

    /**
     * Relationship: Family member belongs to a role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Business Logic: Check if member can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->accounts()->count() === 0;
    }

    /**
     * Get the member's full name with position
     */
    public function getFullNameWithPositionAttribute(): string
    {
        return $this->name . ' (' . $this->position?->name . ')';
    }

    /**
     * Get avatar URL or generate one
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Scope: Get only active members
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get members by family
     */
    public function scopeByFamily($query, $familyId)
    {
        return $query->where('family_id', $familyId);
    }
}