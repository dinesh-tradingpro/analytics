<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizedEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'added_by_email',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Check if an email address is authorized and active
     */
    public static function isAuthorized(string $email): bool
    {
        return static::where('email', $email)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get all active authorized emails
     */
    public static function getActiveEmails(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)
            ->orderBy('email')
            ->get();
    }
}
