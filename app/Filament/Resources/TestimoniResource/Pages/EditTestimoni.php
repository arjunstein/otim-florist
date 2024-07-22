<?php

namespace App\Filament\Resources\TestimoniResource\Pages;

use App\Filament\Resources\TestimoniResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTestimoni extends EditRecord
{
    protected static string $resource = TestimoniResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Testimoni updated')
            ->body('The testimoni has been updated successfully.');
    }
}
