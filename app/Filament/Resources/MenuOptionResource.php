<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuOptionResource\Pages;
use App\Filament\Resources\MenuOptionResource\RelationManagers;
use App\Models\MenuOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuOptionResource extends Resource
{
    protected static ?string $model = MenuOption::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Restaurant';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                        ->required()
                        ->autocapitalize('words')
                        ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('price')
                        ->required()
                        ->default(0.0)
                        ->numeric()
                        ->prefix(function () {
                            $user = auth()->user();
                            return $user->tenant->currency;
                        }),
                \Filament\Forms\Components\TextInput::make('priority')
                        ->required()
                        ->default(0)
                        ->numeric(),
                \Filament\Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),
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
                \Filament\Tables\Columns\TextColumn::make('priority')->numeric(),
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
            'index' => Pages\ListMenuOptions::route('/'),
            'create' => Pages\CreateMenuOption::route('/create'),
            'edit' => Pages\EditMenuOption::route('/{record}/edit'),
        ];
    }
}
