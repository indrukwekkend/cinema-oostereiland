<?php
// Festivals.
//
// onverdeel van single content Films

$featured_post = get_field('festival_films');

if( $featured_post ): 
  global $post; 
  $post = $featured_post;
	setup_postdata( $post );
  
  $image = get_field('header_festival');
  $backgroundImage = esc_url($image['url']); 
  // Festival Blok hieronder
  ?>

  <div class="wp-block-group alignfull has-festival-grijs-background-color has-background">
    <div class="wp-block-group__inner-container">
      <div class="wp-block-indrukwekkend-festival alignwide">
        <div class="festival-content">
          
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="wp-block-columns">
            <div class="wp-block-column" style="flex-basis:55%">
              <img class="" src="<?=$backgroundImage?>">
            </div>
            <div class="wp-block-column" style="flex-basis:45%">
              <div class="text">
                <?php 
                    $intro = get_field( 'intro_tekst' ); 
                    if ($intro != '') {
                      echo "<p class='intro-text'>$intro</p>";
                    }
                ?>
                  <?php the_excerpt(); ?>
                  <a href="<?php the_permalink(); ?>">Lees alles over <?php the_title();?></a>
              </div>  
            </div>
          </div>
        </div>
        
        <div class="festival-films">
          <h3><?php the_field( 'reeks_titel' ); ?></h3>
          
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
                              <h3><?php the_title(); ?></h3>
                              <p>Extra informatie</p>
                            </div>
                        </div>
                      </li>

                    <?php endforeach; ?>

                  </div>
                  <?php 
                  // Reset the global post object so that the rest of the page works correctly.
                  wp_reset_postdata(); ?>

            <?php endif; ?> 

        </div>
      </div>
    </div>
  </div>
  <?php 
  // Reset the global post object so that the rest of the page works correctly.
  wp_reset_postdata(); ?>

<?php endif; ?>
