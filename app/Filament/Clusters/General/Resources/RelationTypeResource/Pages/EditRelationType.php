<?php

namespace App\Filament\Clusters\General\Resources\RelationTypeResource\Pages;

use App\Filament\Clusters\General\Resources\RelationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelationType extends EditRecord
{
    protected static string $resource = RelationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
