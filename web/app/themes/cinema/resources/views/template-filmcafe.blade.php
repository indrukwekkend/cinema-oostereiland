{{--
  Template Name: Filmcafe Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.page-filmcafe-transparant')
    @include('partials.content-filmcafe')
  @endwhile
@endsection

@push('custom_footer')
  @include('partials.footer-filmcafe')
@endpush