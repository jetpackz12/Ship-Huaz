<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;

#[Unguarded]
class ChatBotNode extends Model
{
    public function options()
    {
        return $this->hasMany(ChatBotNodeOption::class)->where('status', 'active');
    }

    public function allOptions()
    {
        return $this->hasMany(ChatBotNodeOption::class);
    }
}
