<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeasonResource\Pages;
// use App\Filament\Resources\SeasonResource\RelationManagers;
use App\Models\Season;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeasonResource extends Resource
{
    protected static ?string $model = Season::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        // return auth()->user()->currentTeam()->isOrg();
        return True;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->format('Y/m/d')    
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->format('Y/m/d')
                    ->required(),
                Forms\Components\DatePicker::make('age_date')
                    ->format('Y/m/d')
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                // Tables\Columns\TextColumn::make('org_id')
                //     ->label('Org'),
                // Tables\Columns\TextColumn::make('team_id')
                //     ->label('Team'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('end_date', 'desc')
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
            'index' => Pages\ListSeasons::route('/'),
            'create' => Pages\CreateSeason::route('/create'),
            'view' => Pages\ViewSeason::route('/{record}'),
            'edit' => Pages\EditSeason::route('/{record}/edit'),
        ];
    }
}
