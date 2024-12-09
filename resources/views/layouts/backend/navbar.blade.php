<div class="nav-header">
    <a href="index.html" class="brand-logo" id="logo-container">
        <img class="logo-abbr" src="{{ asset('backend/img/logo-stie.png') }}" >
        <img class="logo-compact" src="{{ asset('backend/img/pmb.png') }}" alt="">
        <img class="brand-title" src="{{ asset('backend/img/pmb.png') }}" alt="">
    </a>
     
    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>
<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    {{-- <div class="search_bar dropdown">
                        <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                        <div class="dropdown-menu p-0 m-0">
                            <form>
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                            </form>
                        </div>
                    </div> --}}
                </div>

                <ul class="navbar-nav header-right">
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span style="font-size: 15px">{{ Auth::user()->name }}</span> 
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @php
                                $user = Auth::user();
                            @endphp
                            @if ($user->roles == 'superadmin')
                            <a href="{{ route('superadmin.profile') }}" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                <span class="ml-2">Profile </span>
                            </a>
                            @elseif ($user->roles == 'admin')
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                <span class="ml-2">Profile </span>
                            </a>
                            @elseif ($user->roles == 'mhs')
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                <span class="ml-2">Profile </span>
                            </a>
                            @endif
                         
                            <hr>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                                <i class="bi bi-box-arrow-right"></i>
                                <span class="ml-2">Logout</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>