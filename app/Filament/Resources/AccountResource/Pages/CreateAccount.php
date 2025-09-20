<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\WhatsAppService;
use App\Models\Account; // Import the Account model

class CreateAccount extends CreateRecord
{
    protected static string $resource = AccountResource::class;

    protected function afterCreate(): void
    {
        $account = $this->record;
        $member = $account->familyMember;

        if ($member) {
            // FIX: Pass the entire $account object, not just its balances.
            WhatsAppService::sendAccountCreated($member, $account);
        }
    }
}