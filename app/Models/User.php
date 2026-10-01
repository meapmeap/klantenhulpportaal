<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable implements CanResetPassword
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

    public function getEmailForPasswordReset()
    {
        return $this->email_adres;
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email_adres;
    }

    public function sendPasswordResetNotification($token)
    {
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return 'http://127.0.0.1:8000/reset-password?token='
                . $token
                . '&email='
                . urlencode($notifiable->email_adres);
        });

        $this->notify(new ResetPassword($token));
    }
}
