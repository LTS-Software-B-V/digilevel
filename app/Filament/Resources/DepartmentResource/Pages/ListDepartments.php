<?php
namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Afdeling toevoegen')
                ->slideOver()
                ->modalWidth(MaxWidth::FourExtraLarge)
                ->modalHeading('Afdeling toevoegen')
                ->modalSubmitActionLabel('Opslaan')
                ->modalIcon('heroicon-o-plus')
                ->icon('heroicon-m-plus')
                ->slideOver()
                ->label('Afdeling toevoegen'),
        ];
    }
}
