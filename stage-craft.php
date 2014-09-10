<?php
/**
 * Plugin Name: Stage Craft
 * Plugin URI: http://github.com/thetmkay/stagecraft
 * Description: Dummy Post Generation.
 * Version: 0.1
 * Author: George Nishimura
 * Author URI: http://georgenishimura.com
 * License: GPL2
 */

if( ! defined( 'ABSPATH' ) ) exit;

if (! class_exists('StageCraft')):

class StageCraft	{

	function init() {

		self::include_files();

		add_action('admin_menu', 'register_stage_craft_menus_custom');
		add_action('admin_menu', 'register_stage_craft_menus_post');
		add_action( 'admin_enqueue_scripts', 'register_stage_craft_style' );
		add_action('wp_trash_post', 'register_stage_craft_trash_check');
		add_action('wp_trash_post', 'register_stage_craft_trash_check');
		add_action('wp_untrash_post', 'register_stage_craft_untrash_check');
		add_action('untrash_post', 'register_stage_craft_untrash_check');

		function register_stage_craft_menus_custom() {

			$post_types = array('page');

			foreach($post_types as $post_type) {

				add_submenu_page( 	'edit.php?post_type=' . $post_type,
									'Stage ' . ucfirst($post_type) . 's',
									'Stage ' . ucfirst($post_type) . 's',
									'manage_options',
									'stage-craft-' . $post_type,
									function() { stage_craft_render_menu('page'); }
								);
			}

		}

		function register_stage_craft_menus_post() {

			add_submenu_page( 	'edit.php',
								'Stage Posts',
								'Stage Posts',
								'manage_options',
								'stage-craft-post',
								function() { stage_craft_render_menu(); }
							);

		}


		function stage_craft_render_menu($post_type = 'post') {

			$name = $post_type;
			$plural = $name . 's';
			$title = ucfirst($plural);
			$slug = 'stage-craft-' . $plural;

			$context = array();
			$context['tag'] = $slug;
			$context['post_type'] = $post_type;
			$context['posts'] = array(
				array('post_name' => 'post-1', 'post_title' => 'Post #1', 'ID' => 35),
				array('post_name' => 'post-2', 'post_title' => 'Post #2', 'ID' => 35),
				array('post_name' => 'post-3', 'post_title' => 'Post #3', 'ID' => 35),
			);


			echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Generate Stage ' . $title . '</h2>';

			render_posts_table($context);

			include_posts_table_ajax($slug);
			include_orphans_table_ajax($slug);

			// add_meta_box($slug .'-menu', $title, 'render_main_meta_box', $slug, 'advanced', 'high');
			// do_meta_boxes($slug , 'advanced', $post_type);

			// $wp_query = new WP_Query();


			// print_r(get_post());

			echo '</div>';
		}

		function render_main_meta_box($post_type) {
			echo '<p>Hello World ' . $post_type . '</p>';
		}

		function register_stage_craft_style($hook) {

		    if( strpos($hook, 'stage-craft') === false)
		        return;
		    wp_register_style( 'stage_craft_css', plugin_dir_url( __FILE__ ) . 'css/stage-craft.css', false, '1.0.0');
		    wp_enqueue_style( 'stage_craft_css');
		}

		function register_stage_craft_trash_check($id) {


			if(get_post_meta($id, 'is_stage_parent', true) == 1) {

				update_post_meta($id, 'is_stage_parent',-1);

				$args = array(
					'meta_key'         => 'stage_parent',
					'meta_value'       => $id,
					'post_status'	   => 'any');
					$posts = get_posts($args);


				foreach($posts as $post) {
					orphan_stage_child($post, $id);
				}
			}
		}

		function register_stage_craft_untrash_check($id) {

			if(get_post_meta($id, 'is_stage_parent', true) == (-1)) {

				update_post_meta($id, 'is_stage_parent',1);

				$args = array(
					'meta_key'         => 'stage_parent',
					'meta_value'       => (-$id),
					'post_status'	   => 'any');
					$posts = get_posts($args);


				foreach($posts as $post) {
					adopt_stage_child($post, $id);
				}
			}
		}

		function orphan_stage_child($post, $parent_id) {
			update_post_meta($post->ID, 'stage_parent', -($parent_id));
		}

		function adopt_stage_child($post, $parent_id) {
			update_post_meta($post->ID, 'stage_parent', $parent_id);
		}

	}

	function include_files() {
		include('utilities/stage-post-view-builder.php');


		include('views/posts-row.php');
		include('views/posts-table.php');
		include('views/ajax/orphans-table-ajax.php');
		include('views/ajax/posts-table-ajax.php');


		include('api/stage-posts-controller.php');
		include('api/trash-posts-controller.php');

		include('functions/stage-post.php');

	}

}


function init() {
	global $stage_craft;

	if(!isset($stage_craft)) {
		$stage_craft = new StageCraft();

		$stage_craft->init();
	}

	return $stage_craft;
}

init();

endif;
