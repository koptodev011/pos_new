<?php

namespace App\Filament\Clusters\TaxCluster\Resources;

use App\Enums\TaxPriceDisplayType;
use App\Enums\TaxZoneType;
use App\Filament\Clusters\TaxCluster;
use App\Filament\Clusters\TaxCluster\Resources\TaxZoneResource\Pages;
use App\Filament\Clusters\TaxCluster\Resources\TaxZoneResource\RelationManagers;
use App\Models\TaxZone;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaxZoneResource extends Resource
{
    protected static ?string $model = TaxZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = TaxCluster::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->label('Name'),

                \Filament\Forms\Components\Select::make('zone_type')
                ->enum(TaxZoneType::class)
                ->options(TaxZoneType::class),

                \Filament\Forms\Components\Select::make('price_display')
                ->enum(TaxPriceDisplayType::class)
                ->options(TaxPriceDisplayType::class),

                \Filament\Forms\Components\Section::make('Countries')->schema([
                    \Filament\Forms\Components\Repeater::make('taxZoneCountries')->schema([
                        \Filament\Forms\Components\Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return "{$record->emoji} {$record->name}";
                            })
                            ->searchable()
                            ->preload()
                    ])->relationship('taxZoneCountries')

                ]),

                \Filament\Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),

                \Filament\Forms\Components\Toggle::make('default')
                    ->label('Default')
                    ->default(true),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),

                \Filament\Tables\Columns\SelectColumn::make('zone_type')
                ->options(TaxZoneType::class),

                \Filament\Tables\Columns\SelectColumn::make('price_display')
                ->options(TaxPriceDisplayType::class),

                \Filament\Tables\Columns\IconColumn::make('active')
                ->label('Active')->boolean(),

                \Filament\Tables\Columns\IconColumn::make('default')
                ->label('Default')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTaxZones::route('/'),
        ];
    }
}
