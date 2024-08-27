<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'org';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day')
                    ->options([
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                        'Sunday' => 'Sunday',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Select::make('location_id')
                    ->relationship('location', 'name')
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->seconds(false)
                    ->required(),
                Forms\Components\Select::make('league_id')
                    ->relationship('league', 'name')
                    ->required(),
                Forms\Components\Select::make('home_team_id')
                    ->relationship('homeTeam', 'name')
                    ->required(),
                Forms\Components\Select::make('away_team_id')
                    ->relationship('awayTeam', 'name')
                    ->required(),
                Forms\Components\TextInput::make('home_score')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),
                Forms\Components\TextInput::make('away_score')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),
                Forms\Components\Toggle::make('tie')
                    ->default(false),
                Forms\Components\Select::make('type')
                    ->options([
                        'Regular' => 'Regular',
                        'PlayOffs' => 'Playoffs',
                        'Final' => 'Final',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Scheduled' => 'Scheduled',
                        'Finished' => 'Finished',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\Hidden::make('org_id')
                    ->default(auth()->user()->org_id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('location.name')->label('Location')
                    ->searchable(),
                TextColumn::make('start_time')
                    ->time('h:i A')
                    ->sortable(),
                TextColumn::make('end_time')                    
                    ->time('h:i A')
                    ->sortable(),
                TextColumn::make('league.name')->label('League')
                    ->searchable(),
                TextColumn::make('homeTeam.name')->label('Home Team')
                    ->searchable(),
                TextColumn::make('awayTeam.name')->label('Away Team')
                    ->searchable(),
                TextColumn::make('home_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('away_score')
                    ->numeric()
                    ->sortable(),
                BooleanColumn::make('tie')
                    ->sortable(),
                TextColumn::make('type')
                    ->sortable(),
                TextColumn::make('status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }
}



