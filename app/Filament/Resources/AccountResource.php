<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use App\Models\FamilyMember;
use App\Models\Position;
use App\Services\WhatsAppService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Account Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Account Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Main Savings, Emergency Fund'),
                        
                        Select::make('family_member_id')
                            ->label('Account Owner')
                            ->options(function () {
                                return FamilyMember::all()->mapWithKeys(function ($member) {
                                    return [$member->id => $member->name ?: 'Unnamed Member'];
                                });
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Auto-set position based on family member
                                if ($state) {
                                    $member = FamilyMember::find($state);
                                    if ($member && $member->position_id) {
                                        $set('position_id', $member->position_id);
                                    }
                                }
                            }),
                        
                        Select::make('position_id')
                            ->label('Position')
                            ->options(function () {
                                return Position::all()->mapWithKeys(function ($position) {
                                    return [$position->id => $position->name ?: 'Unnamed Position'];
                                });
                            })
                            ->searchable()
                            ->required(),
                        
                        Select::make('type')
                            ->label('Account Type')
                            ->options([
                                'main' => 'Main Account',
                                'apartment' => 'Apartment Account',
                                'house' => 'House Account',
                                'isolated' => 'Isolated Account'
                            ])
                            ->default('main')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Balance Information')
                    ->schema([
                        TextInput::make('usd_balance')
                            ->label('USD Balance')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->prefix('$')
                            ->step(0.01),
                        
                        TextInput::make('egp_balance')
                            ->label('EGP Balance')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->prefix('LE')
                            ->step(0.01),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Account Settings')
                    ->schema([
                        Toggle::make('is_isolated')
                            ->label('Isolate Account')
                            ->helperText('Isolated accounts are temporarily restricted')
                            ->reactive(),
                        
                        DatePicker::make('isolation_until')
                            ->label('Isolation Until')
                            ->visible(fn ($get) => $get('is_isolated'))
                            ->required(fn ($get) => $get('is_isolated'))
                            ->minDate(now()),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->maxLength(500)
                            ->placeholder('Optional notes about this account')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Account Name')
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),
                
                TextColumn::make('familyMember.name')
                    ->label('Owner')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('position.name')
                    ->label('Position')
                    ->badge()
                    ->color('gray'),
                
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'main' => 'primary',
                        'savings' => 'success',
                        'emergency' => 'warning',
                        'investment' => 'info',
                        'business' => 'purple',
                        default => 'gray',
                    }),
                
                TextColumn::make('usd_balance')
                    ->label('USD')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('egp_balance')
                    ->label('EGP')
                    ->money('EGP')
                    ->sortable(),
                
                TextColumn::make('total_balance_usd')
                    ->label('Total (USD)')
                    ->getStateUsing(function ($record) {
                        // Assuming 1 USD = 50 EGP (you can adjust this)
                        $exchangeRate = 50;
                        return $record->usd_balance + ($record->egp_balance / $exchangeRate);
                    })
                    ->money('USD')
                    ->sortable(false),
                
                TextColumn::make('is_isolated')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Isolated' : 'Active')
                    ->color(fn ($state) => $state ? 'danger' : 'success'),
                
                TextColumn::make('isolation_until')
                    ->label('Isolated Until')
                    ->date('M j, Y')
                    ->placeholder('Not isolated'),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('family_member_id')
                    ->options(FamilyMember::pluck('name', 'id'))
                    ->label('Owner'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'main' => 'Main Account',
                        'savings' => 'Savings',
                        'emergency' => 'Emergency Fund',
                        'investment' => 'Investment',
                        'business' => 'Business',
                        'other' => 'Other'
                    ])
                    ->label('Account Type'),
                Tables\Filters\TernaryFilter::make('is_isolated')
                    ->label('Isolation Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Account $record) {
                        // Check if account has transactions
                        if ($record->transactions()->count() > 0) {
                            Notification::make()
                                ->title('Cannot delete')
                                ->body('This account has transaction history. Archive it instead of deleting.')
                                ->danger()
                                ->send();
                            return false;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // You can add transaction relation here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }

    protected function handleRecordCreation(array $data): Account
    {
        $account = Account::create($data);
        $familyMember = FamilyMember::find($data['family_member_id']);
        
        // Send WhatsApp notification
        if ($familyMember) {
            WhatsAppService::sendAccountCreated($familyMember, $account);
        }
        
        return $account;
    }
}