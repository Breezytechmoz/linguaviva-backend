<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
           public function users()
                {
                    // O segundo argumento 'user_languages' resolve o problema
                    return $this->belongsToMany(User::class, 'user_languages')
                                ->withPivot('type', 'level')
                                ->withTimestamps();
                }
}
