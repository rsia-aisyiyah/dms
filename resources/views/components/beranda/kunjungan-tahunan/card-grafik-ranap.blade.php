<x-card class="card card-outline">
    <x-card.card-header>
        <div class="card-title">
            <strong>Kunjungan Tahunan Rawat Inap</strong>
        </div>
    </x-card.card-header>
    <x-card.card-body>
        <canvas class="canvasKunjunganTahun" {{ $attributes->merge(['id' => $id ?? 'grafikKunjunganTahunanRanap']) }}
            style="height: 50vh; max-height: 50vh"></canvas>
    </x-card.card-body>
    <div class="overlay overlayGrafikTahunan">
        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
        <div class="text-bold pt-2">Sedang mengambil data...</div>
    </div>
    {{-- <x-card.card-footer>
    </x-card.card-footer> --}}
</x-card>
