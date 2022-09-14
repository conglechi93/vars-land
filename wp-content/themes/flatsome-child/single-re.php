<?php
get_header(); ?>

<?php do_action( 'flatsome_before_page' ); ?>

<?php
  global $post;
  $post_id = $post->ID;
  $terms = get_the_terms($post_id, 're_cat');
  $re_demand = $terms[0];
  $re_cat = $terms[1];
  $re_province = get_field('re_province');
  $re_district = get_field('re_district');
  $re_ward = get_field('re_ward');
  $re_gallery = get_field('re_gallery');
  // $re_video = get_field('re_video');
  $re_area = get_field('re_area');
  $re_front = get_field('re_front');
  $re_row = get_field('re_row');
  $re_floor = get_field('re_floor');
  $re_toilet = get_field('re_toilet');
  $re_bedroom = get_field('re_bedroom');
  $re_bo_field = get_field_object('re_bo');
  $re_bo_value = get_field('re_bo');
  $re_bo_label = $re_bo_field['choices'][ $re_bo_value ];
  $re_price = get_field('re_price');
  $re_video_url ='';
  $re_video = get_field('re_video');
  if($re_video) {
  	// preg_match('/src="(.+?)"/', $re_video, $matches);
  	// $re_video_url = $matches[1];

    // echo '<pre>';
    // print_r($matches);
    // echo '</pre>';

    $re_video_url = getYouTubeVideoId($re_video);

  }
  $re_gia_thoa_thuan = (get_field('re_gia_thoa_thuan')?1:0);
  $provinces = willgroup_get_assoc_array_of_provinces();
  $loai_bando = get_field('loai_ban_do');
  $bando_text = get_field('bando_texy');
  $phone_author = get_the_author_meta('user_phone',  $post->post_author);
  $user = new WP_User( $post->post_author );
  $author_url = get_author_posts_url($user->ID);
  $author_name = $user->display_name;
?>

<div class="page-wrapper page-right-sidebar">
<div class="row">

<div id="content" class="large-9 left col" role="main">
	<div class="page-inner">
		<?php do_action('mh_flatsome_share'); ?>

    <div class="mh-wrapper-re">
      <!-- Title -->
      <h1 class="title-re"><?php the_title(); ?></h1>
      
      <!-- Tabs -->
      <div class="tabbed-content">
        <div class="tab-panels">
          <div class="panel entry-content lightbox active" id="tab_tab-1-title">
            <?php
              if( $re_gallery ): ?>
                <div class="mh-slider-re mh-main-slider row row-collapse">
                  <?php foreach( $re_gallery as $image ):;
					
                    ?>
                    <div class="col medium-12 small-12 large-12">
                      <div class="col-inner">
                        <a class="image-lightbox lightbox-gallery" href="<?php echo wp_get_attachment_image_src($image, 'full')[0]; ?>">
                          <?php echo wp_get_attachment_image($image, 'full'); ?>
                        </a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="mh-nav-re">
                  <div class="mh-nav-slider row row-small large-columns-8 medium-columns-7 small-columns-5">
                    <?php foreach( $re_gallery as $image ): ?>
                      <div class="col">
                        <div class="col-inner">
                          <?php echo wp_get_attachment_image($image, 'thumbnail'); ?>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif;
            ?>
          </div>
          <?php if($re_video_url) { ?>
          <div class="panel entry-content" id="tab_tab-2-title">
            <div class="row row-collapse">
              <div class="col medium-12 small-12 large-12">
                <div class="col-inner">
                  <div class="re_video_content"></div>
                </div>
              </div>
            </div>
          </div>

          <?php } if($loai_bando == 1) {
            $map = willgroup_get_lat_lng(get_the_address(),$post_id); ?>
            <div class="panel entry-content" id="tab_tab-3-title">
              <div data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>" class="nav-link-map-content embed-responsive embed-responsive-16by9"></div>
            </div>
          <?php } elseif($loai_bando == 2) {
            echo '<div class="panel entry-content" id="tab_tab-3-title">';
              echo $bando_text;
            echo '</div>';
          } ?>

        </div>
        <ul class="nav nav-size-normal nav-left mh-tabs-re">
          <li class="tab has-icon active">
            <a href="#tab_tab-1-title">
              <span><?php _e('Hình ảnh', 'flatsome'); ?></span>
            </a>
          </li>
          <?php if ( $re_video_url ) : ?>
            <li class="tab has-icon">
              <a class="nav-link-video" href="#tab_tab-2-title" data-video="<?php echo $re_video_url; ?>">
                <span><?php _e('Video', 'flatsome'); ?></span>
              </a>
            </li>
          <?php endif; ?>

          <?php if($loai_bando == 1) { ?>
            <li class="tab has-icon ">
              <a class="nav-link-map" href="#tab_tab-3-title" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>">
                <?php _e( 'Bản đồ', 'flatsome' ); ?>
              </a>
            </li>
          <?php } elseif($loai_bando == 2) {
            if($bando_text != '') { ?>
              <li class="tab has-icon">
                <a class="nav-link-map" href="#tab_tab-3-title">
                  <?php _e( 'Bản đồ', 'flatsome' ); ?>
                </a>
              </li>
            <?php }
          } ?>
        </ul>
      </div>

      <div class="mh-box-cat relative">
        <a class="" href="<?php echo get_term_link($re_cat) . willgroup_get_province_slug($re_province); ?>">
          <?php echo $re_cat->name . ' ' .__( 'tại', 'flatsome' ) . ' ' . $provinces[$re_province]; ?>
        </a>

        <?php if( $re_price ) : ?>
          <div class="box-price <?php echo ($re_gia_thoa_thuan==1?"gia_thoa_thuan":""); ?>">
            <?php
              if($re_gia_thoa_thuan==1) {
                _e( 'Giá: ', 'flatsome' );
                echo '<strong>';
                  _e( 'Thỏa thuận', 'flatsome' );
                echo '</strong>';
              } else {
                _e( 'Giá: ', 'flatsome' );
                echo get_the_price();
              }
            ?>
          </div>
        <?php endif; ?>

      </div>

      <!-- Content -->
      <div class="mh-box-content">
		   <div class="row">
			   <div class="col medium-12 small-12 large-12 row-user-profile">
			   <div class="user_avatar">
				   <a title="Xem tất cả tin đăng bởi <?php echo esc_attr($author_name); ?>" href="<?php echo $author_url; ?>">
				   <?php
				        
				          
                          if(get_the_author_meta('avatar',   $post->post_author) )
						  {
							   echo '<img src="'.  wp_get_attachment_image_src(get_the_author_meta('avatar',  $post->post_author), 'thumbnail')[0] .'" />';
						  }
				          else{
							  echo '<img src="https://secure.gravatar.com/avatar/b2cab300bdb7aaba1c086fc78091ae75?s=96&d=mm&r=g"/ >';
						  }
                         ?>
					 </a>
				   </div>
				   <div class="user_data">
					   <div class="UserName">
						  <?php echo '<a title="Xem tất cả tin đăng bởi '. esc_attr($author_name) .'" href="'. $author_url .'"><strong>'. $author_name .'</strong></a>'; ?>
					   </div>
					   <a class="btn btn-primary user-phone" href="tel:<?php echo preg_replace('/\./', '',  $phone_author); ?>"><i class="fas fa-phone-alt"></i>   <?php echo $phone_author; ?></a> <a class="btn btn-primary user-contact" href="#yeu-cau-tu-van">Yêu cầu tư vấn</a>                       </div>
			   </div>
		  </div>
        <!-- Info -->
        <div class="row row-collapse row-solid mh-table-re">
          <!-- re_area -->
          <?php if( $re_area ) : ?>
            <div class="col medium-6 small-12 large-3">
              <div class="col-inner">
                <i class="fas fa-ruler-combined fa-fw mr-2"></i>
                <?php echo __( 'Diện tích', 'flatsome' ) . ': ' . $re_area . 'm<sup>2</sup>'; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- re_bo -->
          <?php if( $re_bo_label ) : ?>
            <div class="col medium-6 small-12 large-3">
              <div class="col-inner">
                <i class="fas fa-compass fa-fw mr-2"></i>
                <?php echo __( 'Hướng', 'flatsome' ) . ': ' . $re_bo_label; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- re_front -->
          <?php if( $re_front ) : ?>
            <div class="col medium-6 small-12 large-3">
              <div class="col-inner">
                <i class="fas fa-arrows-alt-h fa-fw mr-2"></i>
                <?php echo __( 'Mặt tiền', 'flatsome' ) . ': ' . $re_front . 'm'; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- re_row -->
          <?php if( $re_row ) : ?>
            <div class="col medium-6 small-12 large-3">
              <div class="col-inner">
                <i class="fas fa-exchange-alt fa-fw mr-2"></i>
                <?php echo __( 'Lộ giới', 'flatsome' ) . ': ' . $re_row . 'm'; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- re_floor -->
          <?php if( $re_floor ) : ?>
            <div class="col medium-6 small-12 large-3">
              <div class="col-inner">
                <i class="fas fa-layer-group fa-fw mr-2"></i>
                <?php echo __( 'Số tầng', 'flatsome' ) . ': ' . $re_floor; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- re_bedroom -->
          <?php if( $re_bedroom ) : ?>
            <div class="col medium-6 small-12 large-3">
              <div class="col-inner">
                <i class="fas fa-bed fa-fw mr-2"></i>
                <?php echo __( 'Số phòng ngủ', 'flatsome' ) . ': ' . $re_bedroom; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- re_toilet -->
          <?php if( $re_toilet ) : ?>
            <div class="col medium-6 small-12 large-3">
              <div class="col-inner">
                <i class="fas fa-toilet fa-fw mr-2"></i>
                <?php echo __( 'Số toilet', 'flatsome' ) . ': ' . $re_toilet; ?>
              </div>
            </div>
          <?php endif; ?>

          <div class="col medium-6 small-12 large-3">
            <div class="col-inner">
              <i class="fas fa-clock fa-fw mr-2"></i>
              <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __( 'trước', 'flatsome' ); ?>
            </div>
          </div>
        </div>

        <!-- Address -->
        <?php if ( get_the_address() ) : ?>
          <div class="address-re">
            <i class="fas fa-map-marker-alt"></i><?php echo get_the_address(); ?>
          </div>
        <?php endif; ?>

        <?php while ( have_posts() ) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; // end of the loop. ?>
      </div>

      <!-- Share -->
      <?php do_action('mh_flatsome_share'); ?>

	</div>

  <!-- Related -->
  <?php
    $args = array(
      'post_type' 	 => 're',
      'posts_per_page' => 10,
      'tax_query' 	 => array(
        'relation'   => 'AND',
        array(
          'taxonomy' => 're_cat',
          'field'    => 'term_id',
          'terms'    => $re_demand->term_id,
        ),
        array(
          'taxonomy' => 're_cat',
          'field'    => 'term_id',
          'terms'    => $re_cat->term_id,
        )
      ),
      'meta_query'     => array(
        array(
          'key'    => 're_province',
          'value'  => $re_province
        )
      )
    );
    $query = new WP_Query( $args );
    if( $query->have_posts() ) : ?>
      <div class="mh-related-re">
        <div class="container section-title-container mh-title-label">
          <h3 class="section-title section-title-normal">
            <b></b>
            <span class="section-title-main">
             
                <?php echo $re_cat->name . ' ' .__( 'khác tại', 'trustweb' ) . ' ' . $provinces[$re_province]; ?>
              
            </span>
            <b></b>
          </h3>
        </div>

        <div class="module-content">
          <div class="row row-small">
          <?php
            while( $query->have_posts() ) : $query->the_post(); ?>
              <div class="col medium-6 small-12 large-4">
                <div class="col-inner">
                  <?php get_template_part( 'content', 're' ); ?>
                </div>
              </div>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

</div>

<div class="large-3 col">
	<?php get_sidebar(); ?>
</div>

</div>
</div>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>
