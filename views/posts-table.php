<?php

function render_posts_table($context) {

	//dependencies


	$post_type = $context['post_type'];

	$tag = $context['tag'];

	$staged_posts = get_staged_posts($post_type);
	$possible_orphans = $staged_posts;


	$posts = get_posts_data($post_type, $staged_posts);
	$orphans = get_orphans($possible_orphans);
?>

	<table class="<?php echo $tag . '-table'?> stage-craft-table widefat fixed">
		<thead>
			<?php render_posts_table_header(); ?>
		</thead>
		<tbody>
		<?php
			foreach($posts as $index=>$post) {

				$classes = array();

				if($index % 2 == 0) {
					$classes = array('alternate');
				}

				render_posts_row($post, $classes);
			}
		?>
		</tbody>
	</table>

	<table class="<?php echo $tag . 'orphans-table'?> stage-craft-table widefat fixed">
		<thead>
			<?php render_orphans_table_header(); ?>
		</thead>
		<tbody>
		<?php
			foreach($orphans as $index=>$orphan) {

				$classes = array();

				if($index % 2 == 0) {
					$classes = array('alternate');
				}

				render_orphans_row($orphan, $classes);
			}
		?>
		</tbody>
	</table>

<?php
}


function get_staged_posts($post_type) {
	$args = array(
		'meta_key'         => 'is_staged',
		'meta_value'       => '1',
		'post_type'        => $post_type,
		'post_status'	   => 'any');

	$posts = get_posts($args);

	$posts = array_map('include_stage_meta', $posts);

	return $posts;
}

function get_posts_data($post_type, $staged_posts) {

	$staged_ids = array_map('extract_id', $staged_posts);

	// print_r($staged_ids);

	$args = array(
		'exclude'		=> $staged_ids,
		'post_type'		=> $post_type,
		'post_status'	=> 'any');

	$posts = get_posts($args);

	foreach($posts as $post) {

		$children = array_filter($staged_posts, function($p) use ($post) {
			return $p->stage_parent == $post->ID;
		});

		$post->stage_children = count($children);
	}


	return $posts;
}

function get_orphans($staged_posts) {
	return array_filter($staged_posts, function($post) {
		if(get_post_meta($post->ID, 'stage_parent', true) < 0) {
			return true;
		}

		return false;
	});
}

function extract_id($post) {
	return $post->ID;
}

function include_stage_meta($post) {
	$parent = get_post_meta($post->ID, 'stage_parent', true);
	$post->stage_parent = $parent;
	return $post;
}
