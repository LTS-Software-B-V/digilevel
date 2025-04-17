<?php
namespace App\Filament\Resources\RelationTypeResource\Pages;

use App\Filament\Resources\RelationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListRelationTypes extends ListRecords
{
    protected static string $resource = RelationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Relatie Type toevoegen')
                ->slideOver()
                ->modalWidth(MaxWidth::FourExtraLarge)
                ->modalHeading('Object Type toevoegen')
                ->modalDescription('Voeg een nieuw relatie type toe door de onderstaande gegeven zo volledig mogelijk in te vullen.')
                ->modalSubmitActionLabel('Opslaan')
                ->modalIcon('heroicon-o-plus')
                ->icon('heroicon-m-plus')
                ->slideOver()
                ->label('Object Type toevoegen'),
        ];
    }

    public function getHeading(): string
    {
        return "Relatie Types - Overzicht";
    }
}
