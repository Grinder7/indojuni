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
        session()->forget('chat_fe_log');
        return response()->json(['status' => 'success', 'message' => 'Riwayat obrolan telah dihapus.']);
    }

    public function getChats(Request $request)
    {
        $chat_fe_log = $request->session()->get('chat_fe_log', []);
        return response()->json(['status' => 'success', 'chat_fe_log' => $chat_fe_log]);
    }

    public function sendMessage(Request $request)
    {
        $token = $request->user()->createToken('chatbot_token', ['*'], now()->addMinutes(5))->plainTextToken;
        $message = $request->input('message');
        $chats = $request->session()->get('chats', []);
        $chat_fe_log = $request->session()->get('chat_fe_log', []);
        $chatHistoryExists = true;
        if (count($chats) === 0) {
            $chatHistoryExists = false;
        }
        try {
            $response = Http::withToken($token)->post(config('app.chatbot_api_url') . '/chat', [
                'messages' => $chats,
                'flag' => $chatHistoryExists,
                'user_prompt' => $message,
            ]);
            if ($response->failed()) {
                $chat = ['role' => 'assistant', 'content' => 'Gagal mendapatkan respon dari asisten.'];
                $chat_fe_log[] = $chat;
                $request->session()->put('chat_fe_log', $chat_fe_log);
                return response()->json(['status' => 'error', 'chat' => $chat, 'message' => 'Gagal mendapatkan respon dari asisten.', 'error' => $response->body()], 500);
            }
            $responseMessages = $response->json()['messages'] ?? ['role' => 'assistant', 'content' => 'Maaf, saya tidak dapat merespons saat ini.'];
            $request->session()->put('chats', $responseMessages);
            $lastIndex = count($responseMessages) - 1;
            $chat = $responseMessages[$lastIndex];
            $userChat = ['role' => 'user', 'content' => $message];
            $chat_fe_log[] = $userChat;
            $chat_fe_log[] = $chat;
            $request->session()->put('chat_fe_log', $chat_fe_log);
            return response()->json(['status' => 'success', 'chat' => $chat]);
        } catch (\Throwable $th) {
            $chat = ['role' => 'assistant', 'content' => 'Terjadi kesalahan saat memproses permintaan Anda.'];
            $chat_fe_log[] = $chat;
            $request->session()->put('chat_fe_log', $chat_fe_log);
            error_log($th->getMessage());
            return response()->json(['status' => 'error', 'chat' => $chat, 'message' => $th->getMessage()], 500);
        }
    }
}
