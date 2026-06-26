<?php

namespace App\Http\Controllers;

use App\Models\ChatBotNode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatBotNodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'chatBotNodes' => ChatBotNode::latest()->get(),
        ];

        return Inertia::render('Admin/ChatNodes', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'node_key' => 'required|min:3|unique:chat_bot_nodes,node_key',
            'message' => 'required|min:20',
        ]);

        ChatBotNode::create($request->only(['node_key', 'message']));

        return redirect()->route('admin.chat-nodes.index')->with('success', 'Chat Node created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatBotNode $chatBotNode)
    {
        $request->validate([
            'node_key' => 'required|min:3|unique:chat_bot_nodes,node_key,' . $chatBotNode->id,
            'message' => 'required|min:20',
            'status' => 'required|in:active,inactive',
        ]);

        $chatBotNode->update($request->only(['node_key', 'message', 'status']));

        return redirect()->route('admin.chat-nodes.index')->with('success', 'Chat Node updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatBotNode $chatBotNode)
    {
        $chatBotNode->allOptions()->delete();
        $chatBotNode->delete();

        return redirect()->route('admin.chat-nodes.index')->with('success', 'Chat Node deleted successfully.');
    }
}
