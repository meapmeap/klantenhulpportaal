<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactieRequest;
use App\Http\Resources\ReactieResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ReactieController extends Controller
{
    private function canAccessTicket(Request $request, Ticket $ticket): bool
    {
        return $request->user()->rol === 'admin' || $ticket->created_by === $request->user()->id;
    }

    public function index(Request $request, Ticket $ticket)
    {
        if (!$this->canAccessTicket($request, $ticket)) {
            abort(403);
        }

        return ReactieResource::collection(
            $ticket->reacties()
                ->with('user')
                ->orderBy('created_at')
                ->get()
        );
    }

    public function store(StoreReactieRequest $request, Ticket $ticket)
    {
        if (!$this->canAccessTicket($request, $ticket)) {
            abort(403);
        }

        $reactie = $ticket->reacties()->create([
            'bericht' => $request->validated()['bericht'],
            'user_id' => $request->user()->id,
        ]);

        return new ReactieResource($reactie->load('user'));
    }
}