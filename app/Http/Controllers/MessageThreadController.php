<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MessageThread;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageThreadController extends Controller
{
    public function index()
    {
        $data = [
            'bookings' => Booking::with('user', 'user.userInfo')->whereIn('status', ['pending', 'confirmed'])->get(),
            'threads' => MessageThread::with(['messages', 'booking', 'user', 'user.userInfo'])
                ->whereHas('booking', fn($q) => $q->whereIn('status', ['pending', 'confirmed']))
                ->orderByDesc('updated_at')
                ->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'type' => $t->type,
                    'read' => $t->read,
                    'client' => $this->clientLabel($t),
                    'ref' => $t->booking?->booking_ref,
                    'subject' => $t->subject,
                    'date' => $t->created_at->format('M j'),
                    'time' => $t->created_at->format('g:i A'),
                    'canReply' => $t->can_reply,
                    'messages' => $t->messages->map(fn($m) => [
                        'id' => $m->id,
                        'from' => $m->from,
                        'name' => $m->name,
                        'body' => $m->body,
                        'time' => $m->created_at->format('g:i A'),
                    ]),
                ]),
        ];

        return Inertia::render('Admin/Messages', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client' => 'required|integer|exists:bookings,id',
            'type' => 'required|in:message,booking_reminder,booking_confirmed',
            'subject' => 'required|string',
            'body' => 'required|string',
            'sender' => 'required|string',
        ]);

        $booking = Booking::findOrFail($data['client']);

        $thread = MessageThread::create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'type' => $data['type'],
            'subject' => $data['subject'],
            'read' => true,
            'can_reply' => true,
        ]);

        $thread->messages()->create([
            'from' => 'admin',
            'name' => $data['sender'],
            'body' => $data['body'],
        ]);

        return redirect()->back();
    }

    public function reply(Request $request, MessageThread $thread)
    {
        $data = $request->validate([
            'body' => 'required|string',
            'sender' => 'required|string',
        ]);

        $thread->messages()->create([
            'from' => 'admin',
            'name' => $data['sender'],
            'body' => $data['body'],
        ]);

        $thread->touch();

        return redirect()->back();
    }

    public function markRead(MessageThread $thread)
    {
        $thread->update(['read' => true]);

        return redirect()->back();
    }

    public function destroy(MessageThread $thread)
    {
        $thread->delete();

        return redirect()->back();
    }

    /**
     * Build a display label for the thread's client, falling back gracefully
     * if the booking/user relation or userInfo record is missing.
     */
    private function clientLabel(MessageThread $t): string
    {
        $user = $t->booking?->user ?? $t->user;
        $info = $user?->userInfo;

        if ($info) {
            return trim("{$info->first_name} {$info->last_name}");
        }

        return $user?->name ?? 'Unknown client';
    }
}
