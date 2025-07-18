<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method \Laravel\Sanctum\NewAccessToken createToken(string $name, array $abilities = ['*'])
 * @method \Illuminate\Database\Eloquent\Relations\MorphMany tokens()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email_preferences' => 'array',
        ];
    }

    // Relationships
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // Email preference methods
    public function getEmailPreference(string $type): bool
    {
        $preferences = $this->email_preferences ?? [];
        return $preferences[$type] ?? true;
    }

    public function setEmailPreference(string $type, bool $enabled): void
    {
        $preferences = $this->email_preferences ?? [];
        $preferences[$type] = $enabled;
        $this->email_preferences = $preferences;
        $this->save();
    }

    public function canReceiveEmail(string $type): bool
    {
        return $this->getEmailPreference($type) && $this->email;
    }
}
