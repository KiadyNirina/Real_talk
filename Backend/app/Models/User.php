<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

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
        'is_online',
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
        'password' => 'hashed',
        'is_online' => 'boolean',
    ];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id');
    }

    public function isOnline()
    {
        // Supposons que $lastSeen soit la date sous forme de chaîne
        $lastSeen = $this->last_seen; // ou une autre variable contenant une date
        $lastSeen = Carbon::parse($lastSeen); // Conversion en objet Carbon

        $minutesAgo = $lastSeen->diffInMinutes(now());

        // Si l'utilisateur a été actif dans les 5 dernières minutes, il est considéré comme en ligne
        //return $this->last_seen && $this->last_seen->diffInMinutes(Carbon::now()) <= 5;
        return $minutesAgo <= 1;
    }

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'sender_id');
    }

    public function receivedInvitations()
    {
        return $this->hasMany(Invitation::class, 'receiver_id');
    }
}

