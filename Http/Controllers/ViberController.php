<?php

namespace Modules\Feedback\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ViberController extends Controller
{
    public function registerWebhook()
    {
        // $webhookUrl = route('viber.webhook'); // Replace with your public URL
        $webhookUrl = 'https://full-moved-hornet.ngrok-free.app/api/feedback/viber/webhook';
        $response = Http::withHeaders([
            'X-Viber-Auth-Token' => "",
        ])->post('https://chatapi.viber.com/pa/set_webhook', [
            'url' => $webhookUrl,
            "event_types"=>[
                "delivered",
                "seen",
                "failed",
                "subscribed",
                "unsubscribed",
                "conversation_started"
            ]
        ]);

        logger('here');

        if ($response->successful()) {
            return response()->json([
                'message' => 'Webhook registered successfully.',
                'data' => $response->json(),
            ]);
        }


        return response()->json([
            'message' => 'Failed to register webhook.',
            'error' => $response->json(),
        ], 500);
    }

    public function webhook(Request $request)
    {
        logger('here');
        $event = $request->input('event');

        // Log the entire payload for debugging
        logger( $request->all());
        logger('Viber Webhook Event:');

        if ($event === 'webhook') {
            $receiverId = $request->input('sender.id'); // Get the sender's unique ID
            $name = $request->input('sender.name'); // Optional: Get the sender's name
            logger($receiverId);
            logger($name);
            // Save or process the receiverId
            // Log::info(message: 'New message from user:', ['id' => $receiverId, 'name' => $name]);

            // Optionally respond to the user
            return response()->json([
                'status' => 0,
                'status_message' => 'ok',
                'message_token' => $request->input('message_token'),
            ]);
        }

        return response()->json(['status' => 0]);
    }
    public function send(){

        $response = Http::withHeaders([
            'X-Viber-Auth-Token' => "",
        ])->post("https://chatapi.viber.com/pa/". 'send_message', [
            'receiver' => $receiverId,
            'min_api_version' => 1,
            'sender' => [
                'name' => 'mybot', // Sender's name
                'avatar' => 'https://example.com/avatar.png', // Optional avatar URL
            ],
            'type' => 'text',
            'text' => "hello world",
        ]);

        return $response->json();
    }
}
