@extends('layouts.frontend.master')
@section('title','Beranda')
@push('css')
<style>
    .tinymce-content p {
    font-size: 14pt;
    font-style: italic;
}

.tinymce-content ul {
    padding-left: 20px;
}

.tinymce-content li {
    font-size: 14pt;
    margin-bottom: 5px;
}

</style>
@endpush
@section('content')
<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('storage/' . optional($banner)->path) }}" alt="" data-aos="fade-in">

      <div class="container">
        <h2 data-aos="fade-up" data-aos-delay="100"><span style="color: #00c946">{{ $banner->title1 ?? "" }}</span><br>{{ $banner->title2 ?? "" }}</h2>
        <p data-aos="fade-up" data-aos-delay="200">{{ $banner->description ?? "" }}</p>
        <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
          <a href="{{ route('register') }}" class="btn-get-started">Daftar Sekarang</a>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('storage/'. optional($tentang)->path) }}" class="img-fluid" alt="">
          </div>

          <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">
            <h3>{{ $tentang->judul ?? "" }}</h3>
            <div class="tinymce-content">
               {!! html_entity_decode($tentang->content ?? "") !!}
            </div>
            </ul>
            <!--<a href="#" class="read-more"><span>Selengkapnya</span><i class="bi bi-arrow-right"></i></a>-->
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Counts Section -->
    <section id="counts" class="section counts light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

            @foreach ($count as $item)
            <div class="col-lg-4 col-md-6">
                <div class="stats-item text-center w-100 h-100">
                <span data-purecounter-start="0" data-purecounter-end="{{ $item->jumlah }}" data-purecounter-duration="1" class="purecounter"></span>
                <p>{{ $item->judul }}</p>
                </div>
            </div><!-- End Stats Item -->
            @endforeach
        </div>

      </div>

    </section><!-- /Counts Section -->

    <!-- Trainers Index Section -->
    <section id="trainers-index" class="section trainers-index">

      <div class="container">
        <div class="testimoni text-center" data-aos="fade-up" data-aos-delay="100">
            <h1 class="text-success" style="font-weight: 600">Testimonial Alumni</h1>
            <p>Apa kata mereka yang sudah menyelesaikan studinya di STIE Tamansiswa Banjarnegara?</p>
        </div>
        <div class="row">

            @foreach ($testimoni as $item)
            <div class="col-lg-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                <div class="member">
                  <img src="{{ asset('storage/' .$item->path) }}" class="img-fluid mt-4 rounded" style="height: 10rem;" alt="{{ $item->name }}">
                  <div class="member-content">
                    <h4>{{ $item->name }}</h4>
                    <h6>{{ $item->alumni }}</h6>
                    <span>{{ $item->kerja }}</span>
                    <p>
                     {{ $item->isi }}
                    </p>
                  </div>
                </div>
              </div><!-- End Team Member -->
            @endforeach


        </div>

      </div>

    </section><!-- /Trainers Index Section -->

  </main>
@endsection
