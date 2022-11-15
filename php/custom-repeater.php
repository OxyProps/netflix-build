<?php
global $movies;
global $images_base_url;

$category   = 'NetflixOriginals';
$image_type = 'poster';

foreach ( $movies[ $category ] as $key => $movie ) {
	if ( isset( $movie[ $image_type . '_path' ] ) ) {
		echo '<img
        class="o-size-fixed-9 o-radius-2 o-portrait"
        src="' . $images_base_url .
		esc_html( $movie[ $image_type . '_path' ] ) .
		'"alt="' . esc_html( get_movie_title( $movie ) ) .
		'"/>';
	}
}


