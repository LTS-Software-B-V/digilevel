<?php

namespace App\Filament\Resources\Connections\Elevators\modusystemResource\Pages;

use App\Filament\Resources\Connections\Elevators\modusystemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Editmodusystem extends EditRecord
{
    protected static string $resource = modusystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
