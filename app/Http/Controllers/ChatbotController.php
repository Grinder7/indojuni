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

    public function initChat(Request $request)
    {
        $token = $request->user()->createToken('chatbot_token', ['*'], now()->addMinutes(5))->plainTextToken;
        $request->session()->forget('chats');

        try {
            $response = Http::withToken($token)->post(config('app.chatbot_api_url') . '/init', []);
            if ($response->failed()) {
                error_log('Failed to initialize chat: ' . $response->body());
                $chats[] = ['role' => 'agent', 'content' => 'Error occurred while initializing chat. Please try again.'];
                return response()->json(['status' => 'error', 'chats' => $chats, 'message' => 'Failed to initialize chat.'], 500);
            }
            $responseData = $response->json()['data'] ?? ['role' => 'agent', 'content' => 'Sorry, I am unable to respond right now.'];
            $beginingFlag = ['role' => 'system', 'content' => 'This is the beginning of the chat.'];
            $chats = array_merge([$beginingFlag], $responseData);
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
        $token = $request->user()->createToken('chatbot_token', ['*'], now()->addMinutes(5))->plainTextToken;
        $message = $request->input('message');
        $chats = $request->session()->get('chats', []);
        $chats[] = ['role' => 'user', 'content' => $message];
        $request->session()->put('chats', $chats);
        try {
            $response = Http::withToken($token)->post(config('app.chatbot_api_url') . '/chat', [
                'data' => $chats
            ]);
            if ($response->failed() || !isset($response->json()['data'])) {
                $chat = ['role' => 'agent', 'content' => 'Failed to get response from agent.'];
                $chats[] = $chat;
                $request->session()->put('chats', $chats);
                return response()->json(['status' => 'error', 'chat' => $chat], 500);
            }
            $responseData = $response->json()['data'] ?? ['role' => 'agent', 'content' => 'Sorry, I am unable to respond right now.'];
            $chats = array_merge($chats, $responseData);
            $request->session()->put('chats', $chats);
            $chat = [];
            for ($i = count($responseData) - 1; $i >= 0; $i--) {
                if ($responseData[$i]['role'] === 'agent') {
                    $chat = $responseData[$i];
                    break;
                }
            }
            if ($chat === []) {
                $chat = ['role' => 'agent', 'content' => 'Sorry, I am unable to respond right now.'];
            }
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
