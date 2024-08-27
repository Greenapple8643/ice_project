<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Ramsey\Uuid\Uuid;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['accesscode'] = UUid::uuid4()->toString();
        return auth()->user()->mutateData($data,TRUE);
    }  
}
