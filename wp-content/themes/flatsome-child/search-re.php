<?php
/*
 * Template name: Search re
 */
get_header();
global $default_img;
global $wp_query;

?>

<?php do_action( 'flatsome_before_page' ); ?>

<div class="page-wrapper page-right-sidebar">
	<div class="row">
		<div id="content" class="large-9 left col" role="main">
			<div class="page-inner">
				<?php
				// if ( isset( $_GET['demand'] ) && isset( $_GET['key_word'] ) && isset( $_GET['category'] ) && isset( $_GET['province'] ) && isset( $_GET['district'] ) && isset( $_GET['min_price'] ) && isset( $_GET['max_price'] ) && isset( $_GET['building_orientation'] ) ) {

					// Set tax_query
					$tax_query = array(
						'relation' => 'AND'
					);

					if( isset( $_GET['demand'] ) && ! empty( $_GET['demand'] ) ) {
						$tax_query[] = array(
							'taxonomy' => 're_cat',
							'field'    => 'term_id',
							'terms'    => array( (int)$_GET['demand'] )
						);
						$demand = get_term_by( 'id', (int)$_GET['demand'], 're_cat' );
					}

					if( isset( $_GET['category'] ) && ! empty( $_GET['category'] ) ) {
						$tax_query[] = array(
							'taxonomy' => 're_cat',
							'field'    => 'term_id',
							'terms'    => array( (int)$_GET['category'] )
						);
					}

					// Set meta_query
					$meta_query = array(
						'relation' => 'AND'
					);

					if( isset( $_GET['province'] ) && ! empty( $_GET['province'] ) ) {
						$meta_query[] = array(
							'key'   => 're_province',
							'value' => $_GET['province']
						);
					}

					if( isset( $_GET['district'] ) && ! empty( $_GET['district'] ) ) {
						$meta_query[] = array(
							'key'   => 're_district',
							'value' => $_GET['district']
						);
					}
				    
                    if( isset( $_GET['ward'] ) && ! empty( $_GET['ward'] ) ) {
			$meta_query[] = array(
				'key'   => 're_ward',
				'value' => $_GET['ward']
			);
		}
					if( isset( $_GET['min_price'] ) && ! empty( $_GET['min_price'] ) ) {
						$meta_query[] = array(
							'key'     => 're_price',
							'value'   => (int)$_GET['min_price'],
							'type'    => 'numeric',
							'compare' => '>='
						);
					}

					if( isset( $_GET['re_area'] ) && ! empty( $_GET['re_area'] ) ) {
			$min_area_val =  explode(',',$_GET['re_area'])[0];
			$max_area_val =  explode(',',$_GET['re_area'])[1];
			$meta_query[] = array(
				'key'     => 're_area',
				'value'   => (int)$min_area_val,
				'type'    => 'numeric',
				'compare' => '>='
			);
			$meta_query[] = array(
				'key'     => 're_area',
				'value'   => (int)$max_area_val,
				'type'    => 'numeric',
				'compare' => '<='
			);
		}
		
		if( isset( $_GET['max_price'] ) && ! empty( $_GET['max_price'] ) ) {
			$min_price_val =  explode(',',$_GET['max_price'])[0];
			$max_price_val =  explode(',',$_GET['max_price'])[1];
			$meta_query[] = array(
				'key'     => 're_price',
				'value'   => (int)$min_price_val,
				'type'    => 'numeric',
				'compare' => '>='
			);
			$meta_query[] = array(
				'key'     => 're_price',
				'value'   => (int)$max_price_val,
				'type'    => 'numeric',
				'compare' => '<='
			);
		}

					if( isset( $_GET['building_orientation'] ) && ! empty( $_GET['building_orientation'] ) ) {
						$meta_query[] = array(
							'key'   => 're_bo',
							'value' => (int)$_GET['building_orientation']
						);
					}

					$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$args = array(
						'post_type' => 're',
						'order' => 'DESC',
						'post_status' => 'publish',
						'posts_per_page' => get_option( 'posts_per_page' ),
						'paged' => $page,
						/* Fix 404: 'paged' => get_query_var('paged') */
						'tax_query' => $tax_query,
						'meta_query' => $meta_query,
					);

					if( isset( $_GET['key_word'] ) && ! empty( $_GET['key_word'] ) ) {
						$args['s'] = $_GET['key_word'];
					}

					// echo '<pre>';
					// print_r($args);
					// echo '</pre>';

					$mh_filter = new WP_Query( $args ); ?>
					<div class="container section-title-container mh-title-label" style="margin-bottom:0px;">
						<h3 class="section-title section-title-normal">
							<b></b>
							<span class="section-title-main">
								<?php if( isset( $_GET['demand'] ) ) { ?>
									<a href="<?php echo get_term_link( $demand ); ?>"><?php echo $demand->name; ?></a>
									<?php } else { ?>
										<?php _e( 'Tìm kiếm', 'flatsome' ); ?>
								<?php } ?>
								</span>
							<b></b>
						</h3>
					</div>
					<p class="module-more">
						<?php echo __( 'Tìm thấy ', 'flatsome' ) . '<strong>' . $mh_filter->found_posts . '</strong>' . __( ' tin đăng', 'flatsome' ); ?>
					</p>
					<?php if( $mh_filter->have_posts() ) : ?>
					<div class="row row-small">
						<?php while( $mh_filter->have_posts() ) : $mh_filter->the_post(); ?>
							<div class="col medium-6 small-12 large-4">
								<div class="col-inner">
									<?php get_template_part( 'content', 're' ); ?>
								</div>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
					<?php
					/* Pagination */
					echo '<div class="mh-nav-pagination">';
						echo paginate_links( array(
							'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
							'total'        => $mh_filter->max_num_pages,
							'current'      => max( 1, get_query_var( 'paged' ) ),
							'format'       => '?paged=%#%',
							'show_all'     => false,
							'type'         => 'plain',
							'end_size'     => 2,
							'mid_size'     => 1,
							'prev_next'    => true,
							'prev_text'    => '<i class="far fa-chevron-left"></i>',
							'next_text'    => '<i class="far fa-chevron-right"></i>',
							'add_args'     => false,
							'add_fragment' => '',
						) );
					echo '</div>';

					else : ?>
						<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>

				<?php
					// } else {
					// 	echo '<p>'.__( 'Hãy sử dụng Form Tìm kiếm ...', 'flatsome' ).'</p>';
					// }
				?>

		  </div>
		</div>

		<div class="large-3 col widget-area">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>
