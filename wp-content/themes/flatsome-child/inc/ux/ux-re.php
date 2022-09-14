<?php
function get_users_by_post_count( $post_type = 'post', $top ) {
    global $wpdb;

    $users = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT {$wpdb->users}.ID, p.post_count
            FROM {$wpdb->users}  
            LEFT JOIN (
                SELECT post_author, COUNT(*) AS post_count 
                FROM {$wpdb->posts} WHERE post_type = %s
                GROUP BY post_author
            ) p ON {$wpdb->users}.id = p.post_author 
            ORDER BY p.post_count DESC LIMIT ".$top,
            $post_type
        )
    );

    return $users;
}
function fnTopmoigioi($atts){
	extract(shortcode_atts(array(
		'soluong' => 1
		
	), $atts));
	$soluong = ($atts['soluong']?$atts['soluong']:1);
	ob_start();
	$users = get_users_by_post_count( 're' ,$soluong);

if ( ! empty( $users ) ) {
	echo '<div class="featured-partners__container">';
   foreach ( $users as $user ) {
       // there you have user ID and post_count
      // echo $user->ID . ' has ' . $user->post_count . ' posts';
       $userp = new WP_User( $user->ID  );
	   $author_url = get_author_posts_url($user->ID);
       $author_name = $userp->display_name;
	   $author_img = 'https://secure.gravatar.com/avatar/b2cab300bdb7aaba1c086fc78091ae75?s=96&d=mm&r=g';
	   $phone_author = get_the_author_meta('user_phone',  $user->ID);
	   if(get_the_author_meta('avatar',$user->ID) )
	   {
				$author_img = wp_get_attachment_image_src(get_the_author_meta('avatar',  $user->ID), 'thumbnail')[0];
	  }
				         
	   echo '<div class="partner">
                                    <div class="partner__image">
                                        <a title="Tất cả tin đăng bởi '. esc_attr($author_name)  .'" href="'. $author_url .'"><img src="'. $author_img.'" alt="'. esc_attr($author_name) .'"></a>
                                    </div>
                                    <div class="partner__info">
                                        <p class="partner__info__name"><a title="Tất cả tin đăng bởi '. esc_attr($author_name)  .'" href="'. $author_url .'">'. $author_name .'</a></p>
                                        <a href="tel:'. preg_replace('/\./', '',  $phone_author) .'" class="partner__info__phone" title="'. $phone_author .'">
                                        <span class="partner__info__phone__icon">
                                            <i class="fa fa-phone" aria-hidden="true"></i>&nbsp;</span>
                                            <span class="partner__info__phone__number">Gọi Ngay</span>
                                        </a>
                                    </div>
                                </div>';
    }
	echo '</div>';
}
	
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('ux_topmoigioi', 'fnTopmoigioi');
// Flatsome Products
function ux_re($atts) {
	extract(shortcode_atts(array(
		'columns' => '3',
		'res' => '3',
		'cat' => '',
		'tinhthanh' => '',
		'ids' => '',
		'vip' => 'false'
	), $atts));
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
		
	   $class = 'medium-6 small-12 large-4';
		

		// echo '<pre>';
		// print_r($args);
		// echo '</pre>';

		$query = new WP_Query( $args );
		if( $query->have_posts() ) : ?>
			<div class="row row-small slider slider-nav-circle slider-nav-large slider-nav-light slider-style-normal <?php echo ($query->post_count>3 ? 'mh-carousel mh-slider-arrow-top ': ''); ?>"  data-flickity-options='{
            "cellAlign": "center",
            "imagesLoaded": true,
            "lazyLoad": 4,
            "freeScroll": false,
            "wrapAround": true,
            "autoPlay": 6000,
            "pauseAutoPlayOnHover" : true,
            "prevNextButtons": true,
            "contain" : true,
            "adaptiveHeight" : true,
            "dragThreshold" : 10,
            "percentPosition": false,
            "pageDots": false,
            "rightToLeft": false,
            "draggable": false,
            "selectedAttraction": 0.1,
            "parallax" : 0,
            "friction": 1        }'>
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<div class="col <?php echo $class; ?>">
						<div class="col-inner">
							<?php
								get_template_part( 'content', 're' ); ?>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php endif;


	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('ux_re', 'ux_re');
