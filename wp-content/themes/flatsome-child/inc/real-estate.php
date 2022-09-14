<?php
/* Add re post type, re_cat taxonomy */
function muatheme_com_init_re() {
  $capabilities = array(
    'edit_post' => 'edit_re',
    'edit_posts' => 'edit_res',
    'publish_posts' => 'publish_res',
    'read_post' => 'read_re',
    'read_private_posts' => 'read_private_res',
    'delete_post' => 'delete_re'
  );
  register_post_type( 're',
    array (
      'labels' => array(
        'name' => __( 'Nhà đất', 'flatsome' ),
        'singular_name' => __( 'Nhà đất', 'flatsome' ),
        'menu_name' => __( 'Nhà đất', 'flatsome' ),
        'name_admin_bar' => __( 'Nhà đất', 'flatsome' ),
        'all_items'	=> __( 'Tất cả nhà đất', 'flatsome' ),
        'add_new' => __( 'Thêm nhà đất', 'flatsome' ),
        'add_new_item' => __( 'Thêm nhà đất', 'flatsome' ),
        'edit_item' => __( 'Sửa nhà đất', 'flatsome' ),
      ),
      'description' 		=> __( 'Nhà đất', 'flatsome' ),
      'menu_position' 	=> 5,
      'menu_icon' 		=> 'dashicons-building',
      'capability_type' 	=> array('re','res'),
      // 'map_meta_cap'    => true,
      'capabilities' 	=> $capabilities,
      'public' 			=> true,
      'has_archive' 		=> 'nha-dat',
      'supports' 			=> array( 'title', 'thumbnail', 'editor' ),
      'rewrite' 			=> array( 'slug' => 'nha-dat' ),
    )
  );

  register_taxonomy( 're_cat', array( 're' ),
    array(
      'labels'            	=> array(
        'name'              => __( 'Loại nhà đất', 'flatsome' ),
        'singular_name'     => __( 'Loại nhà đất', 'flatsome' ),
        'search_items'      => __( 'Tìm kiếm Loại nhà đất', 'flatsome' ),
        'all_items'         => __( 'Tất cả Loại nhà đất', 'flatsome' ),
        'parent_item'       => __( 'Loại nhà đất cha', 'flatsome' ),
        'parent_item_colon' => __( 'Loại nhà đất cha:', 'flatsome' ),
        'edit_item'         => __( 'Sửa Loại nhà đất', 'flatsome' ),
        'update_item'       => __( 'Cập nhật Loại nhà đất', 'flatsome' ),
        'add_new_item'      => __( 'Thêm Loại nhà đất', 'flatsome' ),
        'new_item_name'     => __( 'Tên Loại nhà đất', 'flatsome' ),
        'menu_name'         => __( 'Loại nhà đất', 'flatsome' ),
      ),
    'hierarchical'      => true,
    'public'            => true,
    )
  );

}
add_action( 'init', 'muatheme_com_init_re' );

/* Add re columns */
function willgroup_re_columns_head($cols) {
	$cols = array_merge(
		array_slice( $cols, 0, 1, true ),
		array( 'thumbnail' => __( 'Hình ảnh' , 'flatsome' ) ),
		array_slice( $cols, 1, 1, true ),
		array( 're_demand' => __( 'Nhu cầu' , 'flatsome' ) ),
		array( 're_cat' => __( 'Loại nhà đất' , 'flatsome' ) ),
		array( 're_province' => __( 'Tỉnh/thành' , 'flatsome' ) ),
		array( 're_area' => __( 'Diện tích' , 'flatsome' ) ),
		array( 're_price' => __( 'Giá' , 'flatsome' ) ),
		array_slice( $cols, 2, null, true )
	);
	return $cols;
}
add_filter('manage_re_posts_columns', 'willgroup_re_columns_head');

function willgroup_re_columns_content($col_name, $post_ID) {
  global $default_img;
	if ($col_name == 'thumbnail') {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );
		if ( $image ) {
			echo '<a href="' . get_edit_post_link( $post_ID ) . '"><img width="50px" class="lazy" src="'.  $image['0'] .'" data-src="' . $image['0'] . '"/></a>';
		} else {
      $re_thumbnail_default = get_field('re_thumbnail_default', 'customizer');
      $size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)
      $thumb = $re_thumbnail_default['sizes'][ $size ]; ?>
      <img width="50px" src="<?php echo esc_url($thumb); ?>" />
    <?php }
		echo '<style type="text/css">';
		echo '.column-thumbnail { width:60px; }';
		echo '</style>';
	}

	if ($col_name == 're_demand') {
		$value = get_the_terms( $post_ID, 're_cat' );
		if ( $value ) {
			echo $value[0]->name;
		}
	}

	if ($col_name == 're_cat') {
		$value = get_the_terms( $post_ID, 're_cat' );
		if ( $value ) {
			echo $value[1]->name;
		}
	}

	if ($col_name == 're_province') {
		$value = get_field( 're_province', $post_ID );
		$provinces = willgroup_get_assoc_array_of_provinces();
		if ( $value ) {
			echo $provinces[$value];
		}
	}

	if ($col_name == 're_area') {
		$value = get_field( 're_area', $post_ID );
		if ( $value ) {
			echo $value . 'm<sup>2</sup>';
		}
	}

	if ($col_name == 're_price') {
		$value = get_field( 're_price', $post_ID );
		if ( $value ) {
			echo get_the_price( $post_ID );
		}
	}

}
add_action('manage_re_posts_custom_column', 'willgroup_re_columns_content', 10, 2);

// function fnLoadBDSHomeNoneAjax($re_cat_id){
// $html = '';
// global $default_img;
// $lazyplaceholder = $default_img;
//
// $re_so_tin = get_field( 're_so_tin', 'customizer' );
// global $layout_homepage;
// $demand = get_term($re_cat_id,'re_cat');
//
// 	                $args = array(
// 							'post_type' 	 => 're',
// 							'posts_per_page' => 10,
// 						'post_status'   => 'publish',
// 							'tax_query'		 => array(
// 								array(
// 									'taxonomy' => 're_cat',
// 									'field'	   => 'term_id',
// 									'terms'    => array( $demand->term_id ),
// 								)
// 							),
// 							'meta_query'	 => array(
// 								array(
// 									'key'	 => 're_vip',
// 									'value'  => 1
// 								)
// 							)
// 						);
// 						$query = new WP_Query( $args );
// 						if( $query->have_posts() ) :
// 	                    $html .='<section class="module">
// 					<header class="module-header">
// 						<h2 class="module-title">'. $demand->name .' - Tin VIP</h2>
//
// 					</header>
// 					<div class="module-content">';
// 						$html .= '<div class="row tin_vip_row '. ($layout_homepage == '0'?'':'has_rightsidebar') .'">';
// 								while( $query->have_posts() ) : $query->the_post();
// 								 ob_start();
// 								 get_template_part( 'template-parts/content', 'archive-re' );
// 	                             $html .= ob_get_contents();
// 	                             ob_end_clean();
// 								 endwhile; wp_reset_postdata();
// 						$html .= '</div>';
// 						 $banner1 = get_field('banner_1', 're_cat_' . $demand->term_id);
//                         if ( $banner1 ) :
//                         	$html .=  '<div class="text-center my-3"><img src="'. $lazyplaceholder  .'" class="lazyload" data-src="'. $banner1 .'"/></div>';
//                         endif;
// 	                $html .= '</div></section>';
// 						endif;
//
//
//
// 				$html .='<section class="module">
// 					<header class="module-header">
// 						<h2 class="module-title"><a title="'. esc_attr($demand->name) .'" href="'. get_term_link( $demand->term_id ) .'">'. $demand->name .'</a></h2>
// 						<a class="module-more"  title="'. esc_attr($demand->name) .'"  href="'.  get_term_link( $demand->term_id ).'">Xem thêm</a>
// 					</header>
// 					<div class="module-content">';
//
//
//
//
// 						//Danh sach bất động sản
//
//
// 						$args = array(
// 							'post_type' 	 => 're',
// 							'posts_per_page' => $re_so_tin,
// 							'post_status'   => 'publish',
// 							'tax_query'		 => array(
// 								array(
// 									'taxonomy' => 're_cat',
// 									'field'	   => 'term_id',
// 									'terms'    => array( $demand->term_id ),
// 								)
// 							)
// 						);
// 						$query = new WP_Query( $args );
// 						if( $query->have_posts() ) :
// 	                         $html .='<div class="row">';
// 							while( $query->have_posts() ) : $query->the_post();
// 	                             ob_start();
// 	                              if($layout_homepage == '0') {
// 								  get_template_part( 'template-parts/content', 'archive-re' );
// 								  }
// 	                              else{
// 								   get_template_part( 'template-parts/content', 'archive-rerow' );
// 								  }
// 	                             $html .= ob_get_contents();
// 	                             ob_end_clean();
// 							endwhile;
// 	 	                     $html .='</div>';
// 	                        wp_reset_postdata();
//
// 						endif;
//
//                         $banner2 = get_field('banner_2', 're_cat_' . $demand->term_id);
//                         if ( $banner2 ) :
//                         	$html .= '<div class="text-center my-3"><img  src="'. $lazyplaceholder  .'" class="lazyload" data-src="'. $banner2 .'"/></div>';
//                         endif;
// 					$html .= '</div>';
// 				$html .= '</section>';
//  	echo $html;
// }
