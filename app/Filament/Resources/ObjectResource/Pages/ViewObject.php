<?php
namespace App\Filament\Resources\ObjectResource\Pages;

use App\Filament\Resources\ObjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewObject extends ViewRecord
{
    protected static string $resource = ObjectResource::class;
    protected static ?string $title   = 'Objecten eigenschappen';

    public function getSubheading(): ?string
    {

        if ($this->getRecord()->monitoring_object_id) {

            return "Monitoring: " . ucfirst($this?->getRecord()?->getMonitoringVersion?->brand) . " - " . $this->getRecord()?->getMonitoringVersion?->value . " " . $this->getRecord()?->getMonitoringType?->value . " " . $this->getRecord()?->getMonitoringStateText();

        } else {
            if ($this->getRecord()->location) {

                $location_name = null;
                if ($this->getRecord()->location?->name) {
                    $location_name = " | " . $this->getRecord()->location?->name;
                }
                return $this->getRecord()->location->address . " " . $this->getRecord()->location->zipcode . " " . $this->getRecord()->location->place . $location_name;

            } else {
                return "";
            }
        }

    }
    public function getHeaderWidgetsColumns(): int | array
    {
        return 8;
    }
    protected function getHeaderWidgets(): array
    {

        if ($this->getRecord()->monitoring_object_id) {
            return [
                // ObjectResource\Widgets\Monitoring::class,
                ObjectResource\Widgets\MonitoringStopsenDoors::class,
                ObjectResource\Widgets\MonitoringIncidentChart::class,
            ];
        } else {
            return [];
        }
    }
    protected function getHeaderActions(): array
    {

        if ($this->getRecord()->monitoring_object_id) {
            return [

                Actions\Action::make('cancel_top')
                    ->iconButton()
                    ->color('gray')
                    ->label('Uitgebreide monitoring')
                    ->link()
                    ->url(function ($record) {
                        return $this->getRecord()?->id . "/monitoring";
                    }),

                Actions\Action::make('cancel_top')
                    ->iconButton()
                    ->color('gray')
                    ->label('Open locatie')
                    ->link()
                    ->icon('heroicon-s-map-pin')
                    ->url(function ($record) {
                        return "/object-locations/" . $this->getRecord()?->location?->id;
                    }),

                Actions\EditAction::make('cancel_top')
                    ->slideOver()
                    ->icon('heroicon-m-pencil-square')
                    ->label('Wijzig'),

            ];
        } else {
            return [

                Actions\Action::make('cancel_top')
                    ->iconButton()
                    ->color('gray')
                    ->label('Open locatie')
                    ->link()
                    ->icon('heroicon-s-map-pin')
                    ->url(function ($record) {
                        return "/object-locations/" . $this->getRecord()?->location?->id;
                    }),

                Actions\EditAction::make('cancel_top')
                    ->slideOver()
                    ->icon('heroicon-m-pencil-square')
                    ->label('Wijzig'),

            ];
        }

    }

}
