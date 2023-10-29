<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Filament\Resources\AgencyResource\RelationManagers\DomainsRelationManager;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\KeyValue;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use App\Models\City;
use Illuminate\Database\Eloquent\Model;

class AgencyResource extends Resource
{
    protected static ?string $model = Tenant::class;

    // protected static ?string $model = Agency::class;
    protected static ?int $navigationSort = 40;
    protected static ?string $navigationLabel = 'Agências';
    protected static ?string $modelLabel = 'Agência';
    protected static ?string $pluralModelLabel = 'Agências';
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Gestão';

    // protected static ?string $navigationGroup = 'Global';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informações principais')
                            ->schema([
                                Forms\Components\TextInput::make('id')
                                    ->label(__('models.Tenant.form.id'))
                                    ->helperText(__('models.Tenant.form.id_helperText'))
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn ($operation) => $operation != 'create')
                                    ->dehydrated(fn ($operation) => $operation === 'create')
                                    ->required(fn ($operation) => $operation === 'create')
                                    ->regex('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)$/')
                                    ->suffixIcon('heroicon-o-identification')
                                    ->minLength(2)
                                    ->maxLength(30),

                                Forms\Components\TextInput::make('name')
                                    ->minLength(2)
                                    ->maxLength(50)
                                    ->required()
                                    ->suffixIcon('heroicon-o-building-storefront')
                                    ->label(__('models.Tenant.form.name')),

                                Select::make('city_codigo')
                                    ->label('Município sede')
                                    ->searchable()
                                    ->required()
                                    ->getSearchResultsUsing(
                                        fn (string $search): array => City::whereRaw(
                                            "LOWER(nome) like ?",
                                            [
                                                strtolower("%{$search}%")
                                            ]
                                        )
                                            ->limit(50)
                                            ->get()
                                                ?->map(function (?Model $record) {
                                                    return [
                                                        'label' => "{$record->nome} - {$record?->uf}",
                                                        'codigo' => $record?->codigo,
                                                    ];
                                                })
                                                ?->pluck('label', 'codigo')
                                                ?->toArray()
                                    )
                                    ->getOptionLabelUsing(
                                        // alternativa ao titleAttribute
                                        function ($value): ?string {
                                            $city = City::whereCodigo($value)->first();

                                            if (!$city) {
                                                return null;
                                            }

                                            return "{$city->nome} - {$city?->uf}";
                                        }
                                    ),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Meta configs')
                            ->schema([
                                KeyValue::make('meta.config')
                                    ->label(__('models.Tenant.form.meta_config.label'))
                                    ->keyLabel(__('models.Tenant.form.meta_config.keyLabel'))
                                    ->keyPlaceholder(__('models.Tenant.form.meta_config.keyPlaceholder'))
                                    ->valueLabel(__('models.Tenant.form.meta_config.valueLabel'))
                                    ->valuePlaceholder(__('models.Tenant.form.meta_config.valuePlaceholder'))
                                    ->addActionLabel(__('models.Tenant.form.meta_config.addActionLabel'))
                                    // Bom: afterStateHydrated e formatStateUsing
                                    ->formatStateUsing(static function (?array $state) {
                                        $state = array_filter(
                                            \Arr::wrap($state ?? []),
                                            fn ($value, $key) => !in_array($key, ['color']),
                                            ARRAY_FILTER_USE_BOTH
                                        );

                                        return collect($state ?? [])
                                            ->mapWithKeys(static fn (?string $value, ?string $key): array => [trim($key) => $value])
                                            ->filter(static fn (?string $value, ?string $key): bool => filled($key))
                                            ->map(static fn (?string $value): ?string => filled($value) ? $value : null)
                                            ->all();
                                    })
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Identidade visual')
                            ->schema([
                                FileUpload::make('logo')
                                    ->directory('logos')
                                    // ->preserveFilenames()
                                    // ->disk('public')
                                    ->visibility('public')
                                    ->image(),

                                Forms\Components\ColorPicker::make('meta.color')
                                    ->label(__('models.Tenant.form.meta_config.color_label')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('models.Tenant.table.id'))
                    ->sortable()
                    ->searchable(
                        isIndividual: true,
                    ),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('models.Tenant.table.name'))
                    ->sortable(['id'])
                    ->searchable(
                        isIndividual: true,
                    )
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('models.Tenant.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('models.Tenant.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(), // Evitar deletar em massa
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DomainsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('id')
                    ->inlineLabel(),

                Infolists\Components\TextEntry::make('nome')
                    ->inlineLabel(),

                Infolists\Components\TextEntry::make('city.nome')
                    ->inlineLabel(),

                Infolists\Components\ImageEntry::make('logo')
                    ->inlineLabel(),
            ]);
    }
}
