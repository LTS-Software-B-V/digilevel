<?php
namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Actions\ActionGroup;
use Livewire\Attributes\On;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;
    #[On('refreshForm')]
    public function refreshForm(): void
    {
        $this->fillForm();
    }

    protected $listeners = ["refresh" => '$refresh'];

    protected function getHeaderActions():
    array {
        return [
            Action::make('back')
                ->label('Terug naar overzicht')
                ->link()
                ->icon('heroicon-s-map-pin')
                ->url(url()->previous())
                ->color('gray'),

            Actions\EditAction::make()->icon('heroicon-m-pencil-square')
            ,

            ActionGroup::make([
                Actions\DeleteAction::make('Verwijderen'),
            ]),

        ];
    }

}
