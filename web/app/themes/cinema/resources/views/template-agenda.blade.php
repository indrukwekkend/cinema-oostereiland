{{--
  Template Name: Agenda Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
  <div class="page-holder alignwide">
    @include('partials.page-header-agenda')
    @include('partials.content-agenda')
  </div>
  @endwhile
@endsection
