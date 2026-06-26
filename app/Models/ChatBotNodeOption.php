<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;

#[Unguarded]
class ChatBotNodeOption extends Model
{
    protected $casts = [
        'option' => 'array',
    ];

    public function node()
    {
        return $this->belongsTo(ChatBotNode::class, 'chat_bot_node_id');
    }

    public function nextNode()
    {
        $option = $this->option;
        if (!isset($option['next_node_id'])) return null;
        return ChatBotNode::find($option['next_node_id']);
    }
}
