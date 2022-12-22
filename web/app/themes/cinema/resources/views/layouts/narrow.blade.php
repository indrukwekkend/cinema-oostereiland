<?php 
namespace Narrow;

/**
 * Displays the site header.
 *
 * @package WordPress
 * @subpackage Indrukwekkend
 * @since Indrukwekkend 1.1
 */

$wrapper_classes  = 'narrowcasting'; 

?>

<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')
  <body @php body_class() @endphp>
    <div id="page" class="<?php echo esc_attr( $wrapper_classes ); ?>">
      <a class="skip-link screen-reader-text" href="#site-content" title="skip link">Skip to content</a>
      @php do_action('get_header') @endphp
      @include('partials.header-narrow')
      
      <main class="main" id="site-content" role="main">
        @yield('content')
      </main>

    </div>
    @php wp_footer() @endphp
  </body>
</html>