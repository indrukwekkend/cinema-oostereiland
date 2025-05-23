<?php 
namespace App;

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

      @if (is_page_template('views/template-filmcafe.blade.php'))
        @include('partials.page-header-filmcafe')
      @else
        @include('partials.header')
      @endif
   
      <main class="main" id="site-content" role="main">
        @yield('content')
      </main>
 
      @php do_action('get_footer') @endphp
      @include('partials.footer-cta')
      @if (is_page_template('views/template-filmcafe.blade.php'))
        @include('partials.footer-filmcafe')
      @else
        @include('partials.footer')
      @endif
    </div>
    @php wp_footer() @endphp
  </body>
</html>