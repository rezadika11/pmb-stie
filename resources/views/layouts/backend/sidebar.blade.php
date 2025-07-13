<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">Main Menu</li>
            <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}"><a href="{{ route('dashboard') }}" aria-expanded="false" ><i class="bi bi-grid"></i><span
                class="nav-text">Dashboard</span></a></li>

                {{-- @php
                    $user = Auth::user();
                @endphp --}}
                @if (Auth::user()->roles == 'superadmin')
                <li class="{{ request()->routeIs('pendaftaran*') || request()->routeIs('registrasi*') || request()->routeIs('brosur*') || request()->routeIs('kontak*') ? 'mm-active' : '' }}"><a class="has-arrow" href="javascript:void()" aria-expanded="{{ request()->routeIs('pendaftaran*') || request()->routeIs('registrasi*') || request()->routeIs('brosur*') || request()->routeIs('kontak*') ? 'true' : 'false' }}"><i class="bi bi-sliders"></i><span class="nav-text">Halaman</span></a>
                    <ul aria-expanded="{{ request()->routeIs('pendaftaran*') || request()->routeIs('registrasi*') || request()->routeIs('brosur*') || request()->routeIs('kontak*') ? 'true' : 'false' }}"
                        class="mm-collapse {{ request()->routeIs('pendaftaran*') || request()->routeIs('registrasi*') || request()->routeIs('brosur*') || request()->routeIs('kontak*') ? 'mm-show' : '' }}">
                        <li class="{{ request()->routeIs('pendaftaran*') ? 'mm-active' : '' }}">
                            <a href="{{ route('pendaftaran.index') }}">
                                <span class="nav-text">Pendaftaran</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('registrasi*') ? 'mm-active' : '' }}">
                            <a href="{{ route('registrasi.index') }}">
                                <span class="nav-text">Registrasi</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('brosur*') ? 'mm-active' : '' }}">
                            <a href="{{ route('brosur.index') }}">
                                <span class="nav-text">Brosur</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('kontak*') ? 'mm-active' : '' }}">
                            <a href="{{ route('kontak.index') }}">
                                <span class="nav-text">Kontak</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('banner*') || request()->routeIs('tentang*') ||  request()->routeIs('count*') || request()->routeIs('testimoni*') ? 'mm-active' : '' }}"><a class="has-arrow" href="javascript:void()" aria-expanded="{{ request()->routeIs('banner*') || request()->routeIs('tentang*') || request()->routeIs('count*') || request()->routeIs('testimoni*')  ? 'true' : 'false' }}"><i class="bi bi-house-gear"></i></i><span class="nav-text">Beranda</span></a>
                    <ul aria-expanded="{{ request()->routeIs('banner*') || request()->routeIs('tentang*') || request()->routeIs('count*') || request()->routeIs('testimoni*') ? 'true' : 'false' }}"
                        class="mm-collapse {{ request()->routeIs('banner*') || request()->routeIs('tentang*') ||  request()->routeIs('count*') || request()->routeIs('testimoni*')   ? 'mm-show' : '' }}">
                        <li class="{{ request()->routeIs('banner*') ? 'mm-active' : '' }}">
                            <a href="{{ route('banner.index') }}">
                                <span class="nav-text">Banner</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('tentang*') ? 'mm-active' : '' }}">
                            <a href="{{ route('tentang.index') }}">
                                <span class="nav-text">Tentang</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('count*') ? 'mm-active' : '' }}">
                            <a href="{{ route('count.index') }}">
                                <span class="nav-text">Jumlah</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('testimoni*') ? 'mm-active' : '' }}">
                            <a href="{{ route('testimoni.index') }}">
                                <span class="nav-text">Testimoni</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs(['tahun_akademin.*','gelombang.*']) ? 'mm-active' : '' }}"><a class="has-arrow" href="javascript:void()" aria-expanded="{{ request()->routeIs(['tahun_akademin.*','gelombang.*'])  ? 'true' : 'false' }}"><i class="bi bi-calendar"></i></i><span class="nav-text">Pengaturan Pendaftaran</span></a>
                    <ul aria-expanded="{{ request()->routeIs(['tahun_akademin.*','gelombang.*']) ? 'true' : 'false' }}"
                        class="mm-collapse {{ request()->routeIs(['tahun_akademin.*','gelombang.*'])   ? 'mm-show' : '' }}">
                        <li class="{{ request()->routeIs('tahun_akademik.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('tahun_akademik.index') }}">
                                <span class="nav-text">Tahun Akademik</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('gelombang.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('gelombang.index') }}">
                                <span class="nav-text">Gelombang</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="{{ request()->routeIs('tahun-akademik*') ? 'mm-active' : '' }}"><a href="{{ route('tahun_akademik.index') }}" aria-expanded="false"><i class="bi bi-calendar4"></i><span class="nav-text">Tahun Akademik</span></a></li> --}}

               {{-- <li class="{{ request()->routeIs('kontak*') ? 'mm-active' : '' }}"><a href="{{ route('kontak.index') }}" aria-expanded="false" ><i class="bi bi-telephone"></i><span
                    class="nav-text">Kontak</span></a></li> --}}

                {{-- <li class="{{ request()->routeIs('banner*') ? 'mm-active' : '' }}"><a href="{{ route('banner.index') }}" aria-expanded="false" ><i class="bi bi-bookmark"></i><span
                    class="nav-text">Banner</span></a></li> --}}
                <li class="nav-label first">Setting</li>
                <li class="{{ request()->routeIs('users*') ? 'mm-active' : '' }}"><a href="{{ route('users.index') }}" aria-expanded="false"><i class="bi bi-people"></i><span
                    class="nav-text">User</span></a></li>
                @elseif (Auth::user()->roles == 'admin')
                <li class="{{ request()->routeIs('pmb*') ? 'mm-active' : '' }}"><a href="{{ route('pmb.index') }}" aria-expanded="false" ><i class="bi bi-file-earmark-text"></i><span
                    class="nav-text">PMB</span></a></li>
                
                {{-- <li class="{{ request()->routeIs('tahun-akademik*') ? 'mm-active' : '' }}"><a href="{{ route('admin.tahun_akademik.index') }}" aria-expanded="false"><i class="bi bi-calendar4"></i><span class="nav-text">Tahun Akademik</span></a></li> --}}
                <li class="{{ request()->routeIs('laporan*') ? 'mm-active' : '' }}"><a href="{{ route('laporan.index') }}" aria-expanded="false"><i class="bi bi-archive"></i><span
                    class="nav-text">Laporan</span></a></li>
                @elseif (Auth::user()->roles == 'mhs')
                <li class="{{ request()->routeIs('formulir*') ? 'mm-active' : '' }}"><a href="{{ route('formulir.index') }}" aria-expanded="false" ><i class="bi bi-file-earmark-text"></i><span
                    class="nav-text">Formulir</span></a></li>
                @endif

            {{-- <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                        class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a>
                <ul aria-expanded="false">
                    <li><a href="./index.html">Dashboard 1</a></li>
                    <li><a href="./index2.html">Dashboard 2</a></li>
                </ul>
            </li> --}}
        </ul>
    </div>
</div>
