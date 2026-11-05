<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = ['translator_id', 'language_id', 'source_word', 'translated_word', 'status'];

    // ADICIONA ESTAS DUAS RELAÇÕES
    public function translator()
    {
        return $this->belongsTo(User::class, 'translator_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

        public function revisions()
    {
        return $this->hasMany(TranslationRevision::class);
    }
}
