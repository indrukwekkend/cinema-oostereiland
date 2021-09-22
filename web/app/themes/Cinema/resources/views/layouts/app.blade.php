<?php 
/**
 * Displays the site header.
 *
 * @package WordPress
 * @subpackage Indrukwekkend
 * @since Indrukwekkend 1.1
 */

$wrapper_classes  = 'site'; 
$wrapper_classes .= true === get_theme_mod( 'display_transparant_nav', true ) ? ' transparant' : '';

if ( has_block( 'indrukwekkend/header-achtergrond' ) || is_singular('films') )  {
  $wrapper_classes  .= ' achtergrond';
}
?>

<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')
  <body @php body_class() @endphp>
    <div id="page" class="<?php echo esc_attr( $wrapper_classes ); ?>">
      <a class="skip-link screen-reader-text" href="#site-content" title="skip link">Skip to content</a>
      @php do_action('get_header') @endphp
      @include('partials.header')
      
      <main class="main" id="site-content" role="main">
        @yield('content')
      </main>
      @if (App\display_sidebar())
      <aside class="sidebar">
        @include('partials.sidebar')
      </aside>
      @endif
 
      @php do_action('get_footer') @endphp
      @include('partials.footer')
    </div>
    @php wp_footer() @endphp
  </body>
</html>