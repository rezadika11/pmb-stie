@extends('layouts.frontend.master')
@section('title','Pendaftaran')
@section('content')
<main class="main">
    <section id="about" class="about section">
        <div class="container">
            <h2>{{ $data->name }}</h2>
            <div class="content">
                {!! $data->content !!}
            </div>
        </div>
  
      </section><!-- /About Section -->
</main>
@endsection