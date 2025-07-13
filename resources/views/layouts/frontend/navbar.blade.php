<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{ asset('backend/img/logo-stie.png') }}" height="100%" >
        {{-- <h1 class="sitename">Mentor</h1> --}}
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="{{ request()->routeIs('/') ? 'active' : '' }}">Beranda<br></a></li>
          @php
            $pendaftaran = DB::table('pendaftaran')->get();
          @endphp
           <li class="dropdown"><a href="#"><span>Pendaftaran</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
            @foreach ($pendaftaran as $item)
              <li><a href="{{ route('pendaftaran',$item->slug) }}">{{ $item->name }}</a></li>
            @endforeach
            </ul>
          </li>
          @php
          $registrasi = DB::table('registrasi')->get();
          @endphp
         <li class="dropdown"><a href=""><span>Registrasi</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
          <ul>
          @foreach ($registrasi as $item)
            <li><a href="{{ route('registrasi',$item->slug) }}">{{ $item->name }}</a></li>
          @endforeach
          </ul>
        </li>
          <li><a href="{{ route('brosur') }}">Brosur</a></li>
          <li><a href="{{ route('kontak') }}">Kontak</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      @if (!Auth::check())
      <a class="btn btn-getstarted btn-daftar" href="{{ route('register') }}">
        Daftar
        <i class="bi bi-person-plus-fill ms-2"></i>
      </a>
      <a class="btn btn-getstarted btn-login" href="{{ route('login') }}">
          Login
          <i class="bi bi-box-arrow-in-right ms-2"></i>
      </a>
      {{-- <a class="btn-getstarted" href="{{ route('login') }}">Login <i class="bi bi-box-arrow-in-right ms-2"></i></a>
      <a class="btn-getstarted btn-custom-orange " href="{{ route('register') }}">Daftar <i class="bi bi-box-arrow-in-right ms-2"></i></a> --}}
      @endif
    </div>
 
</header>