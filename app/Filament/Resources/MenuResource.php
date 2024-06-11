<?php

namespace App\Filament\Resources;

use App\Enums\MenuOrderRestriction;
use App\Enums\MenuPriceType;
use App\Enums\MenuPriceValidity;
use App\Enums\MenuType;
use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Restaurant';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                \Filament\Forms\Components\Tabs::make('Tabs')
                ->tabs([
                    \Filament\Forms\Components\Tabs\Tab::make('Details')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('name')
                            ->required()
                            ->autocapitalize('words')
                            ->maxLength(255)
                            ->columnSpan(2),
                        \Filament\Forms\Components\Select::make('type')
                                    ->enum(MenuType::class)
                                    ->default(MenuType::Veg->value)
                                    ->label('Type')
                                    ->options(MenuType::class),
                        \Filament\Forms\Components\TextInput::make('price')
                                ->required()
                                ->default(0.0)
                                ->numeric()
                                ->prefix(function () {
                                    $user = auth()->user();
                                    return $user->tenant->currency;
                                }),
                        \Filament\Forms\Components\RichEditor::make('description')
                                ->required()->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('min_qty')
                                ->label('Min. Quantity')
                                ->required()
                                ->default(1)
                                ->numeric(),
                        \Filament\Forms\Components\TextInput::make('priority')
                                ->required()
                                ->default(0)
                                ->numeric(),
                        \Filament\Forms\Components\Select::make('resc')
                            ->enum(MenuOrderRestriction::class)
                            ->label('Order Restriction')
                            ->options(MenuOrderRestriction::class),
                        \Filament\Forms\Components\Select::make('tax_class_id')
                            ->relationship(name: 'taxClass', titleAttribute: 'name')
                            ->label('Tax Class')
                            ->nullable(),

                        \Filament\Forms\Components\Toggle::make('active')
                            ->label('Active')
                            ->default(true),
                        ])->columns(4),
                    \Filament\Forms\Components\Tabs\Tab::make('Special Price')
                        ->schema([

                            \Filament\Forms\Components\Fieldset::make('Price')
                            ->relationship('menuPrice')
                            ->schema([

                                \Filament\Forms\Components\Select::make('type')
                                    ->enum(MenuPriceType::class)
                                    ->label('Type')
                                    ->default(MenuPriceType::Fixed->value)
                                    ->options(MenuPriceType::class)
                                    ->live(),

                                \Filament\Forms\Components\Toggle::make('active')
                                    ->label('Enable/Disable')
                                    ->default(true),

                                \Filament\Forms\Components\TextInput::make('value')
                                    ->label(function (\Filament\Forms\Get $get) {
                                        return $get('type') == MenuPriceType::Percentage->value ? 'Value' : 'Price';
                                    })
                                    ->required()
                                    ->default(0.0)
                                    ->numeric()
                                    ->prefix(function (\Filament\Forms\Get $get) {
                                        if($get('type') == MenuPriceType::Percentage->value) {
                                            return '%';
                                        }
                                        $user = auth()->user();
                                        return $user->tenant->currency;
                                    }),

                                \Filament\Forms\Components\Select::make('validity')
                                    ->enum(MenuPriceValidity::class)
                                    ->default(MenuPriceValidity::Forever->value)
                                    ->label('Validity')
                                    ->options(MenuPriceValidity::class)
                                    ->live(),

                                Section::make('Period')->schema([
                                    \Filament\Forms\Components\DatePicker::make('start_date')
                                    ->nullable(),
                                    \Filament\Forms\Components\DatePicker::make('end_date')
                                    ->nullable()
                                ])->visible(function (\Filament\Forms\Get $get) {
                                    return $get('validity') == MenuPriceValidity::Period->value;
                                }),

                                Section::make('Recurring')->schema([
                                    \Filament\Forms\Components\ToggleButtons::make('days')
                                    ->multiple()
                                    ->options([
                                        Carbon::SUNDAY => 'Sun',
                                        Carbon::MONDAY => 'Mon',
                                        Carbon::TUESDAY => 'Tue',
                                        Carbon::WEDNESDAY => 'Wed',
                                        Carbon::THURSDAY => 'Thu',
                                        Carbon::FRIDAY => 'Fri',
                                        Carbon::SATURDAY => 'Sat',
                                    ])->nullable()
                                    ->columns(7)
                                    ->grouped()
                                    ->gridDirection('row'),
                                    \Filament\Forms\Components\TimePicker::make('start_time')->seconds(false)
                                    ->nullable(),
                                    \Filament\Forms\Components\TimePicker::make('end_time')->seconds(false)
                                    ->nullable()
                                ])->visible(function (\Filament\Forms\Get $get) {
                                    return $get('validity') == MenuPriceValidity::Recurring->value;
                                }),


                            ])

                        ]),
                    \Filament\Forms\Components\Tabs\Tab::make('Options')
                        ->schema([

                            \Filament\Forms\Components\Select::make('menuCategories')
                            ->label('Categories')
                            ->multiple()
                            ->preload()
                            ->relationship(titleAttribute: 'name'),

                            \Filament\Forms\Components\Select::make('menuMealTimes')
                            ->label('Meal Times')
                            ->multiple()
                            ->preload()
                            ->relationship(titleAttribute: 'name'),

                            \Filament\Forms\Components\Select::make('menuOptions')
                            ->label('Add Ons')
                            ->multiple()
                            ->preload()
                            ->relationship(titleAttribute: 'name'),

                            \Filament\Forms\Components\SpatieTagsInput::make('tags')->type('menu')

                        ]),

                    \Filament\Forms\Components\Tabs\Tab::make('Locations')->schema([
                        \Filament\Forms\Components\Repeater::make('tenantUnits')
                        ->relationship('tenantUnits')
                        ->schema([
                            \Filament\Forms\Components\Select::make('tenant_unit_id')
                                ->relationship(name: 'tenantUnit', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    $user = auth()->user();
                                    $tenant_unit_ids = $user->userTenantUnits()->pluck('tenant_unit_id')->all();
                                    return $query->whereIn('id', $tenant_unit_ids);
                                })
                                ->getOptionLabelFromRecordUsing(function ($record) {
                                    return "{$record->name}";
                                })
                                ->preload()
                        ])
                        ->cloneable()
                            ]),

                    \Filament\Forms\Components\Tabs\Tab::make('Images')
                        ->schema([
                            \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                                ->collection('menus')
                                ->multiple()
                                ->reorderable()
                                ->responsiveImages()
                        ])

                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name'),
                \Filament\Tables\Columns\TextColumn::make('price')->money(function () {
                    $user = auth()->user();
                    return $user->tenant->currency;
                }),
                \Filament\Tables\Columns\TextColumn::make('min_qty')->numeric(),
                \Filament\Tables\Columns\IconColumn::make('active')->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
