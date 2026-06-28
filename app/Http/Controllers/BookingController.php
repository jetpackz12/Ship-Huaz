<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\EventType;
use App\Models\PackageAddOn;
use App\Models\PaymentOption;
use App\Models\VenuePackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allAddOns = PackageAddOn::pluck('title', 'id');

        $bookings = Booking::with('eventType', 'venuePackage', 'paymentOption')
            ->latest()
            ->where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])
            ->get()
            ->map(function ($booking) use ($allAddOns) {
                $addonIds = $booking->package_add_ons ?? [];
                $booking->package_add_ons = collect($addonIds)
                    ->map(fn($id) => $allAddOns[$id] ?? null)
                    ->filter()
                    ->values();
                return $booking;
            });

        $data = [
            'bookings'       => $bookings,
            'eventTypes'     => EventType::where('status', 'active')->get(),
            'venuePackages'  => VenuePackage::where('status', 'active')->get(),
            'packageAddOns'  => PackageAddOn::where('status', 'active')->get(),
            'paymentOptions' => PaymentOption::where('status', 'active')->get(),
        ];

        return Inertia::render('Client/Booking', $data);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $bookings = Booking::with('user', 'user.userInfo', 'eventType', 'venuePackage', 'paymentOption')
            ->latest()
            ->get()
            ->map(function ($booking) {
                $addonIds = $booking->package_add_ons ?? [];
                $booking->package_add_ons = PackageAddOn::whereIn('id', $addonIds)
                    ->get(['id', 'title', 'price'])
                    ->toArray();

                return $booking;
            });

        return Inertia::render('Admin/Bookings', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($request) {
                    $requestedSlot = $request->time_slot;
                    $date = $value;

                    $fullDay   = '8:00 AM – 5:00 PM';
                    $morning   = '8:00 AM – 12:00 PM';
                    $afternoon = '1:00 PM – 5:00 PM';

                    $conflictingSlots = match ($requestedSlot) {
                        $fullDay   => [$fullDay, $morning, $afternoon],
                        $morning   => [$fullDay, $morning],
                        $afternoon => [$fullDay, $afternoon],
                        default    => [],
                    };

                    $exists = Booking::where('date', $date)
                        ->whereIn('time_slot', $conflictingSlots)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->exists();

                    if ($exists) {
                        $fail("The selected date and time slot is unavailable. Please choose a different slot or date.");
                    }
                },
            ],
            'time_slot' => ['required', 'string', 'in:8:00 AM – 12:00 PM,1:00 PM – 5:00 PM,8:00 AM – 5:00 PM'],
            'event_type_id'     => ['required', 'integer', 'exists:event_types,id'],

            'venue_package_id'  => ['required', 'integer', 'exists:venue_packages,id'],
            'package_add_ons'   => ['nullable', 'array'],
            'package_add_ons.*' => ['integer', 'exists:package_add_ons,id'],
            'walk_in_guests'    => ['required', 'integer', 'min:0'],

            'guest_first_name'      => ['required', 'string', 'max:100'],
            'guest_last_name'       => ['required', 'string', 'max:100'],
            'guest_email'           => ['required', 'email', 'max:255'],
            'guest_phone'           => ['required', 'regex:/^9\d{9}$/'],
            'guest_count'           => ['required', 'integer', 'min:1'],
            'guest_request_notes'   => ['nullable', 'string', 'max:1000'],

            'payment_option_id'         => ['required'],
            'payment_account_number'    => [
                'nullable',
                'required_unless:payment_option_id,property',
                'string',
                'max:20',
            ],
            'payment_transaction_ref'   => [
                'nullable',
                'required_unless:payment_option_id,property',
                'string',
                'max:100',
            ],
        ]);

        $package = VenuePackage::findOrFail($validated['venue_package_id']);

        $addonsTotal = 0;
        if (!empty($validated['package_add_ons'])) {
            $addons = PackageAddOn::whereIn('id', $validated['package_add_ons'])->get();
            foreach ($addons as $addon) {
                $isPerHead = str_contains(strtolower($addon->title), 'entry pass') ||
                    str_contains(strtolower($addon->title), 'per head');
                $addonsTotal += $isPerHead
                    ? $addon->price * $validated['walk_in_guests']
                    : $addon->price;
            }
        }

        $totalPayment = $package->price + $addonsTotal;

        $isPayAtVenue = $validated['payment_option_id'] === 'property';

        $booking = Booking::create([
            'user_id'                   => auth()->id(),
            'booking_ref'               => 'BSH-' . strtoupper(substr(uniqid(), -8)),
            'event_type_id'             => $validated['event_type_id'],
            'venue_package_id'          => $validated['venue_package_id'],
            'package_add_ons'           => $validated['package_add_ons'] ?? [],
            'walk_in_guests'            => $validated['walk_in_guests'],
            'payment_option_id'         => $isPayAtVenue ? null : $validated['payment_option_id'],
            'payment_account_number'    => $validated['payment_account_number'] ?? null,
            'payment_transaction_ref'   => $validated['payment_transaction_ref'] ?? null,
            'guest_first_name'          => $validated['guest_first_name'],
            'guest_last_name'           => $validated['guest_last_name'],
            'guest_email'               => $validated['guest_email'],
            'guest_phone'               => $validated['guest_phone'],
            'guest_count'               => $validated['guest_count'],
            'guest_request_notes'       => $validated['guest_request_notes'] ?? null,
            'time_slot'                 => $validated['time_slot'],
            'total_payment'             => $totalPayment,
            'date'                      => $validated['date'],
            'status'                    => 'pending',
        ]);

        return redirect()->route('client.booking.index')->with([
            'booking_ref' => $booking->booking_ref,
            'success'     => 'Booking confirmed successfully.',
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date'      => ['required', 'date'],
            'time_slot' => ['required', 'string'],
        ]);

        $date          = $request->date;
        $requestedSlot = $request->time_slot;

        $fullDay   = '8:00 AM – 5:00 PM';
        $morning   = '8:00 AM – 12:00 PM';
        $afternoon = '1:00 PM – 5:00 PM';

        $conflictingSlots = match ($requestedSlot) {
            $fullDay   => [$fullDay, $morning, $afternoon],
            $morning   => [$fullDay, $morning],
            $afternoon => [$fullDay, $afternoon],
            default    => [],
        };

        $exists = Booking::where('date', $date)
            ->whereIn('time_slot', $conflictingSlots)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        return response()->json(['available' => !$exists]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.bookings.index')->with([
            'success' => 'Booking status updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
