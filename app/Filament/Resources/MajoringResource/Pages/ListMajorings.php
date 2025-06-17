<?php

namespace App\Filament\Resources\MajoringResource\Pages;

use App\Filament\Resources\MajoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMajorings extends ListRecords
{
    protected static string $resource = MajoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
