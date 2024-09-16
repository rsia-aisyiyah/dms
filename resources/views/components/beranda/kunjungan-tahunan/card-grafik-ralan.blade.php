<x-card class="card card-outline">
    <x-card.header>
        <div class="card-title">
            <strong>Kunjungan Tahunan Rawat Jalan</strong>
        </div>
    </x-card.header>
    <x-card.body>
        <canvas class="canvasKunjunganTahun" {{ $attributes->merge(['id' => $id ?? 'grafikKunjunganTahunanRalan']) }}
            style="height: 50vh; max-height: 50vh"></canvas>
    </x-card.body>
    <div class="overlay overlayGrafikTahunan">
        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
        <div class="text-bold pt-2">Sedang mengambil data...</div>
    </div>
    {{-- <x-card.footer> --}}
    {{-- </x-card.footer> --}}
</x-card>
