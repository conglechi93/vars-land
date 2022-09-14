<?php
/**
 * The template for displaying all locations.
 *
 * @package willgroup
 */

global $wp_query, $cat_object, $location_title, $province_code, $district_code, $ward_code, $province_name, $district_name, $ward_name;
$cat_slug = $wp_query->query_vars['name'];
$cat_object = get_term_by('slug', $cat_slug, 're_cat');
$cat_title = '';
if(!$cat_object){
	$cat_title = 'Mua bán cho thuê nhà đất tại ';
} else {
	$cat_title = $cat_object->name;
}

$loc_slug = $wp_query->query_vars['tai'];
$loc_slugs = explode('/', $loc_slug);
$cat_title = '';
$lvel = 0;
if ( isset($loc_slugs[0]) || $loc_slugs[1] == 'page') {
	$lvel = 1;
	$provinces = willgroup_get_array_of_provinces();
	foreach ( $provinces as $value ) {
		if ( $value['slug'] == $loc_slugs[0] ) {
			$province_code = $value['code'];
			$province_name = $value['name'];
			if($cat_object !=null){
				$location_title = $cat_object->name . ' tại ' . $province_name;
			} else {
				$location_title = $cat_title . $province_name;
			}
			break;
		}
	}
}

if ( isset($loc_slugs[1]) && $loc_slugs[1] != 'page' ) {
	$lvel = 2;
	$districts = willgroup_get_array_of_districts($province_code);
	foreach ( $districts as $value ) {
		if ( $value['slug'] == $loc_slugs[1] ) {
			$district_code = $value['code'];
			$district_name = $value['name_with_type'];
			$cat_title = '';
			if($cat_object ==null){
				$cat_title = 'Mua bán cho thuê nhà đất tại ';
			} else {
				$cat_title = $cat_object->name;
			}
			$location_title = $cat_title . ' tại ' . $district_name . ' ' . $province_name;
			break;
		}
	}
}

if ( isset($loc_slugs[2]) && isset($district_code) && $loc_slugs[2] != 'page' ) {
	$lvel = 3;
	$wards = willgroup_get_array_of_wards($district_code);
	foreach ( $wards as $value ) {
		if ( $value['slug'] == $loc_slugs[2] ) {
			$ward_code = $value['code'];
			$ward_name = $value['name_with_type'];
				$cat_title = '';
			if($cat_object ==null){
				$cat_title = 'Mua bán cho thuê nhà đất tại ';
			} else {
				$cat_title = $cat_object->name;
			}
			$location_title = $cat_title . ' tại ' . $ward_name . ' ' . $district_name . ' ' . $province_name;
			break;
		}
	}
}

global $paged;
$paged = 1;
foreach ( $loc_slugs as $key => $value ) {
	if ( $value == 'page' ) {
		$paged = $loc_slugs[$key + 1];
		break;
	}
}

$args = array();
if($cat_slug!=null) {
	$args = array(
		'post_type' => 're',
		'paged'	    => $paged,
		'tax_query' => array(
			array(
				'taxonomy' => 're_cat',
				'field'	   => 'slug',
				'terms'	   => $cat_slug
			)
		),
		'meta_query'
	);
	} else {
		$args = array(
			'post_type' => 're',
			'paged'	    => $paged,
		);
	}

	if ( isset($province_code) ) {
		$args['meta_query'][] = array(
			'key'	=> 're_province',
			'value' => $province_code
		);
	}

	if ( isset($district_code) ) {
		$args['meta_query'][] = array(
			'key'	=> 're_district',
			'value' => $district_code
		);
	}

	if ( isset($ward_code) ) {
		$args['meta_query'][] = array(
			'key'	=> 're_ward',
			'value' => $ward_code
		);
	}

	$query = new WP_Query($args);
	get_header(); ?>

	<div class="page-wrapper page-right-sidebar">
		<div class="row">
			<div id="content" class="large-9 left col col-divided" role="main">
				<div class="page-inner">
					<div class="container section-title-container mh-title-label" style="margin-bottom:0px;">
						<h3 class="section-title section-title-normal">
							<b></b>
							<span class="section-title-main">
								<?php echo $location_title; ?>
							</span>
							<b></b>
						</h3>
					</div>
					<div class="row row-small">
						<?php
							if ($query->have_posts()) :
								while ($query->have_posts()) : $query->the_post(); ?>
									<div class="col medium-6 small-12 large-4">
										<div class="col-inner">
											<?php get_template_part( 'content', 're' ); ?>
										</div>
									</div>
								<?php endwhile; wp_reset_postdata();
								willgroup_pagination( $query->max_num_pages );
							else : ?>
								<?php get_template_part( 'content', 'none' ); ?>
							<?php endif; ?>

							<?php if ( $location_desc = get_field('location_desc', 'customizer') ) :
								if ( ! empty($ward_name) ) {
									$location = $ward_name;
								} elseif ( ! empty($district_name) ) {
									$location = $district_name;
								} elseif ( ! empty($province_name) ) {
									$location = $province_name;
								}

								$location_desc = str_replace('[location]', $location, $location_desc);

								if ( ! empty($cat_object) ) {
									$cat = $cat_object->name;
									if ( $cat_object->parent ) {
										$cat = get_term($cat_object->parent)->name . ' ' . $cat;
									}
								}

								try {
									if (strpos($location_desc, '[cat]')=== true) {
										$location_desc = str_replace('[cat]', $cat, $location_desc);
									} else {
										$location_desc = str_replace('[cat]', 'Mua bán cho thuê nhà đất', $location_desc);
									}
								} catch(Exception $e) {

								}

								$website = get_home_url();
								$website = str_replace('http://', ' ', $website);
								$website = str_replace('https://', ' ', $website);
								$location_desc = str_replace('[website]', $website, $location_desc); ?>

								<div class="col medium-12 small-12 large-12">
									<div class="col-inner">
										<div class="mh-location-desc">
											<?php echo $location_desc; ?>
										</div>
									</div>
								</div>

							<?php endif; ?>

					</div>
				</div>
			</div>
			<div class="large-3 col widget-area">
				<aside class="widget widget_nav_menu">
					<span class="widget-title ">
						<span>
							<?php
								$wdtitle = '';
								switch ($lvel) {
									case 1:
										$wdtitle = __( 'Quận / huyện', 'flatsome' );
									break;
										case 2:
									$wdtitle = __( 'Phường / xã', 'flatsome' );
									break;
										default:
										$wdtitle = __( 'Quận / huyện', 'flatsome' );
									break;
								}
								echo $wdtitle;;
							?>
						</span>
					</span>
					<div class="menu-sidebar-blog-container">
						<ul class="menu">
							<?php
								switch ($lvel) {
									case 1:
										$districts = willgroup_get_array_of_districts($province_code);
										foreach ( $districts as $value ) {
											echo '<li><a  title="'. esc_attr($location_title) . ', '. $value['name_with_type'] .'" href="/tai/'. $loc_slugs[0] . '/' . $value['slug'] .'/"><i class="fas fa-chevron-right"></i> '. $value['name_with_type'] .'</a></li>';
										}
										break;
									case 2:
										$wards = willgroup_get_array_of_wards($district_code);
										foreach ( $wards as $valuea ) {
											echo '<li><a title="'. esc_attr($location_title) . ', '. $valuea['name_with_type'] .'" href="/tai/'. $loc_slugs[0] . '/' . $loc_slugs[1] . '/'. $valuea['slug'] .'/"><i class="fas fa-chevron-right"></i> '. $valuea['name_with_type'] .'</a></li>';
										}
										break;
									default:
										$wards = willgroup_get_array_of_wards($district_code);
										foreach ( $wards as $valuea ) {
											echo '<li><a title="'. esc_attr($location_title) . ', '. $valuea['name_with_type'] .'" href="/tai/'. $loc_slugs[0] . '/' . $loc_slugs[1] . '/'. $valuea['slug'] .'/"><i class="fas fa-chevron-right"></i> '. $valuea['name_with_type'] .'</a></li>';
										}
									break;
								}
							?>
						</ul>
					</div>
				</aside>

			</div>
		</div>
	</div>
<?php get_footer();
