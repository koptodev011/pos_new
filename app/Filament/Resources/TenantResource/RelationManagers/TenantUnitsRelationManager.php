<?php

namespace App\Filament\Resources\TenantResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenantUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'tenantUnits';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')->schema([
                    FileUpload::make('image')->nullable()->image(),
                    Group::make()->schema([
                        Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                        \Filament\Forms\Components\Toggle::make('active')
                            ->label('Active')
                            ->default(true),
                        \Filament\Forms\Components\Toggle::make('default')
                            ->label('Main Branch')
                            ->default(true),
                    ])->columnSpan(2)->columns(2)
                ])->columns(3),

                Section::make('Address')->schema([
                    Forms\Components\TextInput::make('line_one')
                        ->required()
                        ->label('Line 1')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('line_two')
                        ->nullable()
                        ->label('Line 2')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('line_three')
                        ->nullable()
                        ->label('Line 3')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('landmark')
                        ->nullable()
                        ->label('Landmark')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('postal_code')
                        ->required()
                        ->label('Postal Code')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('city')
                        ->required()
                        ->label('City')
                        ->maxLength(255),
                    Forms\Components\Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return "{$record->emoji} {$record->name}";
                    })
                    ->searchable()
                    ->preload(),
                    Forms\Components\Select::make('state_id')
                    ->relationship(name: 'state', titleAttribute: 'name', modifyQueryUsing :fn (Builder $query, $get) => $query->where('country_id', $get('country_id')))
                    ->searchable()
                    ->preload(),
                ])->columns(3),

                Section::make('Location')->schema([

                    Forms\Components\TextInput::make('lattitude')
                        ->nullable()
                        ->label('Lattitude')
                        ->numeric(),

                    Forms\Components\TextInput::make('longitude')
                        ->nullable()
                        ->label('Longitude')
                        ->numeric(),

                ])->columns(3),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                IconColumn::make('active')
                ->label('Active')->boolean(),
                IconColumn::make('default')
                ->label('Main Branch')->boolean(),
                Tables\Columns\TextColumn::make('one_line')
                    ->label('Address')->getStateUsing(function ($record) {
                        return $record->one_line;
                    }),
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
