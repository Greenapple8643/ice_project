<?php

namespace App\Filament\Resources;

use App\Enums\PositionPreference;
use App\Enums\RegisteredPosition;
use App\Enums\PlayDefence;

use App\Filament\Resources\PlayerResource\Pages;
use App\Models\Player;
use App\Modesl\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Season;
use App\Models\League;
use App\Models\Division;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Log;


class PlayerResource extends Resource
{
    protected static ?string $model = Player::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {     
        return $form
            ->schema([
                //
                Section::make('Player Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('playerid')
                            ->required()
                            ->label('Player ID')
                            ->maxLength(20),
                        Forms\Components\DatePicker::make('dob')
                            ->required()
                            ->format('Y/m/d')    
                            ->label('Date of Birth')
                            ,
                        Forms\Components\TextInput::make('first')
                            ->required()
                            ->label('First Name')
                            ->maxLength(30),
                        Forms\Components\TextInput::make('last')
                            ->required()
                            ->label('Last Name')
                            ->maxLength(40),
                        Forms\Components\TextInput::make('emails')
                            ->required()
                            ->label('Emails')
                            ->maxLength(150),
                    ]),
                    Section::make('Registration Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('season_id')
                            ->required()
                            ->default(auth()->user()->currentOrg()->getActiveSeasons()->first()->id)
                            ->label('Season')
                            ->options(auth()->user()->currentOrg()->getActiveSeasons()->pluck('name', 'id'))
                            ,
                        Forms\Components\Select::make('status')
                            ->required()
                            ->default(1)
                            ->label('Status')
                            ->options([
                                1 => 'Active',
                                2 => 'Withdrawn',
                            ])
                            ,
                    ]),
                    Actions::make([
                        Action::make('star')
                            ->label('Factory Fill')
                            ->icon('heroicon-m-star')
                            ->visible(function (string $operation) {
                                if($operation !== 'create') {
                                    return false;
                                }
                                if(! app()->environment('local')) {
                                    return false;
                                }
                                return true;
                            })  
                            ->action(function ($livewire) {
                                $currentuser = auth()->user(); 
                                $seasons = $currentuser->currentOrg()->getActiveSeasons();
                                $leagues = $currentuser->currentOrg()->getActiveLeagues();
                                $currentteam = $currentuser->currentTeam();

                                $data = Player::factory()->make()->toArray();
                                $data['season_id'] = $seasons->first()->id;
                                $data['league_id'] = $leagues->first()->id;
                                $data['division_id'] = $seasons->first()->id;
                                $data['accesscode'] = UUid::uuid4()->toString();
                                
                                $livewire->form->fill($data);
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('playerid')
                    ->searchable()
                    ->sortable()
                    ->label('Player ID'),
                Tables\Columns\TextColumn::make('last')
                    ->searchable()
                    ->sortable()
                    ->label('Last Name'),
                Tables\Columns\TextColumn::make('first')
                    ->searchable()
                    ->sortable()
                    ->label('First Name'),
                Tables\Columns\TextColumn::make('emails')
                    ->searchable()
                    ->label('Emails'),
            ])
            ->defaultSort('last', 'first')
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
            'index' => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'view' => Pages\ViewPlayer::route('/{record}'),
            'edit' => Pages\EditPlayer::route('/{record}/edit'),
        ];
    }
}
