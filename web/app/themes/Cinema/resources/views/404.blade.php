@extends('layouts.app')

@section('content')
<article @php post_class() @endphp>

  @include('partials.page-header')

  @if (!have_posts())
  <div class="entry-content">

      <p>{{ __('Sorry, but the page you were trying to view does not exist.', 'sage') }}</p>
      <?php 
			if( have_rows('opt_general_404', 'option') ):
				while( have_rows('opt_general_404', 'option') ): the_row(); 
		        	$title = get_sub_field('title', 'option');
					$text = get_sub_field('txt', 'option');
					echo '<h1>'.$title.'</h1>';
					echo $text;
				endwhile;
			endif;
		?>

	{!! get_search_form(false) !!}
	</div>
  @endif

  </article>
@endsection
