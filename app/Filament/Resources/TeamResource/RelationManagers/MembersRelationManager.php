<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\MaxWidth;
use App\Models\Player;
use App\Models\League;
use App\Models\Team;
// use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// use Filament\Support\Enums\MaxWidth;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'players';

    public function form(Form $form): Form
    {
        // $ownerRecord = $this->getOwnerRecord();
        // // Logger::insteadOf
        // Log::info("Owner" . $ownerRecord);
        return $form
            ->schema([
                Forms\Components\Select::make('label')
                    ->getSearchResultsUsing(function (string $search)  {
                        return $this->getOwnerRecord()->searchAvailablePlayers($search);
                    } )
                    ->label('Player')
                    // ->getOptionLabelUsing(function ($value) : string  {
                    //     return $this->getOwnerRecord()->getOptionLabelUsing($value);
                    // } )
                    ->searchable()
                    
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('first'),
                Tables\Columns\TextColumn::make('last'),
                Tables\Columns\TextColumn::make('playerid'),
                Tables\Columns\TextColumn::make('emails'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model,) : Model  {
                        Log::info("create model");
                        Log::info($this->getOwnerRecord());
                        Log::info($data);
                        $ownerRecord = $this->getOwnerRecord();
                        $player = Player::find($data['label']);
                        $ownerRecord->players()->save($player);
                        // return $model::create($data);
                        return $this->getOwnerRecord();
                    })
                    ->successRedirectUrl(fn (Model $record): string => route('filament.app.resources.teams.edit', [
                        'tenant' => auth()->user()->current_org,
                        'record' => $this->getOwnerRecord()->id,
                    ])),
                Tables\Actions\Action::make('star')
                    ->label('Factory Fill')
                    ->icon('heroicon-m-star')
                    ->visible(function () {
                        if(! app()->environment('local')) {
                            return false;
                        }
                        return true;
                    })
                    ->form([
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->default(17)
                            ->minValue(1)
                            ->maxValue(20)
                            ->required(),
                    ])
                    ->modalWidth(MaxWidth::ExtraSmall)
                    ->action(function ($livewire, array $data) {
                        // dd($this->getOwnerRecord());
                        $league = League::find($this->getOwnerRecord()->league_id);
                        $teamplayers = Player::factory($data['qty'])
                                        ->create(['org_id' => $league->org_id, 
                                                'season_id' => $league->season_id
                                            ]);
                        $this->getOwnerRecord()->players()->saveMany($teamplayers);
                    })
                    ,                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
