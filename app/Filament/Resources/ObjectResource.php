<?php
namespace App\Filament\Resources;

use App\Enums\ElevatorStatus;
use App\Enums\InspectionStatus;
use App\Filament\Resources\ObjectResource\Pages;
use App\Filament\Resources\ObjectResource\RelationManagers;
use App\Models\Customer;
use App\Models\Elevator;
use App\Models\ObjectMonitoring;
use App\Models\ObjectType;
use App\Models\Relation;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ObjectResource extends Resource
{
    protected static ?string $model = Elevator::class;
    protected static ?string $navigationIcon = "heroicon-c-arrows-up-down";
    protected static ?string $navigationLabel = "Objecten";
    protected static ?string $pluralModelLabel = 'Objecten';
    protected static ?string $navigationGroup = 'Objecten';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(4)->schema([
                TextInput::make("nobo_no")
                    ->label("NOBO Nummer")
                    ->placeholder("Niet opgegeven"),
                Select::make("monitoring_object_id")
                    ->label("Status")
                    ->label("Monitoring ID")
                    ->searchable()
                    ->placeholder("Niet opgegeven")
                    ->reactive()
                    ->options(ObjectMonitoring::groupBy("external_object_id")->pluck("external_object_id", "external_object_id")),
                Select::make("type_id")
                    ->label("Type")
                    ->options(ObjectType::where("is_active", 1)->pluck("name", "id")),
                TextInput::make("unit_no")->label("Unit Nummer"),
                Select::make("energy_label")
                    ->live()
                    ->label("Energielabel")
                    ->options([
                        "A" => "A",
                        "B" => "B",
                        "C" => "C",
                        "D" => "D",
                        "E" => "E",
                        "F" => "F",
                    ]),
                Select::make("status_id")
                    ->label("Status")
                    ->options(ElevatorStatus::class),
                Select::make("supplier_id")
                    ->label("Leverancier")
                    ->options(Relation::where('type_id', 4)->pluck("name", "id")),
                TextInput::make("stopping_places")
                    ->label("Stoppplaatsen")
                    ->placeholder("Niet opgegeven"),
                TextInput::make("construction_year")
                    ->label("Bouwjaar")
                    ->placeholder("Niet opgegeven"),
                Select::make("maintenance_company_id")
                    ->label("Onderhoudspartij")
                    ->options(Relation::where('type_id', 1)->pluck("name", "id")),
                Select::make("inspection_company_id")
                    ->label("Keuringsinstantie")
                    ->live()
                    ->options(Relation::where('type_id', 3)->pluck("name", "id")),
                TextInput::make("name")->label("Naam"),
            ]),
            Grid::make(2)->schema([
                Textarea::make("remark")
                    ->rows(7)
                    ->label('Notitie')
                    ->columnSpan(3)
                    ->autosize()
                    ->hint(fn($state, $component) => "Aantal karakters: " . $component->getMaxLength() - strlen($state) . '/' . $component->getMaxLength())
                    ->maxlength(255)
                    ->reactive(),
            ]),
            Section::make("Afbeeldingen")
                ->description('Afbeeldingen van het object')
                ->compact()
                ->schema([
                    SpatieMediaLibraryFileUpload::make('objectimage')
                        ->directory("objects/images/")
                        ->responsiveImages()
                        ->image()
                        ->hiddenlabel()
                        ->panelLayout('grid')
                        ->maxFiles(8)
                        ->label('Afbeeldingen')
                        ->multiple()
                        ->collection('objectimages'),
                ])
                ->collapsible()
                ->collapsed(false)
                ->persistCollapsed()
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('ifMonitoring')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->trueIcon('heroicon-o-check-badge')
                    ->boolean()
                    ->label('Monitoring')
                    ->alignment(Alignment::Center)
                    ->falseIcon('heroicon-o-x-mark'),
                Tables\Columns\TextColumn::make("unit_no")
                    ->label("Nummer")
                    ->searchable()
                    ->sortable()
                    ->placeholder("Geen unitnummer")
                    ->toggleable(),
                Tables\Columns\TextColumn::make("name")
                    ->label("Naam")
                    ->placeholder("-")
                    ->toggleable(),
                SpatieMediaLibraryImageColumn::make('objectimage')
                    ->label('Afbeelding')
                    ->toggleable()
                    ->limit(2)
                    ->collection('objectimages'),
                Tables\Columns\TextColumn::make("status_id")
                    ->label("Status")
                    ->default(0)
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make("current_inspection_status_id")
                    ->label("KeuringStatus")
                    ->placeholder('-')
                    ->badge(),
                Tables\Columns\TextColumn::make("current_inspection_end_date")
                    ->label("Keuringsdatum")
                    ->placeholder('-')
                    ->sortable()
                    ->date('d-m-Y'),
                Tables\Columns\TextColumn::make("location.address")
                    ->label("Adres")
                    ->searchable()
                    ->sortable()
                    ->hidden(true),
                Tables\Columns\TextColumn::make("incidents_count")
                    ->toggleable()
                    ->counts("incidents")
                    ->label("Storingen")
                    ->alignment(Alignment::Center)
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make("nobo_no")
                    ->toggleable()
                    ->label("Nobonummer")
                    ->searchable()
                    ->placeholder("Geen Nobonummer"),
                Tables\Columns\TextColumn::make("location")
                    ->toggleable()
                    ->getStateUsing(function (Elevator $record): ?string {
                        if ($record?->location?->name) {
                            return $record?->location->name;
                        } else {
                            return $record?->location?->address . " - " . $record?->location?->zipcode . " " . $record?->location?->place;
                        }
                    })
                    ->label("Locatie")
                    ->description(function (Elevator $record) {
                        if (! $record?->location?->name) {
                            return $record?->location?->name;
                        } else {
                            return $record->location->address . " - " . $record->location->zipcode . " " . $record->location->place;
                        }
                    }),
                Tables\Columns\TextColumn::make("location.zipcode")
                    ->label("Postcode")
                    ->searchable()
                    ->hidden(true),
                Tables\Columns\TextColumn::make("location.place")
                    ->toggleable()
                    ->label("Plaats")
                    ->searchable(),
                Tables\Columns\TextColumn::make("location.customer.name")
                    ->toggleable()
                    ->searchable()
                    ->label("Relatie")
                    ->placeholder("Niet gekoppeld aan relatie")
                    ->sortable(),
                Tables\Columns\TextColumn::make("management_company.name")
                    ->toggleable()
                    ->label("Beheerder")
                    ->placeholder("Geen beheerder")
                    ->sortable(),
                Tables\Columns\TextColumn::make("maintenance_company.name")
                    ->searchable()
                    ->toggleable()
                    ->placeholder("Geen onderhoudspartij")
                    ->sortable()
                    ->label("Onderhoudspartij"),
            ])
            ->filters([
                SelectFilter::make('type_id')
                    ->label('Type')
                    ->options(ObjectType::where('is_active', 1)->pluck('name', 'id')),
                SelectFilter::make('maintenance_company_id')
                    ->label('Onderhoudspartij')
                    ->options(Relation::where('type_id', 1)->pluck("name", "id")),
                SelectFilter::make('status_id')
                    ->label("Status")
                    ->options(ElevatorStatus::class),
                SelectFilter::make('customer_id')
                    ->label('Relatie')
                    ->options(Customer::all()->pluck("name", "id")),
                SelectFilter::make("current_inspection_status_id")
                    ->searchable()
                    ->label("Keuringstatus")
                    ->options(InspectionStatus::class),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                EditAction::make()
                    ->modalHeading('Snel bewerken')
                    ->tooltip('Bewerken')
                    ->label('')
                    ->modalIcon('heroicon-o-pencil')
                    ->slideOver(),
                DeleteAction::make()
                    ->modalIcon('heroicon-o-trash')
                    ->tooltip('Verwijderen')
                    ->label('')
                    ->modalHeading('Verwijderen')
                    ->color('danger'),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->askForFilename()
                            ->askForWriterType()
                            ->withColumns([
                                Column::make("customer.name")->heading("Relatie"),
                                Column::make("type.name")->heading("Type"),
                                Column::make("unit_no")->heading("Unit no"),
                                Column::make("nobo_no")->heading("Nobo no"),
                                Column::make("energy_label")->heading("Energielael"),
                                Column::make("construction_year")->heading("Bouwjaar"),
                                Column::make("status_id")->heading("Status"),
                                Column::make("supplier.name")->heading("Leverancier"),
                                Column::make("stopping_places")->heading("Stopplaatsen"),
                                Column::make("inspectioncompany.name")->heading("Keuringsinstantie"),
                                Column::make("name")->heading("Naam"),
                                Column::make("management_company.name")->heading("Beheerder"),
                                Column::make("remark")->heading("Opmerking"),
                            ])
                            ->withFilename(date("m-d-Y H:i") . " - objecten export")
                    ])
            ])
            ->emptyState(view("partials.empty-state"));
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Object Details')
                    ->columnSpan('full')
                    ->tabs([
                        Tabs\Tab::make('Basisinformatie')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextEntry::make('unit_no')
                                    ->label('Unitnummer')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('nobo_no')
                                    ->label('NOBO Nummer')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('type.name')
                                    ->label('Type')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('name')
                                    ->label('Naam')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('construction_year')
                                    ->label('Bouwjaar')
                                    ->placeholder('Niet opgegeven'),
                                ViewEntry::make('energy_label')
                                    ->view('filament.infolists.entries.energylabel')
                                    ->label('Energielabel')
                                    ->placeholder('Niet opgegeven'),
                            ])->columns(3),
                            
                        Tabs\Tab::make('Locatie & Relatie')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                TextEntry::make('address')
                                    ->label('Adres')
                                    ->getStateUsing(function ($record): ?string {
                                        $housenumber = $record?->location?->housenumber ? " " . $record?->location?->housenumber : "";
                                        return $record?->location?->address . $housenumber . " - " . $record?->location?->zipcode . " " . $record?->location?->place;
                                    })
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('location.relation.name')
                                    ->label('Relatie')
                                    ->url(fn($record) => "/relations/" . $record?->location?->customer_id)
                                    ->icon('heroicon-c-link')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('stopping_places')
                                    ->label('Stopplaatsen')
                                    ->placeholder('Niet opgegeven'),
                            ])->columns(2),
                            
                        Tabs\Tab::make('Partijen')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                TextEntry::make('supplier.name')
                                    ->label('Leverancier')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('maintenance_company.name')
                                    ->label('Onderhoudspartij')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('inspectioncompany.name')
                                    ->label('Keuringsinstantie')
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('location.managementcompany.name')
                                    ->label('Beheerder')
                                    ->placeholder('Niet opgegeven'),
                            ])->columns(2),
                            
                        Tabs\Tab::make('Status & Keuring')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                TextEntry::make('status_id')
                                    ->label('Status')
                                    ->badge()
                                    ->placeholder('Niet opgegeven'),
                                TextEntry::make('latestInspection.status_id')
                                    ->label('Keuringsstatus')
                                    ->badge()
                                    ->placeholder('Onbekend'),
                                TextEntry::make('current_inspection_end_date')
                                    ->label('Keuringsdatum')
                                    ->date('d-m-Y')
                                    ->placeholder('Onbekend'),
                                TextEntry::make('incidents_count')
                                    ->label('Aantal storingen')
                                    ->badge()
                                    ->placeholder('0'),
                            ])->columns(2),
                            
                        Tabs\Tab::make('Afbeeldingen')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('objectimage')
                                    ->hiddenLabel()
                                    ->placeholder('Geen afbeeldingen')
                                    ->height(200)
                                    ->ring(5)
                                    ->collection('objectimages'),
                            ]),
                            
                        Tabs\Tab::make('Opmerkingen')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->schema([
                                TextEntry::make('remark')
                                    ->label('')
                                    ->columnSpanFull()
                                    ->placeholder('Geen opmerkingen'),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\IncidentsRelationManager::class,
            RelationManagers\MaintenanceContractsRelationManager::class,
            RelationManagers\MaintenanceVisitsRelationManager::class,
            RelationManagers\inspectionsRelationManager::class,
            RelationManagers\ObjectMonitoringRelationManager::class,
            RelationManagers\AttachmentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            "index"   => Pages\ListObjects::route("/"),
            "view"    => Pages\ViewObject::route("/{record}"),
            "monitor" => Pages\MonitorObject::route("/{record}/monitoring"),
        ];
    }
}