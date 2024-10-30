<?php

namespace App\Filament\Resources;

use App\Actions\UnbanAction as ActionsUnbanAction;
use App\Actions\UnbanBulkAction as ActionsUnbanBulkAction;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Gerenuk\FilamentBanhammer\Resources\Actions\BanAction;
use Gerenuk\FilamentBanhammer\Resources\Actions\BanBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mchev\Banhammer\Models\Ban;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                ->state(fn($record)=> !$record->isBanned())
                ->boolean(),
                Tables\Columns\TextColumn::make('roles.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                BanAction::make()
                ->visible(fn($record) => !$record->isBanned() && Auth::user()->can('create', Ban::class))
                ->after(function (User $record) {
                    $record->notify(
                        Notification::make()
                            ->title('Your account is banned.')
                            ->toDatabase()
                    );
                    Auth::user()->notify(
                        Notification::make()
                            ->title('User is banned successfully.')
                            ->toDatabase()
                    );
                }),
                ActionsUnbanAction::make()
                ->visible(fn($record) => $record->isBanned() && Auth::user()->can('create', Ban::class))
                ->after(function (User $record) {
                    $record->notify(
                        Notification::make()
                            ->title('Your account is unbanned.')
                            ->toDatabase()
                    );
                    Auth::user()->notify(
                        Notification::make()
                            ->title('User is unbanned successfully.')
                            ->toDatabase()
                    );
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BanBulkAction::make(),
                    ActionsUnbanBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // ...
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
