<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FloorTableResource\Pages;
use App\Filament\Resources\FloorTableResource\RelationManagers;
use App\Models\FloorTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FloorTableResource extends Resource
{
    protected static ?string $model = FloorTable::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Restaurant';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name'),
                \Filament\Forms\Components\TextInput::make('min_capacity')->integer()->default(1),
                \Filament\Forms\Components\TextInput::make('max_capacity')->integer()->default(1),
                \Filament\Forms\Components\TextInput::make('extra_capacity')->integer()->default(0),
                \Filament\Forms\Components\TextInput::make('floor')->nullable(),
                \Filament\Forms\Components\Toggle::make('active')->default(true),
                \Filament\Forms\Components\Select::make('tenant_unit_id')
                    ->relationship(
                        name: 'tenantUnit',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            $user = auth()->user();
                            $tenantUnitIDs = $user->userTenantUnits()->pluck('tenant_unit_id')->all();
                            $query->whereIn('id', $tenantUnitIDs);
                        },
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name'),
                \Filament\Tables\Columns\TextColumn::make('min_capacity')
                    ->numeric(),
                \Filament\Tables\Columns\TextColumn::make('max_capacity')
                    ->numeric(),
                \Filament\Tables\Columns\TextColumn::make('extra_capacity')
                    ->numeric(),
                \Filament\Tables\Columns\TextColumn::make('tenantUnit.name')
                    ->label('Unit'),
                \Filament\Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                // \Filament\Tables\Columns\ViewColumn::make('Share')->view('filament.tables.columns.qrcode')
                // \Filament\Tables\Columns\TextColumn::make('share_url')
                //     ->label('Share')
                //     ->getStateUsing(fn ($record): string => "Copy")
                //     ->copyable()
                //     ->copyMessage('Copied')
                //     ->copyMessageDuration(1500)
                //     ->copyableState(fn ($record): string => "{$record->share_url}"),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('Share')
                    ->url(fn ($record): string => $record->share_url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-share'),
                    
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
            'index' => Pages\ManageFloorTables::route('/'),
        ];
    }

    
}
