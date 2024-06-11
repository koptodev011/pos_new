<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfolistsSection;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['Owner']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Category Details')->schema([
                    FileUpload::make('image')->nullable()->image()->avatar(),
                    Group::make()->schema([
                        Forms\Components\TextInput::make('name')
                        ->required()
                        ->autocapitalize('words')
                        ->maxLength(255),

                        Forms\Components\Select::make('parent_id')
                        ->label('Select Parent')
                        ->relationship(name: 'parent', titleAttribute: 'name', ignoreRecord: true)
                        ->searchable()
                        ->preload(),

                        Forms\Components\Textarea::make('description')->columnSpanFull(),

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
                            ->columnSpanFull()
                    ])->columnSpan(3)->columns(2)
                ])->columns(4),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->circular(),
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('parent.name'),
            ])
            ->filters([
                //
                SelectFilter::make('parent_id')
                ->label('Parent')
                ->relationship(name: 'parent', titleAttribute: 'name')

                ->attribute('parent_id')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
            'view' => Pages\ViewCategory::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfolistsSection::make('Details')
            ->columns(3)
            ->schema([
                ImageEntry::make('image')->circular(),
                TextEntry::make('name'),
                TextEntry::make('description'),
            ])
        ]);
    }
}
