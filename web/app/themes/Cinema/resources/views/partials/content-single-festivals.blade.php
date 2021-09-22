<div @php post_class() @endphp>

  <?php
  $image = get_field('header_festival');
  $backgroundImage = esc_url($image['url']); 
  // overige meta:
  ?>

  <header class="page-header-film alignfull" style="background-image: url(<?=$backgroundImage?>); ">


    <div class="header-content-wrapper">
        
        <!-- <div class="header-content-wrapper__content alignwide">
          <p>hier het label</p>
          <h1 class="entry-title">{!! get_the_title() !!}</h1>
          <p>hier de subtekst</p>
        </div> -->

        <div id="cta-bar" class="cta-bar alignfull">
          <div class="cta-bar__content alignwide">
            
            <div class="cta-bar__content__left">
              <h1 class="entry-title">{!! get_the_title() !!}</h1>
              <div class="film-info">
                  <div class="ticketDates"></div>
                  <p>&nbsp; / @php echo $regisseur @endphp</p>
              </div>
             
            </div>
          </div>
        </div>
    </div>
  
  </header>


  <div class="entry-content">
    @php the_content() @endphp
  </div>

<?php
$featured_posts = get_field('festival_films');
global $post; 
  if( $featured_posts ): ?>
    <div class="specials-block-films alignwide">
        <h2><?php the_field( 'reeks_titel' ); ?></h2>
        <ul class="filmlijst">
        <?php foreach( $featured_posts as $post ): 

            // Setup this post for WP functions (variable must be named $post).
            setup_postdata($post); ?>

            <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <span>A custom field from this post: <?php the_field( 'reeks_titel' ); ?></span>
            </li>

        <?php endforeach; ?>
        </ul>
      </div>
      <?php 
      // Reset the global post object so that the rest of the page works correctly.
      wp_reset_postdata(); ?>

<?php endif; ?>



  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</div>
