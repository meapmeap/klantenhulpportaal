<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'admin'])->orderBy('created_at', 'desc');

        if ($request->user()->rol !== 'admin') {
            $query->where('created_by', $request->user()->id);
        }

        return TicketResource::collection($query->get());
    }

    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        
        $data['status'] = 'Nieuw';
        $data['created_by'] = $request->user()->id;

        $ticket = Ticket::create($data);
        return new TicketResource($ticket);
    }

    public function myTickets(Request $request)
    {
        $tickets = Ticket::with(['user', 'admin'])
            ->where('created_by', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return TicketResource::collection($tickets);
    }

    public function update(StoreTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());

        return new TicketResource($ticket);
    }
}
