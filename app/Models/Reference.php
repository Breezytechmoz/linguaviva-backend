<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = ['user_id', 'reviewer_id', 'content'];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
