<?php

namespace App\Services;

use App\Models\FamilyMember;
use App\Models\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    public static function sendAccountCreated(FamilyMember $member, Account $account)
    {
        $instanceId = env('HYPERSENDER_INSTANCE_ID');
        $token = env('HYPERSENDER_API_TOKEN');
        $phone = env('MY_WHATSAPP_NUMBER');

        // Correct URL format from your dashboard
        $url = "https://app.hypersender.com/api/whatsapp/v1/{$instanceId}/send-text";

        $message = "New family account created:\n\n"
            . "👤 For: {$member->name}\n"
            . "💳 Account Name: {$account->name}\n"
            . "💵 USD Balance: {$account->usd_balance}\n"
            . "🇪🇬 EGP Balance: {$account->egp_balance}\n"
            . "🗓️ Date: " . Carbon::now()->format('Y-m-d H:i');

        Log::info('Attempting to send WhatsApp message', [
            'url' => $url,
            'instanceId' => $instanceId,
            'phone' => $phone,
        ]);

        $response = Http::timeout(10)
            ->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($url, [
                "chatId" => $phone . "@c.us",
                "text" => $message,
                "link_preview" => false,
                "link_preview_high_quality" => false
            ]);
            
        if (!$response->successful()) {
            Log::error('Failed to send WhatsApp message.', [
                'status' => $response->status(),
                'response' => $response->body(),
                'url' => $url,
                'payload' => [
                    "chatId" => $phone . "@c.us",
                    "text" => $message,
                ]
            ]);
        } else {
            Log::info('WhatsApp message sent successfully', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
        }
    }
}