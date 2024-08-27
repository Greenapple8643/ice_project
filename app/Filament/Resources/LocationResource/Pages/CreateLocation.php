<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Location;

class CreateLocation extends CreateRecord
{
    protected static string $resource = LocationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return Location::mutateFormDataBeforeCreate($data);
        // return auth()->user()->mutateData($data, TRUE, FALSE);
    }    
}
