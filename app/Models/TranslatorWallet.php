<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslatorWallet extends Model
{
    protected $fillable = ['user_id', 'balance', 'total_earned'];
}
