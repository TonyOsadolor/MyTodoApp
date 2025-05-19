<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class EbulksmsService
{
    /**
     * Send SMS
     */
    public function sendSms(mixed $data)
    {
        $username = config('mytodoapp.ebulksms_username');
        $apikey = config('mytodoapp.ebulksms_api_key');
        $phone = $data->text_number ?? $data['text_number'];
        $msgBody = $data->messagetext ?? $data['messagetext'];
        $url = "https://api.ebulksms.com/sendsms.json";

        $payload = [
            "SMS" => [
                "auth" => [
                    "username" => $username,
                    "apikey" => $apikey
                ],
                "message" => [
                    "sender" => 'TonyTech',
                    "messagetext" => $msgBody,
                    "flash" => "0"
                ],
                "recipients" => [
                    "gsm" => [
                        "msidn" => $phone,
                        "msgid" => $phone,
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ])->post($url, $payload);

        if ($response->failed()) {
            Log::error("Error with Sending SMS via Ebulk: ", [$response->body()]);
        }

        $data = $response->json();
        Log::channel('ebulk_log')->info('SMS '.$data['response']['status'] .' '.$data['response']['totalsent'] .' was Successfully sent To: '.$phone .' via EbulkSMS and costs: '.$data['response']['cost']);
    }
    
    /**
     * Send WhatsApp
     */
    public function sendWhatsapp(mixed $data)
    {
        $username = config('mytodoapp.ebulksms_username');
        $apikey = config('mytodoapp.ebulksms_api_key');
        $phone = $data->text_number ?? $data['text_number'];
        $subject = $data->subject ?? $data['subject'];
        $msgBody = $data->messagetext ?? $data['messagetext'];
        $url = "https://api.ebulksms.com/sendwhatsapp.json";

        $payload = [
            "WA" => [
                "auth" => [
                    "username" => $username,
                    "apikey" => $apikey
                ],
                "message" => [
                    "subject" => $subject,
                    "messagetext" => $msgBody,
                ],
                "recipients" => $phone,
            ]
        ];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ])->post($url, $payload);

        if ($response->failed()) {
            Log::error("Error with Sending WhatsApp via Ebulk: ", [$response->body()]);
        }

        $data = $response->json();
        Log::channel('ebulk_log')->info('WhatsApp '.$data['response']['status'] .' '.$data['response']['totalsent'] .' was Successfully sent To: '.$phone .' via EbulkSMS and costs: '.$data['response']['cost']);
    }
}