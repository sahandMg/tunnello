<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory, HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
