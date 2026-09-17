<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reactie extends Model
{
    use HasFactory;

    protected $table = 'Reacties';

    protected $fillable = [ 'bericht', 'user_id', 'ticket_id', ];

    public function ticket(): BelongsTo { 
        return $this->belongsTo(Ticket::class, 'ticket_id'); 
    }

    public function user(): BelongsTo { 
        return $this->belongsTo(User::class, 'user_id'); 
    }
}
