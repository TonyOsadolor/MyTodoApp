<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    /**
     * Receive Send Champ Webhooks
     */
    public function sendChamp(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        Log::info('This is the SendChamp Webhook', [$data]);

        return response()->json('Webhook Received', 200);
    }

    /**
     * Test Send Champ SMS API
     */
    public function testSMS(Request $request)
    {
        $access_token = config('mytodoapp.sendchamp_token');

        $url = "https://api.sendchamp.com/api/v1/sms/send";

        $response = Http::withHeaders([
            "Accept" => "application/json,text/plain,*/*",
            "Authorization" => "Bearer {$access_token}",
            "Content-Type" => "application/json"
        ])->post($url, [
            "to" => $request->input('to'),
            "message" => $request->input('message'),
            "sender_name" => 'SAlert',
            "route" => "non_dnd"
        ]);

        if ($response->failed()) {
            return "Error: " . $response->body();
        }

        return $response->json();
    }

    /**
     * Test Send Champ WhatsApp API
     */
    public function testWhatsApp(Request $request)
    {
        // $url = 'https://api.sendchamp.com/api/v1/whatsapp/validate';
        $url = 'https://api.sendchamp.com/api/v1';
        $response = Http::withHeaders([])->post($url, [
            'Authorization' => 'Bearer sendchamp_live_$2y$10$zNVQnItn1zmvOTHmU6ZazO2PjZdluN5GuGQEoyDKIwweoeyZhUll2',           
        ]);

        if ($response->failed()) {
            return "Error: " . $response->body();
        }

        return $response->json();

        // $access_token = config('mytodoapp.sendchamp_token');

        // $url = "https://api.sendchamp.com/api/v1/whatsapp/send";

        // $response = Http::withHeaders([
        //     "Accept" => "application/json,text/plain,*/*",
        //     "Authorization" => "Bearer {$access_token}",
        //     "Content-Type" => "application/json"
        // ])->post($url, [
        //     "type" => "template",
        //     "template_code" => '8de17afd-43f9-497d-94a5-a9f5dcd196b6',
        //     "custom_data" => "",
        //     "recipient" => $request->input('to'),
        //     "sender" => '2348156337371'
        // ]);

        // if ($response->failed()) {
        //     return "Error: " . $response->body();
        // }

        // return $response->json();
    }

    /**
     * Test Send EBulk SMS WhatsApp API
     */
    public function sendEbulkSMS(Request $request)
    {
        $username = config('mytodoapp.ebulksms_username');
        $apikey = config('mytodoapp.ebulksms_api_key');
        $url = "https://api.ebulksms.com/sendsms.json";

        $payload = [
            "SMS" => [
                "auth" => [
                    "username" => $username,
                    "apikey" => $apikey
                ],
                "message" => [
                    "sender" => $request->input('sender'),
                    "messagetext" => $request->input('messagetext'),
                    "flash" => "0"
                ],
                "recipients" => [
                    "gsm" => [
                        "msidn" => $request->input('recipient'),
                        "msgid" => $request->input('recipient'),
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ])->post($url, $payload);

        if ($response->failed()) {
            return "Error: " . $response->body();
        }

        return $response->json();
    }

    /**
     * Test Send EBulk SMS WhatsApp API
     */
    public function sendWhatsAppEbulkSMS(Request $request)
    {
        $username = config('mytodoapp.ebulksms_username');
        $apikey = config('mytodoapp.ebulksms_api_key');
        $url = "https://api.ebulksms.com/sendwhatsapp.json";

        $payload = [
            "WA" => [
                "auth" => [
                    "username" => $username,
                    "apikey" => $apikey
                ],
                "message" => [
                    "subject" => $request->input('subject'),
                    "messagetext" => $request->input('messagetext'),
                ],
                "recipients" => $request->input('recipients'),
            ]
        ];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ])->post($url, $payload);

        if ($response->failed()) {
            return "Error: " . $response->body();
        }

        return $response->json();
    }
}
