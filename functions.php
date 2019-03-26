<?php

// https://www.sitepoint.com/creating-custom-endpoints-for-the-wordpress-rest-api/




$dir = plugin_dir_path( __FILE__ );
//
// /**
//  * Shortcodes
//  */
// require_once $dir . '/inc/shortcode.php';
//
// /**
//  * Scripts and Styles
//  */
// require_once $dir . '/inc/enqueue-scripts.php';




/**
 * Init Class
 */
// require_once $dir . '/Slug_Custom_Route.php';

// add_action( 'rest_api_init', function () {
//   Slug_Custom_Route::register_routes();
// });



add_action( 'rest_api_init', function () {
  register_rest_route( 'svestkovka/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'my_awesome_func',
    'args' => array(
      'id' => array(
        'validate_callback' => 'is_numeric'
      ),
    ),
    'permission_callback' => function () {
      // return current_user_can( 'edit_others_posts' );
      return true;
    }
  ) );
} );

/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,
 * or null if none.
 */
function my_awesome_func( $data ) {

  // local fetch from DB:

  // $posts = get_posts( array(
  //   'author' => $data['id'],
  // ) );
  //
  // if ( empty( $posts ) ) {
  //   return new WP_Error( 'no_author', 'Invalid author', array( 'status' => 404 ) );
  // }
  //
  // return $posts[0]->post_title;



  // how to get remote API: https://pippinsplugins.com/using-wp_remote_get-to-parse-json-from-remote-apis/

  $request = wp_remote_get( 'https://pippinsplugins.com/edd-api/products' );

  if( is_wp_error( $request ) ) {
  	return false; // Bail early
  }
  $body = wp_remote_retrieve_body( $request );
  $data = json_decode( $body );
  if( ! empty( $data ) ) {

  	echo '<ul>';
  	foreach( $data->products as $product ) {
  		echo '<li>';
  			echo '<a href="' . esc_url( $product->info->link ) . '">' . $product->info->title . '</a>';
  		echo '</li>';
  	}
  	echo '</ul>';
  }
}
