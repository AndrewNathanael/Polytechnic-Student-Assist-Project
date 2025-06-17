<?php

namespace App\Filament\Resources\StudyprogramResource\Pages;

use App\Filament\Resources\StudyprogramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudyprogram extends EditRecord
{
    protected static string $resource = StudyprogramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
