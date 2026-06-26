<?php

namespace App\Http\Controllers;

use App\Models\ChatBotNode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    /**
     * Return all active nodes with their active options.
     */
    public function index(): JsonResponse
    {
        $nodes = ChatBotNode::with('options')
            ->where('status', 'active')
            ->get();

        $mapped = [];
        $mainNodeId = null;

        foreach ($nodes as $node) {
            $mapped[$node->id] = [
                'id'       => $node->id,
                'node_key' => $node->node_key,
                'message'  => $node->message,
                'options'  => $node->options->map(fn($opt) => [
                    'label'       => $opt->option['label'] ?? '',
                    'next_node_id' => $opt->option['next_node_id'] ?? 0,
                ])->values()->toArray(),
            ];

            if (strtolower($node->node_key) === 'main') {
                $mainNodeId = $node->id;
            }
        }

        return response()->json([
            'nodes'        => $mapped,
            'main_node_id' => $mainNodeId,
        ]);
    }

    /**
     * Return a flat list of all active nodes for admin dropdowns.
     */
    public function nodes(): JsonResponse
    {
        $nodes = ChatBotNode::where('status', 'active')
            ->orderBy('node_key')
            ->get(['id', 'node_key']);

        return response()->json($nodes);
    }
}
