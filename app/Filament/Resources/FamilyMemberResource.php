<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyMemberResource\Pages;
use App\Models\FamilyMember;
use App\Models\Family;
use App\Models\Role;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

class FamilyMemberResource extends Resource
{
    protected static ?string $model = FamilyMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /*
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),
                TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(),
                Select::make('family_id')
                    ->label('Family')
                    ->relationship('family', 'family_name')
                    ->required(),
                Select::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    ->required(),
                Select::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'name')
                    ->required(),
                Toggle::make('is_active')
                ->label('Is Active?')
                ->default(true),
                // You can add other fields here as your table/requirements need:
                // TextInput::make('permission_level'), etc.
            ]);
    } */
public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(100),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('phone')
                ->label('Phone')
                ->tel()
                ->maxLength(30),
            DatePicker::make('dob')
                ->label('Date of Birth'),
            Select::make('gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other',
                ])
                ->label('Gender'),
            TextInput::make('personal_id')
                ->label('National/Personal ID')
                ->maxLength(50),
            FileUpload::make('photo')
                ->label('Profile Photo')
                ->directory('family-photos')
                ->image()
                ->imageEditor(),
            Textarea::make('address')
                ->label('Address')
                ->columnSpanFull(),
            TextInput::make('emergency_name')
                ->label('Emergency Contact Name')
                ->maxLength(100),
            TextInput::make('emergency_phone')
                ->label('Emergency Contact Phone')
                ->tel()
                ->maxLength(30),
            Textarea::make('notes')
                ->label('Notes')
                ->columnSpanFull(),

            Select::make('family_id')
                ->label('Family')
                ->relationship('family', 'family_name')
                ->required(),
            Select::make('role_id')
                ->label('Role')
                ->relationship('role', 'name')
                ->required(),
            Select::make('position_id')
                ->label('Position')
                ->relationship('position', 'name')
                ->required(),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->hiddenOn('edit'), // Show only on create
            Toggle::make('is_active')
                ->label('Is Active?')
                ->default(true),
        ]);
}



public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')->label('Name')->searchable()->sortable(),
            TextColumn::make('email')->label('Email')->sortable(),
            TextColumn::make('family.family_name')->label('Family')->sortable(),
            TextColumn::make('role.name')->label('Role')->sortable(),
            TextColumn::make('position.name')->label('Position')->sortable(),
            TextColumn::make('is_active')
                ->label('Active?')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                ->color(fn ($state) => $state ? 'success' : 'danger')
                ->sortable(),
            TextColumn::make('created_at')->dateTime('Y-m-d H:i')->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('position_id')
                ->relationship('position', 'name')
                ->label('Position'),
            Tables\Filters\SelectFilter::make('role_id')
                ->relationship('role', 'name')
                ->label('Role'),
            Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                // The ->before() hook is now correctly chained to the DeleteAction
                ->before(function ($record) {
                    if (! $record->canBeDeleted()) {
                        // This will now correctly prevent the deletion and show the notification.
                        Notification::make()
                            ->title('Cannot delete')
                            ->body('This family member has one or more accounts. Please transfer or delete their accounts first.')
                            ->danger()
                            ->send();

                        // Halt the action
                        return false;
                    }
                }),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamilyMembers::route('/'),
            'create' => Pages\CreateFamilyMember::route('/create'),
            'edit' => Pages\EditFamilyMember::route('/{record}/edit'),
        ];
    }
}
