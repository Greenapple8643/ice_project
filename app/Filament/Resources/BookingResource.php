<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\BookingResource\RelationManagers\EventsRelationManager;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('program_id')
                    ->relationship('program', 'name')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(['Acquired', 'Returned', 'Deleted'])
                    ->required(),
                Forms\Components\Select::make('league_id')
                    ->relationship('league', 'name')
                    ->required(),
                Forms\Components\Select::make('location_id')
                    ->relationship('location', 'name')
                    ->required(),
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\DatePicker::make('booking_date')
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->seconds(false)
                    ->required(),
                Forms\Components\Toggle::make('used')
                    ->label('Is the ice currently being used?')
                    ->required(),
                Forms\Components\TextInput::make('org_id')
                    ->disabled()
                    ->hidden()
                    ->default(auth()->user()->org_id)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('league.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.abbr')
                    ->label('Arena')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->time('h:i A')
                    ->sortable(),
                Tables\Columns\IconColumn::make('used')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema(components:[
            Section::make(heading:'Booking Information')
            ->schema(components:[
                TextEntry::make(name:'org.name')
                    ->label('Host Organization'),
                TextEntry::make(name:'league.name'),
                TextEntry::make(name:'league.season.name')
                    ->label('Season'),
                TextEntry::make(name:'program.name'),
                TextEntry::make(name:'location.name')
                    ->label('Arena'),
                TextEntry::make(name:'location.address'),
                TextEntry::make(name:'status'),
                TextEntry::make(name:'cost')
                    ->money(),
            ])
        ]);
    }

    public static function getRelations(): array
    {
        return [
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBookings::route('/create'),
            'edit' => Pages\EditBookings::route('/{record}/edit'),
            'view' => Pages\ViewBookings::route('/{record}')
        ];
    }
}
