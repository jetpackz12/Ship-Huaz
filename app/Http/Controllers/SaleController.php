<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PackageAddOn;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with('user', 'user.userInfo', 'eventType', 'venuePackage', 'paymentOption')
            ->where('status', 'completed')
            ->latest()
            ->get()
            ->map(function ($booking) {
                $addonIds = $booking->package_add_ons ?? [];
                $booking->package_add_ons = PackageAddOn::whereIn('id', $addonIds)
                    ->get(['id', 'title', 'price'])
                    ->toArray();

                return $booking;
            });

        return Inertia::render('Admin/Sales', [
            'bookings' => $bookings,
        ]);
    }
}
