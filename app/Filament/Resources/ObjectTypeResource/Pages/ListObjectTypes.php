<?php
namespace App\Filament\Resources\ObjectTypeResource\Pages;

use App\Filament\Resources\ObjectTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListObjectTypes extends ListRecords
{
    protected static string $resource = ObjectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Object Type toevoegen')
                ->slideOver()
                ->modalWidth(MaxWidth::FourExtraLarge)
                ->modalHeading('Object Type toevoegen')
                ->modalDescription('Voeg een nieuw object type toe door de onderstaande gegeven zo volledig mogelijk in te vullen.')
                ->modalSubmitActionLabel('Opslaan')
                ->modalIcon('heroicon-o-plus')
                ->icon('heroicon-m-plus')
                ->slideOver()
                ->label('Object Type toevoegen'),
        ];
    }

    public function getHeading(): string
    {
        return "Object Types - Overzicht";
    }
}

