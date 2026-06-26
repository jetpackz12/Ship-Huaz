<?php

namespace App\Http\Controllers;

use App\Models\ChatBotNode;
use App\Models\ChatBotNodeOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatBotNodeOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nodes = ChatBotNode::with('allOptions')->latest()->get();

        $data = [
            'tableData' => $nodes->map(function ($node) {
                return [
                    'id'       => $node->id,
                    'node_key' => $node->node_key,
                    'message'  => $node->message,
                    'status'   => $node->status,
                    'options'  => $node->allOptions->map(function ($opt) {
                        $data = $opt->option;
                        return [
                            'option_id'   => $opt->id,
                            'label'       => $data['label'] ?? '',
                            'next_node_id' => $data['next_node_id'] ?? null,
                            'status'      => $opt->status,
                        ];
                    }),
                ];
            }),
            'allNodes' => ChatBotNode::orderBy('node_key')
                ->get(['id', 'node_key'])
                ->map(fn($n) => [
                    'value' => (string) $n->id,
                    'label' => $n->node_key,
                ]),
        ];

        return Inertia::render('Admin/ChatNodeOptions', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'node_id'           => 'required|exists:chat_bot_nodes,id',
            'option_node_ids'   => 'required|array|min:1',
            'option_node_ids.*' => 'required|exists:chat_bot_nodes,id',
        ]);
 
        ChatBotNodeOption::where('chat_bot_node_id', $request->node_id)->delete();
 
        $targetNodes = ChatBotNode::whereIn('id', $request->option_node_ids)
            ->pluck('node_key', 'id');
 
        foreach ($request->option_node_ids as $targetId) {
            ChatBotNodeOption::create([
                'chat_bot_node_id' => $request->node_id,
                'option' => [
                    'label'        => $targetNodes[$targetId] ?? 'Option',
                    'next_node_id' => (int) $targetId,
                ],
                'status' => 'active',
            ]);
        }

        return redirect()->route('admin.chat-node-options.index')->with('success', 'Chat Node Option created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string|int $nodeId)
    {
        $request->validate([
            'option_node_ids'   => 'required|array|min:1',
            'option_node_ids.*' => 'required|exists:chat_bot_nodes,id',
            'status'            => 'required|in:active,inactive',
        ]);
 
        $node = ChatBotNode::findOrFail($nodeId);
        $node->update(['status' => $request->status]);
 
        ChatBotNodeOption::where('chat_bot_node_id', $nodeId)->delete();
 
        $targetNodes = ChatBotNode::whereIn('id', $request->option_node_ids)
            ->pluck('node_key', 'id');
 
        foreach ($request->option_node_ids as $targetId) {
            ChatBotNodeOption::create([
                'chat_bot_node_id' => $nodeId,
                'option' => [
                    'label'        => $targetNodes[$targetId] ?? 'Option',
                    'next_node_id' => (int) $targetId,
                ],
                'status' => $request->status,
            ]);
        }

        return redirect()->route('admin.chat-node-options.index')->with('success', 'Chat Node Option updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $nodeId)
    {
         ChatBotNodeOption::where('chat_bot_node_id', $nodeId)->delete();

        return redirect()->route('admin.chat-node-options.index')->with('success', 'Chat Node Option deleted successfully.');
    }
}
