<?php
namespace App\Filament\Support;
use App\Models\Listing;
use App\Support\VideoTrimmer;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components as F;
use Filament\Tables;
use Filament\Tables\Table;
class ListingForm {
    public static function schema(bool $admin): array {
        $s = [
            F\Section::make('Informasi Warung')->schema([
                F\TextInput::make('title')->label('Judul Listing')->required()->maxLength(120)->columnSpanFull(),
                F\Select::make('transaction_type')->label('Jenis Transaksi')->options(Listing::TYPES)->default('jual')->required(),
                F\TextInput::make('price')->label('Harga')->numeric()->prefix('Rp')->required(),
                F\Textarea::make('description')->label('Deskripsi')->rows(5)->columnSpanFull(),
            ])->columns(2),
            F\Section::make('Tempat / Daerah')->schema([
                F\TextInput::make('province')->label('Provinsi'),
                F\TextInput::make('city')->label('Kota / Kabupaten')->required(),
                F\TextInput::make('district')->label('Kecamatan / Daerah'),
                F\Textarea::make('address')->label('Alamat / Keterangan Tempat')->rows(2)->columnSpanFull(),
            ])->columns(2),
            F\Section::make('Foto & Video')->schema([
                F\FileUpload::make('photos')->label('Foto (maks 6)')->image()->multiple()->maxFiles(6)->maxSize(4096)->reorderable()
                    ->disk('public')->directory('listings')->columnSpanFull(),
                F\FileUpload::make('video')->label('Video (opsional, MP4/WebM)')
                    ->helperText('Maks tampil 50 MB. Jika file lebih besar (sampai '.config('warmad.video_upload_max_mb', 500).' MB), video otomatis dipotong dari awal sampai muat 50 MB.')
                    ->acceptedFileTypes(['video/mp4','video/webm'])
                    ->maxSize((int) config('warmad.video_upload_max_mb', 500) * 1024)
                    ->disk('public')->directory('listings/video')
                    ->saveUploadedFileUsing(fn (TemporaryUploadedFile $file) => VideoTrimmer::store($file))
                    ->columnSpanFull(),
            ]),
        ];
        $s[] = F\Section::make('Status Tampil')->schema([
            F\Select::make('status')->options(Listing::STATUSES)->default('active')->required(),
            F\Toggle::make('is_featured')->label('Listing Unggulan'),
        ])->columns(2);
        return $s;
    }
    public static function table(Table $t, bool $admin): Table {
        $cols = [
            Tables\Columns\ImageColumn::make('photos')->label('Foto')->stacked()->limit(1)->disk('public'),
            Tables\Columns\TextColumn::make('title')->label('Listing')->searchable()->limit(38)->description(fn ($record) => $record->city),
            Tables\Columns\TextColumn::make('transaction_type')->label('Jenis')->badge()->formatStateUsing(fn ($state) => Listing::TYPES[$state] ?? $state),
            Tables\Columns\TextColumn::make('price')->label('Harga')->formatStateUsing(fn ($state) => 'Rp '.number_format($state,0,',','.'))->sortable(),
            Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn ($state) => Listing::STATUSES[$state] ?? $state)
                ->color(fn ($state) => match ($state) { 'active'=>'success','sold'=>'info', default=>'gray' }),
            Tables\Columns\TextColumn::make('views')->label('Dilihat')->sortable(),
        ];
        $actions = [Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('sold')->label('Tandai Terjual')->icon('heroicon-o-check-circle')->color('info')
                ->visible(fn ($record) => $record->status === 'active')->requiresConfirmation()->action(fn ($record) => $record->update(['status'=>'sold']))];
        return $t->columns($cols)->defaultSort('created_at','desc')
            ->filters([Tables\Filters\SelectFilter::make('status')->options(Listing::STATUSES), Tables\Filters\SelectFilter::make('transaction_type')->label('Jenis')->options(Listing::TYPES)])
            ->actions($actions)->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
