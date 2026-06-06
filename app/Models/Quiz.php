<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'course_material_id', 'title', 'duration', 'min_score', 'questions'];

    protected $casts = [
        'questions' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function material()
    {
        return $this->belongsTo(CourseMaterial::class, 'course_material_id');
    }
}