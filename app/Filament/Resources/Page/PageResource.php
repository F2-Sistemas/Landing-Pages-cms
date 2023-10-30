<?php

namespace App\Filament\Resources\Page;

use App\Filament\Resources\Page\PageResource\Pages;
use App\Models\Page\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Toggle;
use Closure;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informações principais')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Select::make('tenant_id')
                                            ->label(__('models.User.form.tenant_id'))
                                            ->hidden(
                                                fn (?Model $record) => tenancy()?->initialized
                                                // || !Auth::user()?->canAny(['editTenantUser', 'editAny'], $record) // TODO: adicionar pacote spatie/permission
                                            )
                                            ->disabled(
                                                fn (?Model $record) => tenancy()?->initialized
                                                // || !Auth::user()?->canAny(['edit', 'editAny'], $record) // TODO: adicionar pacote spatie/permission
                                            )
                                            ->disabledOn('edit')
                                            ->searchable()
                                            ->preload()
                                            ->options(
                                                cache()->remember(
                                                    'tenant_id_first_10',
                                                    60 * 5,
                                                    fn () => Tenant::orderBy('id')
                                                        ->select('id')
                                                        ->limit(10)
                                                        ->get()
                                                            ?->pluck('id', 'id')
                                                            ?->toArray() ?: []
                                                )
                                            )
                                            ->getSearchResultsUsing(
                                                fn (string $search): array => Tenant::orderBy('id')
                                                    ->select('id')
                                                    ->whereRaw(
                                                        "LOWER(id) like ?",
                                                        [
                                                            strtolower("%{$search}%")
                                                        ]
                                                    )
                                                    ->limit(50)
                                                    ->get()
                                                        ?->pluck('id', 'id')
                                                        ?->toArray()
                                            )
                                            ->getOptionLabelUsing(
                                                fn ($value): ?string => Tenant::whereId($value)->first()?->id
                                            ),
                                    ])
                                    ->columnSpanFull(),

                                Grid::make(7)
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label(__('models.Page.form.title'))
                                            ->live(debounce: 1000)
                                            ->afterStateHydrated(
                                                function ($operation, $state, callable $set, callable $get) {
                                                    if (
                                                        !filled($state) || ($operation != 'create')
                                                        && filled("{$get('slug')}")
                                                    ) {
                                                        return;
                                                    }

                                                    $set('slug', str($state)->trim()->slug());
                                                }
                                            )
                                            ->afterStateUpdated(
                                                function ($operation, $state, callable $set, callable $get) {
                                                    if (($operation != 'create') || filled("{$get('slug')}")) {
                                                        return;
                                                    }

                                                    $set('slug', str($state)->trim()->slug()->toString());
                                                }
                                            )
                                            ->columnSpan(3)
                                            ->required(),

                                        Forms\Components\TextInput::make('slug')
                                            ->label(__('models.Page.form.slug'))
                                            ->helperText(__('models.Page.form.slug_helperText'))
                                            ->unique(ignoreRecord: true)
                                            ->disabled(function ($operation, ?Model $record) {
                                                $value = trim("{$record?->slug}");

                                                if ($operation != 'create') {
                                                    return boolval($value);
                                                }

                                                return false;
                                            })
                                            ->dehydrated(
                                                function ($operation, ?Model $record) {
                                                    if ($operation === 'create') {
                                                        return true;
                                                    }

                                                    return !filled(trim("{$record?->slug}"));
                                                }
                                            )
                                            ->required(function ($operation, ?Model $record) {
                                                $value = trim("{$record?->slug}");

                                                if ($operation === 'create') {
                                                    return boolval($value);
                                                }
                                            })
                                            ->prefix(fn () => str(url('/'))->finish('/'))
                                            ->live(onBlur: true, debounce: 500)
                                            ->afterStateHydrated(
                                                fn ($state, callable $set) => $set('slug', str($state)->trim()->slug())
                                            )
                                            ->afterStateUpdated(
                                                fn ($state, callable $set) => $set('slug', str($state)->trim()->slug())
                                            )
                                            ->rules([
                                                function () {
                                                    return function (string $attribute, $value, Closure $fail) {
                                                        if (
                                                            !preg_match(
                                                                '/^(?!-)(?!.*--)[a-z0-9\-]{2,100}(?<!-)$/',
                                                                $value
                                                            )
                                                        ) {
                                                            $fail('The :attribute is invalid.');
                                                        }
                                                    };
                                                },
                                            ])
                                            ->minLength(2)
                                            ->maxLength(100)
                                            ->columnSpan(4),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('page_contents')
                            ->heading(__('models.Page.form.page_contents.heading'))
                            ->schema([
                                // begin page contents
                                Builder::make('content')
                                    ->blocks([
                                        Builder\Block::make('heading')
                                            ->schema([
                                                TextInput::make('content')
                                                    ->label('Heading')
                                                    ->required(),
                                                Select::make('level')
                                                    ->options([
                                                        'h1' => 'Heading 1',
                                                        'h2' => 'Heading 2',
                                                        'h3' => 'Heading 3',
                                                        'h4' => 'Heading 4',
                                                        'h5' => 'Heading 5',
                                                        'h6' => 'Heading 6',
                                                    ])
                                                    ->required(),
                                            ])
                                            ->columns(2),
                                        Builder\Block::make('paragraph')
                                            ->schema([
                                                Textarea::make('content')
                                                    ->label('Paragraph')
                                                    ->required(),
                                            ]),
                                        Builder\Block::make('image')
                                            ->schema([
                                                FileUpload::make('url')
                                                    ->label('Image')
                                                    ->visibility('private')
                                                    ->disk('tenant_public')
                                                    ->image()
                                                    ->required(),
                                                TextInput::make('alt')
                                                    ->label('Alt text')
                                                    ->required(),
                                            ]),
                                    ])
                                // end page contents
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('visibility')
                            ->heading(__('models.Page.form.visibility'))
                            ->schema([
                                Toggle::make('only_auth')
                                    ->label(__('models.Page.form.only_auth'))
                                    ->helperText(__('models.Page.form.only_auth_helperText'))
                                    ->default(false),

                                Toggle::make('published')
                                    ->label(__('models.Page.form.published'))
                                    ->helperText(__('models.Page.form.published_helperText'))
                                    ->default(true),
                            ]),

                        Forms\Components\Section::make('visual_settings')
                            ->heading(__('models.Page.form.visual_settings'))
                            ->schema([
                                Select::make('view')
                                    ->label(__('models.Page.form.view'))
                                    ->options(function () {
                                        // \App\View\Components\WebTheme::getThemes()
                                        return [
                                            'tail-single::pages.landing_01' => 'Tail single Landing 01',
                                        ];
                                    })
                                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('slug')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('tenant_id')
                    ->label(__('models.User.table.tenant_id'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(
                        isIndividual: true
                    )
                    ->hidden(fn () => tenancy()?->initialized)
                    ->sortable()
                    ->searchable(isIndividual: true),

                Tables\Columns\IconColumn::make('only_auth')
                    ->label(__('models.Page.table.only_auth'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('published')
                    ->label(__('models.Page.table.published'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('models.Page.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('models.Page.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->searchable(isIndividual: true)
                //     ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('deleted_at')
                    ->label(__('models.Page.table.deleted_at'))
                    ->sortable(['deleted_at'])
                    ->icon(function (string $state) {
                        if (empty($state)) {
                            return 'heroicon-o-check-circle';
                        }

                        return 'heroicon-o-x-circle';
                    })
                    ->default(fn ($record) => !filled($record->deleted_at))
                    ->options([
                        'heroicon-o-x-circle' => fn ($state, $record): bool => $record->deleted_at != '',
                        'heroicon-o-check-circle' => fn ($state, $record): bool => $record->deleted_at == '',
                    ])
                    ->colors([
                        'danger' => fn ($state, $record): bool => $record->deleted_at != '',
                        'success' => fn ($state, $record): bool => $record->deleted_at == '',
                    ]),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('deleted_at')
                    ->nullable()
                    ->placeholder(__('models.Page.filters.ternary.placeholder')) // ('All users')
                    ->trueLabel(__('models.Page.filters.ternary.truelabel')) // ('Verified users')
                    ->falseLabel(__('models.Page.filters.ternary.falselabel')) // ('Not verified users')
                    ->label(__('models.common.filters.ternary.active'))
                    ->nullable()
                    ->attribute('deleted_at')
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->url(fn (?Model $record) => route('pages.show', $record?->slug))
                    ->hidden(fn (?Model $record) => $record?->deleted_at || !($record?->published))
                    ->icon('heroicon-s-eye')
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('models.Page.modelLabel');
    }

    public static function getPluralModelLabel(): string
    {
        return __('models.Page.pluralModelLabel');
    }
}
