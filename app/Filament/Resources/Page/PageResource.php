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
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;

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
                                    ->label(__('models.Page.form.page_contents.content'))
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
                                        Builder\Block::make('jumbotron')
                                            ->label(__('models.Page.form.page_contents.blocks.jumbotron_label'))
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('header')
                                                            ->label('Header')
                                                            ->required()
                                                            ->columnSpanFull(),
                                                        Textarea::make('content')
                                                            ->label('Content')
                                                            ->required()
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->columnSpanFull(),

                                                Forms\Components\Section::make('media_blocks')
                                                    ->heading(__('models.Page.form.page_contents.blocks.media_block_heading'))
                                                    ->schema([
                                                        Radio::make('media_type')
                                                            ->options([
                                                                'no_media' => 'No media',
                                                                'youtube_url' => 'YouTube video URL',
                                                                'image_url' => 'Image URL',
                                                                'stored_image' => 'Image upload',
                                                            ])
                                                            ->default('no_media')
                                                            ->live()
                                                            ->columnSpanFull(),

                                                        FileUpload::make('media_image')
                                                            ->label('Image')
                                                            ->visibility('private')
                                                            ->disk('tenant_public')
                                                            ->image()
                                                            ->enableReordering()
                                                            ->hidden(
                                                                fn (callable $get) => $get('media_type') !== 'stored_image'
                                                            )
                                                            ->required(
                                                                fn (callable $get) => $get('media_type') === 'stored_image'
                                                            )
                                                            ->columnSpanFull(),

                                                        TextInput::make('media_url')
                                                            ->label(
                                                                fn (callable $get) => __(
                                                                    "models.Page.form.page_contents.blocks.{$get('media_type')}"
                                                                )
                                                            )
                                                            ->placeholder(
                                                                fn (callable $get) => __(
                                                                    "models.Page.form.page_contents.blocks.{$get('media_type')}"
                                                                )
                                                            )
                                                            ->url()
                                                            ->hidden(
                                                                fn (callable $get) => !in_array(
                                                                    $get('media_type'),
                                                                    ['youtube_url', 'image_url']
                                                                )
                                                            )
                                                            ->required(
                                                                fn (callable $get) => in_array(
                                                                    $get('media_type'),
                                                                    ['youtube_url', 'image_url']
                                                                )
                                                            )
                                                            ->columnSpanFull(),

                                                        Radio::make('media_position')
                                                            ->options([
                                                                'right' => 'right',
                                                                'left' => 'left',
                                                                'no_show' => 'No show',
                                                            ])
                                                            ->descriptions([
                                                                'right' => 'The media will be on right and text on left',
                                                                'left' => 'The media will be on left and text on right',
                                                                'no_show' => 'Hide media on page',
                                                            ])
                                                            ->hidden(
                                                                fn (callable $get) => $get('media_type') === 'no_media'
                                                            )
                                                            ->required(
                                                                fn (callable $get) => $get('media_type') !== 'no_media'
                                                            )
                                                            // ->inline()
                                                            ->inlineLabel()
                                                            ->disableOptionWhen(fn (string $value): bool => $value === 'published')
                                                            ->required()
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->collapsible()
                                                    ->collapsed()
                                                    ->columnSpanFull(),

                                                Forms\Components\Section::make('cta_blocks')
                                                    ->heading(__('models.Page.form.page_contents.blocks.cta_heading'))
                                                    ->schema([
                                                        Repeater::make('members')
                                                            ->schema([
                                                                Grid::make(6)
                                                                    ->schema([
                                                                        TextInput::make('label')
                                                                            ->label('Label')
                                                                            ->required()
                                                                            ->columnSpan(2),

                                                                        Select::make('cta_type')
                                                                            ->options([
                                                                                'button' => 'button',
                                                                                'link' => 'link',
                                                                                'anchor' => 'anchor',
                                                                            ])
                                                                            ->required(),

                                                                        Select::make('cta_class')
                                                                            ->options(collect([
                                                                                'fi-sidebar-item-label flex-1 truncate text-primary-600 dark:text-primary-400 font-semibold',
                                                                                'inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900',
                                                                                'w-full px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'ml-auto text-sm font-medium text-blue-600 hover:underline dark:text-blue-500',
                                                                                'inline font-medium text-blue-600 underline dark:text-blue-500 underline-offset-2 decoration-600 dark:decoration-500 decoration-solid hover:no-underline',
                                                                                'inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800',
                                                                                'inline-flex justify-between items-center py-1 px-1 pr-4 mb-7 text-sm text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800',
                                                                                'text-white right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800',
                                                                                'py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700',
                                                                                'text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700',
                                                                                'text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700',
                                                                                'focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800',
                                                                                'focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
                                                                                'focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900',
                                                                                'focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900',
                                                                                'text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700',
                                                                                'text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700',
                                                                                'text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700',
                                                                                'text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800',
                                                                                'text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
                                                                                'text-white bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:focus:ring-yellow-900',
                                                                                'text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900',
                                                                                'text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-pink-400 via-pink-500 to-pink-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-pink-300 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'px-6 py-3.5 text-base font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                                                                'text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800',
                                                                                'text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800',
                                                                                'text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800',
                                                                                'text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900',
                                                                                'text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900',
                                                                                'text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900',
                                                                                'text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-purple-500 to-pink-500 hover:bg-gradient-to-l focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-gray-900 bg-gradient-to-r from-red-200 via-red-300 to-yellow-200 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-red-100 dark:focus:ring-red-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 ',
                                                                                'text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 shadow-lg shadow-cyan-500/50 dark:shadow-lg dark:shadow-cyan-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 shadow-lg shadow-lime-500/50 dark:shadow-lg dark:shadow-lime-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-pink-400 via-pink-500 to-pink-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-pink-300 dark:focus:ring-pink-800 shadow-lg shadow-pink-500/50 dark:shadow-lg dark:shadow-pink-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2',
                                                                                'text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55 mr-2 mb-2',
                                                                                'text-white bg-[#1da1f2] hover:bg-[#1da1f2]/90 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#1da1f2]/55 mr-2 mb-2',
                                                                                'text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 mr-2 mb-2',
                                                                                'text-white bg-[#4285F4] hover:bg-[#4285F4]/90 focus:ring-4 focus:outline-none focus:ring-[#4285F4]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#4285F4]/55 mr-2 mb-2',
                                                                                'text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#050708]/50 dark:hover:bg-[#050708]/30 mr-2 mb-2',
                                                                                // '',
                                                                                // '',
                                                                            ])
                                                                                ->mapWithKeys(function ($class, $key) {
                                                                                    $keyLabel = ($key + 1);

                                                                                    return ["{$class} key_label_{$keyLabel}" => "<span class=\"{$class} my-2 my-2 flex w-full key_label_{$keyLabel}\">Text demo #{$keyLabel}</span>"];
                                                                                })
                                                                                ->toArray())
                                                                            ->searchable()
                                                                            ->allowHtml()
                                                                            ->columnSpan(3),

                                                                        TextInput::make('cta_value')
                                                                        ->label('Value')
                                                                        ->required()
                                                                        ->columnSpan(2),
                                                                    ])
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->columns(2),
                                                    ])
                                                    ->collapsible()
                                                    ->collapsed()
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->collapsible()
                                    ->collapsed()
                                    ->addAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.addAction_label')
                                        ),
                                    )
                                    ->addBetweenAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.addBetweenAction_label')
                                        ),
                                    )
                                    ->cloneAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.cloneAction_label')
                                        ),
                                    )
                                    ->collapseAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.collapseAction_label')
                                        ),
                                    )
                                    ->collapseAllAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.collapseAllAction_label')
                                        ),
                                    )
                                    ->deleteAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.deleteAction_label')
                                        ),
                                    )
                                    ->expandAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.expandAction_label')
                                        ),
                                    )
                                    ->expandAllAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.expandAllAction_label')
                                        ),
                                    )
                                    ->moveDownAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.moveDownAction_label')
                                        ),
                                    )
                                    ->moveUpAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.moveUpAction_label')
                                        ),
                                    )
                                    ->reorderAction(
                                        fn (Action $action) => $action->label(
                                            __('models.Page.form.page_contents.actions.reorderAction_label')
                                        ),
                                    )
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
