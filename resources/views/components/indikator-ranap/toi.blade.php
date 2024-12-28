<x-card class="card-outline card-primary">
    <x-card.header>
        <div class="card-title"><strong>TOI (TURN OVER INTERVAL) {{ Str::upper($spc) }}</strong></div>
    </x-card.header>
    <x-card.body>
        <table class="table table-bordered table-striped table-sm" id="toi-{{ $spc }}" data-spesialis="{{ $spc }}">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Lama Inap</th>
                    <th>Σ Hari</th>
                    <th>Σ TT</th>
                    <th>Σ Pulang</th>
                    <th>TOI</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </x-card.body>
    <div class="overlay" id="overlayToi-{{ $spc }}">
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
                    <input type="text" id="yearToi-{{ $spc }}" class="form-control yearPicker" data-toggle="datetimepicker" aria-describedby="yearToi-{{ $spc }}" data-target="#yearToi-{{ $spc }}" autocomplete="off">
                    <button type="button" class="btn btn-primary" onclick="getLos('{{ $spc }}')">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-6">
                <div class="float-right">
                    <button type="button" class="btn btn-success" id="exportToi-{{ $spc }}"><i class="fas fa-file-excel"></i> Export Excel</button>
                </div>
            </div>
        </div>
    </x-card.footer>
</x-card>
@push('scripts')
    <script>
        $(document).ready(function() {
            renderToi('{{ $spc }}');
        });

        function getLos(spesialis) {
            const year = $(`#yearToi-${spesialis}`).val();
            toggleOverlayToi(spesialis);
            renderToi(spesialis, year);
        }

        $("#exportToi-{{ $spc }}").click(function(e) {
            const table = $('#toi-{{ $spc }}');
            if (table && table.length) {
                var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "dataToi-{{ $spc }}" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: preserveColors
                });
            }
        });

        function renderToi(spesialis, tahun = '') {
            const table = $(`#toi-${spesialis}`).find('tbody')
            $.get({
                url: `${url}/indikator-ranap/toi/${spesialis}/${tahun}`,
                success: (data) => {

                    const rows = data.map((item, index) => {
                        return `<tr>
                            <td>${item.month} ${item.year}</td>
                            <td>${item.lama_inap}</td>
                            <td>${item.periode_rawat}</td>
                            <td>${item.tempat_tidur}</td>
                            <td>${item.pulang}</td>
                            <td>${item.toi}</td>
                            </tr>`;
                    })
                    table.empty().html(rows);
                }

            }).done((response) => {
                toggleOverlayToi(spesialis);
            })
        }

        // function setJumlahKamarInap(spesialis, index, tahun) {
        //     $.post(`${url}/log/kamar/create`, {
        //         _token: "{{ csrf_token() }}",
        //         kategori: spesialis,
        //         bulan: index,
        //         tahun: tahun,
        //     }).done((response) => {
        //         renderToi(spesialis, tahun);
        //         toggleOverlayToi(spesialis);
        //     }).fail((error) => {
        //         alert(error.responseText)
        //     })

        // }

        function toggleOverlayToi(spesialis) {
            const overlayToi = $(`#overlayToi-${spesialis}`);
            const isHasClass = overlayToi.hasClass('d-none');

            if (isHasClass) {
                overlayToi.removeClass('d-none')
            } else {
                overlayToi.addClass('d-none')
            }
        }
    </script>
@endpush
