 <?php

 get_header(); ?>

  <!-- Page Content -->
  <div class="container">

      <div class="row">

          <!-- Blog Entries Column -->
          <div class="col-md-8">


              <!-- add page title to pages -->
<?php if ( is_front_page() && is_home() ) {
  // Default homepage, do nothing, no title
  ?><h1 class="page-header"><?php bloginfo( 'name' ); ?> <small><?php bloginfo( 'description' ); ?></small></h1><?php
} elseif ( is_home() ) {
  // blog page, use page title
  $posts_page = get_post( get_option( 'page_for_posts' ) ); 
  echo '<h1 class="page-header">' . apply_filters( 'the_title', $posts_page->post_title ) . '</h1>';
} ?>
<!-- end add title to pages -->

<!-- The Loop -->


<?php

/* determine if on the first page of posts */

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if(1 == $paged) :

  /*  
   *  First Loop 
   *  Returns posts with a custom post order value
   */

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

      if ( !empty(get_post_meta( $post->ID, '_custom_post_order', true )) ) : ?>

        <div>
        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>">Read More</a>
        </div>
        <hr>
        <!--insert additional code for rendering posts-->

      <?php endif;
    endwhile;
  endif;
  wp_reset_postdata();
endif;


/*  
 *  Second Loop 
 *  Returns all posts except those in the list above
 */

$args2 = array(
  'post_type' => 'post',
  'orderby' => 'date',
  'order' => 'DESC'
);

$query2 = new WP_query ( $args2 );

if ( $query2->have_posts() ) :
  while ($query2->have_posts() ) :
    $query2->the_post(); ?>

    

      <div>
      <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
      <?php the_excerpt(); ?>
      <a href="<?php the_permalink(); ?>">Read More</a>
      </div>
      <hr>
      <!--insert additional code for rendering posts-->

    <?php
  endwhile;
endif;
wp_reset_postdata();
?>

<!-- add next/previous navigation to posts page -->
<ul class="pager">
  <li class="previous">
    <?php next_posts_link( '&larr; Older' ); ?>
  </li>
  <li class="next">
    <?php previous_posts_link( 'Newer &rarr;' ); ?>
  </li>
</ul>
              

</div>

<?php get_sidebar(); ?>    
<?php get_footer(); ?>