<?php
function mh_login_form() {
	ob_start();

	if( ! is_user_logged_in() ) { ?>
		<li class="html header-button-1">
			<div class="header-button">
				<a href="#mh-login" class="mh-login-button button primary">
			    <span><?php _e('Đăng tin', 'flatsome'); ?></span>
			  </a>
			</div>
		</li>
		<div id="mh-login" class="lightbox-by-id lightbox-content mfp-hide lightbox-white " style="max-width: 800px; padding:20px 20px 0;">
			<div class="row row-small modal-login">
				<div class="col medium-6 small-12 large-6">
					<form class="form-login" action="" method="POST">
						<h5 class="form-title"><?php _e( 'Đăng nhập', 'flatsome' ); ?></h5>
						<div class="form-group">
							<input class="form-control" type="text" name="email" placeholder="<?php _e('Email', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" name="password" placeholder="<?php _e('Mật khẩu', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-block" type="submit"><?php _e('Đăng nhập', 'flatsome'); ?></button>
						</div>
						<input type="hidden" name="action" value="willgroup_login" />
					</form>
	        <div class="forgot-password text-center">
						<button class="button primary is-link"><?php _e('Quên mật khẩu?', 'flatsome'); ?></button>
					</div>
					<div id="collapse-forgot-password" class="collapse forgot-password-form">
						<form class="form-forgot-password mt-3" method="POST" action="">
							<div class="form-group">
								<input class="form-control" type="text" name="email" placeholder="<?php _e('Email', 'flatsome'); ?>"/>
							</div>
							<div class="form-group">
								<button class="button primary is-outline" type="submit"><?php _e('Lấy mật khẩu mới', 'flatsome'); ?></button>
							</div>
							<input type="hidden" name="action" value="willgroup_forgot_password" />
						</form>
					</div>
				</div>

				<div class="col medium-6 small-12 large-6">
					<form class="form-register" action="" method="POST">
						<h5 class="form-title"><?php _e( 'Đăng ký', 'flatsome' ); ?></h5>
						<div class="form-group">
							<input class="form-control" type="text" name="name" placeholder="<?php _e('Họ và tên', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="text" name="email" placeholder="<?php _e('Email', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="text" name="phone" placeholder="<?php _e('Số điện thoại', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" name="password" placeholder="<?php _e('Mật khẩu', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" name="confirm_password" placeholder="<?php _e('Nhập lại mật khẩu', 'flatsome'); ?>" />
						</div>
						<div class="form-group text-right">
							<button class="btn btn-primary btn-block" type="submit"><?php _e('Đăng ký', 'flatsome'); ?></button>
						</div>
						<input type="hidden" name="action" value="willgroup_register"/>
					</form>
				</div>

			</div>
		</div>

	<?php } else {
		$current_user = wp_get_current_user(); 
        $user_full = 'quý khách' ;
		if(!empty( $current_user->display_name) &&  $current_user->display_name !=''){
			
			$user_full = $current_user->display_name;
		}
        
       ?>
		<li class="has-dropdown mh-user-list">
			<a href="<?php echo home_url(); ?>/nguoi-dung/tai-khoan" class="nav-top-link">
				<?php 
		                if(get_the_author_meta('avatar',   $current_user->ID) )
						  {
							   echo '<img class="avatar avatar-36 photo" height="36" width="36" src="'.  wp_get_attachment_image_src(get_the_author_meta('avatar',  $current_user->ID), 'thumbnail')[0] .'" />';
						  }
				          else{
							  echo '<img class="avatar avatar-36 photo" height="36" width="36" src="https://secure.gravatar.com/avatar/b2cab300bdb7aaba1c086fc78091ae75?s=96&d=mm&r=g"/ >';
						  }
		
		 ?>
			</a>
			<ul class="sub-menu nav-dropdown nav-dropdown-simple">
				<li class="menu-item"><a href="<?php echo home_url(); ?>/nguoi-dung/tai-khoan"><?php echo 'Xin chào '. $user_full; ?></a></li>
				<li class="menu-item"><a href="<?php echo home_url(); ?>/nguoi-dung/dang-tin"><?php _e( 'Đăng tin', 'flatsome' ); ?></a></li>
				<li class="menu-item"><a href="<?php echo home_url(); ?>/nguoi-dung/danh-sach-tin-dang"><?php _e( 'Danh sách tin đăng', 'flatsome' ); ?></a></li>
				<li class="menu-item"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Đăng xuất', 'flatsome' ); ?></a></li>
			</ul>
		</li>
	<?php }

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('mh_login_form', 'mh_login_form');
function mh_login_form_mb() {
	ob_start();

	if( ! is_user_logged_in() ) { ?>
		<li class="html header-button-1 header-button-1-mobile">
			<div class="header-button">
				<a href="#mh-login" class="button primary">
			    <span><?php _e('Đăng tin miễn phí', 'flatsome'); ?></span>
			  </a>
			</div>
		</li>
		<div id="mh-login" class="lightbox-by-id lightbox-content mfp-hide lightbox-white " style="max-width: 800px; padding:20px 20px 0;">
			<div class="row row-small modal-login">
				<div class="col medium-6 small-12 large-6">
					<form class="form-login" action="" method="POST">
						<h5 class="form-title"><?php _e( 'Đăng nhập', 'flatsome' ); ?></h5>
						<div class="form-group">
							<input class="form-control" type="text" name="email" placeholder="<?php _e('Email', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" name="password" placeholder="<?php _e('Mật khẩu', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-block" type="submit"><?php _e('Đăng nhập', 'flatsome'); ?></button>
						</div>
						<input type="hidden" name="action" value="willgroup_login" />
					</form>
	        <div class="forgot-password text-center">
						<button class="button primary is-link"><?php _e('Quên mật khẩu?', 'flatsome'); ?></button>
					</div>
					<div id="collapse-forgot-password" class="collapse forgot-password-form">
						<form class="form-forgot-password mt-3" method="POST" action="">
							<div class="form-group">
								<input class="form-control" type="text" name="email" placeholder="<?php _e('Email', 'flatsome'); ?>"/>
							</div>
							<div class="form-group">
								<button class="button primary is-outline" type="submit"><?php _e('Lấy mật khẩu mới', 'flatsome'); ?></button>
							</div>
							<input type="hidden" name="action" value="willgroup_forgot_password" />
						</form>
					</div>
				</div>

				<div class="col medium-6 small-12 large-6">
					<form class="form-register" action="" method="POST">
						<h5 class="form-title"><?php _e( 'Đăng ký', 'flatsome' ); ?></h5>
						<div class="form-group">
							<input class="form-control" type="text" name="name" placeholder="<?php _e('Họ và tên', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="text" name="email" placeholder="<?php _e('Email', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="text" name="phone" placeholder="<?php _e('Số điện thoại', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" name="password" placeholder="<?php _e('Mật khẩu', 'flatsome'); ?>" />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" name="confirm_password" placeholder="<?php _e('Nhập lại mật khẩu', 'flatsome'); ?>" />
						</div>
						<div class="form-group text-right">
							<button class="btn btn-primary btn-block" type="submit"><?php _e('Đăng ký', 'flatsome'); ?></button>
						</div>
						<input type="hidden" name="action" value="willgroup_register"/>
					</form>
				</div>

			</div>
		</div>

	<?php } else {
		$current_user = wp_get_current_user(); ?>
		<li class="menu-item-has-children has-child mh-user-list">
			<a href="<?php echo home_url(); ?>/nguoi-dung/tai-khoan" class="nav-top-link">
				<i class="icon-user"></i> <?php echo 'Tài khoản' ?>
			</a>
			
			<ul class="sub-menu nav-sidebar-ul children" aria-hidden="true">
				
				<li class="menu-item"><a  tabindex="-1" href="<?php echo home_url(); ?>/nguoi-dung/tai-khoan">Quản lý tài khoản</a></li>
				<li class="menu-item"><a  tabindex="-1" href="<?php echo home_url(); ?>/nguoi-dung/dang-tin"><?php _e( 'Đăng tin', 'flatsome' ); ?></a></li>
				<li class="menu-item"><a  tabindex="-1" href="<?php echo home_url(); ?>/nguoi-dung/danh-sach-tin-dang"><?php _e( 'Danh sách tin đăng', 'flatsome' ); ?></a></li>
				<li class="menu-item"><a  tabindex="-1" href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Đăng xuất', 'flatsome' ); ?></a></li>
			</ul>
		</li>
	<?php }

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('mh_login_form_mb', 'mh_login_form_mb');
