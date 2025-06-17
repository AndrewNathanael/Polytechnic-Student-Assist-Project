<?php

namespace App\Filament\Resources\MajoringResource\Pages;

use App\Filament\Resources\MajoringResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMajoring extends EditRecord
{
    protected static string $resource = MajoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
