<?php

namespace App\Filament\Resources\SeasonResource\Pages;

use App\Filament\Resources\SeasonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;

class CreateSeason extends CreateRecord
{
    protected static string $resource = SeasonResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return auth()->user()->mutateData($data);
    }
}
