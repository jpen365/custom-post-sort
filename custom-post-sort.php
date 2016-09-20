<?php
/**
 * Plugin Name:   Custom Post Sort
 * Plugin URI:    https://github.com/jpen365/custom-post-sort
 * Description:   Add a custom post order field to WordPress posts and display posts on the blog page using this new field.
 * Version:       0.1
 * Author:        Jon Penland
 * Author URI:    http://www.jonpenland.com
 * Text Domain:   custom-post-sort
 */


/* Create custom meta data box to the post edit screen */

function jpen_custom_post_sort( $post ){
  add_meta_box( 
    'custom_post_sort_box', 
    'Position in List of Posts', 
    'jpen_custom_post_order', 
    'post' ,
    'side'
    );
}
add_action( 'add_meta_boxes', 'jpen_custom_post_sort' );


/* Add a field to the metabox */

function jpen_custom_post_order( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'jpen_custom_post_order_nonce' );
  $current_pos = get_post_meta( $post->ID, '_custom_post_order', true); ?>
  <p>Enter the position at which you would like the post to appear. For exampe, post "1" will appear first, post "2" second, and so forth.</p>
  <p><input type="number" name="pos" value="<?php echo $current_pos; ?>" /></p>
  <p>Posts that are not assigned a number will appear after numbered posts and will be sorted by date of publication.</p>
  <?php
}


/* Save the input to post_meta_data */

function jpen_save_custom_post_order( $post_id ){
  if ( !isset( $_POST['jpen_custom_post_order_nonce'] ) || !wp_verify_nonce( $_POST['jpen_custom_post_order_nonce'], basename( __FILE__ ) ) ){
    return;
  } 
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
    return;
  }
  if ( ! current_user_can( 'edit_post', $post_id ) ){
    return;
  }
  if ( isset( $_REQUEST['pos'] ) ) {
    update_post_meta( $post_id, '_custom_post_order', sanitize_text_field( $_POST['pos'] ) );
  }
}
add_action( 'save_post', 'jpen_save_custom_post_order' );


/* Add custom post order column to post list */

function jpen_add_custom_post_order_column( $columns ){
  return array_merge ( $columns,
    array( 'pos' => 'Position', ));
}
add_filter('manage_posts_columns' , 'jpen_add_custom_post_order_column');


/* Display custom post order in the post list */

function jpen_custom_post_order_value( $column, $post_id ){
  if ($column == 'pos' ){
    echo '<p>' . get_post_meta( $post_id, '_custom_post_order', true) . '</p>';
  }
}
add_action( 'manage_posts_custom_column' , 'jpen_custom_post_order_value' , 10 , 2 );