<?php

namespace Tests\Unit\Services;

use App\Services\WhatsAppService;
use App\Models\FamilyMember;
use App\Models\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    public function test_it_sends_whatsapp_message_successfully()
    {
        // Arrange
        $member = new FamilyMember();
        $member->name = 'John Doe';
        
        $account = new Account();
        $account->name = 'savings_account';
        $account->usd_balance = 1000.00;
        $account->egp_balance = 15000.00;

        // Mock HTTP response
        Http::fake([
            '*' => Http::response([
                'key' => ['id' => 'test-message-id'],
                'status' => 'PENDING'
            ], 201)
        ]);

        // Act
        WhatsAppService::sendAccountCreated($member, $account);

        // Assert
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'send-text') &&
                   str_contains($request['text'], 'New family account created') &&
                   str_contains($request['text'], 'John Doe');
        });
    }

    public function test_it_handles_api_failure_gracefully()
    {
        // Arrange
        $member = new FamilyMember();
        $member->name = 'Jane Smith';
        
        $account = new Account();
        $account->name = 'checking_account';
        $account->usd_balance = 500.00;
        $account->egp_balance = 7500.00;

        // Mock HTTP failure response
        Http::fake([
            '*' => Http::response(['error' => 'Unauthorized'], 401)
        ]);

        // Mock Log to verify error is logged
        Log::shouldReceive('error')
            ->once()
            ->with('Failed to send WhatsApp message.', \Mockery::type('array'));

        Log::shouldReceive('info')->once(); // For the initial attempt log

        // Act - should not throw exception
        WhatsAppService::sendAccountCreated($member, $account);

        // Assert - request was attempted
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'send-text') &&
                   str_contains($request['text'], 'Jane Smith') &&
                   str_contains($request['text'], 'checking_account');
        });
    }

    public function test_it_formats_message_with_correct_data()
    {
        // Arrange
        $member = new FamilyMember();
        $member->name = 'Ahmed Hassan';
        
        $account = new Account();
        $account->name = 'business_account';
        $account->usd_balance = 2500.75;
        $account->egp_balance = 45000.50;

        Http::fake([
            '*' => Http::response(['status' => 'PENDING'], 201)
        ]);

        // Act
        WhatsAppService::sendAccountCreated($member, $account);

        // Assert - verify specific message formatting
        Http::assertSent(function ($request) {
            $text = $request['text'];
            
            return str_contains($text, 'New family account created:') &&
                   str_contains($text, '👤 For: Ahmed Hassan') &&
                   str_contains($text, '💳 Account Name: business_account') &&
                   str_contains($text, '💵 USD Balance: 2500.75') &&
                   str_contains($text, '🇪🇬 EGP Balance: 45000.5') &&
                   str_contains($text, '🗓️ Date: ') &&
                   $request['chatId'] === env('MY_WHATSAPP_NUMBER') . '@c.us' &&
                   $request['link_preview'] === false &&
                   $request['link_preview_high_quality'] === false;
        });
    }
}