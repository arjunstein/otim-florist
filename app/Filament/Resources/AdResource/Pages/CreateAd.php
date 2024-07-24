<?php

namespace App\Filament\Resources\AdResource\Pages;

use App\Filament\Resources\AdResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateAd extends CreateRecord
{
    protected static string $resource = AdResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ad created')
            ->body('The ad has been created successfully.');
    }
}
