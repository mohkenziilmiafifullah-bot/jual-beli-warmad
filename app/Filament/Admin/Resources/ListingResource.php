<?php
namespace App\Filament\Admin\Resources;
use App\Filament\Admin\Resources\ListingResource\Pages;
use App\Filament\Support\ListingForm;
use App\Models\Listing;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class ListingResource extends Resource {
    protected static ?string $model = Listing::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $modelLabel = 'Listing';
    protected static ?string $pluralModelLabel = 'Listing Warung';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form { return $form->schema(ListingForm::schema(true)); }
    public static function table(Table $table): Table { return ListingForm::table($table, true); }
    public static function getPages(): array {
        return ['index'=>Pages\ListListings::route('/'),'create'=>Pages\CreateListing::route('/create'),'edit'=>Pages\EditListing::route('/{record}/edit')];
    }
}
