<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: fit-content; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { padding: 30px; text-align: center; color: #fff; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 8px 0 0; opacity: 0.85; font-size: 14px; }
        .body { padding: 30px; }
        .ref-badge { background: #f0f4ff; border: 1px solid #d0d9ff; border-radius: 6px; padding: 14px 20px; margin-bottom: 24px; text-align: center; }
        .ref-badge span { font-size: 20px; font-weight: bold; letter-spacing: 2px; color: #1a1a2e; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .detail-row:last-child { border-bottom: none; }
        .label { color: #666; font-size: 14px; }
        .value { font-weight: 600; font-size: 14px; text-align: right; max-width: 60%; margin-left: auto; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .footer { background: #f9f9f9; padding: 20px 30px; text-align: center; font-size: 12px; color: #999; }
        .message-box { border-radius: 6px; padding: 14px 18px; margin: 20px 0; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">

    {{-- Dynamic header color per status --}}
    @php
        $headerColors = [
            'pending'   => '#1a1a2e',
            'confirmed' => '#1a7a3c',
            'cancelled' => '#9b1c1c',
            'completed' => '#1a4a7a',
        ];
        $statusLabels = [
            'pending'   => 'Booking Received',
            'confirmed' => 'Booking Confirmed',
            'cancelled' => 'Booking Cancelled',
            'completed' => 'Booking Completed',
        ];
        $badgeStyles = [
            'pending'   => 'background:#fff3cd; color:#856404;',
            'confirmed' => 'background:#d1fae5; color:#065f46;',
            'cancelled' => 'background:#fee2e2; color:#991b1b;',
            'completed' => 'background:#dbeafe; color:#1e40af;',
        ];
        $messages = [
            'pending'   => 'Your booking has been received and is currently under review. We\'ll notify you once it\'s confirmed.',
            'confirmed' => 'Great news! Your booking has been confirmed. We look forward to hosting your event.',
            'cancelled' => 'Unfortunately, your booking has been cancelled. Please contact us if you have any questions.',
            'completed' => 'Thank you! Your event has been marked as completed. We hope you had a wonderful experience.',
        ];
        $messageBoxColors = [
            'pending'   => 'background:#fffbeb; border-left: 4px solid #f59e0b;',
            'confirmed' => 'background:#ecfdf5; border-left: 4px solid #10b981;',
            'cancelled' => 'background:#fef2f2; border-left: 4px solid #ef4444;',
            'completed' => 'background:#eff6ff; border-left: 4px solid #3b82f6;',
        ];
        $color       = $headerColors[$status]    ?? '#1a1a2e';
        $label       = $statusLabels[$status]     ?? 'Booking Update';
        $badge       = $badgeStyles[$status]      ?? '';
        $message     = $messages[$status]         ?? '';
        $msgBoxColor = $messageBoxColors[$status] ?? '';
    @endphp

    <div class="header" style="background: {{ $color }};">
        <h1>{{ $label }}</h1>
        <p>Booking Reference: {{ $booking->booking_ref }}</p>
    </div>

    <div class="body">
        <p>Hello, <strong>{{ $recipientName }}</strong>,</p>

        <div class="message-box" style="{{ $msgBoxColor }}">
            {{ $message }}
        </div>

        <div class="ref-badge">
            Booking Reference: <span>{{ $booking->booking_ref }}</span>
        </div>

        <div>
            <div class="detail-row">
                <span class="label">Status</span>
                <span class="value">
                    <span class="status-badge" style="{{ $badge }}">{{ ucfirst($status) }}</span>
                </span>
            </div>
            <div class="detail-row">
                <span class="label">Date</span>
                <span class="value">{{ \Carbon\Carbon::parse($booking->date)->format('F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Time Slot</span>
                <span class="value">{{ $booking->time_slot }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Event Type</span>
                <span class="value">{{ $booking->eventType->type ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Venue Package</span>
                <span class="value">{{ $booking->venuePackage->title ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Guest Count</span>
                <span class="value">{{ $booking->guest_count }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Total Payment</span>
                <span class="value">₱{{ number_format($booking->total_payment, 2) }}</span>
            </div>
            @if ($booking->guest_request_notes)
            <div class="detail-row">
                <span class="label">Special Requests</span>
                <span class="value">{{ $booking->guest_request_notes }}</span>
            </div>
            @endif
        </div>

        <p style="margin-top: 24px; font-size: 14px; color: #555;">
            If you have any questions, feel free to reach out to us.
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Your Venue Name. All rights reserved.<br>
        This is an automated message, please do not reply directly to this email.
    </div>
</div>
</body>
</html>