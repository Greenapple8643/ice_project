<?php
// namespace App\Filament\Resources;

// use App\Filament\Resources\BlackoutResource\Pages;
// use App\Models\Blackout;
// use Filament\Forms;
// use Filament\Forms\Form;
// use Filament\Resources\Resource;
// use Filament\Tables;
// use Filament\Tables\Table;
// use Filament\Forms\Components\Section;
// use Filament\Forms\Components\Actions;
// use Filament\Forms\Components\Actions\Action;
// use Filament\Tables\Actions\EditAction;
// use Filament\Tables\Actions\BulkActionGroup;
// use Filament\Tables\Actions\DeleteBulkAction;
// use Ramsey\Uuid\Uuid;
// use Filament\Forms\Components\Select; // Add this import statement
// use Filament\Forms\Components; // Add this import statement

// class BlackoutResource extends Resource
// {
//     protected static ?string $model = Blackout::class;
//     protected static ?string $navigationIcon = 'heroicon-o-calendar';
//     protected static ?string $navigationLabel = 'Blackouts';
//     protected static ?string $navigationGroup = 'Scheduling'; // Optional grouping

//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\TextInput::make('event_type')
//                     ->required()
//                     ->maxLength(255),
//                 Forms\Components\DateTimePicker::make('start_datetime')
//                     ->required(),
//                 Forms\Components\DateTimePicker::make('end_datetime')
//                     ->required(),
//                 Forms\Components\Hidden::make('org_id')
//                     ->default(auth()->user()->org_id)
//                     ->required(),
//             ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('event_type'),
//                 Tables\Columns\TextColumn::make('start_datetime')
//                     ->dateTime(),
//                 Tables\Columns\TextColumn::make('end_datetime')
//                     ->dateTime(),
//             ])
//             ->filters([
//                 //
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\DeleteBulkAction::make(),
//             ]);
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListBlackouts::route('/'),
//             'create' => Pages\CreateBlackout::route('/create'),
//             'edit' => Pages\EditBlackout::route('/{record}/edit'),
//         ];
//     }
// }

namespace App\Filament\Resources;

use App\Filament\Resources\BlackoutResource\Pages;
use App\Models\Blackout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlackoutResource extends Resource
{
    protected static ?string $model = Blackout::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('start_datetime')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_datetime')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_datetime')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_datetime')
                    ->sortable(),
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
            'index' => Pages\ListBlackouts::route('/'),
            'create' => Pages\CreateBlackout::route('/create'),
            'edit' => Pages\EditBlackout::route('/{record}/edit'),
        ];
    }
}


