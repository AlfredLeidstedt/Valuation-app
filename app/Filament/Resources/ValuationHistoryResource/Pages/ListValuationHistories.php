<?php

namespace App\Filament\Resources\ValuationHistoryResource\Pages;

use App\Filament\Resources\ValuationHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListValuationHistories extends ListRecords
{
    protected static string $resource = ValuationHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
