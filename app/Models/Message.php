<?php

namespace App\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory, HasFactory, EncryptableDbAttribute;

    protected $fillable = ['body', 'recipient_id', 'sender_id', 'group_id'];
    protected $encryptable = ['body'];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
