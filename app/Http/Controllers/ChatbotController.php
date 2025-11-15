<?php

namespace App\Http\Controllers;

use Auth;
use Http;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{

    public function clearChats()
    {
        session()->forget('chats');
        return response()->json(['status' => 'success', 'message' => 'Chat history cleared.']);
    }

    public function getChats(Request $request)
    {
        $chats = $request->session()->get('chats', []);
        return response()->json(['status' => 'success', 'chats' => $chats]);
    }

    // nda pake
    public function initChat(Request $request)
    {
        $token = $request->user()->createToken('chatbot_token', ['*'], now()->addMinutes(5))->plainTextToken;
        $request->session()->forget('chats');

        try {
            $payload = [];
            $payload["messages"] = [];
            $payload["flag"] = false;
            $payload["user_prompt"] = "";
            $response = Http::withToken($token)->post(config('app.chatbot_api_url') . '/chat', $payload);
            if ($response->failed()) {
                error_log('Failed to initialize chat: ' . $response->body());
                $chats[] = ['role' => 'assistant', 'content' => 'Error occurred while initializing chat. Please try again.'];
                return response()->json(['status' => 'error', 'chats' => $chats, 'message' => 'Failed to initialize chat.', 'error' => $response->body()], 500);
            }
            $responseMessages = $response->json()['messages'] ?? [];
            $request->session()->put('chats', $responseMessages);
            return response()->json(['status' => 'success', 'chats' => $responseMessages]);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $chats[] = ['role' => 'assistant', 'content' => 'Error occurred while initializing chat. Please try again.'];
            return response()->json(['status' => 'error', 'chats' => $chats, 'message' => 'Error occurred while initializing chat: ' . $th->getMessage()], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        $token = $request->user()->createToken('chatbot_token', ['*'], now()->addMinutes(5))->plainTextToken;
        $message = $request->input('message');
        $chats = $request->session()->get('chats', []);
        // $chats[] = ['role' => 'user', 'content' => $message];
        $request->session()->put('chats', $chats);
        $firstMessage = false;
        if (count($chats) === 0) {
            $firstMessage = true;
        }
        try {
            $response = Http::withToken($token)->post(config('app.chatbot_api_url') . '/chat', [
                "messages" => $chats,
                "flag" => $firstMessage,
                "user_prompt" => $message
            ]);
            if ($response->failed()) {
                $chat = ['role' => 'assistant', 'content' => 'Failed to get response from assistant.'];
                $chats[] = $chat;
                $request->session()->put('chats', $chats);
                return response()->json(['status' => 'error', 'chat' => $chat, 'message' => 'Failed to get response from assistant.', 'error' => $response->body()], 500);
            }
            $responseMessages = $response->json()['messages'] ?? ['role' => 'assistant', 'content' => 'Sorry, I am unable to respond right now.'];
            // $chats = array_merge($chats, $responseMessages);
            $request->session()->put('chats', $responseMessages);
            $lastIndex = count($responseMessages) - 1;
            $chat = $responseMessages[$lastIndex];
            return response()->json(['status' => 'success', 'chat' => $chat]);
        } catch (\Throwable $th) {
            $chat = ['role' => 'assistant', 'content' => 'Error occurred while processing your request.'];
            $chats[] = $chat;
            $request->session()->put('chats', $chats);
            error_log($th->getMessage());
            return response()->json(['status' => 'error', 'chat' => $chat, 'message' => $th->getMessage()], 500);
        }
    }
}
