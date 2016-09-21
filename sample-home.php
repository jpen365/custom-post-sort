<?php 

/*  
 *  Sample home.php with two loops
 *  First loop returns posts with custom post order
 *  Second loop returns all other posts in normal order by date
 *  Posts in second loop are paginated, but posts in first loop are not
 */

get_header(); 

/*********************
 * Begin The Loop(s) *
 *********************/

/*  
 *  First Loop 
 *  Returns posts with a custom post order value
 */

// First, determine if on the first page of posts
// If on first page, run query to display posts with custom sort first

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if(1 == $paged) :

  $args1 = array(
    'post_type' => 'post',
    'meta_key' => '_custom_post_order',
    'orderby' => 'meta_value',
    'order' => 'ASC'
  );

  $query1 = new WP_query ( $args1 );

  if ( $query1->have_posts() ) :
    while ($query1->have_posts() ) :
      $query1->the_post(); 

      // This if statement will skip posts that were assigned a custom sort value and then had that value removed 
      if ( !empty(get_post_meta( $post->ID, '_custom_post_order', true )) ) :

        // Display the custom sorted posts ?>
        <div>
        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>">Read More</a>
        </div>
        <hr>
        <!--insert additional code for rendering posts-->

      <?php endif; // End displaying custom sorted posts
    endwhile; // End looping through custom sorted posts
  endif; // End loop 1
  wp_reset_postdata(); // Set up post data for next loop
endif; // End checking for first page

/*  
 *  Second Loop 
 *  Returns all posts except those in the list above
 */

$args2 = array(
  'post_type' => 'post',
  'orderby' => 'date',
  'order' => 'DESC',
  'paged' => $paged
);

// For pagination to work, must make temporary use of global $wp_query variable
$temp = $wp_query;
$wp_query = null;
$wp_query = new WP_query ( $args2 );

if ( $wp_query->have_posts() ) :
  while ($wp_query->have_posts() ) :
    $wp_query->the_post();

      // Skip posts with custom sort value
      if ( !empty(get_post_meta( $post->ID, '_custom_post_order', true )) ) { continue; }

      // Display the standard sorted posts ?>
      <div>
      <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
      <?php the_excerpt(); ?>
      <a href="<?php the_permalink(); ?>">Read More</a>
      </div>
      <hr>
      <!--insert additional code for rendering posts-->

    <?php
  endwhile; // End looping through standard sorted posts
endif; // End loop 2
wp_reset_postdata();

/*******************
 * End The Loop(s) *
 *******************/ ?>

<!-- add next/previous navigation -->
<ul>
  <li>
    <?php next_posts_link( '&larr; Older' ); ?>
  </li>
  <li>
    <?php previous_posts_link( 'Newer &rarr;' ); ?>
  </li>
</ul>
              
<?php
// reset global $wp_query variable to its original state
$wp_query = null;
$wp_query = $temp;

get_sidebar();   
get_footer(); 
?>