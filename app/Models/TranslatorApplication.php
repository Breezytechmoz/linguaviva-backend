<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslatorApplication extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'language_requested', 'experience', 'status', 'video_path', 'cv_path', 'cover_letter_path', 'wants_interview', 'status'];

    // ADICIONA ESTA RELAÇÃO
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}