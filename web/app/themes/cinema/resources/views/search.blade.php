@extends('layouts.app')

@section('content')
    <div class="sub-page">
        <div class="wp-editor container">
            <div class="page-header">
                <h1><?php echo the_title() ?></h1>
            </div>
            @if (!have_posts())
            <div class="alert alert-warning">
              {!! __('Sorry, er werden geen resultaten gevonden.', 'sage') !!}
            </div>

          @endif

          <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
            <label>
              <span class="screen-reader-text"><?php _x( 'Search for:', 'label' )?></span>
              <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />
            </label>
            <button type="submit" class="search-submit">Zoeken</button>
          </form>
            
            @while(have_posts()) @php the_post() @endphp
              @include('partials.content-search')
            @endwhile

            {!! get_the_posts_navigation() !!}
        </div>
    </div>
@endsection
