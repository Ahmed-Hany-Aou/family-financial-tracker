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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Get;

class FamilyMemberResource extends Resource
{
    protected static ?string $model = FamilyMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
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
                               
                            ])
                            ->label('Gender'),
                        TextInput::make('personal_id')
                            ->label('National/Personal ID')
                            ->maxLength(50),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Profile Photo')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Profile Photo')
                            ->directory('family-photos')
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
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
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Family & Role')
                    ->schema([
                        Select::make('family_id')
                            ->label('Family')
                            ->options(function () {
                                return Family::all()->mapWithKeys(function ($family) {
                                    return [$family->id => $family->family_name ?: 'Unnamed Family'];
                                });
                            })
                            ->required()
                            ->searchable(),
                        Select::make('role_id')
                            ->label('Role')
                            ->options(function () {
                                return Role::all()->mapWithKeys(function ($role) {
                                    return [$role->id => $role->name ?: 'Unnamed Role'];
                                });
                            })
                            ->required()
                            ->searchable(),
                        Select::make('position_id')
                            ->label('Position')
                            ->options(function () {
                                return Position::all()->mapWithKeys(function ($position) {
                                    return [$position->id => $position->name ?: 'Unnamed Position'];
                                });
                            })
                            ->required()
                            ->searchable(),
                        Select::make('permission_level')
                            ->label('Permission Level')
                            ->options([
                                'read_only' => 'Read Only',
                                'read_write' => 'Read & Write',
                            ])
                            ->default('read_only')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Account Settings')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->visibleOn('create'),
                        
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->same('password')
                            ->dehydrated(false)
                            ->visibleOn('create'),
                        
                        Toggle::make('change_password')
                            ->label('Change Password?')
                            ->default(false)
                            ->live()
                            ->hiddenOn('create'),
                        
                        TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->visible(fn (Get $get): bool => $get('change_password'))
                            ->hiddenOn('create'),
                        
                        TextInput::make('new_password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->same('new_password')
                            ->dehydrated(false)
                            ->visible(fn (Get $get): bool => $get('change_password'))
                            ->hiddenOn('create'),
                        
                        Toggle::make('is_active')
                            ->label('Is Active?')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF')
                    ->size(40),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->placeholder('Not provided'),
                TextColumn::make('family.family_name')
                    ->label('Family')
                    ->sortable(),
                TextColumn::make('role.name')
                    ->label('Role')
                    ->sortable()
                    ->badge(),
                TextColumn::make('position.name')
                    ->label('Position')
                    ->sortable()
                    ->badge(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('position_id')
                    ->options(Position::pluck('name', 'id')->filter())
                    ->label('Position'),
                Tables\Filters\SelectFilter::make('role_id')
                    ->options(Role::pluck('name', 'id')->filter())
                    ->label('Role'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if (!$record->canBeDeleted()) {
                            Notification::make()
                                ->title('Cannot delete')
                                ->body('This family member has one or more accounts. Please transfer or delete their accounts first.')
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