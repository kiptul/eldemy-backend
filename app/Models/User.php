<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'username',
        'email',
        'password',
        'role',
        'google_id',
        'apple_id',
        'avatar_url',
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
        ];
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getAvatarUrlAttribute($value)
    {
        if (!$value) {
            return null; // Akan fallback ke default avatar di frontend
        }

        // Jika sudah berupa URL lengkap (misalnya dari Google), kembalikan apa adanya
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        $host = request()->getSchemeAndHttpHost();

        // Format baru: uploads/avatars/filename.jpg (langsung di folder public)
        if (str_starts_with($value, 'uploads/')) {
            return $host . '/' . $value;
        }

        // Format lama: avatars/filename.jpg (di storage, lewat API endpoint)
        return $host . '/api/user/avatar/' . basename($value);
    }
}
