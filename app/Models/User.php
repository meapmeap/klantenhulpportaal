<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
        'voornaam',
        'achternaam',
        'email_adres',
        'wachtwoord',
        'telefoonnummer',
        'rol',
    ];

    protected $hidden = [
        'wachtwoord',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [ 'wachtwoord' => 'hashed', ];
    }
    
    public function tickets() { 
        return $this->hasMany(Ticket::class, 'created_by');   //user die de ticket heeft aangemaakt
    } 
    
    public function assignedTickets() { 
        return $this->hasMany(Ticket::class, 'user_id');   //admin user die de ticket is toegewezen
    } 
    
    public function reacties() { 
        return $this->hasMany(Reactie::class, 'user_id'); 
    } 
    
    public function notities() { 
        return $this->hasMany(Notitie::class, 'user_id'); 
    }

    public function getAuthPasswordName()
    {
        return 'wachtwoord';
    }

    public function getAuthPassword()
    {
        return $this->wachtwoord;
    }
}
