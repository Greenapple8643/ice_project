<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\Toggle;
// use Filament\Forms\Components\MarkdownEditor;
// use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables;
// use Filament\Tables\Actions\Action as TableAction;
// use Filament\Actions\Action;
use Filament\Tables\Table;
// use Filament\Tables;
use Filament\Facades\Filament;
 

use App\Models\Org;
    
class Location extends Model
{
    use HasFactory;

    public static function exists($abbr): bool
    {
        return Location::where('abbr', '=', $abbr)->count() > 0;
    }

    public function org(): BelongsTo
    {
        return $this->belongsTo(Org::class);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return auth()->user()->mutateData($data);
    }    

    // public static function getQuery(Team $team, Builder $query) : Builder {
    //     switch ($team->type) {
    //         case 1:
    //             return $query->where('team_id', '=' , $team->id)
    //                     ->orWhere(function (Builder $query) use ($team) {
    //                         $query->where('team_id', '=', $team->org_id)
    //                             ->where('public', '=' , 1);
    //                     });
    //         case 2:
    //             return $query->where('team_id', '=' , $team->id)
    //                     ->orWhere(function (Builder $query) use ($team) {
    //                         $query->where('team_id', '=', $team->org_id)
    //                             ->where('public', '=' , 1);
    //                     })
    //                     ->orWhere(function (Builder $query) use ($team) {
    //                         $query->where('team_id', '=', $team->league_teamid)
    //                             ->where('public', '=' , 1);
    //                     });
    //         default:
    //             return $query->where('team_id', '=' , $team->id);
    //     };
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Location Details')
                ->columns(2)
                ->schema([
                        Forms\Components\TextInput::make('abbr')
                            ->required()
                            ->label('Abbreviation')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->label('Address')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('map_link')
                            ->required()
                            ->label('Map Link')
                            // ->prefix('https://')
                            ->suffixIcon('heroicon-m-globe-alt')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('public')
                            ->required()
                            ->inline(false)
                            ->label('Public')
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
                                    $data = Location::factory()->make()->toArray();
                                    $livewire->form->fill($data);
                                }),
                        ])
        
                ]);
    }

    public static function table(Table $table): Table
    {

        return $table
        // ->modifyQueryUsing(function (Builder $query) {
        //     $team = auth()->user()->currentTeam();
        //     return Location::getQuery($team, $query);
        // })
        ->columns([
            Tables\Columns\TextColumn::make('abbr')
                ->searchable()
                ->label('Abbreviation'),
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->label('Name'),

            Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->label('Address'),
            Tables\Columns\TextColumn::make('map_link')
                ->searchable()
                ->label('Map Link'),
            Tables\Columns\TextColumn::make('public')
                ->label('Public'),
            Tables\Columns\TextColumn::make('org_id')
                ->label('Org'),
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
}
