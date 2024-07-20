<?php

namespace App\Filament\Resources\ProjectsResource\Pages;

use App\Filament\Resources\ProjectsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjects extends EditRecord
{
    protected static string $resource = ProjectsResource::class;
    protected static ?string $title = 'Wijzig project';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
