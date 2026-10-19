<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotitieRequest;
use App\Models\Notitie;
use App\Models\Ticket;
use Illuminate\Http\Request;

class NotitieController extends Controller
{
    private function isAdmin(Request $request): bool
    {
        return $request->user()->rol === 'admin';
    }

    public function index(Request $request, Ticket $ticket)
    {
        if (!$this->isAdmin($request)) {
            abort(403);
        }

        return $ticket->notitie()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($notitie) => $this->formatNotitie($notitie));
    }

    public function store(StoreNotitieRequest $request, Ticket $ticket)
    {
        if (!$this->isAdmin($request)) {
            abort(403);
        }

        $notitie = $ticket->notitie()->create([
            'bericht' => $request->validated()['bericht'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json(
            $this->formatNotitie($notitie->load('user')),
            201
        );
    }

    public function update(StoreNotitieRequest $request, Ticket $ticket, Notitie $notitie) 
    {
        if (!$this->isAdmin($request)) {
            abort(403);
        }

        if ((int) $notitie->ticket_id !== (int) $ticket->id) {
            abort(404);
        }

        $notitie->update([
            'bericht' => $request->validated()['bericht'],
        ]);

        return response()->json(
            $this->formatNotitie($notitie->load('user'))
        );
    }

    private function formatNotitie(Notitie $notitie): array
    {
        return [
            'id' => $notitie->id,
            'bericht' => $notitie->bericht,
            'user_id' => $notitie->user_id,
            'user' => $notitie->user->voornaam . ' '
                . $notitie->user->achternaam,
            'ticket_id' => $notitie->ticket_id,
            'created_at' => $notitie->created_at,
        ];
    }

    public function destroy(Request $request, Ticket $ticket, Notitie $notitie)
    {
        if (!$this->isAdmin($request)) {
            abort(403);
        }

        if ((int) $notitie->ticket_id !== (int) $ticket->id) {
            abort(404);
        }

        $notitie->delete();

        return response()->noContent();
    }
}