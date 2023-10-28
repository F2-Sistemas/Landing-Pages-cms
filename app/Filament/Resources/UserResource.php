<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Checkbox;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Usuários';

    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
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
                            ->getOptionLabelUsing(fn ($value): ?string => Tenant::whereId($value)->first()?->id),
                    ])
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpan(1),

                TextInput::make('email')
                    ->email()
                    ->label('E-mail')
                    ->required()
                    ->disabled(fn ($operation) => $operation != 'create')
                    ->unique(ignoreRecord: true)
                    ->columnSpan(1),

                Grid::make(5)
                    ->schema([
                        TextInput::make('password')
                            ->label(__('models.User.form.password'))
                            ->password(fn (callable $get) => !filter_var($get('show_password'), FILTER_VALIDATE_BOOL))
                            ->required(fn ($operation) => $operation === 'create')
                            ->unique(ignoreRecord: true)
                            ->columnSpan(3),

                        Checkbox::make('show_password')
                            ->label(__('models.User.form.show_password'))
                            ->live()
                            ->dehydrated(false)
                            ->columnSpan(2),
                    ])->columnSpan(1),

                // Toggle::make('is_admin')
                //     ->label(__('models.User.form.is_admin'))
                //     ->disabled(fn() => !Auth::user()?->canAny(['editAdmin', 'addAdmin']))
                //     ->hidden(fn() => !Auth::user()?->canAny(['editAdmin', 'addAdmin']))
                //     ->default(false)
                //     ->dehydrated(false),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('models.User.table.id'))
                    ->searchable(
                        isIndividual: true
                    )
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('models.User.table.name'))
                    ->searchable(
                        isIndividual: true
                    )
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('models.User.table.email'))
                    ->searchable(
                        isIndividual: true
                    )
                    ->sortable(),

                TextColumn::make('tenant_id')
                    ->label(__('models.User.table.tenant_id'))
                    ->searchable(
                        isIndividual: true
                    )
                    ->hidden(fn () => tenancy()?->initialized)
                    ->sortable(),

                ToggleColumn::make('is_admin')
                    ->label(__('models.User.table.is_admin'))
                    ->hidden(fn () => tenancy()?->initialized),

                // IconColumn::make('deleted_at')
                //     ->label(__('models.User.table.deleted_at'))
                //     ->icon(function (string $state) {
                //             if (empty($state)) {
                //                 return 'heroicon-o-check-circle';
                //             }
                //             return 'heroicon-o-x-circle';
                //         })
                // ->default(function ($record) {
                //     \Log::info(boolval($record->deleted_at));
                //     return empty($record->deleted_at);
                // })
                // ->options([
                //     'heroicon-o-x-circle'     => fn ($state, $record): bool => $record->deleted_at != '',
                //     'heroicon-o-check-circle' => fn ($state, $record): bool => $record->deleted_at == '',
                // ])
                // ->colors([
                //     'danger'  => fn ($state, $record): bool => $record->deleted_at != '',
                //     'success' => fn ($state, $record): bool => $record->deleted_at == '',
                // ]),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        if ($tenant = tenant()) {
            $query
                ->whereNotNull('tenant_id')
                ->where([
                    'tenant_id' => $tenant?->id,
                ]);
        }

        return $query;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
