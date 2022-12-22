{{--
  Template Name: Narrowcasting Template
--}}

@extends('layouts.narrow')

@section('content')
  @while(have_posts()) @php the_post() @endphp
  <div class="page-holder alignfull">
    @include('partials.page-header-agenda')
    @include('partials.content-page')

  </div>
  @endwhile
@endsection
