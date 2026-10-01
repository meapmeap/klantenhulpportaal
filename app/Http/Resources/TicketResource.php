<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titel' => $this->titel,

            'categorie_id' => $this->categorie_id,
            'categorie' => $this->categorie?->naam,

            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'created_by' => $this->created_by,
            'creator' => $this->user ? $this->user->voornaam . ' ' . $this->user->achternaam : null,
            
            'user_id' => $this->when(
                $request->user()->rol === 'admin',
                $this->user_id
            ),

            'admin' => $this->when(
                $request->user()->rol === 'admin',
                $this->admin ? $this->admin->voornaam . ' ' . $this->admin->achternaam : null
            ),
        ];
    }
}
