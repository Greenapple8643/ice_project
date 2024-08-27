<?php

namespace App\Filament\Imports;

use App\Models\Player;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Carbon;
use App\Models\Season;
use App\Models\Division;
use App\Models\League;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Log;

class PlayerImporter extends Importer
{
    protected static ?string $model = Player::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('playerid')
                ->label('Player Id')
                ->requiredMapping()
                ->guess(['id', 'HCR Number'])
                ->rules(['required', 'max:255']),
            ImportColumn::make('first')
                ->label('First Name')
                ->guess(['first', 'firstname', 'First Name'])
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('last')
                ->label('Last Name')
                ->guess(['last', 'lastname', 'Last Name'])
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('emails')
                ->label('Email')
                ->requiredMapping()
                ->guess(['email', 'Email', 'Emails'])
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('dob')
                ->label('Date of Birth')
                ->requiredMapping()
                ->guess(['dob', 'birth', 'Birthdate'])
                ->fillRecordUsing(function (Player $record, string $state): void {
                    $record->dob = Carbon::parse($state)->format('Y/m/d');
                })
                ->rules(['required', 'date']),
        ];
    }   
 
    public function resolveRecord(): ?Player
    {
        $org_id = auth()->user()->current_org;
        $player = Player::firstOrNew([
            'org_id' => $org_id,
            'playerid' => $this->data['playerid'],
        ]);

        $player->org_id =  $org_id;
        $player->status = 1;
        if ($this->options['season_id'] > 0) {
            $player->season_id = $this->options['season_id'];
        }

        // if ($this->options['league_id'] > 0) {
        //     $player->league_id = $this->options['league_id'];
        // }

        $player->dob = $this->data['dob'];
        // Log::info ($this->data['first'] . " " . $this->data['last'] . " dob -> " . $player->dob . " " . $player->age());

        // $player->division_id = $player->calcDivision()->id;

        if ($player->accesscode == NULL or $player->accesscode = "") {
            $player->accesscode = UUid::uuid4()->toString();
        }

        return $player;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            // Checkbox::make('updateExisting')
            //     ->label('Update existing records'),

            Select::make('season_id')
                    ->required()
                    ->label('Season')
                    ->selectablePlaceholder(false)
                    ->options(Season::all()->where('org_id','=', auth()->user()->current_org)
                        ->where('active','=',TRUE)
                        ->pluck('name', 'id'))
                    ->default(Season::all()->where('org_id','=', auth()->user()->current_org)
                        ->where('active','=',TRUE)->first()->id)
                        ,
    //             // Select::make('league_id')
    //             //         ->required()
    //             //         ->label('League')
    //             //         ->selectablePlaceholder(false)
    //             //         ->options(League::all()->where('org_id','=', auth()->user()->current_org)
    //             //             ->pluck('name', 'id'))
    //             //         ->default(function () {
    //             //             $leagueid = auth()->user()->currentTeam()->league_id; 
    //             //             if ($leagueid == NULL) {
    //             //                 $leagueid = League::all()->where('org_id','=', auth()->user()->current_org)
    //             //                 ->first()->id;
    //             //             }
    //             //             return $leagueid;                            
    //             //             }),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your player import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
