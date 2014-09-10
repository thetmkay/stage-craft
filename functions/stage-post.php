<?php

	class StagePost {

		private $parent_id;
		private $id;
		private $default_fields;
		private $acfs;
		private $parent;
		private $options;
		private $defaults = array('post_title', 'post_content', 'post_date', 'post_name', 'post_excerpt', 'post_status', 'post_type');

		function __construct($id){
			$this->parent_id = $id;
			$this->parent = get_post($id);
		}

		public function build($options = array(), $defaults = array()) {

			if(!isset($defaults) || !count($defaults)) {
				$defaults = $this->defaults;
			}

			$this->defaults = array_merge(array_keys($options), $defaults);
			$this->options = array_merge((array)$this->parent, $options);

			$this->get_default_fields();
			$this->configure_default_fields();
			$this->create_new_post();
			$this->get_acf_fields();
			$this->update_acf_fields();
			$this->set_meta();

			return get_post($this->id);
		}

		private function get_default_fields () {
			$this->default_fields = array_intersect_key( $this->options, array_flip($this->defaults));
		}

		private function configure_default_fields() {
			unset($this->default_fields['ID']);
			return $this->default_fields;
		}

		private function create_new_post() {
			if(!isset($this->id)) {
				$this->id = wp_insert_post($this->default_fields);
			}
		}

		private function get_acf_fields() {

			if(function_exists('get_field_objects')) {
				$this->acfs = get_field_objects($this->parent_id);
			}
		}

		private function update_acf_fields() {

			if(!function_exists('get_field') || !function_exists('update_field')) {
				return;
			}

			if(!isset($this->id)) {
				$this->create_new_post();
			}

			if(isset($this->acfs) && $this->acfs) {
				foreach($this->acfs as $acf) {
					$value = get_field($acf['name'], $this->parent_id, false);
					update_field($acf['key'], $value, $this->id);
				}
			}
		}

		private function set_meta() {

			if(!isset($this->id)) {
				$this->create_new_post();
			}

			add_post_meta($this->id, 'stage_parent', $this->parent_id);
			add_post_meta($this->id, 'is_staged', 1);

			update_post_meta($this->parent_id, 'is_stage_parent', 1);
		}


		//for debugging
		public static function print_obj($obj) {
			echo print_r($obj, false) . '<br>';
		}

		public function parent() {
			//get parent default fields
			//get parent acf fields

		}

	}
