<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeagueResource\Pages;
use App\Filament\Resources\LeagueResource\RelationManagers;
use App\Models\League;
use App\Models\Season;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeagueResource extends Resource
{
    protected static ?string $model = League::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('season_id')
                    ->required()
                    ->label('Season')
                    ->options(auth()->user()->currentOrg()->getActiveSeasons()->pluck('name', 'id'))
                    ->default(auth()->user()->currentOrg()->getActiveSeasons()->first()->id),
                Forms\Components\Select::make('program_id')
                    ->required()
                    ->label('Season')
                    ->options(auth()->user()->currentOrg()->programs()->pluck('abbr', 'id'))
                    ->default(auth()->user()->currentOrg()->programs()->first()->id),
                Forms\Components\Select::make('division_id')
                    ->required()
                    ->label('Division')
                    ->options(auth()->user()->currentOrg()->divisions()->pluck('name', 'id'))
                    ->default(auth()->user()->currentOrg()->divisions()->first()->id),
                Forms\Components\Select::make('tier_id')
                    ->required()
                    ->label('Tier')
                    ->options(auth()->user()->currentOrg()->tiers()->pluck('name', 'id'))
                    ->default(auth()->user()->currentOrg()->tiers()->first()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('org_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('season.name')
                    // ->exists('seasons')
                    // ->numeric()
                    ->sortable(),
                 
                Tables\Columns\TextColumn::make('program.abbr')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tier.name')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListLeagues::route('/'),
            'create' => Pages\CreateLeague::route('/create'),
            'view' => Pages\ViewLeague::route('/{record}'),
            'edit' => Pages\EditLeague::route('/{record}/edit'),
        ];
    }
}
