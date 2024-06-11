<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Filament\Resources\TenantResource\RelationManagers;
use App\Filament\Resources\TenantResource\RelationManagers\TenantUnitsRelationManager;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    // protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->label('Name')
                    ->columnSpanFull(),

                \Filament\Forms\Components\FileUpload::make('image')->nullable()->image()->avatar(),

                \Filament\Forms\Components\TextInput::make('website')
                    ->nullable()
                    ->label('Website')
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->columns(2),

                \Filament\Forms\Components\TextInput::make('gst')
                    ->label('Tax Number')
                    ->placeholder('E.g GST in India')
                    ->minLength(6)
                    ->columns(2),

                \Filament\Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),


            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\ImageColumn::make('image')->circular(),

            TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),


            TextColumn::make('website')
                ->label('Website')
                ->searchable()
                ->url(function ($record) {
                    return $record->website;
                }),

            TextColumn::make('units')
                ->label('Units')
                ->getStateUsing(fn ($record): string => "{$record->tenantUnits()->count()} Unit(s)"),

            IconColumn::make('active')
                ->label('Active')->boolean(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            TenantUnitsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
            'view' => Pages\ViewTenant::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Details')
            ->columns(2)
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('website')->url(function ($record) {
                    return $record->website;
                }),
                TextEntry::make('gst')->copyable()->copyMessage('Copied!'),
                IconEntry::make('active')->icon(fn (bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
            ])
        ]);
    }

}
