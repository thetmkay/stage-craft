<?php

add_action( 'wp_ajax_stage_post_trash', 'stage_post_trash_callback' );

function stage_post_trash_callback() {
	global $wpdb; // this is how you get access to the database

	$id = intval( $_POST['post_id'] );

	wp_trash_post($id);

	echo 'successfully deleted '. $id;

	die(); // this is required to return a proper result
}
