<?php
/**
 * Template Name: Page - Change password
 *
 * @package willgroup
 */

if( ! is_user_logged_in() ) {
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
if( isset( $_COOKIE['old_password'] ) ) {
	$old_password = $_COOKIE['old_password'];
	unset( $_COOKIE['old_password'] );
	setcookie( 'old_password', null, -1, '/' );
}
if( isset( $_COOKIE['new_password'] ) ) {
	$new_password = $_COOKIE['new_password'];
	unset( $_COOKIE['new_password'] );
	setcookie( 'new_password', null, -1, '/' );
}
if( isset( $_COOKIE['confirm_new_password'] ) ) {
	$confirm_new_password = $_COOKIE['confirm_new_password'];
	unset( $_COOKIE['confirm_new_password'] );
	setcookie( 'confirm_new_password', null, -1, '/' );
}

if( isset( $_POST['action'] ) ) {
	$old_password = $_POST['old_password'];
	$new_password = $_POST['new_password'];
	$confirm_new_password = $_POST['confirm_new_password'];

	if ( $old_password == '' ) {
		$error = __( 'Bạn chưa nhập mật khẩu cũ.', 'trustweb' ) . '<br>';
	} else {
		if ( ! wp_check_password($old_password, $current_user->data->user_pass, $current_user->ID) ) {
			$error .= __( 'Mật khẩu cũ của bạn không đúng.', 'trustweb' ) . '<br>';
		}
	}
	if ( $new_password == '' ) {
		$error .= __( 'Bạn chưa nhập mật khẩu mới.', 'trustweb' ) . '<br>';
	}
	if ( $confirm_new_password == '' ) {
		$error .= __( 'Bạn chưa nhập xác nhận mật khẩu mới.', 'trustweb' ) . '<br>';
	} else {
		if ( $new_password != $confirm_new_password ) {
			$error .= __( 'Mật khẩu mới và xác nhận mật khẩu mới phải giống nhau.', 'trustweb' ) . '<br>';
		}
	}

	if( $error == '' ) {
		$userdata = array(
			'ID' 	       => $current_user->ID,
			'user_pass'    => $new_password,
		);
		$user_id = wp_update_user( $userdata );
		$success = __( 'Đổi mật khẩu thành công.', 'flatsome' ) . '<br>';
		setcookie( 'success', $success, time() + 3600, '/');
	} else {
		setcookie( 'error', $error, time() + 3600, '/');
		setcookie( 'old_password', $old_password, time() + 3600, '/');
		setcookie( 'new_password', $new_password, time() + 3600, '/');
		setcookie( 'confirm_new_password', $confirm_new_password, time() + 3600, '/');
	}
	wp_redirect( get_the_permalink() );
	exit;
}
get_header(); ?>

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
      <form class="form-account" method="POST" action="">
        <div class="form-group">
          <label><?php _e( 'Mật khẩu cũ', 'trustweb' ); ?> <span class="required">*</span></label>
          <input class="form-control" type="password" name="old_password" value="<?php echo isset( $old_password ) ? $old_password : ''; ?>"/>
        </div>
        <div class="form-group">
          <label><?php _e( 'Mật khẩu mới', 'trustweb' ); ?> <span class="required">*</span></label>
          <input class="form-control" type="password" name="new_password" value="<?php echo isset( $new_password ) ? $new_password : ''; ?>"/>
        </div>
        <div class="form-group">
          <label><?php _e( 'Xác nhận mật khẩu mới', 'trustweb' ); ?> <span class="required">*</span></label>
          <input class="form-control" type="password" name="confirm_new_password" value="<?php echo isset( $confirm_new_password ) ? $confirm_new_password : ''; ?>"/>
        </div>
        <div class="form-group text-right">
          <button class="btn btn-primary" type="submit"><?php _e( 'Đổi mật khẩu', 'trustweb' ); ?></button>
        </div>
        <input type="hidden" name="action" value="update"/>
      </form>
    </div>
  </div>
</div>

<?php
get_footer();
