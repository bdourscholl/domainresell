<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Ticket;
use App\Services\TicketService;

class TicketController
{
    public function index(Request $request): Response
    {
        $status = $request->get('status', '');
        $tickets = Ticket::all(50, $status);
        return View::renderWithLayout('admin/tickets', ['tickets' => $tickets, 'status' => $status], 'admin');
    }

    public function show(Request $request): Response
    {
        $ticketId = (int) $request->param('id');
        $ticket = Ticket::findWithUser($ticketId);
        $replies = Ticket::getReplies($ticketId);
        return View::renderWithLayout('admin/ticket-view', ['ticket' => $ticket, 'replies' => $replies], 'admin');
    }

    public function reply(Request $request): Response
    {
        $ticketId = (int) $request->param('id');
        $message = $request->post('message', '');

        if ($message) {
            $ticketService = new TicketService();
            $ticketService->addReply($ticketId, current_user_id(), $message, true);
        }

        flash('success', 'Reply sent.');
        return Response::redirect("/admin/tickets/{$ticketId}");
    }

    public function updateStatus(Request $request): Response
    {
        $ticketId = (int) $request->param('id');
        Ticket::update($ticketId, ['status' => $request->post('status')]);
        flash('success', 'Ticket status updated.');
        return Response::redirect("/admin/tickets/{$ticketId}");
    }
}
