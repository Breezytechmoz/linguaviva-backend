<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceRoom extends Model
{
    use HasFactory;
    
  
    protected $fillable = ['title', 'description', 'host_id', 'language_id', 'is_active'];


    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function participants()
    {
        return $this->hasMany(\App\Models\RoomParticipant::class, 'room_id');
    }
}