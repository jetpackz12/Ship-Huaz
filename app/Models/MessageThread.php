<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['booking_id', 'user_id', 'user_name', 'ref', 'type', 'subject', 'read', 'can_reply'])]
class MessageThread extends Model
{
    protected $casts = [
        'read' => 'boolean',
        'can_reply' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
