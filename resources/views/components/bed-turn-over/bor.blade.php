<x-card class="card-outline card-indigo">
    <x-card.header>
        <div class="card-title"><strong>BOR (BED OCCUPANCY RATE) {{ Str::upper($spc) }}</strong></div>
    </x-card.header>
    <x-card.body>
        <table class="table table-bordered table-striped" id="bor-{{ $spc }}" data-spesialis="{{ $spc }}">
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
</x-card>

@push('scripts')
    <script>
        $(document).ready(function() {
            renderBor('{{ $spc }}');
        });

        function renderBor(spesialis, tahun = '') {
            const table = $(`#bor-${spesialis}`).find('tbody')
            $.get({
                url: `${url}/bed-turn-over/bor/${spesialis}/${tahun}`,
                success: (data) => {
                    console.log(data);

                    const rows = data.map((item, index) => {
                        return `<tr>
                            <td>${item.month} ${item.year}</td>
                            <td>${item.countRawat}</td>
                            <td>${item.daysOnMonth}</td>
                            <td>${item.jumlahKamar} ${item.jumlahKamar !==0 ?  '' : `<a href="javascript:void(0)" onclick="setJumlahKamarInap('${spesialis}','${item.jumlahKamar}' ,'${index+1}', ${item.year})" class="text-sm"><i class="fas fa-search"></i></a>` }</td>
                            <td>${item.jumlahBor} %</td>
                            </tr>`;
                    })
                    table.empty().html(rows);
                }

            })
        }

        function setJumlahKamarInap(spesialis, jumlah, index, tahun) {
            $.post(`${url}/log/kamar`, {
                _token: "{{ csrf_token() }}",
                kategori: spesialis,
                jumlah: jumlah,
                bulan: index,
                tahun: tahun,
            }).done((response) => {
                renderBor(spesialis, tahun);
            }).fail((error) => {
                alert(error.responseText)
            })

        }
    </script>
@endpush
