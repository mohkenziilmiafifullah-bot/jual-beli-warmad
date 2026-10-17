<?php
namespace App\Filament\Admin\Resources;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Support\ListingForm;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class UserResource extends Resource {
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Admin';
    protected static ?string $pluralModelLabel = 'Admin';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form {
        return $form->schema([
            \Filament\Forms\Components\TextInput::make('name')->label('Nama')->required(),
            \Filament\Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            \Filament\Forms\Components\Hidden::make('role')->default('admin'),
            \Filament\Forms\Components\TextInput::make('password')->password()->revealable()
                ->required(fn (string $operation) => $operation === 'create')->dehydrated(fn ($state) => filled($state)),
        ])->columns(2);
    }
    public static function table(Table $table): Table {
        return $table->columns([
            \Filament\Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('email')->searchable(),
        ])->modifyQueryUsing(fn ($query) => $query->where('role','admin'))->actions([\Filament\Tables\Actions\EditAction::make()]);
    }
    public static function getPages(): array {
        return ['index'=>Pages\ListUsers::route('/'),'create'=>Pages\CreateUser::route('/create'),'edit'=>Pages\EditUser::route('/{record}/edit')];
    }
}
