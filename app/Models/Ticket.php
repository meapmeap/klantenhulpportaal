<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['titel', 'categorie_id', 'status', 'user_id', 'created_by', 'created_at', 'updated_at'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by'); //gebruiker die de ticket aan maakt
    }

    public function admin(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id'); //admin gebruiker aan wie de ticket is toegewezen
    }

    public function categorie(): BelongsTo {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function reactie(): HasMany {
        return $this->hasMany(Reactie::class, 'ticket_id');
    }

    public function notitie(): HasMany {
        return $this->hasMany(Notitie::class, 'ticket_id');
    }
}
