{{--
  Template Name: Narrowcasting Template
--}}

@extends('layouts.narrow')

@section('content')
  @while(have_posts()) @php the_post() @endphp
  <div class="page-holder alignwide">
    @include('partials.page-header-agenda')

  </div>
  @endwhile
@endsection
