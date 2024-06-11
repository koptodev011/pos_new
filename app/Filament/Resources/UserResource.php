<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\UserTenantUnitsRelationManager;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['Super Admin', 'Owner']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\FileUpload::make('profile_photo_path')
                ->nullable()
                ->image()
                ->avatar()
                ->label('Profile Photo')
                ->avatar()
                ->columnspan('2'),

                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->label('Name'),

                \Filament\Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->rules(['required', 'email']),

                \Filament\Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->rules(['required', 'min:6']),

                \Filament\Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->confirmed()
                    ->rules(['required', new Password(8)]),

                \Filament\Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->rules(['required', new Password(8)]),


                Select::make('role')
                ->relationship(name: 'roles', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                    $user = auth()->user();
                    $list = collect($user->getRoleNames());
                    if($user->hasAnyRole(['Owner'])) {
                        $list->push('Super Admin');
                    }
                    return $query->whereNotIn('name', $list->toArray());
                })
                ->required(),

                // Select::make('userTenantUnits')
                //     ->relationship(name: 'userTenantUnits', modifyQueryUsing: function (Builder $query) {
                //         return $query;
                //     })
                //     ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->tenantUnit->name}")
                //     ->label('Units')
                //     ->searchable()
                //     ->preload(),

                \Filament\Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),

                \Filament\Forms\Components\Section::make('Units')->schema([
                    \Filament\Forms\Components\Repeater::make('userTenantUnits')->schema([
                        \Filament\Forms\Components\Select::make('tenant_unit_id')
                            ->relationship(name: 'tenantUnit', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                $user = auth()->user();
                                if($user->hasAnyRole(['Super Admin'])) {
                                    return $query;
                                }
                                $tenant_unit_ids = $user->userTenantUnits()->pluck('tenant_unit_id')->all();
                                return $query->whereIn('id', $tenant_unit_ids);
                            })
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return "{$record->name}";
                            })
                            ->preload()
                    ])->relationship('userTenantUnits')
                ]),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo_path')->circular(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge(),

                IconColumn::make('active')->boolean()
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
            ])->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if($user->hasAnyRole(['Owner'])) {
                    $tenant_unit_ids = $user->userTenantUnits()->pluck('tenant_unit_id')->all();
                    return $query->with(['userTenantUnits'])->whereHas('userTenantUnits', function ($subquery) use ($user, $tenant_unit_ids) {
                        $subquery->where('user_id', '<>', $user->id)
                        ->whereIn('tenant_unit_id', $tenant_unit_ids);
                    });
                }
                return $query;

            });
    }

    public static function getRelations(): array
    {
        return [
            UserTenantUnitsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Personal')
            ->columns(2)
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('phone'),
                IconEntry::make('active')->icon(fn (bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
            ])
        ]);
    }

}
