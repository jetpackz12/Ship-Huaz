<?php

namespace App\Http\Controllers;

use App\Models\MessageThread;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $threads = MessageThread::with('messages', 'booking')
            ->where('user_id', Auth::id())
            ->whereHas('booking', fn($q) => $q->whereIn('status', ['pending', 'confirmed']))
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn($t) => $this->formatThread($t));

        return Inertia::render('Client/Notification', [
            'notifications' => $threads,
        ]);
    }

    public function reply(Request $request, MessageThread $thread)
    {
        $this->authorizeOwner($thread);

        $data = $request->validate([
            'body' => 'required|string',
        ]);


        $thread->update([
            'read_by_client' => true,
            'read_by_admin' => false,
        ]);

        $thread->messages()->create([
            'from' => 'client',
            'name' => Auth::user()->userInfo->first_name . ' ' . Auth::user()->userInfo->last_name,
            'body' => $data['body'],
        ]);

        $thread->touch();

        return redirect()->back();
    }

    public function markRead(MessageThread $thread)
    {
        $this->authorizeOwner($thread);

        $thread->update(['read_by_client' => true]);

        return response()->noContent();
    }

    public function markAllRead()
    {
        MessageThread::where('user_id', Auth::id())->update(['read_by_client' => true]);

        return redirect()->back();
    }

    public function destroy(MessageThread $thread)
    {
        $this->authorizeOwner($thread);

        $thread->delete();

        return redirect()->back();
    }

    public function destroyAll()
    {
        MessageThread::where('user_id', Auth::id())->delete();

        return redirect()->back();
    }

    /**
     * First message becomes the "title/body/sender" of the card;
     * everything else becomes the expandable thread.
     */
    private function formatThread(MessageThread $t): array
    {
        $messages = $t->messages->sortBy('created_at')->values();
        $first = $messages->first();
        $rest = $messages->slice(1)->values();

        return [
            'id' => $t->id,
            'type' => $t->type,
            'read' => (bool) $t->read_by_client,
            'timestamp' => $t->created_at->toIso8601String(),
            'booking_ref' => $t->booking?->booking_ref,
            'title' => $t->subject,
            'body' => $first?->body ?? '',
            'sender' => $first?->from ?? 'system',
            'sender_name' => $first?->name,
            'canReply' => (bool) $t->can_reply,
            'thread' => $rest->map(fn($m) => [
                'id' => $m->id,
                'from' => $m->from,
                'name' => $m->name,
                'body' => $m->body,
                'timestamp' => $m->created_at->toIso8601String(),
            ])->all(),
        ];
    }

    private function authorizeOwner(MessageThread $thread): void
    {
        abort_unless($thread->user_id === Auth::id(), 403);
    }
}
