<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        <div class="mt-6 flex flex-wrap items-center gap-4">
            <x-filament::button type="submit">Simpan</x-filament::button>
            <a href="https://wa.me/{{ \App\Models\Setting::adminWa() }}" target="_blank" rel="noopener" class="text-sm underline">
                Tes nomor yang aktif saat ini (+{{ \App\Models\Setting::adminWa() }})
            </a>
        </div>
    </form>
</x-filament-panels::page>
