<div class="sidebar text-sm">

    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('dist/img/user-profile.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block"><strong>{{ auth()->user()->nama }}</strong></a>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
            @can('rm')
                <li class="nav-item has-treeview {{ Request::is('rekammedis*') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('rekammedis*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Laporan Rekam Medis
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-3">
                        <li class="nav-item">
                            <a href="/dms/rekammedis" class="nav-link">
                                <i
                                    class="nav-icon {{ Request::is('rekammedis') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>10 Besar Penyakit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/dms/rekammedis/dinkes" class="nav-link">
                                <i
                                    class="nav-icon {{ Request::is('rekammedis/dinkes') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Laporan Dinkes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/dms/rekammedis/penyakit" class="nav-link">
                                <i
                                    class="nav-icon {{ Request::is('rekammedis/penyakit') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Laporan Diagnosa Penyakit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/dms/rekammedis/pasientb" class="nav-link">
                                <i
                                    class="nav-icon {{ Request::is('rekammedis/pasientb') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Laporan Pasien TB</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            <li class="nav-item has-treeview {{ Request::is('operasi*') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('operasi*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book-medical"></i>
                    <p>
                        Laporan Operasi
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview ml-3">
                    <li class="nav-item">
                        <a href="{{ '/dms/operasi' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('operasi') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Operasi</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ Request::is('persalinan*') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('persalinan*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-baby"></i>
                    <p>
                        Tindakan Persalinan
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview ml-3">
                    <li class="nav-item">
                        <a href="{{ '/dms/persalinan' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('persalinan') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Laporan Tindakan Persalinan</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ Request::is('igd*') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('igd*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-ambulance"></i>
                    <p>
                        Kunjungan IGD
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview ml-3">
                    <li class="nav-item">
                        <a href="{{ '/dms/igd' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('igd') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Rekap Kunjungan IGD</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ Request::is('tindakan*') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('tindakan*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-stethoscope"></i>
                    <p>
                        Tindakan
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview ml-3">
                    <li class="nav-item">
                        <a href="{{ '/dms/tindakan' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('tindakan') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Rekap Tindakan Dokter</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ Request::is('ralan*') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('ralan*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-nurse"></i>
                    <p>
                        Kunjungan Rawat Jalan
                        <i class="fas fa-angle-left right"></i>
                    </p>

                </a>
                <ul class="nav nav-treeview ml-3">
                    <li class="nav-item">
                        <a href="{{ '/dms/ralan' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ralan') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Data Pasien Ralan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ '/dms/ralan/sep' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ralan/sep') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>SEP Pasien Ralan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ '/dms/ralan/laporan' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ralan/laporan') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Laporan BPJS</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ Request::is('ranap*') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('ranap*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-procedures"></i>
                    <p>
                        Kunjungan Rawat Inap
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview ml-3">
                    <li class="nav-item">
                        <a href="{{ '/dms/ranap' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ranap') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Data Pasien Ranap</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ '/dms/ranap/laporan' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ranap/laporan') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Laporan BPJS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ '/dms/ranap/bayi' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ranap/bayi') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Pasien Bayi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ '/dms/ranap/visit' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ranap/visit') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Visit Dokter DPJP</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ '/dms/ranap/transfusi' }}" class="nav-link">
                            <i
                                class="far fa-circle nav-icon {{ Request::is('ranap/transfusi') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                            <p>Transfusi Darah</p>
                        </a>
                    </li>
                </ul>
            </li>
            @can('admin')
                <li class="nav-item has-treeview {{ Request::is('tarif*') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('tarif*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>
                            Tarif Pelayanan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-3">
                        <li class="nav-item">
                            <a href="{{ '/dms/tarif/kamar' }}" class="nav-link">
                                <i
                                    class="far fa-circle nav-icon {{ Request::is('tarif/kamar') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Tarif Kamar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ '/dms/tarif/ralan' }}" class="nav-link">
                                <i
                                    class="far fa-circle nav-icon {{ Request::is('tarif/ralan') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Tarif Layanan Ralan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ '/dms/tarif/ranap' }}" class="nav-link">
                                <i
                                    class="far fa-circle nav-icon {{ Request::is('tarif/ranap') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Tarif Layanan Ranap</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ '/dms/tarif/lab' }}" class="nav-link">
                                <i
                                    class="far fa-circle nav-icon {{ Request::is('tarif/lab') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Tarif Layanan Lab</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ '/dms/tarif/operasi' }}" class="nav-link">
                                <i
                                    class="far fa-circle nav-icon {{ Request::is('tarif/operasi') ? 'fas fa-circle text-teal' : 'far fa-circle' }}"></i>
                                <p>Tarif Layanan OK/VK</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
