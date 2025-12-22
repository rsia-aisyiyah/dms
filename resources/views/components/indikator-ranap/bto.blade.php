<x-card class="card-outline card-indigo">
    <x-card.header>
        <div class="card-title"><strong>BTO (BED TURN OVER) {{ Str::upper($spc) }}</strong></div>
    </x-card.header>
    <x-card.body>
        <table class="table table-bordered table-striped table-sm" id="bto-{{ $spc }}" data-spesialis="{{ $spc }}">
            <thead>
            <tr>
                <th>Bulan</th>
                <th>Σ Pasien Pulang</th>
                <th>Σ Kamar</th>
                <th>BTO</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div class="mt-3" id="bto-summary-{{ $spc }}">
            <div class="alert alert-secondary mb-0">
                <strong>BTO Tahunan:</strong> -
                <span class="ml-2 badge badge-secondary">Menunggu data</span>
            </div>
        </div>
    </x-card.body>
    <div class="overlay" id="overlayBto-{{ $spc }}">
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
                    <input type="text" id="yearBto-{{ $spc }}" class="form-control yearPicker"
                           data-toggle="datetimepicker" aria-describedby="yearBto-{{ $spc }}"
                           data-target="#yearBto-{{ $spc }}" autocomplete="off">
                    <button type="button" class="btn btn-primary" onclick="getBto('{{ $spc }}')">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-6">
                <div class="float-right">
                    <button type="button" class="btn btn-success" id="exportBto-{{ $spc }}"><i
                                class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </x-card.footer>
</x-card>

@push('scripts')
    <script>
        $(document).ready(function () {
            renderBto('{{ $spc }}');
        });

        function getBto(spesialis) {
            const year = $(`#yearBto-${spesialis}`).val();
            toggleOverlayBto(spesialis);
            renderBto(spesialis, year);
        }

        $("#exportBto-{{ $spc }}").click(function (e) {
            const table = $('#bto-{{ $spc }}');
            if (table && table.length) {
                var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "dataBto-{{ $spc }}" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: preserveColors
                });
            }
        });

        function renderBto(spesialis, tahun = '') {
            const table = $(`#bto-${spesialis}`).find('tbody')
            $.get({
                ururl: `/dms/indikator-ranap/bto/${spesialis}/${tahun}`,
                success: (data) => {

                    const rows = data.map((item, index) => {
                        return `<tr>
                            <td>${item.month} ${item.year}</td>
                            <td>${item.jumlahPasien}</td>
                            <td>${item.jumlahKamar} ${item.jumlahKamar !== 0 ? '' : `<a href="javascript:void(0)" onclick="setJumlahKamarInap('${spesialis}' ,'${index + 1}', ${item.year})" class="text-sm"><i class="fas fa-search"></i></a>`}</td>
                            <td>${item.bto}</td>
                            </tr>`;
                    })
                    table.empty().html(rows);
                    renderBtoTahunan(data, spesialis);
                }

            }).done((response) => {
                toggleOverlayBto(spesialis);
            })
        }

        function setJumlahKamarInap(spesialis, index, tahun) {
            $.post(`${url}/log/kamar/create`, {
                _token: "{{ csrf_token() }}",
                kategori: spesialis,
                bulan: index,
                tahun: tahun,
            }).done((response) => {
                renderBto(spesialis, tahun);
                toggleOverlayBto(spesialis);

            }).fail((error) => {
                alert(error.responseText)
            })

        }

        function toggleOverlayBto(spesialis) {
            const overlayBto = $(`#overlayBto-${spesialis}`);
            const isHasClass = overlayBto.hasClass('d-none');

            if (isHasClass) {
                overlayBto.removeClass('d-none')
            } else {
                overlayBto.addClass('d-none')
            }
        }

        function renderBtoTahunan(data, spesialis) {

            // ambil bulan yang ada datanya
            const validMonths = data.filter(item => item.jumlahPasien > 0);

            if (!validMonths.length) {
                $(`#bto-summary-${spesialis}`).empty()
                return;
            }

            // rata-rata BTO bulanan
            const avgBto = validMonths.reduce((sum, item) => {
                return sum + parseFloat(item.bto);
            }, 0) / validMonths.length;

            // normalisasi ke tahunan
            const btoTahunan = avgBto * 12;

            // indikator
            let badge = 'secondary';
            let status = 'Tidak diketahui';

            if (btoTahunan < 40) {
                badge = 'info';
                status = 'Rendah';
                ra
            } else if (btoTahunan >= 40 && btoTahunan <= 50) {
                badge = 'success';
                status = 'Ideal';
            } else if (btoTahunan > 50 && btoTahunan <= 60) {
                badge = 'warning';
                status = 'Tinggi';
            } else {
                badge = 'danger';
                status = 'Sangat Tinggi';
            }

            $(`#bto-summary-${spesialis}`).html(`
        <div class="alert alert-${badge} mb-0">
            <strong>BTO Tahunan:</strong> ${btoTahunan.toFixed(2)}
            <span class="ml-2 badge badge-${badge}">${status}</span>
        </div>
        <small class="">BTO Tahunan = avgBto * 12</small>
    `);
        }
    </script>
@endpush
