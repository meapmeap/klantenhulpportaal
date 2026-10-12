<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bericht' => $this->bericht,
            'user_id' => $this->user_id,
            'user' => $this->user->voornaam . ' ' . $this->user->achternaam,
            'ticket_id' => $this->ticket_id,
            'created_at' => $this->created_at,
        ];
    }
}