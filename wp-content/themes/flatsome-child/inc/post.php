<?php
// Add post columns
function willgroup_columns_head($cols) {
	$cols = array_merge(
		array_slice( $cols, 0, 1, true ),
		array( 'thumbnail' => __( 'Ảnh' , 'flatsome' ) ),
		array_slice( $cols, 1, null, true )
	);
	return $cols;
}
function willgroup_columns_content($col_name, $post_ID) {
	global $default_img;
	if ($col_name == 'thumbnail') {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );
		if ( $image ) {
			echo '<a href="' . get_edit_post_link( $post_ID ) . '"><img width="70px" class="lazy" src="'.   $image['0'] .'" data-src="' . $image['0'] . '"/></a>';
		} else {
			echo '—';
		}
		echo '<style type="text/css">';
		echo '.column-thumbnail { width:70px; }';
		echo '</style>';
	}
}
add_filter('manage_post_posts_columns', 'willgroup_columns_head');
add_action('manage_post_posts_custom_column', 'willgroup_columns_content', 10, 2);
