<?php 

namespace App\Notifications; 

use Illuminate\Bus\Queueable; 
use Illuminate\Notifications\Messages\MailMessage; 
use Illuminate\Notifications\Notification; 

class AccountCreatedNotification extends Notification 
{ 
    use Queueable; 
    
    public function __construct() 
    {
        // 
    } 
    
    public function via(object $notifiable): array 
    { 
        return ['mail']; 
    } 
    
    public function toMail(object $notifiable): MailMessage 
    { 
        return (new MailMessage) 
            ->subject('Je account is aangemaakt') 
            ->greeting('Welkom bij het Klantenhulpportaal!') 
            ->line('Je account is succesvol aangemaakt.') 
            ->line('Je kunt nu inloggen met het e-mailadres en wachtwoord dat je tijdens de registratie hebt opgegeven.') 
            ->action('Naar het Klantenhulpportaal', 'http://127.0.0.1:8000/') 
            ->line('Bedankt voor het registreren.'); 
    } 
}