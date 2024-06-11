<?php

namespace App\Filament\Clusters\TaxCluster\Resources;

use App\Filament\Clusters\TaxCluster;
use App\Filament\Clusters\TaxCluster\Resources\TaxRateResource\Pages;
use App\Filament\Clusters\TaxCluster\Resources\TaxRateResource\RelationManagers;
use App\Models\TaxRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaxRateResource extends Resource
{
    protected static ?string $model = TaxRate::class;

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
                \Filament\Forms\Components\Select::make('tax_zone_id')
                    ->required()
                    ->label('Tax Zone')
                    ->relationship(name: 'taxZone', titleAttribute: 'Name'),
                \Filament\Forms\Components\TextInput::make('priority')
                    ->required()
                    ->numeric()
                    ->label('Priority'),

                \Filament\Forms\Components\Section::make('Amounts')->schema([
                    \Filament\Forms\Components\Repeater::make('taxRateAmounts')->schema([
                        \Filament\Forms\Components\Select::make('tax_class_id')
                            ->relationship(name: 'taxClass', titleAttribute: 'name')
                            ->preload(),
                        \Filament\Forms\Components\TextInput::make('percentage')
                            ->required()
                            ->numeric()
                    ])->relationship('taxRateAmounts')

                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('taxZone.name')
                ->label('Zone')
                ->searchable()
                ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('priority')
                ->label('Priority'),

            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTaxRates::route('/'),
        ];
    }
}
