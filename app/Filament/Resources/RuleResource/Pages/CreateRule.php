<?php

namespace App\Filament\Resources\RuleResource\Pages;

use App\Filament\Resources\RuleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

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

    protected function getCreatedNotificationTitle(): ?string
{
    return 'Rule saved! 🎉';
}

}
