<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MessageThread;
use App\Models\User;
use App\Models\VenuePackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $bookingsThisMonth = Booking::whereBetween('date', [$startOfThisMonth, $now])->count();
        $bookingsLastMonth = Booking::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->count();

        $revenueThisMonth = Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('date', [$startOfThisMonth, $now])
            ->sum('total_payment');

        $revenueLastMonth = Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_payment');

        $newClientsThisMonth = User::where('role', 'client')
            ->whereBetween('created_at', [$startOfThisMonth, $now])
            ->count();

        $newClientsLastMonth = User::where('role', 'client')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $unreadMessages = MessageThread::where('read_by_admin', false)->count();

        $data = [
            'stats' => [
                'totalBookings' => $bookingsThisMonth,
                'bookingsDelta' => $this->percentChange($bookingsLastMonth, $bookingsThisMonth),
                'revenueThisMonth' => (float) $revenueThisMonth,
                'revenueDelta' => $this->percentChange($revenueLastMonth, $revenueThisMonth),
                'newClients' => $newClientsThisMonth,
                'clientsDelta' => $this->percentChange($newClientsLastMonth, $newClientsThisMonth),
                'unreadMessages' => $unreadMessages,
            ],

            'upcomingBookings' => Booking::with(['user.userInfo', 'eventType', 'venuePackage'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->whereDate('date', '>=', $now->toDateString())
                ->orderBy('date')
                ->take(5)
                ->get()
                ->map(fn(Booking $booking) => [
                    'id' => $booking->booking_ref,
                    'client' => trim($booking->guest_first_name . ' ' . $booking->guest_last_name),
                    'eventType' => $booking->eventType->title ?? $booking->eventType->name ?? '—',
                    'date' => $booking->date->toDateString(),
                    'pax' => $booking->guest_count,
                    'status' => $booking->status,
                    'package' => $booking->venuePackage->title ?? '—',
                ]),

            'recentMessages' => MessageThread::with(['latestMessage', 'user.userInfo'])
                ->orderByDesc('updated_at')
                ->take(3)
                ->get()
                ->map(fn(MessageThread $thread) => [
                    'client' => optional($thread->user)->userInfo
                        ? trim($thread->user->userInfo->first_name . ' ' . $thread->user->userInfo->last_name)
                        : $thread->subject,
                    'preview' => optional($thread->latestMessage)->body ?? '',
                    'time' => optional($thread->latestMessage)->created_at?->diffForHumans() ?? '',
                    'unread' => ! $thread->read_by_admin,
                ]),

            'popularPackages' => VenuePackage::withCount('bookings')
                ->orderByDesc('bookings_count')
                ->take(4)
                ->get()
                ->map(fn(VenuePackage $package) => [
                    'name' => $package->title,
                    'count' => $package->bookings_count,
                ]),
        ];

        return Inertia::render('Admin/Dashboard', $data);
    }

    /**
     * Percentage change from $previous to $current, rounded to nearest whole number.
     */
    private function percentChange(float $previous, float $current): int
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }
}
