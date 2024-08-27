<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Location;
use App\Models\Booking;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('org_id')
                    ->default(auth()->user()->org_id)
                    ->disabled()
                    ->hidden(),
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Select Event Type')
                        ->schema([
                            Forms\Components\Select::make('type_id')
                                ->label('Event Type')
                                ->options(EventType::pluck('name', 'id'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $eventType = EventType::find($state);
                                    $set('requires_ice', $eventType ? $eventType->requires_ice : false);
                                }),
                        ]),
                    Forms\Components\Wizard\Step::make('Event Details')
                        ->schema(function (callable $get) {
                            if ($get('requires_ice')) {
                                return [
                                    Forms\Components\DatePicker::make('date')
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updateBookingOptions($state, $set, $get, 'location_id')),
                                    Forms\Components\Select::make('location_id')
                                        ->label("Location")
                                        ->required()
                                        ->reactive()
                                        ->options(Location::pluck('name', 'id'))
                                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updateBookingOptions($get('date'), $set, $get, 'location_id')),
                                    Forms\Components\Select::make('booking_id')
                                        ->label('Available Bookings')
                                        ->options(function (callable $get) {
                                            return $get('booking_options') ?? [];
                                        })
                                        ->reactive()
                                        ->required()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $booking = Booking::find($state);
                                            if ($booking) {
                                                $set('start_time', $booking->start_time ?? null);
                                                $set('end_time', $booking->end_time ?? null);
                                            } else {
                                                $set('start_time', null);
                                                $set('end_time', null);
                                            }
                                        })
                                        ->hint('Select both date and a location to see available bookings'),
                                    Forms\Components\Hidden::make('start_time')
                                        ->required(),
                                    Forms\Components\Hidden::make('end_time')
                                        ->required(),
                                    Forms\Components\Select::make('home_team_id')
                                        ->label('Home Team')
                                        ->options(function (callable $get) {
                                            $bookingId = $get('booking_id');
                                            if (!$bookingId) return [];
                                            $booking = Booking::find($bookingId);
                                            return $booking ? Team::where('league_id', $booking->league_id)->pluck('name', 'id') : [];
                                        })
                                        ->reactive()
                                        ->required(function (callable $get) {
                                            $eventTypeId = $get('type_id');
                                            if (!$eventTypeId) return false;
                                            $eventType = EventType::find($eventTypeId);
                                            return $eventType && $eventType->min_teams > 0;
                                        })
                                        ->disabled(fn (callable $get) => !$get('booking_id'))
                                        ->afterStateUpdated(fn (callable $set) => $set('away_team_id', null)),
                                    Forms\Components\Select::make('away_team_id')
                                        ->label('Away Team')
                                        ->options(function (callable $get) {
                                            $bookingId = $get('booking_id');
                                            $homeTeamId = $get('home_team_id');
                                            if (!$bookingId) return [];
                                            $booking = Booking::find($bookingId);
                                            return $booking ? Team::where('league_id', $booking->league_id)
                                                ->when($homeTeamId, function ($query) use ($homeTeamId) {
                                                    return $query->where('id', '!=', $homeTeamId);
                                                })
                                                ->pluck('name', 'id') : [];
                                        })
                                        ->reactive()
                                        ->required(function (callable $get) {
                                            $eventTypeId = $get('type_id');
                                            if (!$eventTypeId) return false;
                                            $eventType = EventType::find($eventTypeId);
                                            return $eventType && $eventType->min_teams > 1;
                                        })
                                        ->disabled(function (callable $get) {
                                            $eventTypeId = $get('type_id');
                                            if (!$eventTypeId) return true;
                                            $eventType = EventType::find($eventTypeId);
                                            return !$get('home_team_id') || ($eventType && $eventType->max_teams <= 1);
                                        })
                                        ->visible(function (callable $get) {
                                            $eventTypeId = $get('type_id');
                                            if (!$eventTypeId) return false;
                                            $eventType = EventType::find($eventTypeId);
                                            return $eventType && $eventType->max_teams > 1;
                                        })
                                        ->hint('If it\'s practice and don\'t have opponent leave this field blank')
                                        ->validationMessages([
                                            'required' => 'Away team is required when the event type is game',
                                        ]),
                                ];

                            } else {
                                return [
                                    Forms\Components\TextInput::make('location_external')
                                        ->label("Location")
                                        ->required(),
                                    Forms\Components\DatePicker::make('date')
                                        ->required(),
                                    Forms\Components\TextInput::make('cost')
                                        ->label('Cost')
                                        ->numeric()
                                        ->prefix('$')
                                        ->required()
                                        ->rule('gte:0'),
                                    Forms\Components\TimePicker::make('start_time')
                                        ->label('Start Time')
                                        ->required(),
                                    Forms\Components\TimePicker::make('end_time')
                                        ->label('End Time')
                                        ->required(),
                                    Forms\Components\Hidden::make('status')
                                        ->required()
                                        ->default('Added'),
                                ];
                            }
                        }),
                ]),
                Forms\Components\Hidden::make('status')
                ->required()
                ->default('Added'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type.name')
                    ->label('Event Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_external')
                    ->label('External Location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function updateBookingOptions($date, callable $set, callable $get, string $locationKey): void
    {
        $location = $get($locationKey);
        if ($location && $date) {
            $bookings = Booking::where('location_id', $location)
                ->whereDate('booking_date', $date)
                ->whereNull('event_id')
                ->get()
                ->pluck('start_time', 'id')
                ->map(function ($startTime, $id) {
                    $booking = Booking::find($id);
                    return $startTime . ' - ' . $booking->end_time;
                });
            $set('booking_options', $bookings);
        } else {
            // Clear booking options if either location or date is not set
            $set('booking_options', []);
        }
    }
}