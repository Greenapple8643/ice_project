<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Booking;
use App\Models\Event;
use App\Models\EventType;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    public $originalBookingId;
    public $originalStartTime;
    public $originalEndTime;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);
    
        $location_id = $this->record->location_id;
        $location_external = $this->record->location_external;
        $cost = $this->record->cost;
        $date = $this->record->date;
    
        $bookings = Booking::where('location_id', $location_id)
            ->whereDate('booking_date', $date)
            ->where(function ($query) use ($record) {
                $query->whereNull('event_id')
                      ->orWhere('event_id', $this->record->id); // Include the current event's booking
            })
            ->get(['id', 'start_time', 'end_time'])
            ->mapWithKeys(function ($booking) {
                return [$booking->id => $booking->start_time . ' - ' . $booking->end_time];
            })
            ->toArray();
            
        $eventType = EventType::find($this->record->type_id);
    
        $this->originalBookingId = $this->record->booking_id;
        $this->originalStartTime = $this->record->start_time;
        $this->originalEndTime = $this->record->end_time;
    
        $this->form->fill([
            'location_id' => $location_id,
            'location_external' => $location_external,
            'cost' => $cost,
            'date' => $date,
            'booking_id' => $this->record->booking_id,
            'booking_options' => $bookings,
            'start_time' => $this->record->start_time,
            'end_time' => $this->record->end_time,
            'type_id' => $this->record->type_id,
            'status' => $this->record->status,
            'home_team_id' => $this->record->home_team_id,
            'away_team_id' => $this->record->away_team_id,
            'requires_ice' => $eventType ? $eventType->requires_ice : false,
        ]);
    }

    protected function afterSave()
    {
        $record = $this->record;
        $newBookingId = $record->booking_id;

        if ($this->originalBookingId !== $newBookingId) {
            // Update original booking
            Booking::where('id', $this->originalBookingId)->update(['event_id' => null]);

            // Update new booking
            Booking::where('id', $newBookingId)->update(['event_id' => $record->id]);
        }

        if ($this->originalStartTime !== $record->start_time || $this->originalEndTime !== $record->end_time) {
            // Update status to 'Rescheduled' if the time has changed
            $record->status = 'Rescheduled';
            $record->save();
        }
    }
}