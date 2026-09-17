<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'Categorieen';

    protected $fillable = ['naam', 'beschrijving'];

    public function tickets(): HasMany{
        return $this->hasMany(Ticket::class, 'categorie_id');
    }
}
