<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantUnitResource\Pages;
use App\Filament\Resources\TenantUnitResource\RelationManagers;
use App\Models\TenantUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenantUnitResource extends Resource
{
    protected static ?string $model = TenantUnit::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListTenantUnits::route('/'),
            'create' => Pages\CreateTenantUnit::route('/create'),
            'edit' => Pages\EditTenantUnit::route('/{record}/edit'),
        ];
    }
}
