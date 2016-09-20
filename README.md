# Custom Post Sort

This plugin adds a custom field to WordPress posts. Users can specify the order in which posts should appear by adding a numeric value to the custom field. 

## Implementation

The core plugin just adds the custom sort field to posts. Implementation of the custom sort can be accomplished in a few different ways:

### Use `pre_get_posts`

Simply copy and paste the following function into *custom-post-sort.php* to sort the posts on the blog home page according to the custom sort order. Note that any posts that do not have a custom order value will not be displayed in the posts list with this function added to the plugin.

```php
/* Sort posts on the blog posts page according to the custom sort order */
function jpen_custom_post_order_sort( $query ){
  if ( $query->is_main_query() && is_home() ){
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', '_custom_post_order' );
    $query->set( 'order' , 'ASC' );
  }
}
add_action( 'pre_get_posts' , 'jpen_custom_post_order_sort' );
```

### Use `WP_Query`

A more flexible way to use the custom sort order value would be to create a class of the [`WP_Query`](https://codex.wordpress.org/Class_Reference/WP_Query) object that queries for posts that possess a custom sort order value. 

For example:

```php
<?php
$args = array(
  'post_type' => 'post',
  'meta_key' => '_custom_post_order',
  'orderby' => 'meta_value',
  'order' => 'ASC'
);

$query = new WP_query ( $args );

if ( $query->have_posts() ) {
  while ($query->have_posts() ) {
    $query->the_post();

    /* only list posts that have a current custom post order value */
    if ( !empty(get_post_meta( $post->ID, '_custom_post_order', true )) ) : ?>

    /* insert code for rendering posts */

<?php 
    endif; }
  wp_reset_postdata();
} ?>
```

This query could be used for a [custom page template](https://developer.wordpress.org/themes/template-files-section/page-template-files/page-templates/#creating-custom-page-templates-for-global-use) or to populate a list of posts in a [custom sidebar widget](https://codex.wordpress.org/Widgets_API#Developing_Widgets).

One way to make the custom sort even more useful would be to create a custom page template that includes two `WP_Query` objects:

- The first to query for posts that possess a custom post order value.
- The second to query for other posts.

In this way, pages can be built that have a preset list of sorted posts that appear first followed by posts displayed in normal order. 

For example:

```php
<?php
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

if ( $query1->have_posts() ) {
  while ($query1->have_posts() ) {
    $query1->the_post(); 

    if ( !empty(get_post_meta( $post->ID, '_custom_post_order', true )) ) : ?>

      <div>
      <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
      <?php the_excerpt(); ?>
      <a href="<?php the_permalink(); ?>">Read More</a>
      </div>
      <hr>
      <!--insert additional code for rendering posts-->

<?php 
    endif; }
  wp_reset_postdata();
} ?>


<?php
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

if ( $query2->have_posts() ) {
  while ($query2->have_posts() ) {
    $query2->the_post();

    if ( !empty(get_post_meta( $post->ID, '_custom_post_order', true )) ) { continue; } ?>

      <div>
      <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
      <?php the_excerpt(); ?>
      <a href="<?php the_permalink(); ?>">Read More</a>
      </div>
      <hr>
      <!--insert additional code for rendering posts-->

<?php 
    }
  wp_reset_postdata();
} ?>
```

Note that when you add multiple loops to a single page template it becomes necessary to manually manipulate pagination. [Learn more](http://wordpress.stackexchange.com/questions/108679/wp-query-pagination-on-multiple-loop-page-breaks-wp-or-doesnt-show-up).