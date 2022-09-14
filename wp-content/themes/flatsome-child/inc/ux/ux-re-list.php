<?php
// Flatsome Re
function ux_re_list($atts) {
	extract(shortcode_atts(array(
		'res' => '3',
		'cat' => '',
		'tinhthanh' => '',
		'ids' => '',
		'layout' => '',
		'vip' => 'false'
	), $atts));
	$class_layout = 'col medium-12 small-12 large-12';
	if($layout == 'grid')
	{
		$class_layout = 'col medium-6 small-12 large-3';
	}
	ob_start();

		$args = array(
			'post_type' 	 => 're',
			'posts_per_page' => $res,
		);

		if ( $ids ) {
			$args['post__in'] = explode( ',', $ids );
		}

		if ( $cat ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 're_cat',
					'field'	   => 'term_id',
					'terms'    => $cat,
				)
			);
		}
         if($tinhthanh)
		{
			$args['meta_query'] = array(
		    array(
			'key' => 're_province',
			'value' => $tinhthanh
		      ));
		}
	    if($vip == 'true')
		{
			
			$args['meta_query'] = array(
		array(
			'key' => 're_vip',
			'value' => '1'
		));
		}
		// if ( $columns == 1 ) {
		// 	$class = 'medium-6 small-12 large-12';
		// } elseif ( $columns == 2 ) {
		// 	$class = 'medium-6 small-12 large-6';
		// } elseif ( $columns == 3 ) {
		// 	$class = 'medium-6 small-12 large-4';
		// } elseif ( $columns == 4 ) {
		// 	$class = 'medium-6 small-12 large-3';
		// }

		// echo '<pre>';
		// print_r($args);
		// echo '</pre>';

		$query = new WP_Query( $args );
		if( $query->have_posts() ) : ?>
			<div class="row row-small">
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<div class="<?php echo $class_layout; ?>">
						<div class="col-inner">
							<?php
								if($layout == 'grid')
								{
									get_template_part( 'content', 're' );
	                            
                                }
								else
								{
									  get_template_part( 'content', 're-list' );
								}
							?>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php endif;


	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('ux_re_list', 'ux_re_list');
