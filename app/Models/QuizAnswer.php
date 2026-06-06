<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'user_id', 'score', 'answers_data'];

    protected $casts = [
        'answers_data' => 'array',
    ];
}