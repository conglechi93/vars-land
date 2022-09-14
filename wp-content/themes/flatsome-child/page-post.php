<?php
/**
 * Template Name: Page - User Post
 *
 * @package willgroup
 */

if( ! is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}

$cho_phep_dang_tin = get_field('cho_phep_dang_tin','customizer');
$current_user = wp_get_current_user();

if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
	$id = $_GET['id'];

	$args = array(
		'post_type'      => 're',
		'posts_per_page' => 1,
		'p'				 => $id,
		'author'		 => $current_user->ID
	);

	$query = new WP_Query($args);
	if ( ! $query->have_posts() ) {
		wp_redirect( home_url() );
		exit();
	}

	$_POST['title'] = get_the_title( $id );
	$_POST['demand'] = get_field( 're_demand', $id );
	$_POST['category'] = get_field( 're_cat', $id );
	$_POST['province'] = get_field( 're_province', $id );
	$_POST['district'] = get_field( 're_district', $id );
	$_POST['ward'] = get_field( 're_ward', $id );
	$_POST['address'] = get_field( 're_address', $id );
	$_POST['area'] = get_field( 're_area', $id );
	$_POST['price'] = get_field( 're_price', $id );
	$_POST['unit_price'] = get_field( 're_unit_price', $id );
	$_POST['building_orientation'] = get_field( 're_bo', $id );
	$_POST['front'] = get_field( 're_front', $id );
	$_POST['row'] = get_field( 're_row', $id );
	$_POST['floor'] = get_field( 're_floor', $id );
	$_POST['bedroom'] = get_field( 're_bedroom', $id );
	$_POST['toilet'] = get_field( 're_toilet', $id );
	$_POST['video'] = get_field( 're_video', $id, false );
	$_POST['desc'] = apply_filters( 'the_content', get_post_field( 'post_content', $id ) );
	$_POST['re_gia_thoa_thuan'] = (get_field( 're_gia_thoa_thuan', $id )?1:0);

	if( get_field( 're_gallery', $id ) ) {
		foreach( get_field( 're_gallery', $id ) as $value ) {
			$gallery[] = $value;
		}
	}

	// echo '<pre>';
	// print_r(get_field( 're_gallery', $id ));
	// echo '</pre>';
}

if( isset( $_POST['action'] ) && $_POST['action'] == 'post' ) {
	$title = $_POST['title'];
	$demand = $_POST['demand'];
	$category = $_POST['category'];
	$province = $_POST['province'];
	$district = $_POST['district'];
	$ward = $_POST['ward'];
	$address = $_POST['address'];
	$area = $_POST['area'];
	$price = $_POST['price'];
	$unit_price = $_POST['unit_price'];
	$building_orientation = $_POST['building_orientation'];
	$front = $_POST['front'];
	$row = $_POST['row'];
	$floor = $_POST['floor'];
	$bedroom = $_POST['bedroom'];
	$toilet = $_POST['toilet'];
	$video = $_POST['video'];
	$desc = $_POST['desc'];
  $re_gia_thoa_thuan = ($_POST['re_gia_thoa_thuan']?1:0);

	if( isset( $_POST['gallery'] ) ) {
		$gallery = $_POST['gallery'];
	}

	if( isset( $_POST['image_360'] ) ) {
		$image_360 = $_POST['image_360'];
	}

	if ( $title == '' ) {
		$error = __( 'Bạn chưa nhập tiêu đề.', 'flatsome' ) . '<br>';
	}

	if ( $demand == 0 ) {
		$error .= __( 'Bạn chưa chọn nhu cầu.', 'flatsome' ) . '<br>';
	}

	if ( $category == 0 ) {
		$error .= __( 'Bạn chưa chọn loại nhà đất.', 'flatsome' ) . '<br>';
	}

	if ( $province == 0 ) {
		$error .= __( 'Bạn chưa chọn tỉnh/thành.', 'flatsome' ) . '<br>';
	}

	if ( $district == 0 ) {
		$error .= __( 'Bạn chưa chọn quận/huyện.', 'flatsome' ) . '<br>';
	}

	if ( $address == '' ) {
		$error .= __( 'Bạn chưa nhập địa chỉ.', 'flatsome' ) . '<br>';
	}

	if ( $area == '' ) {
		$error .= __( 'Bạn chưa nhập diện tích.', 'flatsome' ) . '<br>';
	}

	if ( $price == '' ) {
		if($re_gia_thoa_thuan == 1) {
      $price = '0';
		} else {
	    $error .= __( 'Bạn chưa nhập giá.', 'flatsome' ) . '<br>';
		}
	}

	if ( $price != '' && ! is_numeric ( $price ) ) {
		$error .= __( 'Giá chỉ bao gồm những số.', 'flatsome' ) . '<br>';
	}

	if ( $unit_price == 0 ) {
		$error .= __( 'Bạn chưa chọn đơn giá.', 'flatsome' ) . '<br>';
	}

	if ( $desc == '' ) {
		$error .= __( 'Bạn chưa nhập thông tin mô tả.', 'flatsome' ) . '<br>';
	}
    if( $_POST['action'] == 'post')
	{
	$posr_per_day = get_field('posr_per_day','customizer');
	$user_post_perday = 0;
	if(get_the_author_meta('set_gioi_han_tin_dang',  $current_user->ID) == 1)
	{
		$user_post_perday = get_the_author_meta('so_tin_dang',  $current_user->ID);
		$posr_per_day = $user_post_perday;
	}	
    $count_posts = $wpdb->get_var(
        $wpdb->prepare("
            SELECT COUNT(*) 
            FROM $wpdb->posts 
            WHERE
			post_type = %s 
            AND post_author = %s
            AND post_date >= DATE_SUB(CURDATE(),INTERVAL %s DAY)",
            're',
            $current_user->ID,
            1
        )
    );
		if ( 0 < $count_posts ) 
        $count_posts = number_format( $count_posts );
		if (  $count_posts == $posr_per_day ) 
		{
        
           $error .= __( 'Bạn chỉ được phép đăng '. $posr_per_day . ' tin mỗi ngày.', 'trustweb' ) . '<br>';
        }
	}
	if( $error == '' ) {
		$args = array(
			'post_type'     => 're',
			'post_status'   => 'pending',
			'post_author'	=> $current_user->ID,
			'post_title'    => $title,
			'post_content'  => $desc,
			'meta_input'    => array(
				're_demand'     => $demand,
				're_cat'        => $category,
				're_province'   => $province,
				're_district'   => $district,
				're_ward'   	=> $ward,
				're_address'    => $address,
				're_area'       => $area,
				're_price'	    => $price,
				're_unit_price' => $unit_price,
				're_bo'			=> $building_orientation,
				're_front'		=> $front,
				're_row'		=> $row,
				're_floor'		=> $floor,
				're_bedroom'    => $bedroom,
				're_toilet'     => $toilet,
				're_video'		=> $video,
				're_gallery'    => $gallery,
				're_360'  		=> $image_360,
				're_views'		=> 0,
				're_gia_thoa_thuan'    => $re_gia_thoa_thuan,
			)
		);
       
		
        //<?php if( isset( $_GET['id'] ) ) 
        //Web demo thi không save dữ liệu
		if( isset( $_POST['id'] ) ) {

			$args['ID'] = $_POST['id'];

			$post_id = wp_update_post( $args );

		} else {

			$post_id = wp_insert_post( $args );

		}

		set_post_thumbnail( $post_id, $gallery[0] );

		wp_set_post_terms( $post_id, array( $demand, $category ), 're_cat' );

		wp_redirect( home_url() . '/nguoi-dung/danh-sach-tin-dang' );

		exit;
		
	}

}

get_header(); ?>

<?php if($cho_phep_dang_tin) { ?>
	<form class="form-post" method="POST" action="<?php echo home_url(); ?>/nguoi-dung/dang-tin/">
		<input type="hidden" name="action" value="post" />
		<div class="row row-small">
			<?php if( isset( $error ) && $error != '' ) : ?>
				<div class="col medium-12 small-12 large-12">
					<div class="alert alert-danger mh-no-margin-bottom"><?php echo $error; ?></div>
				</div>
			<?php endif; ?>

			<div class="col medium-12 small-12 large-6 form-group">
				<label><?php _e( 'Tiêu đề', 'flatsome' ); ?> <span class="required">*</span></label>
				<input class="form-control" type="text" name="title" value="<?php echo isset( $_POST['title'] ) ? $_POST['title'] : ''; ?>"/>
			</div>

			<div class="col medium-6 small-12 large-3 form-group">
				<label><?php _e( 'Nhu cầu ', 'flatsome' ); ?><span class="required">*</span></label>
				<select class="form-control custom-select" name="demand">
					<option value="0"><?php _e( 'Chọn nhu cầu', 'flatsome' ); ?></option>
					<?php
					$demands = get_terms( array( 'taxonomy' => 're_cat', 'hide_empty' => false, 'parent' => 0 ) );
					foreach ( $demands as $value ) : ?>
						<option value="<?php echo $value->term_id; ?>" <?php echo isset( $_POST['demand'] ) && $_POST['demand'] == $value->term_id ? 'selected' : ''; ?>>
							<?php echo $value->name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col medium-6 small-12 large-3 form-group">
				<label><?php _e( 'Loại nhà đất', 'flatsome' ); ?> <span class="required">*</span></label>
				<select class="form-control custom-select" name="category">
					<option value="0"><?php _e( 'Chọn loại nhà đất', 'flatsome' ); ?></option>
					<?php
					if( isset( $_POST['demand'] ) && ! empty( $_POST['demand'] ) ) :
						$cats = get_terms( array( 'taxonomy' => 're_cat', 'hide_empty' => false, 'parent' => $_POST['demand'] ) );
						foreach ( $cats as $value ) : ?>
							<option value="<?php echo $value->term_id; ?>" <?php echo isset( $_POST['category'] ) && $_POST['category'] == $value->term_id ? 'selected' : ''; ?>>
							<?php echo $value->name; ?>
							</option>
							<?php
						endforeach;
					endif; ?>
				</select>
			</div>

			<div class="col medium-4 small-12 large-2 form-group">
				<label><?php _e( 'Tỉnh/thành', 'flatsome' ); ?> <span class="required">*</span></label>
				<select class="form-control custom-select" name="province">
					<?php
					$provinces = willgroup_get_assoc_array_of_provinces();
					foreach ( $provinces as $key => $value ) : ?>
						<option value="<?php echo $key; ?>" <?php echo isset( $_POST['province'] ) && $_POST['province'] == $key ? 'selected' : ''; ?>>
							<?php echo $value; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col medium-4 small-12 large-2 form-group">
				<label><?php _e( 'Quận/huyện', 'flatsome' ); ?> <span class="required">*</span></label>
				<select class="form-control custom-select" name="district">
					<?php
					if( isset( $_POST['province'] ) && ! empty( $_POST['province'] ) ) :
						$districts = willgroup_get_assoc_array_of_districts($_POST['province']);
						foreach ( $districts as $key => $value ) : ?>
							<option value="<?php echo $key; ?>" <?php echo isset( $_POST['district'] ) && $_POST['district'] == $key ? 'selected' : ''; ?>>
							<?php echo $value; ?>
							</option>
							<?php
						endforeach;
					else : ?>
					<option value=""><?php _e('Chọn quận/huyện', 'flatsome'); ?></option>
					<?php endif; ?>
				</select>
			</div>

			<div class="col medium-4 small-12 large-2 form-group">
				<label><?php _e( 'Phường/xã', 'flatsome' ); ?> <span class="required">*</span></label>
				<select class="form-control custom-select" name="ward">
					<?php
					if( isset( $_POST['district'] ) && ! empty( $_POST['district'] ) ) :
						$wards = willgroup_get_assoc_array_of_wards($_POST['district']);
						foreach ( $wards as $key => $value ) : ?>
							<option value="<?php echo $key; ?>" <?php echo isset( $_POST['ward'] ) && $_POST['ward'] == $key ? 'selected' : ''; ?>>
							<?php echo $value; ?>
							</option>
							<?php
						endforeach;
					else : ?>
						<option value=""><?php _e('Chọn phường/xã', 'flatsome'); ?></option>
					<?php endif; ?>
				</select>
			</div>

			<div class="col medium-12 small-12 large-6 form-group">
				<label><?php _e( 'Địa chỉ', 'flatsome' ); ?> <span class="required">*</span></label>
				<input class="form-control" type="text" name="address" value="<?php echo isset( $_POST['address'] ) ? $_POST['address'] : ''; ?>"/>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Diện tích', 'flatsome' ); ?> <span class="required">*</span></label>
				<div class="input-group">
					<input class="form-control" type="text" id="re_area" name="area" value="<?php echo isset( $_POST['area'] ) ? $_POST['area'] : ''; ?>"/>
					<div class="input-group-prepend">
						<span class="input-group-text"><?php _e( 'm<sup>2</sup>', 'flatsome' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Đơn giá', 'flatsome' ); ?> <span class="required">*</span></label>
				<select class="form-control custom-select" id="unit_price" name="unit_price">
					<option value="0"><?php _e( 'Chọn đơn giá', 'flatsome' ); ?></option>
					<option value="1" <?php echo isset( $_POST['unit_price'] ) && $_POST['unit_price'] == 1 ? 'selected' : ''; ?>><?php _e( 'VNĐ', 'flatsome' ); ?></option>
					<option value="2" <?php echo isset( $_POST['unit_price'] ) && $_POST['unit_price'] == 2 ? 'selected' : ''; ?>><?php _e( 'VNĐ/tháng', 'flatsome' ); ?></option>
					<option value="3" <?php echo isset( $_POST['unit_price'] ) && $_POST['unit_price'] == 3 ? 'selected' : ''; ?>><?php _e( 'VNĐ/m2', 'flatsome' ); ?></option>
					<option value="4" <?php echo isset( $_POST['unit_price'] ) && $_POST['unit_price'] == 4 ? 'selected' : ''; ?>>Giá thỏa thuận</option>
				</select>
				<input type="hidden" id="re_gia_thoa_thuan" name="re_gia_thoa_thuan" value="<?php echo isset( $_POST['re_gia_thoa_thuan'] ) ? $_POST['re_gia_thoa_thuan'] : ''; ?>"/>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Giá', 'flatsome' ); ?> <span class="required">*</span></label>
				<input class="form-control" type="text" <?php if ( @$_POST['re_gia_thoa_thuan'] == 1 ) : echo 'disabled="disabled"'; endif; ?> id="price" name="price" value="<?php echo isset( $_POST['price'] ) ? $_POST['price'] : ''; ?>"/>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Hướng nhà', 'flatsome' ); ?></label>
				<select class="form-control custom-select" name="building_orientation">
					<option value="0"><?php _e( 'Chọn hướng nhà', 'flatsome' ); ?></option>
					<?php foreach( get_building_orientations() as $key => $value ) : ?>
						<option value="<?php echo $key; ?>" <?php echo isset( $_POST['building_orientation'] ) && $_POST['building_orientation'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Mặt tiền', 'flatsome' ); ?></label>
				<div class="input-group">
					<input class="form-control" type="text" id="re_front" name="front" value="<?php echo isset( $_POST['front'] ) ? $_POST['front'] : ''; ?>"/>
					<div class="input-group-prepend">
						<span class="input-group-text"><?php _e( 'm', 'flatsome' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Lộ giới', 'flatsome' ); ?></label>
				<div class="input-group">
					<input class="form-control" type="text"  id="re_row" name="row" value="<?php echo isset( $_POST['row'] ) ? $_POST['row'] : ''; ?>"/>
					<div class="input-group-prepend">
						<span class="input-group-text"><?php _e( 'm', 'flatsome' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Số tầng', 'flatsome' ); ?></label>
				<select class="form-control custom-select" name="floor">
					<option value="0"><?php _e( 'Số tầng', 'flatsome' ); ?></option>
					<?php
					$floors = array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
					foreach( $floors as $value ) : ?>
						<option value="<?php echo $value; ?>" <?php echo isset( $_POST['floor'] ) && $_POST['floor'] == $value ? 'selected' : ''; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Số phòng ngủ', 'flatsome' ); ?></label>
				<select class="form-control custom-select" name="bedroom">
					<option value="0"><?php _e( 'Số phòng ngủ', 'flatsome' ); ?></option>
					<?php
					foreach( $floors as $value ) : ?>
						<option value="<?php echo $value; ?>" <?php echo isset( $_POST['bedroom'] ) && $_POST['bedroom'] == $value ? 'selected' : ''; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col medium-3 small-12 large-2 form-group">
				<label><?php _e( 'Số toilet', 'flatsome' ); ?></label>
				<select class="form-control custom-select" name="toilet">
					<option value="0"><?php _e( 'Số toilet', 'flatsome' ); ?></option>
					<?php
					foreach( $floors as $value ) : ?>
						<option value="<?php echo $value; ?>" <?php echo isset( $_POST['toilet'] ) && $_POST['toilet'] == $value ? 'selected' : ''; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col medium-9 small-12 large-6 form-group">
				<label><?php _e( 'Link youtube', 'flatsome' ); ?></label>
				<input class="form-control" type="text" name="video" value="<?php echo isset( $_POST['video'] ) ? $_POST['video'] : ''; ?>"/>
			</div>

			<div class="col medium-12 small-12 large-6">
				<div class="form-group">
					<label><?php _e( 'Hình ảnh', 'flatsome' ); ?></label>
					<div class="bg-light p-4 form-upload">
						<div class="spinner">
					    <div class="bounce1"></div>
					    <div class="bounce2"></div>
					    <div class="bounce3"></div>
					  </div>
						<div class="row row-small images">
							<?php
							if ( ! empty( $gallery ) ) :
								foreach ( $gallery as $value ) : ?>
									<div class="col medium-3 small-4 large-2 image">
										<p class="mh-no-margin-bottom">
											<?php echo wp_get_attachment_image( $value, 'thumbnail' ); ?>
											<input type="hidden" name="gallery[]" value="<?php echo $value; ?>"/>
											<a class="close" href="javascript:void()" title="<?php _e( 'Xóa', 'flatsome' ); ?>" data-toggle="remove-image" data-attachment-id="<?php echo $value; ?>">
												<i class="far fa-times-circle"></i>
											</a>
										</p>
									</div>
								<?php endforeach;
							endif; ?>
						</div>
						<input class="sr-only" type="file" name="images[]" id="gallery" multiple/>
						<p class="text-center">
							<label class="btn btn-secondary mb-0" for="gallery">
								<i class="fal fa-upload"></i><span><?php _e( 'Upload...', 'flatsome' ); ?></span>
							</label>
						</p>
						<p class="text-center mb-0">
							<?php _e( 'Hãy upload hình để tin của bạn được nổi bật hơn, hấp dẫn hơn và nhiều lượt xem hơn.', 'flatsome' ); ?><br>
						</p>
					</div>
				</div>
			</div>

			<div class="col medium-12 small-12 large-6 form-group">
				<label><?php _e( 'Thông tin chi tiết', 'flatsome' ); ?> <span class="required">*</span></label>
				<?php
				$content = '';
				if ( isset( $_POST['desc'] ) ) {
					$content = $_POST['desc'];
				}
				$settings = array(
					'wpautop' => false,
					'media_buttons' => false,
					'teeny' => true,
					'quicktags' => false,
					'editor_height' => 250,
				);
				wp_editor( $content, 'desc', $settings ); ?>
			</div>

			<div class="col medium-12 small-12 large-12 form-group text-right">
				<button class="btn btn-primary" type="submit"><?php _e( 'Đăng tin', 'flatsome' ); ?></button>
			</div>
		</div>

		<?php if( isset( $_GET['id'] ) ) : ?>
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"/>
		<?php endif; ?>
	</form>
<?php } ?>

<?php
get_footer();
