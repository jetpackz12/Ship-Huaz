<?php

namespace App\Http\Middleware;

use App\Models\MessageThread;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? $request->user()->load('userInfo') : null,
            ],
            'flash' => [
                'booking_ref' => $request->session()->get('booking_ref'),
                'success'     => $request->session()->get('success'),
            ],
            'unreadAdminMessagesCount' => fn() => $request->user()
                ? MessageThread::where('read_by_admin', false)->count()
                : 0,
            'unreadClientMessagesCount' => fn() => $request->user()
                ? MessageThread::where('user_id', $request->user()->id)
                ->where('read_by_client', false)
                ->count()
                : 0,
        ];
    }
}
