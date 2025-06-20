<?php

namespace App\Filament\Officer\Resources\ComplaintResource\Pages;

use App\Filament\Officer\Resources\ComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewComplaint extends ViewRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
