<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Vinkla\Hashids\Facades\Hashids;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'device_key',
        'agent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', Hashids::decode($value))->first();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function channels()
    {
        return $this->hasMany(SocketChannel::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_user','user_id','friend_id');
    }

    public function groupOwner()
    {
        return $this->hasOne(Group::class, 'owner_id');
    }
}
