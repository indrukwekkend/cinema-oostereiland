<?php 
//Get current active Genres:
$custom_terms = get_the_terms( $film_id, 'film-genre' );

if( !empty( $custom_terms ) ):
  $number = sizeof ($custom_terms);

    // going to hold our tax_query params
    $tax_query = array();

    // add the relation parameter (not sure if it causes trouble if only 1 term so what the heck)
    if( $number > 1 )
        $tax_query['relation'] = 'OR' ;

    // loop through venues and build a tax query
    foreach( $custom_terms as $custom_term ) {

        $tax_query[] = array(
            'taxonomy' => 'film-genre',
            'field' => 'slug',
            'terms' => $custom_term->slug,
        );

    }

    // put all the WP_Query args together
    $args = array( 'post_type' => 'films',
                    'post__not_in' => array($film_id),
                    'posts_per_page' => 5,
                    'tax_query' => $tax_query );

    // finally run the query
    $loop = new WP_Query($args);

    if( $loop->have_posts() ) : ?>
      <div class="alignwide block-aangeraden-films block-slider">
        <div class="title-element">
          <h2>Interessant</h2>
        </div>
        <div class="content-element">
          <span class="onderwerp h2">Voor jou </span>
          <!-- lijst met aangeraden films -->
          <div class="specials-block-films sliderlijst">
                  
                
            <?php
            while( $loop->have_posts() ) : $loop->the_post(); ?>

              <li class='film'>
                <?php
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

                <div class="card filmsFeatImg">

                    <?php echo sprintf('<picture class="thumbnail">%1$s</picture>', $thumbnail); ?>

                    <div class="text">
                      <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($title); ?>" class="overlay"></a>
                      <h3><?php the_title(); ?></h3>
                      <p class='extra'>Extra informatie</p>
                    </div>
                </div>
              </li> 
                        
            <?php endwhile;?>

        </div>
        </div>
      </div>
   <?php endif; //loop

endif;
?>