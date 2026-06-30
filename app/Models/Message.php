<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['message_thread_id', 'from', 'name', 'body'])]
class Message extends Model
{
    public function thread()
    {
        return $this->belongsTo(MessageThread::class);
    }
}
