<x-card class="card-outline card-indigo">
    <x-card.header>
        <div class="card-title"><strong>BOR (BED OCCUPANCY RATE) {{ Str::upper($spc) }}</strong></div>
    </x-card.header>
    <x-card.body>
        <table class="table table-bordered table-striped table-sm" id="bor-{{ $spc }}" data-spesialis="{{ $spc }}">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Lama Inap</th>
                    <th>Σ Hari</th>
                    <th>Σ Kamar</th>
                    <th>Bor</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </x-card.body>
    <div class="overlay" id="overlayBor-{{ $spc }}">
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
                    <input type="text" id="yearBor-{{ $spc }}" class="form-control yearPicker" data-toggle="datetimepicker" aria-describedby="yearBor-{{ $spc }}" data-target="#yearBor-{{ $spc }}" autocomplete="off">
                    <button type="button" class="btn btn-primary" onclick="getBor('{{ $spc }}')">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-6">
                <div class="float-right">
                    <button type="button" class="btn btn-success" id="exportBor-{{ $spc }}"><i class="fas fa-file-excel"></i> Export Excel</button>
                </div>
            </div>
        </div>
    </x-card.footer>
</x-card>

@push('scripts')
    <script>
        $(document).ready(function() {
            renderBor('{{ $spc }}');
        });

        function getBor(spesialis) {
            const year = $(`#yearBor-${spesialis}`).val();
            toggleOverlayBor(spesialis);
            renderBor(spesialis, year);
        }

        $("#exportBor-{{ $spc }}").click(function(e) {
            const table = $('#bor-{{ $spc }}');
            if (table && table.length) {
                var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "dataBor-{{ $spc }}" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: preserveColors
                });
            }
        });

        function renderBor(spesialis, tahun = '') {
            const table = $(`#bor-${spesialis}`).find('tbody')
            $.get({
                url: `${url}/indikator-ranap/bor/${spesialis}/${tahun}`,
                success: (data) => {

                    const rows = data.map((item, index) => {
                        return `<tr>
                            <td>${item.month} ${item.year}</td>
                            <td>${item.countRawat}</td>
                            <td>${item.daysOnMonth}</td>
                            <td>${item.jumlahKamar} ${item.jumlahKamar !==0 ?  '' : `<a href="javascript:void(0)" onclick="setJumlahKamarInap('${spesialis}' ,'${index+1}', ${item.year})" class="text-sm"><i class="fas fa-search"></i></a>` }</td>
                            <td>${item.jumlahBor} %</td>
                            </tr>`;
                    })
                    table.empty().html(rows);
                }

            }).done((response) => {
                toggleOverlayBor(spesialis);
            })
        }

        function setJumlahKamarInap(spesialis, index, tahun) {
            $.post(`${url}/log/kamar/create`, {
                _token: "{{ csrf_token() }}",
                kategori: spesialis,
                bulan: index,
                tahun: tahun,
            }).done((response) => {
                renderBor(spesialis, tahun);
                toggleOverlayBor(spesialis);
            }).fail((error) => {
                alert(error.responseText)
            })

        }

        function toggleOverlayBor(spesialis) {
            const overlayBor = $(`#overlayBor-${spesialis}`);
            const isHasClass = overlayBor.hasClass('d-none');

            if (isHasClass) {
                overlayBor.removeClass('d-none')
            } else {
                overlayBor.addClass('d-none')
            }
        }
    </script>
@endpush
