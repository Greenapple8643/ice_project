<?php

namespace App\Filament\Resources\TierResource\Pages;

use App\Filament\Resources\TierResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTier extends ViewRecord
{
    protected static string $resource = TierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
