<?php

namespace App\Filament\Resources\MajoringResource\Pages;

use App\Filament\Resources\MajoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMajoring extends ViewRecord
{
    protected static string $resource = MajoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
