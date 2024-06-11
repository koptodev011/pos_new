<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTenantUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'userTenantUnits';

    protected static ?string $title = 'Units';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tenant_unit_id')
                    ->label('Tenant Unit')
                    ->preload()
                    ->relationship(name: 'tenantUnit', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                        $user = auth()->user();
                        if($user->hasAnyRole(['Super Admin'])) {
                            return $query;
                        }
                        $tenant_unit_ids = $user->userTenantUnits()->pluck('tenant_unit_id')->all();
                        return $query->whereIn('id', $tenant_unit_ids);
                    })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tenantUnit.name')
            ->columns([
                Tables\Columns\TextColumn::make('tenantUnit.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
