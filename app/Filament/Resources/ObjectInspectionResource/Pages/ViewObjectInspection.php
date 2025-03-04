<?php
namespace App\Filament\Resources\ObjectInspectionResource\Pages;

use App\Filament\Resources\ObjectInspectionResource;
use Filament\Actions;
use Filament\Facades\Filament;
//Form

use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;

class ViewObjectInspection extends ViewRecord
{
    protected static string $resource         = ObjectInspectionResource::class;
    protected static ?string $navigationLabel = "Keuringen";
    protected static ?string $navigationIcon  = 'heroicon-m-check-badge';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cancel_top')
                ->iconButton()
                ->color('gray')
                ->label('Naar object')
                ->link()
                ->visible(fn($record) => $record->elevator->id ?? false)
                ->icon('heroicon-o-arrow-uturn-left')
                ->url(function ($record) {
                    return "/" . Filament::getTenant()->id . "/objects/" . $record->elevator_id . "/?activeRelationManager=3";

                }),

            Actions\Action::make("Downloaddocument")->color("warning")
                ->label("Download rapport")
                ->icon("heroicon-o-document-arrow-down")
                ->link()
                ->fillForm(
                    fn($record): array=> [
                        "filename" =>
                        $record->status_id->getlabel() .
                        " - Report - " .
                        $record?->elevator?->location?->address .
                        ", " .
                        $record?->elevator?->location?->place,
                    ]
                )

                ->action(function ($data, $record) {
                    if ($record->schedule_run_token) {
                        //    $contents = base64_decode($record->document);

                        $contents = base64_decode($record->document);
                        $path     = public_path($data["filename"] . ".pdf");

                        file_put_contents($path, $contents);
                        return response()
                            ->download($path)
                            ->deleteFileAfterSend(true);
                    } else {

                        $path = "storage/" . $record["document"];

                        return response()
                            ->download($path);

                    }

                })
                ->modalWidth(MaxWidth::Large)
                ->modalHeading("Bestand downloaden")
                ->modalDescription(
                    "Geef een bestandsnaam om om het bestand te downloaden"
                )

                ->form([
                    TextInput::make("filename")
                        ->label("Bestandsnaam")
                        ->required(),
                ])
                ->visible(fn($record) => $record?->document ?? true),
            Actions\EditAction::make('cancel_top')
                ->icon('heroicon-o-pencil')
                ->label('Wijzig')
                ->hidden(fn($record) => $record->external_uuid),

            //  ->hidden(fn($record) => $record?->schedule_run_token &&  $record?->if_match <> 0)
        ];
    }

    public function getSubheading(): ?string
    {
        if ($this->getRecord()->schedule_run_token) {
            return "Geimporteerd vanuit de koppeling met " . $this->getRecord()?->inspectioncompany?->name;
        } else {
            return "";
        }

    }

}
