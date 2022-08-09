<div @php post_class() @endphp>

  <?php
  $image = get_field('header_festival');
  $backgroundImage = esc_url($image['url']); 
  $intro_tekst = get_field('intro_tekst');
  // overige meta:
  ?>

  <header class="page-header-film alignfull" style="background-image: url(<?=$backgroundImage?>); ">


    <div class="header-content-wrapper">
        

        <div id="cta-bar" class="cta-bar alignfull">
          <div class="cta-bar__content alignwide">
            
            <div class="cta-bar__content__left">
              <h1 class="entry-title">{!! get_the_title() !!}</h1>
              <div class="film-info">
                <?= $intro_tekst ?>
                <!-- Hier nog de informatie van de Drama reeks? Datum tijd groepje.. Als het een start en einddatum heeft. -->
              </div>
            </div>
          </div>
        </div>
    </div>
  
  </header>


  <div class="entry-content">
    @php the_content() @endphp
  </div>


<div class="festival-films alignwide">
          <h2><?php the_field( 'reeks_titel' ); ?></h2>
          
          <?php
            // Hier de lijst met de films in het Festival
            $films = get_field('festival_films');
            
              if( $films ): 
                global $post; ?>
                <div class="festival-films-lijst filmsFeatImg slider">
                    
                  
                    <?php foreach( $films as $post ): 

                        // Setup this post for WP functions (variable must be named $post).
                        setup_postdata($post); 

                        $image = get_field('header_img');

                        $url = $image['url'];
                        $title = $image['title'];
                        $alt = $image['alt'];
                        $caption = $image['caption'];
                    
                        // Thumbnail size attributes.
                        $size = 'filmsFeatImg';
                        $thumb = $image['sizes'][ $size ];
                        $thumbnail = "<img src='$thumb' alt='$alt' />";
                    ?>

                      <li class='film'>
                        <div class="card <?= $size ?>">

                            <?php echo sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail);?>
                            <div class="text">
                              <a class="overlay" href="<?php the_permalink(); ?>" title="<?php echo esc_attr($title); ?>"></a>
                              
                              <!-- <p>Extra informatie</p> -->
                            </div>
                            <h3><?php the_title(); ?></h3>
                        </div>
                      </li>

                    <?php endforeach; ?>

                  </div>
                  <?php 
                  // Reset the global post object so that the rest of the page works correctly.
                  wp_reset_postdata(); ?>

            <?php endif; ?> 

        </div>



  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</div>
