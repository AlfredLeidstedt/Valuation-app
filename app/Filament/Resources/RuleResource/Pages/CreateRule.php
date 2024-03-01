<?php

namespace App\Filament\Resources\RuleResource\Pages;

use App\Filament\Resources\RuleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;


class CreateRule extends CreateRecord
{
    protected static string $resource = RuleResource::class;

  
    protected function mutateFormDataBeforeCreate(array $data): array
{
    $valuesCounter = 0;

    foreach ($data as $key => $value) {
        if ($value !== null) {
            $valuesCounter++;
        }
    }

    $data['numberOfSetValues'] = $valuesCounter;

    return $data;
}

    protected function getCreatedNotification(): ?Notification
{
    return Notification::make()
    ->success()
    ->title('Rule saved! 🎉')
    ->body('Make sure to add a deduction as well!');
}

}
