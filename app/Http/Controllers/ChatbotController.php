<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{

    public function getChats(Request $request)
    {
        $chats = $request->session()->get('chats', []);
        return response()->json(['status' => 'success', 'chats' => $chats]);
    }

    public function initChat(Request $request)
    {
        // Initialize a new chat session
        // For simplicity, we'll just return a success response
        $token = $request->user()->currentAccessToken()->plainTextToken ?? '';
        $request->session()->forget('chats');
        try {
            $response = Http::withToken($token)->post(env('CHATBOT_API_URL') . '/init', []);
            if ($response->failed()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to initialize chat.'], 500);
            }
            $responseData = $response->json()['data'] ?? ['role' => 'agent', 'content' => 'Sorry, I am unable to respond right now.'];
            $beginingFlag = ['role' => 'system', 'content' => 'You are a helpful assistant.'];
            $chats = [$beginingFlag, $responseData];
            $request->session()->put('chats', $chats);
            return response()->json(['status' => 'success', 'chats' => $chats]);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $chats[] = ['role' => 'agent', 'content' => 'Error occurred while initializing chat. Please try again.'];
            return response()->json(['status' => 'error', 'chats' => $chats, 'message' => 'Error occurred while initializing chat: ' . $th->getMessage()], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        $token = $request->user()->currentAccessToken()->plainTextToken ?? '';
        $message = $request->input('message');
        $chats = $request->session()->get('chats', []);
        $chats[] = ['role' => 'user', 'content' => $message];
        $request->session()->put('chats', $chats);
        try {
            $response = Http::withToken($token)->post(env('CHATBOT_API_URL') . '/chat', [
                'data' => $chats
            ]);
            if ($response->failed()) {
                $chat = ['role' => 'agent', 'content' => 'Failed to get response from agent.'];
                $chats[] = $chat;
                $request->session()->put('chats', $chats);
                return response()->json(['status' => 'error', 'chat' => $chat], 500);
            }
            $responseData = $response->json()['data'] ?? ['role' => 'agent', 'content' => 'Sorry, I am unable to respond right now.'];
            $chats += $responseData;
            $request->session()->put('chats', $chats);
            $chat = $chats[array_key_last($chats)];
            return response()->json(['status' => 'success', 'chat' => $chat]);
        } catch (\Throwable $th) {
            $chat = ['role' => 'agent', 'content' => 'Error occurred while processing your request.'];
            $chats[] = $chat;
            $request->session()->put('chats', $chats);
            error_log($th->getMessage());
            return response()->json(['status' => 'error', 'chat' => $chat, 'message' => $th->getMessage()], 500);
        }
    }
}
