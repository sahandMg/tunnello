<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Vinkla\Hashids\Facades\Hashids;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'username',
        'phone',
        'password',
        'agent_id',
        'easy_token'
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

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
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function getIdAttribute($val) {
        return $val;
    }
}
