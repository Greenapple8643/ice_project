<?php

namespace App\Filament\Resources\BlackoutResource\Pages;

use App\Filament\Resources\BlackoutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlackouts extends ListRecords
{
    protected static string $resource = BlackoutResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
