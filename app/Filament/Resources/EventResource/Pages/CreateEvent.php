<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Booking;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate()
    {
        $record = $this->record;
        if ($record->booking_id) {
            $booking = Booking::find($record->booking_id);
            if ($booking) {
                $booking->update(['event_id' => $record->id]);
            }
        }
    }
}
