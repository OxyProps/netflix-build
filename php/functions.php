<?php
/**
 * Bricks Child Theme functions.php file
 * Code from the OxyProps Youtube Video
 */

define( 'TMDB_API_KEY', 'YOUR_PERSONAL_TMDB_API_KEY_HERE' );

$base_url        = 'https://api.themoviedb.org/3';
$images_base_url = 'https://image.tmdb.org/t/p/original';
$requests        = array(
	'Trending'         => '/trending/all/week?api_key=' . TMDB_API_KEY . '&language=en-US',
	'NetflixOriginals' => '/discover/tv?api_key=' . TMDB_API_KEY . '&with_networks=213',
	'TopRated'         => '/movie/top_rated?api_key=' . TMDB_API_KEY . '&language=en-US',
	'ActionMovies'     => '/discover/movie?api_key=' . TMDB_API_KEY . '&with_genres=28',
	'ComedyMovies'     => '/discover/movie?api_key=' . TMDB_API_KEY . '&with_genres=35',
	'HorrorMovies'     => '/discover/movie?api_key=' . TMDB_API_KEY . '&with_genres=27',
	'RomanceMovies'    => '/discover/movie?api_key=' . TMDB_API_KEY . '&with_genres=10749',
	'Documentaries'    => '/discover/movie?api_key=' . TMDB_API_KEY . '&with_genres=99',
);

function fetch_movies( $fetch_url ) {
	try {
		$response = wp_remote_get(
			$fetch_url,
			array(
				'headers' => array(
					'Accept' => 'application/json',
				),
			)
		);
		if ( ( ! is_wp_error( $response ) ) && ( 200 === wp_remote_retrieve_response_code( $response ) ) ) {
			$response_body = json_decode( $response['body'] );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				return json_decode( json_encode( $response_body ), true )['results'];
			}
		}
	} catch ( Exception $ex ) {
		error_log( print_r( $ex, true ) );
	}
}

function get_all_movie_categories() {
	global $requests;
	global $base_url;

	$movies_by_category = array();

	foreach ( $requests as $name => $url ) {
		$movies_by_category[ $name ] = fetch_movies( $base_url . $url );
	}
	return $movies_by_category;
}

function pick_hero_movie() {
	global $movies;
	$random_index = random_int( 0, count( $movies['NetflixOriginals'] ) - 1 );
	return $movies['NetflixOriginals'][ $random_index ];
}

function get_hero_image() {
	global $hero_movie;
	global $images_base_url;
	return $images_base_url . $hero_movie['backdrop_path'];
}

function get_movie_title( $movie ) {
	switch ( true ) {
		case isset( $movie['title'] ):
			$title = $movie['title'];
			break;
		case isset( $movie['originl_title'] ):
			$title = $movie['originl_title'];
			break;
		case isset( $movie['name'] ):
			$title = $movie['name'];
			break;
		case isset( $movie['original_name'] ):
			$title = $movie['original_name'];
			break;
		default:
			$title = '';
			break;
	}
	return $title;
}

function get_hero_title() {
	global $hero_movie;
	return get_movie_title( $hero_movie );
}

function get_hero_overview() {
	global $hero_movie;
	return $hero_movie['overview'];
}

$movies     = get_all_movie_categories();
$hero_movie = pick_hero_movie();
