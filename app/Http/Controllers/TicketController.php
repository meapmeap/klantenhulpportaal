<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        return TicketResource::collection(Ticket::all());
    }

    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();

        $data['status'] = 'open';
        $data['created_by'] = $request->user()->id;

        $ticket = Ticket::create($data);
        return new TicketResource($ticket);
    }

    public function myTickets(Request $request)
    {
        $tickets = Ticket::where('created_by', $request->user()->id)->get();

        return TicketResource::collection($tickets);
    }
}
