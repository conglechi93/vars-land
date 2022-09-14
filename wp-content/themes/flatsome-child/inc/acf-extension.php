<?php
new willgroup_acf_extension();

class willgroup_acf_extension {

	public function __construct() {

		// province field on post
		add_action('acf/load_field/name=re_province', array($this, 'willgroup_load_province_field_choices'));

		// district field on post
		add_action('acf/load_field/name=re_district', array($this, 'willgroup_load_district_field_choices'));

		// ajax action for loading district choices
		add_action('wp_ajax_willgroup_load_district_field_choices', array($this, 'willgroup_ajax_load_district_field_choices'));

		// ward field on post
		add_action('acf/load_field/name=re_ward', array($this, 'willgroup_load_ward_field_choices'));

		// ajax action for loading ward choices
		add_action('wp_ajax_willgroup_load_ward_field_choices', array($this, 'willgroup_ajax_load_ward_field_choices'));

		// enqueue js extension for acf
		// do this when ACF in enqueuing scripts
		add_action('acf/input/admin_enqueue_scripts', array($this, 'enqueue_script'));
	}

	public function enqueue_script() {
		global $post;
		if (!$post ||
		    !isset($post->ID) ||
		    get_post_type($post->ID) != 're') {
			return;
		}

		$handle = 'willgroup-acf-extension';
		$src = get_stylesheet_directory_uri() . '/assets/js/acf-extension.js';

		// make this script dependent on acf-input
		$depends = array('acf-input');

		wp_enqueue_script($handle, $src, $depends);
	}

	public function willgroup_load_province_field_choices($field) {
		global $post;
		if (!$post ||
		    !isset($post->ID) ||
		    get_post_type($post->ID) != 're') {
			return $field;
		}

		$provinces = willgroup_get_assoc_array_of_provinces();
		$field['choices'] = $provinces;
		return $field;
	}

	public function willgroup_load_district_field_choices($field) {
		global $post;
		if (!$post ||
		    !isset($post->ID) ||
		    get_post_type($post->ID) != 're') {
			return $field;
		}

		$districts = willgroup_get_assoc_array_of_districts(get_post_meta($post->ID, 're_province', true));
		$field['choices'] = $districts;
		return $field;
	}

	public function willgroup_ajax_load_district_field_choices() {
		if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
			die();
		}
		$province = '';
		if (isset($_POST['province'])) {
			$province = $_POST['province'];
		}
		$districts = willgroup_get_assoc_array_of_districts($province);
		$choices = array();
		foreach ($districts as $key => $value) {
			$choices[] = array('value' => $key, 'label' => $value);
		}
		echo json_encode($choices);
		exit;
	}

	public function willgroup_load_ward_field_choices($field) {
		global $post;
		if (!$post ||
		    !isset($post->ID) ||
		    get_post_type($post->ID) != 're') {
			return $field;
		}

		$wards = willgroup_get_assoc_array_of_wards(get_field('re_district', $post->ID));
		$field['choices'] = $wards;
		return $field;
	}

	public function willgroup_ajax_load_ward_field_choices() {
		if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
			die();
		}
		$district = '';
		if (isset($_POST['district'])) {
			$district = $_POST['district'];
		}
		$wards = willgroup_get_assoc_array_of_wards($district);
		$choices = array();
		foreach ($wards as $key => $value) {
			$choices[] = array('value' => $key, 'label' => $value);
		}
		echo json_encode($choices);
		exit;
	}

}
