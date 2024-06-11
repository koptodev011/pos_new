<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use App\Models\TenantUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
   
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['Owner']);
    }


    public static function form(Form $form): Form
    {
       
        return $form
        ->schema([
            Section::make('Banner Details')->schema([
                FileUpload::make('image')->nullable()->image()->required(),
                Group::make()->schema([
                    Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                    Forms\Components\Select::make('tenant_unit_id')
                    ->label('Select Unit')
                    ->options(function(){
                        $user = auth()->user();
                        if($user->hasAnyRole(['Super Admin'])) {
                            return $query;
                        }
                        $tenant_unit_ids = $user->userTenantUnits()->pluck('tenant_unit_id')->all();
                        return TenantUnit::whereIn('tenant_id', $tenant_unit_ids)->pluck('name', 'id');
                    })
                    
                    ->searchable()
                    ->preload(),

                    Forms\Components\TextInput::make('url')
                    ->required()
                    ->label('URL')
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                    DatePicker::make('start_date'),
                    DatePicker::make('end_date')
                ])->columnSpan(3)->columns(2)
            ])->columns(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('title')
        ->columns([
          
            Tables\Columns\ImageColumn::make('image')->circular(),
            Tables\Columns\TextColumn::make('name')
            ->searchable(),
            Tables\Columns\TextColumn::make('description')->searchable(),
            Tables\Columns\TextColumn::make('start_date') ->dateTime('d-m-Y'),
            Tables\Columns\TextColumn::make('end_date') ->dateTime('d-m-Y'),

        ])
        ->filters([
            //
        ])
        ->headerActions([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
