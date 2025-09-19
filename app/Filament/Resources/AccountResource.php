<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use App\Models\FamilyMember;
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

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('family_member_id')
                ->label('Family Member')
                ->relationship('familyMember', 'name') // the relationship in your Account model
                ->searchable()
                ->preload()
                ->required(),    
                Select::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->options([
                        'main' => 'Main',
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'isolated' => 'Isolated'
                    ])
                    ->default('main')
                    ->required(),
                TextInput::make('usd_balance')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('egp_balance')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_isolated')
                    ->label('Is Isolated?'),
                DatePicker::make('isolation_until')
                    ->label('Isolation Until')
                    ->visible(fn ($get) => $get('is_isolated')),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->label('Description (optional)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('familyMember.name')->label('Family Member'),
                TextColumn::make('position.name')->label('Position'),
                TextColumn::make('type'),
                TextColumn::make('usd_balance')->label('USD')->sortable(),
                TextColumn::make('egp_balance')->label('EGP')->sortable(),
                TextColumn::make('is_isolated')
                    ->label('Isolated?')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                TextColumn::make('isolation_until')->date('Y-m-d'),
                TextColumn::make('created_at')->dateTime('Y-m-d')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
