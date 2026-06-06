<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'skill_level',
        'category',
        'thumbnail',
        'base_price'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class)->orderBy('order', 'asc');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function getThumbnailAttribute($value)
    {
        if ($value) {
            if (str_starts_with($value, 'http')) {
                return $value;
            }
            return asset('storage/' . $value);
        }
        return null;
    }
}