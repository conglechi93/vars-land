<?php
function mh_shortcode_advanced_search($atts){
  extract(shortcode_atts(array(

  ), $atts));
  ob_start();
  echo ' <div id="home_slider_div" class="position-relative">';
  $div_class = '';
  $html_slider = '';
  $slider_options = get_field('re_banner_home','customizer');
  $hien_thi_bo_dem = get_field('hien_thi_bo_dem','customizer');
  $dang_tin = get_field('cho_phep_dang_tin','customizer');
  if($slider_options == 1)
  {
  	$div_class = 'position-absolute';
	 $slides = get_field( 'slide', 'customizer' ); 
	
            if ( $slides ) :
    			$html_slider .='<div class="site-slider">';
    				 foreach ( $slides as $value ) : 
    				 $html_slider .='<div class="bg-cover mh-3 lazyload" data-bg="'. $value['image']['url'] .'"  data-lazy="'. $value['image']['url'] .'">';
                     $html_slider .='<div class="container d-none d-md-flex">
                            <div class="div_in_home_slider text-white bg-dark-50 p-3 mt-5">
            					<h2 class="text-uppercase">'. $value['name_title'] .'</h2>';
                                $html_slider .='<div class="h5">'. $value['desc'] .'</div>';
							    $html_slider .='<a title="'. esc_attr($value['name_title']) .'" class="btn btn-primary" href="'. esc_attr($value['url']) .'">Chi tiết</a>';
								
                                 $html_slider .='</div>
							
                        </div>
    				</div>';
    				endforeach; 
    			$html_slider .='</div>';
	       
            endif; 
	        echo $html_slider;
  }
	
  else{
	  	$slider_banner =  get_field('re_slide_banner','customizer');
	    echo '<div class="lazyload advanced-search" data-bg="'. $slider_banner  .'" data-lazy="'.$slider_banner .'">';
  }
  $provinces = willgroup_get_array_of_provinces();
  $count_province = count($provinces); ?>
<div class="<?php echo $div_class; ?>" style="width: 100%; z-index: 10; left: 0; bottom: 0;">
		<div class="container">
	<form class="bg-dark-50 pt-3 px-3 mh-no-margin-bottom" action="<?php echo home_url(); ?>/ket-qua-tim-kiem" method="GET">
		<div class="row row-small align-bottom">
			<div class="col medium-12 small-12 large-6">
        <div class="col-inner mh-search-type">
          <?php
          $demands = get_terms( array( 'taxonomy' => 're_cat', 'hide_empty' => false, 'parent' => 0, 'orderby' => 'name', 'order' => 'DESC' ) );
          foreach( $demands as $key => $value ) : ?>
            <label class="demand-radio">
              <input name="demand" type="radio" value="<?php echo $value->term_id; ?>" <?php echo $key == 0 || ( isset( $_GET['demand'] ) && $_GET['demand'] == $value->term_id ) ? 'checked' : ''; ?>>
              <span class="name"><?php echo $value->name; ?></span>
            </label>
          <?php endforeach; ?>
        </div>
			</div>

      <div class="col medium-12 small-12 large-6 hide-for-medium">
        <div class="col-inner text-right">
          <p class="text-white text-right_count mh-no-margin-bottom notice">
            <?php
	          if($hien_thi_bo_dem == true) {
              $count_res = wp_count_posts('re');
              $count_users = count_users();
              echo __( 'Hiện có ', 'flatsome' ) . ($dang_tin ==1?'<strong class="h3 font-weight-bold mb-0">'. $count_users['total_users'] .'</strong> thành viên,':'') .'<strong class="slider_count_number h3 font-weight-bold mb-0">' . $count_res->publish . '</strong> ' . __( 'tin đăng', 'flatsome' );
			  }
            ?>
          </p>
        </div>
      </div>

      <div class="col medium-12 small-12 large-10 pb-0">
        <div class="col-inner">
          <input class="form-control" type="text" name="key_word" placeholder="<?php _e( 'Từ khóa', 'flatsome' ); ?>"/>
        </div>
      </div>

      <div class="col medium-12 small-12 large-2 hide-for-medium pb-0">
        <div class="col-inner">
          <button class="btn btn-primary btn-block" type="submit"><?php _e( 'Tìm kiếm', 'flatsome' ); ?>!</button>
        </div>
      </div>

    </div>

    <div class="row row-small large-columns-7 medium-columns-3 small-columns-2 pb-0">
      <div class="col form-group">
				<select class="form-control custom-select" name="category">
					<option value="0"><?php _e( 'Chọn loại nhà đất', 'flatsome' ); ?></option>
					<?php
					if ( isset( $_GET['demand'] ) ) {
						$demand = $_GET['demand'];
					} else {
						$demand = $demands[0]->term_id;
					}
					$cats = get_terms( array( 'taxonomy' => 're_cat', 'hide_empty' => false, 'parent' => $demand ) );
					foreach ( $cats as $key => $value ) : ?>
						<option value="<?php echo $value->term_id; ?>" <?php echo isset( $_GET['category'] ) && $_GET['category'] == $value->term_id ? 'selected' : ''; ?>><?php echo $value->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

      <div class="col form-group">
        <div class="col-inner">
          <select class="form-control custom-select" name="province" <?php echo ($count_province==1?"disabled='disabled'": "")?> >
            <?php if($count_province >1) {
              echo '<option value="">'.__( 'Chọn Tỉnh/ Thành phố', 'flatsome' ).'</option>';
            }
            foreach ( $provinces as $province) : ?>
              <option value="<?php echo $province['code']; ?>" <?php echo isset( $_GET['province'] ) && $_GET['province'] ==  $province['code'] ? 'selected' :  ($count_province==1?'selected':''); ?>>
                <?php echo $province['name']; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col form-group">
        <div class="col-inner">
          <select class="form-control custom-select" name="district">
            <?php if( (isset( $_GET['province'] ) && ! empty( $_GET['province'] )) || $count_province == 1 ) :
              $province_code = ((isset( $_GET['province'] ) && ! empty( $_GET['province'] ))?$_GET['province'] : $provinces[0]['code']);
              $districts = willgroup_get_assoc_array_of_districts($province_code);
              foreach ( $districts as $key => $value ) : ?>
                <option value="<?php echo $key; ?>" <?php echo  $province_code == $key ? 'selected' : ''; ?>>
                  <?php echo $value; ?>
                </option>
              <?php
              endforeach;
                else : ?>
                <option value=""><?php _e('Chọn quận/huyện', 'flatsome'); ?></option>
              <?php endif; ?>
          </select>
        </div>
      </div>

      <div class="col form-group">
        <div class="col-inner">
          <select class="form-control custom-select" name="ward">
            <?php if( isset( $_GET['district'] ) && ! empty( $_GET['district'] ) ) :
              $wards = willgroup_get_assoc_array_of_wards($_GET['district']);
              foreach ( $wards as $key => $value ) : ?>
                <option value="<?php echo $key; ?>" <?php echo isset( $_GET['ward'] ) && $_GET['ward'] == $key ? 'selected' : ''; ?>>
                  <?php echo $value; ?>
                </option>
                <?php
              endforeach;
                else : ?>
              <option value=""><?php _e('Chọn phường/xã', 'flatsome'); ?></option>
            <?php endif; ?>
          </select>
        </div>
      </div>

      <div class="col form-group">
        <div class="col-inner">
          <select class="form-control custom-select" name="building_orientation">
            <option value="0"><?php _e( 'Chọn hướng nhà', 'flatsome' ); ?></option>
            <?php foreach( get_building_orientations() as $key => $value ) : ?>
              <option value="<?php echo $key; ?>" <?php echo isset( $_GET['building_orientation'] ) && $_GET['building_orientation'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col form-group">
        <div class="col-inner">
         <select class="form-control custom-select" name="re_area">
									<option value=""><?php _e( 'Diện tích', 'trustweb' ); ?></option>
									<?php if( have_rows('khoang_dien_tich','customizer') ): 
						  while( have_rows('khoang_dien_tich','customizer') ): the_row(); 
						     $dientich_val =  get_sub_field('dien_tich');
						     
						     $dientich_text =  get_sub_field('van_ban');
						?> 
						<option value="<?php echo $dientich_val; ?>" <?php echo isset( $_GET['re_area'] ) && $_GET['re_area'] == $dientich_val ? 'selected' : ''; ?>><?php echo $dientich_text; ?></option>
						<?php endwhile;
						endif;
						?>
								</select>
        </div>
      </div>

      <div class="col form-group">
        <div class="col-inner">
          <select class="form-control custom-select" name="max_price">
						<option value=""><?php _e( 'Khoảng giá', 'trustweb' ); ?></option>
						
						<?php if( have_rows('khoang_gia','customizer') ): 
						  while( have_rows('khoang_gia','customizer') ): the_row(); 
						     $khoang_gia_val =  get_sub_field('gia');
						     
						     $khoang_gia_text =  get_sub_field('van_ban');
						?> 
						<option value="<?php echo $khoang_gia_val; ?>" <?php echo isset( $_GET['max_price'] ) && $_GET['max_price'] == $khoang_gia_val ? 'selected' : ''; ?>><?php echo $khoang_gia_text; ?></option>
						<?php endwhile;
						endif;
						?>
					</select>
        </div>
      </div>

      <div class="col show-for-medium">
        <div class="col-inner">
          <button class="btn btn-primary btn-block" type="submit"><?php _e( 'Tìm kiếm!', 'flatsome' ); ?></button>
        </div>
      </div>

    </div>
	</form>
	</div>
</div>
  <?php 
	if($slider_options == 1)
	{}
	else{
		echo '</div>';
	}
	echo '</div>';
	return ob_get_clean();
}
add_shortcode('mh_advanced_search', 'mh_shortcode_advanced_search');
