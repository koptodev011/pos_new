<?php

namespace App\Filament\Resources;

use App\Enums\PromoCodeType;
use App\Enums\PromoCodeValueType;
use App\Filament\Resources\PromoCodeResource\Pages;
use App\Filament\Resources\PromoCodeResource\RelationManagers;
use App\Models\PromoCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoCodeResource extends Resource
{
    protected static ?string $model = PromoCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['Owner']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Promo Coupon Details')->schema([

                    Group::make()->schema([
                        \Filament\Forms\Components\TextInput::make('code')
                        ->label('Promo Code')
                        ->required()
                        ->unique(table: PromoCode::class)
                        ->autocapitalize('words')
                        ->maxLength(255),

                        \Filament\Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->autocapitalize('words')
                        ->maxLength(255),

                        \Filament\Forms\Components\Select::make('type')
                        ->enum(PromoCodeType::class)
                        ->label('Type')
                        ->options(PromoCodeType::class)
                        ->required(),

                        \Filament\Forms\Components\TextInput::make('limit')
                        ->label('Limit')
                        ->default(0)
                        ->numeric(),

                        \Filament\Forms\Components\Select::make('value_type')
                        ->enum(PromoCodeValueType::class)
                        ->label('Value Type')
                        ->options(PromoCodeValueType::class)
                        ->required(),

                        \Filament\Forms\Components\TextInput::make('value')
                        ->label('Value')
                        ->numeric()
                        ->required(),

                        \Filament\Forms\Components\DatePicker::make('start_date')
                                ->label('Start Date')
                                ->minDate(now()),

                        \Filament\Forms\Components\DatePicker::make('end_date')
                                ->label('End Date')
                                ->after('start_date')
                                ->nullable()
                                ->validationMessages([
                                    'unique' => 'The End date must be date after Start Date.',
                                ]),

                        \Filament\Forms\Components\TextInput::make('min_value')
                        ->label('Minimum Amount')
                        ->default(0)
                        ->numeric(),

                        \Filament\Forms\Components\TextInput::make('max_value')
                        ->label('Maximum Amount')
                        ->default(0)
                        ->gte('min_value')
                        ->numeric()
                        ->validationMessages([
                            'unique' => 'The Maximun Amount must be greater than Minimum Amount.',
                        ]),


                        \Filament\Forms\Components\RichEditor::make('description')
                        ->columnSpanFull(),

                        \Filament\Forms\Components\Toggle::make('active')
                        ->label('Active')
                        ->default(true),


                    ])->columnSpan(3)->columns(2)
                ])->columns(2),
                Forms\Components\Repeater::make('tenantUnits')
                            ->relationship('tenantUnits')
                            ->schema([
                                Forms\Components\Select::make('tenant_unit_id')
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
                            ->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('value_type')->searchable(),
                Tables\Columns\TextColumn::make('type')->searchable(),
                Tables\Columns\TextColumn::make('value')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('start_date')->date('d-m-Y'),
                \Filament\Tables\Columns\TextColumn::make('end_date')->date('d-m-Y'),
                \Filament\Tables\Columns\IconColumn::make('active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('value_type')
                ->label('Value Type')
                ->options(PromoCodeValueType::class)
                ->attribute('value_type'),

                SelectFilter::make('type')
                ->label('Type')
                ->options(PromoCodeType::class)
                ->attribute('type')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }
}
