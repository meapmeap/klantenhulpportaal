<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactieRequest;
use App\Http\Resources\ReactieResource;
use App\Models\Ticket;
use App\Models\Reactie;
use Illuminate\Http\Request;
use App\Mail\ReactieToegevoegdMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        
        $reactie->load('user');
        if ($request->user()->rol === 'admin') {
            try {
                $ticket->load('user');

                if ($ticket->user) {
                    Mail::to($ticket->user->email_adres)
                    ->send(new ReactieToegevoegdMail($ticket, $reactie));
                }
            } catch (\Throwable $e) {
                Log::error('E-mail bij nieuwe ticketreactie mislukt', [
                    'ticket_id' => $ticket->id,
                    'reactie_id' => $reactie->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return new ReactieResource($reactie->load('user'));
    }

    public function update(StoreReactieRequest $request, Ticket $ticket, Reactie $reactie) {
        if ($request->user()->rol !== 'admin') {
            abort(403);
        }

        if ((int) $reactie->ticket_id !== (int) $ticket->id) {
            abort(404);
        }

        $reactie->update([
            'bericht' => $request->validated()['bericht'],
        ]);

        return new ReactieResource($reactie->load('user'));
    }
}