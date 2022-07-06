{{--
  Template Name: Landing Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp

    @include('partials.page-header-landing')
    @include('partials.content-page')

  @endwhile
@endsection
