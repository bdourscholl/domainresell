<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;

class TicketController
{
    public function index(Request $request): Response
    {
        $tickets = Ticket::findByUser(current_user_id());
        return View::renderWithLayout('account/tickets', ['tickets' => $tickets], 'main');
    }

    public function create(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/account/tickets');
        }

        $user = User::find(current_user_id());
        $ticketService = new TicketService();
        $ticketId = $ticketService->create(current_user_id(), [
            'subject' => $request->post('subject'),
            'department' => $request->post('department', 'general'),
            'priority' => $request->post('priority', 'medium'),
            'message' => $request->post('message'),
            'user_name' => $user['name'] ?? '',
        ]);

        flash('success', 'Ticket created successfully.');
        return Response::redirect("/account/tickets/{$ticketId}");
    }

    public function show(Request $request): Response
    {
        $ticketId = (int) $request->param('id');
        $ticket = Ticket::find($ticketId);

        if (!$ticket || (int) $ticket['user_id'] !== current_user_id()) {
            flash('error', 'Ticket not found.');
            return Response::redirect('/account/tickets');
        }

        $replies = Ticket::getReplies($ticketId);
        return View::renderWithLayout('account/ticket-view', ['ticket' => $ticket, 'replies' => $replies], 'main');
    }

    public function reply(Request $request): Response
    {
        $ticketId = (int) $request->param('id');
        $ticket = Ticket::find($ticketId);

        if (!$ticket || (int) $ticket['user_id'] !== current_user_id()) {
            return Response::redirect('/account/tickets');
        }

        $message = $request->post('message', '');
        if (!$message) {
            flash('error', 'Message is required.');
            return Response::redirect("/account/tickets/{$ticketId}");
        }

        $ticketService = new TicketService();
        $ticketService->addReply($ticketId, current_user_id(), $message, false);

        flash('success', 'Reply sent.');
        return Response::redirect("/account/tickets/{$ticketId}");
    }
}
