<?php
namespace App\Filament\Admin\Pages;
use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
class Settings extends Page implements HasForms {
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.admin.pages.settings';
    public ?array $data = [];

    public function mount(): void {
        $this->form->fill(['admin_wa' => Setting::get('admin_wa', config('warmad.admin_wa'))]);
    }
    public function form(Form $form): Form {
        return $form->statePath('data')->schema([
            Section::make('Kontak WhatsApp Admin')
                ->description('Nomor ini dipakai tombol "Chat Admin" / "Hubungi Admin" di semua halaman publik.')
                ->schema([
                    TextInput::make('admin_wa')->label('Nomor WhatsApp Admin')
                        ->tel()->required()->maxLength(20)->prefixIcon('heroicon-o-phone')
                        ->placeholder('0895630447306')
                        ->helperText('Boleh tulis 08xxxx, 628xxxx, atau +62 8xx. Otomatis diubah ke format 62xxxx.')
                        ->rules([fn () => function (string $attribute, $value, \Closure $fail) {
                            if (!preg_match('/^\d{9,15}$/', Setting::normalizeWa($value))) {
                                $fail('Nomor tidak valid. Contoh: 0895630447306');
                            }
                        }]),
                ]),
        ]);
    }
    public function save(): void {
        $d = $this->form->getState();
        $n = Setting::normalizeWa($d['admin_wa']);
        Setting::set('admin_wa', $n);
        $this->form->fill(['admin_wa' => $n]);
        Notification::make()->title('Nomor WhatsApp admin tersimpan')->success()->send();
    }
}
