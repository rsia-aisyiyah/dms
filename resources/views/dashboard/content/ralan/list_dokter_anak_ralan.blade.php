        <div class="card card-teal">
            <div class="card-header">
                <p class="card-title border-bottom-0">Dokter Poli Anak</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <label>Tahun</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="text" id="yearpicker-poli-anak" class="form-control datetimepicker-input" data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#yearpicker-poli-anak" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped text-sm" id="table-poli-anak" style="width: 100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Dokter</th>
                                        <th>Januari</th>
                                        <th>Februari</th>
                                        <th>Maret</th>
                                        <th>April</th>
                                        <th>Mei</th>
                                        <th>Juni</th>
                                        <th>Juli</th>
                                        <th>Agustus</th>
                                        <th>September</th>
                                        <th>Oktober</th>
                                        <th>November</th>
                                        <th>Desember</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
            <script>
                $(document).ready(function() {


                    $('#yearpicker-poli-anak').datetimepicker({
                        format: "YYYY",
                        useCurrent: false,
                        viewMode: "years"
                    });

                    load_data();

                    function load_data(tahun = '') {
                        console.log(tahun);
                        $.ajax({
                            url: `${url}/ralan/kunjungan/poli/${tahun}`,
                            method: "GET",
                            dataType: "json",
                            success: function(data) {
                                const tableBody = $("#table-poli-anak tbody");
                                tableBody.empty();

                                const allDoctors = new Set();

                                for (const month in data) {
                                    Object.keys(data[month]).forEach(doctor => {
                                        allDoctors.add(doctor);
                                    })
                                }

                                const sortedDoctors = Array.from(allDoctors).sort();

                                sortedDoctors.forEach(doctor => {
                                    const row = `<tr>
                                    <td>${doctor}</td>
                                    ${Object.keys(data).map(month => `<td>${data[month][doctor] ?? 0 }</td>`).join('')}
                                </tr>`;
                                    tableBody.append(row);
                                });
                            }
                        })
                    }


                    $('#yearpicker-poli-anak').on('change.datetimepicker', function() {
                        var tahun = $(this).val();
                        // $('#table-poli-anak').DataTable().destroy();
                        load_data(tahun);
                    });

                });
            </script>
        @endpush
