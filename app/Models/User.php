<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone_no', 'password', 'designation', 'salary', 'role', 'user_status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'salary' => 'decimal:2',
    ];

    // Mutator to ensure passwords are always hashed
    public function setPasswordAttribute($value): void
    {
        if ($value && ! str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    public function projects(): HasMany
    {
        return $this->hasMany(UserProject::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
