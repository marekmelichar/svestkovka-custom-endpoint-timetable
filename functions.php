<?php

// https://www.sitepoint.com/creating-custom-endpoints-for-the-wordpress-rest-api/







// get request for fetch train departures:
// https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
// https://stackoverflow.com/questions/53126137/wordpress-rest-api-custom-endpoint-with-url-parameter

// https://svestkovka.marekmelichar.cz/wp-json/svestkovka/v1/timetable?from=1&to=8&date=2019-05-05


add_action('rest_api_init', function () {
  register_rest_route('svestkovka/v1', '/timetable', array(
    'methods' => 'GET',
    'callback' => 'get_timetable_func',
    'args' => array(
      
    ),
    'permission_callback' => function () {
      // return current_user_can( 'edit_others_posts' );
      return true;
    }
  ));
});

function get_timetable_func($data)
{
  // var_dump($data);

  $search_from = $data->get_param('from');
  $search_to = $data->get_param('to');
  $search_date = $data->get_param('date');

  // echo $search_from;
  // echo $search_to;
  // echo $search_date;

  global $wpdb;

  $querySpoje = "CALL vyhledejMiSpoje($search_from, $search_to, '$search_date', @smer);";

  $result_spoje = $wpdb->get_results($querySpoje);

  $response = array();

  foreach ($result_spoje as $item) {
    $response[] = array(
      'idSpecSpoje' => $item->idSpecSpoje,
      'casOdjezdu' => $item->casOdjezdu,
      'casPrijezdu' => $item->casPrijezdu,
      'cisloSpoje' => $item->cisloSpoje,
      'poznamky' => $item->poznamky
    );
  }

  echo json_encode($response);

	wp_die(); // this is required to terminate immediately and return a proper response
}