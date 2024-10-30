<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use App\Enums\AccessLevel;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use App\Filament\Resources\AuditLogResource\Pages;

class AuditLogResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Admin';

    public static function getModel(): string
    {
        return \OwenIt\Auditing\Models\Audit::class;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric(),

                Tables\Columns\TextColumn::make('old_values')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('new_values')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('-')
                    ->state(fn (Model $model) => $model->user_id ? User::find($model->user_id)?->name : null),

                Tables\Columns\TextColumn::make('ip_address')
                    ->toggleable()
                    ->searchable()
                    ->label('IP Address')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->sortable()
                    ->label('Date')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::Large)
                    ->infolist([

                        Section::make()
                            ->columns(2)
                            ->schema([

                                TextEntry::make('old_values')
                                ->limit(50)
                                ->columnSpan(12),
                                TextEntry::make('new_values')
                                ->limit(50)
                                ->columnSpan(12),

                                TextEntry::make('user')
                                    ->placeholder('-')
                                    ->state(
                                        fn (Model $model) => $model->user_id ? User::find($model->user_id)?->name : null
                                    )->columnSpan(6),

                                TextEntry::make('auditable_type')
                                    ->columnSpan(6),

                                TextEntry::make('event')
                                    ->columnSpan(6),

                                TextEntry::make('ip_address')
                                    ->label('IP Address')
                                    ->placeholder('-')->columnSpan(6),

                                TextEntry::make('created_at')
                                    ->dateTime()->columnSpan(6),
                            ]),

                        KeyValueEntry::make('context')
                            ->label('Additional data')
                            ->hidden(fn ($state) => empty($state))
                            ->placeholder('-'),
                    ])
            ])
            ->paginationPageOptions(['10', '25', '50', '100'])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}


