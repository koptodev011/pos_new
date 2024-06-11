<?php

namespace App\Filament\Clusters\TaxCluster\Resources;

use App\Filament\Clusters\TaxCluster;
use App\Filament\Clusters\TaxCluster\Resources\TaxClassResource\Pages;
use App\Filament\Clusters\TaxCluster\Resources\TaxClassResource\RelationManagers;
use App\Models\TaxClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaxClassResource extends Resource
{
    protected static ?string $model = TaxClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = TaxCluster::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->label('Name')
                    ->columnSpanFull(),

                \Filament\Forms\Components\Toggle::make('default')
                    ->label('Default')
                    ->default(true),
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

                \Filament\Tables\Columns\IconColumn::make('default')
                ->label('Default')->boolean(),
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
            'index' => Pages\ManageTaxClasses::route('/'),
        ];
    }
}
