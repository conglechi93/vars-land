<?php
function mh_widget_advanced_search() {
  ob_start();
  $provinces = willgroup_get_array_of_provinces();
  $count_province = count($provinces);
  ?>
    <div class="mh-aside-advanced-search">
      <span class="widget-title ">
        <span>
          <i class="fas fa-search mr-2"></i>
          <?php _e( 'Tìm nhiều hơn', 'flatsome' ); ?>
        </span>
      </span>

      <div class="advanced-search-content">
        <form class="form-advanced-search form-aside-advanced-search" action="<?php echo home_url(); ?>/ket-qua-tim-kiem" method="GET">
          <div class="row row-small">
            <div class="col medium-12 small-12 large-12 form-group demand-radios">
              <?php
              $demands = get_terms( array( 'taxonomy' => 're_cat', 'hide_empty' => false, 'parent' => 0 ) );
              foreach( $demands as $key => $value ) : ?>
                <label class="demand-radio">
                  <input name="demand" type="radio" value="<?php echo $value->term_id; ?>" <?php echo $key == 0 || ( isset( $_GET['demand'] ) && $_GET['demand'] == $value->term_id ) ? 'checked' : ''; ?>>
                  <span class="name"><?php echo $value->name; ?></span>
                </label>
              <?php endforeach; ?>
              <input class="form-control" type="hidden" name="s" placeholder="<?php _e( 'Từ khóa', 'flatsome' ); ?>"/>
            </div>
            <div class="col medium-12 small-12 large-12 form-group">
              <div class="col-inner">
                  <input class="form-control" type="text" name="key_word" placeholder="Từ khóa">
                </div>
            </div>
            <div class="col medium-12 small-12 large-12 form-group">
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
            
            <div class="col medium-12 small-12 large-12 form-group">
              <select class="form-control custom-select" name="province" <?php echo ($count_province==1?"disabled='disabled'": "")?> >
                <?php
                if($count_province >1) {
                  echo '<option value="">Chọn Tỉnh/ Thành phố</option>';
                }
                foreach ( $provinces as $province) : ?>
                  <option value="<?php echo $province['code']; ?>">
                    <?php echo $province['name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col medium-12 small-12 large-12 form-group">
              <select class="form-control custom-select" name="district">
                <?php
                if( (isset( $_GET['province'] ) && ! empty( $_GET['province'] )) || $count_province == 1 ) :
                $province_code = ((isset( $_GET['province'] ) && ! empty( $_GET['province'] ))?$_GET['province'] : $provinces[0]['code']);
                $districts = willgroup_get_assoc_array_of_districts($province_code);
                foreach ( $districts as $key => $value ) : ?>
                  <option value="<?php echo $key; ?>">
                  <?php echo $value; ?>
                  </option>
                <?php endforeach;
                else : ?>
                  <option value=""><?php _e('Chọn quận/huyện', 'flatsome'); ?></option>
                <?php endif; ?>
              </select>
            </div>

            <div class="col medium-12 small-12 large-12 form-group">
              <select class="form-control custom-select" name="ward">
                <?php
                if( isset( $_GET['district'] ) && ! empty( $_GET['district'] ) ) :
                $wards = willgroup_get_assoc_array_of_wards($_GET['district']);
                foreach ( $wards as $key => $value ) : ?>
                  <option value="<?php echo $key; ?>">
                    <?php echo $value; ?>
                  </option>
                <?php endforeach;
                else : ?>
                  <option value=""><?php _e('Chọn phường/xã', 'flatsome'); ?></option>
              <?php endif; ?>
              </select>
            </div>

            <div class="col medium-12 small-12 large-12 form-group">
              <select class="form-control custom-select" name="building_orientation">
                <option value="0"><?php _e( 'Chọn hướng nhà', 'flatsome' ); ?></option>
                <?php foreach( get_building_orientations() as $key => $value ) : ?>
                  <option value="<?php echo $key; ?>" <?php echo isset( $_GET['building_orientation'] ) && $_GET['building_orientation'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col medium-6 small-6 large-6 form-group">
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

            <div class="col medium-6 small-6 large-6 form-group">
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

            <div class="col medium-12 small-12 large-12 form-group mh-no-padding-bottom">
              <button class="btn btn-primary btn-block" type="submit"><?php _e( 'Tìm kiếm', 'flatsome' ); ?>!</button>
            </div>

          </div>
        </form>
      </div>
    </div>

  <?php $content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('mh_widget_advanced_search', 'mh_widget_advanced_search');
