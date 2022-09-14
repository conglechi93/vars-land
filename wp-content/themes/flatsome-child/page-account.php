<?php
/**
 * Template Name: Page - Account
 *
 * @package willgroup
 */


get_header(); 
if( ! is_user_logged_in() ) 
{
	wp_redirect( home_url() );
	exit;
}

$current_user = wp_get_current_user();




if( isset( $_COOKIE['error'] ) ) {
	$error = $_COOKIE['error'];
	unset( $_COOKIE['error'] );
	setcookie( 'error', null, -1, '/' );
}
if( isset( $_COOKIE['success'] ) ) {
	$success = $_COOKIE['success'];
	unset( $_COOKIE['success'] );
	setcookie( 'success', null, -1, '/' );
}

if( isset( $_POST['action'] ) ) {
	$display_name = $_POST['display_name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
    $hinh_anh = $_POST['upload'];
	if ( $display_name == '' ) {
		$error = __( 'Bạn chưa nhập họ tên.', 'trustweb' ) . '<br>';
	}
	if ( $email == '' ) {
		$error .= __( 'Bạn chưa nhập email.', 'trustweb' ) . '<br>';
	}
	if ( ! is_email( $email ) ) {
		$error .= __( 'Email của bạn không đúng.', 'trustweb' ) . '<br>';
	}
	if ( email_exists( $email ) && $email != $current_user->user_email ) {
		$error .= __( 'Email đã tồn tại.', 'trustweb' ) . '<br>';
	}
	$regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
	if ( $phone == '' ) {
		$error .= __( 'Bạn chưa nhập số điện thoại.', 'trustweb' ) . '<br>';
	}
	if ( ! is_numeric ( $phone ) ) {
		$error .= __( 'Số điện thoại chỉ bao gồm những số.', 'trustweb' ) . '<br>';
	}
	if ( strlen( $phone ) < 10 ) {
		$error .= __( 'Số điện thoại phải có nhiều hơn 9 số.', 'trustweb' ) . '<br>';
	}
  
	if( $error == '' ) {
		
		$userdata = array(
			'ID' 	       => $current_user->ID,
			'display_name' => $_POST['display_name'],
			'user_email'   => $email,
		);
		
		$user_id = wp_update_user( $userdata );
		update_user_meta( $user_id, 'user_phone', $phone );
		update_user_meta( $user_id, 'display_name', $_POST['display_name'] );
		$profile_image_id = 0;
		
		if( !empty($_FILES['attachment']['size']) )
		{            require_once( ABSPATH . 'wp-load.php');
					if(!function_exists( 'media_handle_upload' ))
					{
						
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once( ABSPATH . 'wp-admin/includes/media.php' );
						$profile_image_id = media_handle_upload('attachment', 0);
					
					if( !empty($profile_image_id) && $profile_image_id != 0 )
					{
						update_user_meta($user_id, 'avatar', $profile_image_id);
					}
					}
					
					
					
		}
		$success = __( 'Cập nhật thành công.' , 'trustweb' ) . '<br>';
		setcookie( 'success', $success, time() + 3600, '/');
	} else {
		setcookie( 'error', $error, time() + 3600, '/');
	}
	wp_redirect( get_the_permalink() );
	exit;
}

?>
<div class="row row-small align-center">
  <div class="col medium-12 small-12 large-6">
    <div class="col-inner">
      <?php if( isset( $error ) && $error != '' ) : ?>
        <div class="alert alert-danger fade show">
          <!-- <button type="button" class="close" data-dismiss="alert">
            <span><i class="ion-android-close"></i></span>
          </button> -->
          <?php echo $error; ?>
        </div>
      <?php elseif( isset( $success ) && $success != '' ) : ?>
        <div class="alert alert-success fade show">
          <!-- <button type="button" class="close" data-dismiss="alert">
            <span><i class="ion-android-close"></i></span>
          </button> -->
          <?php echo $success; ?>
        </div>
      <?php endif; ?>

      <form id="form-account" class="form-account" method="POST"  enctype="multipart/form-data"  action="">
		
        <div class="form-group">
          <label><?php _e( 'Họ tên', 'trustweb' ); ?> <span class="required">*</span></label>
          <input class="form-control" type="text" name="display_name" value="<?php echo $current_user->display_name; ?>"/>
        </div>
        <div class="form-group">
          <label><?php _e( 'Email', 'trustweb' ); ?> <span class="required">*</span></label>
          <input class="form-control" type="text" name="email" value="<?php echo $current_user->user_email; ?>"/>
        </div>
        <div class="form-group">
          <label><?php _e( 'Số điện thoại', 'trustweb' ); ?> <span class="required">*</span></label>
          <input class="form-control" type="text" name="phone" value="<?php echo get_field( 'user_phone', 'user_' . $current_user->ID ); ?>"/>
        </div>
		<div class="form-group">
          <label>Ảnh đại diện</label>
			<?php 
			if(get_the_author_meta('avatar',   $current_user->ID) )
						  {
							   echo '<img style="width:90px;height:90px;display:block;" src="'.  wp_get_attachment_image_src(get_the_author_meta('avatar',  $current_user->ID), 'thumbnail')[0] .'" />';
						  }
			?>
           <input style="" class="gdlr-admin-author-image" id="gdlr-admin-author-image" type="file"  accept=".jpg,.png,.jpeg" name="attachment" />
        </div>  
		
        <div class="form-group text-right">
          <button class="btn btn-primary" type="submit"><?php _e( 'Cập nhật', 'trustweb' ); ?></button>
        </div>
        <input type="hidden" name="action" value="update"/>
      </form>
    </div>
  </div>
</div>
<script>
jQuery(document).ready(function( $ ) {

});
</script>
<?php
get_footer();
?>