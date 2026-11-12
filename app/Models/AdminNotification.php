<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
   protected $fillable = ['type', 'title', 'message', 'url', 'is_read'];
}
