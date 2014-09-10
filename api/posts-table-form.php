<?php

add_action( 'wp_ajax_stage_post', 'stage_post_callback' );

function stage_post_callback() {
	global $wpdb; // this is how you get access to the database

	$quantity = intval( $_POST['post_quantity'] );
	$id = intval( $_POST['post_id'] );

	$ids = array();
	for($i = 0; $i < $quantity; $i++) {
		$stage = new StagePost($id);
		$ids[] = $stage->build()->ID;
	}

	print_r($ids);

	// echo $quantity;

	die(); // this is required to return a proper result
}
