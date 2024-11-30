<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">Main Menu</li>
            <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}"><a href="{{ route('dashboard') }}" aria-expanded="false" ><i class="bi bi-grid"></i><span
                class="nav-text">Dashboard</span></a></li>

            <li class="{{ request()->routeIs('pendaftaran*') ? 'mm-active' : '' }}"><a href="{{ route('pendaftaran.index') }}" aria-expanded="false" ><i class="bi bi-file-earmark-text"></i><span
                class="nav-text">Pendaftaran</span></a></li>

                <li class="{{ request()->routeIs('registrasi*') ? 'mm-active' : '' }}"><a href="{{ route('registrasi.index') }}" aria-expanded="false" ><i class="bi bi-archive"></i><span
                    class="nav-text">Registrasi</span></a></li>

                <li class="{{ request()->routeIs('brosur*') ? 'mm-active' : '' }}"><a href="{{ route('brosur.index') }}" aria-expanded="false" ><i class="bi bi-sliders"></i></i><span
                    class="nav-text">Brosur</span></a></li>

               <li class="{{ request()->routeIs('kontak*') ? 'mm-active' : '' }}"><a href="{{ route('kontak.index') }}" aria-expanded="false" ><i class="bi bi-telephone"></i><span
                    class="nav-text">Kontak</span></a></li>

                <li class="{{ request()->routeIs('banner*') ? 'mm-active' : '' }}"><a href="{{ route('banner.index') }}" aria-expanded="false" ><i class="bi bi-bookmark"></i><span
                    class="nav-text">Banner</span></a></li>
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