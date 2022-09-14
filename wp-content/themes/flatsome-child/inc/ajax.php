<?php
function fnUpdateProvince(){
	try{
	global $wpdb;

  $provinces = json_decode(stripslashes($_POST['provinces']),true);
	//$provinces = json_decode($_POST['provinces'],true);
	//$provinces = $data->data;

	foreach( $provinces as $province )
	{
	$code = $province['code'];
	$thutu = $province['thutu'];
    $show_col = $province['show_col'];
    $r = $wpdb->update(
	$wpdb->prefix .'provinces',
	array(
		'thutu' => $thutu,	// integer
		'show_col' => $show_col	// string - 'true' or 'false'
	),
	array( 'code' => $code ),
	array(
		'%d',	// value1
		'%s'	// value2
	),
	array( '%s' )
);
	}

	echo 'OK' ;
		} catch (Exception $e) {

		echo  $e->getMessage();
	}
	die();
}
add_action( 'wp_ajax_nopriv_UpdateProvince', 'fnUpdateProvince' );
add_action( 'wp_ajax_UpdateProvince', 'fnUpdateProvince' );

/**
 * Get districts
 */
function willgroup_get_districts() {
	$province = $_GET['province'];
	$districts = willgroup_get_assoc_array_of_districts($province);
	foreach ( $districts as $key => $value ) { ?>
		<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
	<?php
	}
	die();
}
add_action( 'wp_ajax_nopriv_willgroup_get_districts', 'willgroup_get_districts' );
add_action( 'wp_ajax_willgroup_get_districts', 'willgroup_get_districts' );

/**
 * Get wards
 */
function willgroup_get_wards() {
	$district = $_GET['district'];
	$wards = willgroup_get_assoc_array_of_wards($district);
	foreach ( $wards as $key => $value ) { ?>
		<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
	<?php
	}
	die();
}
add_action( 'wp_ajax_nopriv_willgroup_get_wards', 'willgroup_get_wards' );
add_action( 'wp_ajax_willgroup_get_wards', 'willgroup_get_wards' );

/**
 * Get recat
 */
function willgroup_get_recat() {
	$demand = $_GET['demand']; ?>
	<option value="0"><?php _e( 'Chọn loại nhà đất', 'flatsome' ); ?></option>
	<?php
	if( $demand ) {
		$cats = get_terms( array( 'taxonomy' => 're_cat', 'hide_empty' => false, 'parent' => $demand ) );
		foreach ( $cats as $value ) : ?>
			<option value="<?php echo $value->term_id; ?>"><?php echo $value->name; ?></option>
		<?php
		endforeach;
	}
	die();
}
add_action( 'wp_ajax_nopriv_willgroup_get_recat', 'willgroup_get_recat' );
add_action( 'wp_ajax_willgroup_get_recat', 'willgroup_get_recat' );

/**
 * Login
 */
function willgroup_login() {
	header('Content-Type: application/json');
	$user = get_user_by('email', $_POST['email']);
	$creds = array();
    $creds['user_login'] = $user->user_login;
    $creds['user_password'] = $_POST['password'];
    $creds['remember'] = true;
    $user = wp_signon( $creds, false );
	if( is_wp_error($user) ) {
		echo json_encode(array('status' => false, 'message' => __('Thông tin đăng nhập không đúng. Vui lòng nhập email và password đúng.', 'flatsome')));
	} else {
		echo json_encode(array('status' => true, 'message' => __('Đăng nhập thành công.', 'flatsome')));
	}
	die();
}
add_action( 'wp_ajax_nopriv_willgroup_login', 'willgroup_login' );
add_action( 'wp_ajax_willgroup_login', 'willgroup_login' );

/**
 * Register
 */
function willgroup_register() {
	$name = $_POST['name'];
	$email = strtolower($_POST['email']);
	$phone = $_POST['phone'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	header('Content-Type: application/json');
	if ( $name == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Bạn chưa nhập họ và tên.', 'flatsome' ) ) );
		die();
	}
	if ( $email == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Bạn chưa nhập email.', 'flatsome' ) ) );
		die();
	}
	if ( ! is_email( $email ) ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Email của bạn chưa đúng.', 'flatsome' ) ) );
		die();
	}
	if ( email_exists( $email ) ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Email này đã được sử dụng.', 'flatsome' ) ) );
		die();
	}
	if ( $phone == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Bạn chưa nhập số điện thoại.', 'flatsome' ) ) );
		die();
	}
	if ( ! is_numeric ( $phone ) ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Số điện thoại chỉ bao gồm những số.', 'flatsome' ) ) );
		die();
	}
	if ( strlen( $phone ) < 10 ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Số điện thoại phải có ít nhất 10 số.', 'flatsome' ) ) );
		die();
	}
	if ( $password == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Bạn chưa nhập mật khẩu.', 'flatsome' ) ) );
		die();
	}
	if ( strlen($password) < 6 ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Mật khẩu phải có ít nhất 6 kí tự.', 'flatsome' ) ) );
		die();
	}
	if ( $confirm_password == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Bạn chưa nhập lại mật khẩu.', 'flatsome' ) ) );
		die();
	}
	if ( $password != $confirm_password ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Nhập lại mật khẩu và mật khẩu phải giống nhau.', 'flatsome' ) ) );
		die();
	}

	$userdata = array(
		'user_login' => $name,
		'user_email' => $email,
		'user_pass'	 => $password,
		'role' => 'contributor'
	);
	$user_id = wp_insert_user( $userdata );
	update_user_meta( $user_id, 'user_phone', $phone );

	echo json_encode( array( 'status' => true, 'message'=> __( 'Đăng ký tài khoản thành công. Bạn có thể đăng nhập để bắt đầu đăng bài.', 'flatsome' ) ) );
	die();
}
add_action( 'wp_ajax_nopriv_willgroup_register', 'willgroup_register' );
add_action( 'wp_ajax_willgroup_register', 'willgroup_register' );

/**
 * Forgot password
 */
function willgroup_forgot_password() {
	$email = $_POST['email'];

	header('Content-Type: application/json');
	if ( $email == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Bạn chưa nhập email.', 'flatsome' ) ) );
		die();
	}
	if ( ! is_email( $_POST['email'] ) ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Email của bạn không đúng.', 'flatsome' ) ) );
		die();
	}
	if ( ! email_exists( $email ) ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Email này không tồn tại.', 'flatsome' ) ) );
		die();
	}

	$user = get_user_by('email', $email);
	$password = wp_generate_password(8);
	$userdata = array(
		'ID'    		=> $user->ID,
		'user_pass' 	=> $password
	);
	wp_update_user( $userdata );

	// Send email for user
	$body = __('Password mới của bạn', 'flatsome') . ': ' . $password;
	$subject = '[' . get_bloginfo('name') .'] ' . __('Password của bạn đã được thay đổi', 'flatsome');
	$headers = array(
		'From:' . get_option('admin_email'),
		'Reply-To: ' . get_option('admin_email'),
		'Content-Type: text/html; charset=UTF-8'
	);
	wp_mail( $email, $subject, $body, $headers );

	echo json_encode(array('status' => true, 'message'=> __('Password của bạn đã được thay đổi thành công. Vui lòng kiểm tra email.', 'flatsome')));
	die();
}
add_action( 'wp_ajax_nopriv_willgroup_forgot_password', 'willgroup_forgot_password' );
add_action( 'wp_ajax_willgroup_forgot_password', 'willgroup_forgot_password' );

/**
 * Upload images
 */
function willgroup_upload_images() {
	if ( $_FILES ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		$files = $_FILES['images'];
		foreach ( $files['name'] as $key => $value ) {
            if ( $files['name'][$key] ) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
                $_FILES = array('files' => $file);
                foreach ($_FILES as $file => $array) {
                    if ( $_FILES[$file]['error'] !== UPLOAD_ERR_OK ) return false;
					$attachment_id = media_handle_upload( $file, 0 );
					$result .= '<div class="col medium-3 small-4 large-2 image"><p class="mh-no-margin-bottom">';
					$result .= wp_get_attachment_image( $attachment_id, 'thumbnail' );
					$result .= '<input type="hidden" name="' . $_POST['name'] . '[]" value="' . $attachment_id . '"/>';
					$result .= '<a class="close" href="javascript:void()" title="' . __( 'Xóa', 'flatsome' ) . '" data-toggle="remove-image" data-attachment-id="' . $attachment_id . '"><i class="far fa-times-circle"></i></a>';
					$result .= '</p></div>';
                }
            }
        }
    }
	die( $result );
}
add_action( 'wp_ajax_nopriv_willgroup_upload_images', 'willgroup_upload_images' );
add_action( 'wp_ajax_willgroup_upload_images', 'willgroup_upload_images' );

/**
 * Remove image
 */
function willgroup_remove_image() {
	$attachment_id = $_POST['attachment_id'];
	wp_delete_attachment( $attachment_id );
	die();
}
add_action( 'wp_ajax_nopriv_willgroup_remove_image', 'willgroup_remove_image' );
add_action( 'wp_ajax_willgroup_remove_image', 'willgroup_remove_image' );
/* Delete Re */
function fn_delete_re_ajax() {
	header('Content-Type: application/json');
	if( isset( $_GET['id'] )  ) {
	$id = $_GET['id'];
	$args = array(
		'post_type'      => 're',
		'posts_per_page' => 1,
		'p'				 => $id,
		'author'		 => $current_user->ID
	);
		
	$query = new WP_Query($args);
	//Nếu ID không tồn tại	
	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Không tồn tại tin đăng này', 'flatsome' ) ) );	
	}
    else{
		
	    wp_delete_post($id);
		echo json_encode( array( 'status' => true, 'message'=> __( 'Đã xóa tin thành công.', 'flatsome' ) ) );
	}
	
	}
	else{
	
	echo json_encode( array( 'status' => false, 'message'=> __( 'Có sai sót trong thao tác xóa tin đăng', 'flatsome' ) ) );	
	}
	die();
}
add_action( 'wp_ajax_nopriv_delete_re', 'fn_delete_re_ajax' );
add_action( 'wp_ajax_delete_re', 'fn_delete_re_ajax' );