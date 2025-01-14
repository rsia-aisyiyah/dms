<x-card class="card-outline card-primary">
    <x-card.header>
        <div class="card-title"><strong>LOS (LENGHT OF STAY) {{ Str::upper($spc) }}</strong></div>
    </x-card.header>
    <x-card.body>
        <table class="table table-bordered table-striped table-sm" id="los-{{ $spc }}" data-spesialis="{{ $spc }}">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Lama Inap</th>
                    <th>Σ Pulang</th>
                    <th>LOS</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </x-card.body>
    <div class="overlay" id="overlayLos-{{ $spc }}">
        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
        <div class="text-bold pt-2">Sedang mengambil data...</div>
    </div>
    <x-card.footer>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-lg-6">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                    <input type="text" id="yearLos-{{ $spc }}" class="form-control yearPicker" data-toggle="datetimepicker" aria-describedby="yearLos-{{ $spc }}" data-target="#yearLos-{{ $spc }}" autocomplete="off">
                    <button type="button" class="btn btn-primary" onclick="getLos('{{ $spc }}')">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-6">
                <div class="float-right">
                    <button type="button" class="btn btn-success" id="exportLos-{{ $spc }}"><i class="fas fa-file-excel"></i> Export Excel</button>
                </div>
            </div>
        </div>
    </x-card.footer>
</x-card>
@push('scripts')
    <script>
        $(document).ready(function() {
            renderLos('{{ $spc }}');
        });

        function getLos(spesialis) {
            const year = $(`#yearLos-${spesialis}`).val();
            toggleOverlayLos(spesialis);
            renderLos(spesialis, year);
        }

        $("#exportLos-{{ $spc }}").click(function(e) {
            const table = $('#los-{{ $spc }}');
            if (table && table.length) {
                var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "dataLos" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: preserveColors
                });
            }
        });

        function renderLos(spesialis, tahun = '') {
            const table = $(`#los-${spesialis}`).find('tbody')
            $.get({
                url: `${url}/indikator-ranap/los/${spesialis}/${tahun}`,
                success: (data) => {

                    const rows = data.map((item, index) => {
                        return `<tr>
                            <td>${item.month} ${item.year}</td>
                            <td>${item.lamaInap}</td>
                            <td>${item.pasienPulang}</td>
                            <td>${item.los}</td>
                            </tr>`;
                    })
                    table.empty().html(rows);
                }

            }).done((response) => {
                toggleOverlayLos(spesialis);
            })
        }

        // function setJumlahKamarInap(spesialis, index, tahun) {
        //     $.post(`${url}/log/kamar/create`, {
        //         _token: "{{ csrf_token() }}",
        //         kategori: spesialis,
        //         bulan: index,
        //         tahun: tahun,
        //     }).done((response) => {
        //         renderLos(spesialis, tahun);
        //         toggleOverlayLos(spesialis);
        //     }).fail((error) => {
        //         alert(error.responseText)
        //     })

        // }

        function toggleOverlayLos(spesialis) {
            const overlayLos = $(`#overlayLos-${spesialis}`);
            const isHasClass = overlayLos.hasClass('d-none');

            if (isHasClass) {
                overlayLos.removeClass('d-none')
            } else {
                overlayLos.addClass('d-none')
            }
        }
    </script>
@endpush
