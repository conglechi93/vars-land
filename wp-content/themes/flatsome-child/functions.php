<?php
/**
 * Custom CSS
 */
//Ẩn admin bar đối với user thường (khách đăng tin)
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}
//Ẩn admin bar đối với user thường (khách đăng tin)
function mh_load_theme_style() {
	/* Add Font Awesome */
	wp_register_style( 'all-font-awesome', get_stylesheet_directory_uri() . '/assets/fontsawesome/css/all.min.css', false, false );
	wp_enqueue_style( 'all-font-awesome' );

	/* Custom JS */
	wp_register_script( 'js-custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', false, false, true );
	wp_enqueue_script( 'js-custom' );
	//wp_localize_script( 'js-custom', 'ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'mh_load_theme_style', 998 );

function mh_admin_style() {
	/* Custom CSS */
	wp_register_style( 'style-admin', get_stylesheet_directory_uri() . '/assets/css/style.admin.css', false, '1.0.0' );
	wp_enqueue_style( 'style-admin' );

}
add_action( 'admin_enqueue_scripts', 'mh_admin_style');
add_action( 'init', function () {
	if ( function_exists( 'add_ux_builder_post_type' ) ) {
		add_ux_builder_post_type( 're' );
	}
} );

/**
 * Include
 */
require_once(  __DIR__ . '/thuocloban/index.php' );
require_once(  __DIR__ . '/phongthuy/index.php' );
require_once(  __DIR__ . '/inc/real-estate.php' );
require_once(  __DIR__ . '/inc/functions.php' );
require_once(  __DIR__ . '/inc/acf-extension.php' );
require_once(  __DIR__ . '/inc/ajax.php' );
require_once(  __DIR__ . '/inc/pagination.php' );
require_once(  __DIR__ . '/inc/post.php' );
require_once(  __DIR__ . '/inc/advanced-search-widget.php' );
require_once(  __DIR__ . '/inc/ux-builder.php' );
require_once(  __DIR__ . '/inc/ux/advanced-search.php' );
require_once(  __DIR__ . '/inc/ux/ux-re.php' );
require_once(  __DIR__ . '/inc/ux/ux-re-list.php' );
require_once(  __DIR__ . '/inc/ux/login-form.php' );

/**
 * MH Function
 */
/* Đăng ký menu */
register_nav_menus( array(
	'user-nav' => esc_html__( 'User menu', 'flatsome' ),
) );

/* Bredcrumbs shortcode */
if ( !function_exists('mh_bredcrumbs') ) {
	function mh_bredcrumbs() {
		ob_start();
		echo '<div class="mh-breadcrumbs">';
			echo '<ul class="">';
        echo '<li><a href="';
        	echo get_option('home');
        	echo '">';
        	_e( 'Trang chủ', 'flatsome' );
        echo '</a></li>';
        if (is_category() || is_singular('post') || is_tax('re_cat') || is_singular('re') ) {
					if (is_category()) {
						echo '<li class="mh-child-crumbs">';
							the_category('<span>, </span>');
						echo '</li>';
					}
					if (is_tax('re_cat')) {
						echo '<li class="mh-child-crumbs">';
							echo '<span>';
								echo get_queried_object()->name;
							echo '</span>';
						echo '</li>';
					}
        	if (is_singular('post')) {
						echo '<li class="mh-child-crumbs">';
							the_category('<span>, </span>');
						echo '</li>';
						echo '<li class="mh-child-crumbs">';
            the_title();
            echo '</li>';
          }
					if (is_singular('re')) {
            echo '</li>';
						echo '<li class="mh-child-crumbs mh-child-re">';
						// echo '<pre>';
						// print_r(get_the_terms( get_the_ID(), 're_cat' ));
						// echo '</pre>';
						foreach (get_the_terms( get_the_ID(), 're_cat' ) as $value) {
							echo '<a href="'.get_term_link($value->term_id).'">'.$value->name.'</a>';
						}
            echo '</li>';
						echo '<li style="display: none;" class="mh-child-crumbs">';
							the_title();
            echo '</li>';
          }
        } elseif (is_page()) {
          echo '<li class="mh-child-crumbs">';
          echo the_title();
          echo '</li>';
        }
	      elseif (is_tag()) {
					single_tag_title();
				}
	      elseif ( is_day() ) {
						_e( '<li class="mh-child-crumbs">Archive for', 'flatsome' );
						the_time('F jS, Y');
						echo '</li>';
					}
	      elseif ( is_month() ) {
						_e( '<li class="mh-child-crumbs">Archive for', 'flatsome' );
						the_time('F, Y');
						echo'</li>';
					}
	      elseif ( is_year() ) {
						_e( '<li class="mh-child-crumbs">Archive for', 'flatsome' );
						the_time('Y');
						echo '</li>';
					}
	      elseif ( is_author() ) 
		  {        $qo = get_queried_object();
                        $author = $qo->data->display_name;
 						echo '<li class="mh-child-crumbs">Tất cả tin đăng bởi '. $author .'</li>';
					}
	      elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
					_e( '<li class="mh-child-crumbs">Blog Archives', 'flatsome' );
					echo '</li>';
				}
	      elseif (is_search()) {
					_e( '<li class="mh-child-crumbs">Search Results', 'flatsome' );
					echo '</li>';
				}
	      echo '</ul>';
			echo '</div>';
		return ob_get_clean();
	}
	add_shortcode( 'mh_shortcode_bredcrumbs', 'mh_bredcrumbs' );
}

/* Breadcrumbs */
if ( !function_exists('mh_breadcrumbs') ) {
	function mh_breadcrumbs() {
		if ( is_archive()||is_single()||is_page_template('page-right-sidebar.php')||is_page_template('page-left-sidebar.php') ) {
			// if ( !wp_is_mobile() ) {
				echo do_shortcode('[block id="block-breadcrumbs"]');
			// }
		}

		if( is_page_template( array( 'page-account.php', 'page-post.php', 'page-list-post.php', 'page-change-password.php' ) ) ) { ?>
			<div class="row row-small">
				<div class="col medium-12 small-12 large-12">
					<div class="col-inner">
						<?php if ( has_nav_menu( 'user-nav' ) ) {
							wp_nav_menu (
								array(
									'theme_location'  => 'user-nav',
									'container'		  => 'nav',
									'container_class' => 'user-nav',
									'menu_class' 	  => 'nav nav-center mh-nav-user',
								)
							);
						} ?>
					</div>
				</div>
			</div>
		<?php }

	}
	add_action( 'flatsome_after_header', 'mh_breadcrumbs' );
}

/* Blog title */
if ( !function_exists('mh_blog_title') ) {
	function mh_blog_title() {
		if ( is_archive() ) { ?>
			<div class="container section-title-container mh-title-label" style="margin-bottom:0px;">
				<h3 class="section-title section-title-normal">
					<b></b>
					<span class="section-title-main">
						<?php single_cat_title(); ?>
					</span>
					<b></b>
				</h3>
			</div>
		<?php }
	}
	add_action( 'mh_flatsome_title', 'mh_blog_title' );
}

/* Share blog */
if ( !function_exists('mh_blog_share') ) {
	function mh_blog_share() {
		if ( is_single() ) {
			echo '<div class="blog-share-box text-right">';
			 global $post;
         $postID = $post->ID;
			   echo getPostViews($postID, true, true);
				echo '<span>';
					_e( 'Chia sẻ tin này: ', 'flatsome' );
				echo '</span>';
				echo do_shortcode( '[share]' );
			echo '</div>';
		}
	}
	add_action( 'mh_flatsome_share', 'mh_blog_share' );
}

/* Related post */
if ( !function_exists('mh_related_post') ) {
	function mh_related_post() {
		if ( is_single() ) {
			$categories = get_the_category(get_the_ID());
			if ($categories) { ?>
				<?php
					$category_ids = array();
					foreach($categories as $individual_category) array_push($category_ids, $individual_category->term_id);
					$my_query = new wp_query(array(
						'category__in' => $category_ids,
						'post__not_in' => array(get_the_ID()),
						'posts_per_page' => 6
					));
					$ids = wp_list_pluck( $my_query->posts, 'ID' );
					$ids = implode(',', $ids);
					if( $my_query->have_posts() ) { ?>
						<div class="container section-title-container mh-title-label" style="margin-bottom:0px;">
							<h3 class="section-title section-title-normal">
								<b></b>
								<span class="section-title-main">
									<?php _e( 'Các tin liên quan', 'flatsome' ); ?>
								</span>
								<b></b>
							</h3>
						</div>
						<?php //echo do_shortcode('[blog_posts style="normal" columns="3" columns__md="2" ids="' . $ids . '" image_height="56.25%" text_align="left"]'); // Slider
						echo do_shortcode('[blog_posts style="normal" type="row" columns="4" columns__md="2" columns__sm="1" excerpt="false" image_height="56.25%" image_size="original" text_align="left" ids="' . $ids . '" show_date="hidden" comments="false" class="mh-grid-blog"]'); // Row
					}
				?>
			<?php }

			$tags = get_the_tags();
			if ( !empty($tags) ) :
				echo '<div class="blog-tags-box">';
					echo '<h5 class="uppercase">';
					_e( 'Từ khóa', 'flatsome' );
					echo '</h5>';
					echo '<ul>';
						foreach ($tags as $value) {
							echo '<li>';
								echo '<a href="'.get_term_link($value->term_id).'">';
									echo $value->name;
								echo '</a>';
							echo '</li>';
						}
					echo '</ul>';
				echo '</div>';
			endif;

			// echo '<pre>';
			// print_r(get_the_tags());
			// echo '</pre>';
		}
	}
	add_action( 'mh_flatsome_related_post', 'mh_related_post' );
}

/**
 * Add image size
 */
add_image_size( 'thumbnail', 100, 100, true );
add_image_size( 'medium', 250, 150, true );
add_image_size( 'medium_large', 500, 300, true );
global $layout_homepage, $default_img, $default_img_medium;
$layout_homepage = '1';
$default_img = get_template_directory_uri() .'/images/placehoder.jpg';
$default_img_medium = get_template_directory_uri() .'/images/placehoder.jpg';
$re_defult_image = '';
try {
	if (class_exists('ACF')) {
		$default_img = get_template_directory_uri() .'/assets/img/placehoder.jpg';
		$layout_homepage =  get_field('layout_homepage','customizer');
		$re_defult_image = get_field('re_thumbnail_default','customizer');
		if( $re_defult_image  != false ) {
			$default_img = wp_get_attachment_image_src( $re_defult_image, 'medium_large');
			$default_img_medium = wp_get_attachment_image_src( $re_defult_image, 'medium');
		}
	}
} catch (Exception $e) {
}

/**
 * Theme option
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Tùy chỉnh',
		'menu_title'	=> 'Tùy chỉnh',
		'menu_slug' 	=> 'customizer',
		'capability'	=> 'manage_options',
		'icon_url' 		=> 'dashicons-hammer',
		'id'			=> 'customizer',
		'post_id'			=> 'customizer'
		
	));
}

/**
 * Custom role
 */
function remove_menu_pages() {
	global $user_ID;
	remove_menu_page('edit-comments.php'); // Comments
	remove_menu_page('wpcf7'); // Contact Form 7 Menu
}
//add_action( 'admin_init', 'remove_menu_pages' );
function disable_plugin_updates( $value ) {
  if ( isset($value) && is_object($value) ) {
    if ( isset( $value->response['advanced-custom-fields-pro/acf.php'] ) ) {
      unset( $value->response['advanced-custom-fields-pro/acf.php'] );
    }
    if ( isset( $value->response['woocommerce/woocommerce.php'] ) ) {
     // unset( $value->response['woocommerce/woocommerce.php'] );
    }
if ( isset( $value->response['yith-woocommerce-ajax-product-filter-premium/init.php'] ) ) {
    //  unset( $value->response['yith-woocommerce-ajax-product-filter-premium/init.php'] );
    }  
if ( isset( $value->response['megamenu-pro/megamenu-pro.php'] ) ) {
     // unset( $value->response['megamenu-pro/megamenu-pro.php'] );
    } 
	  if ( isset( $value->response['pt-content-views-pro/content-views.php'] ) ) {
    //  unset( $value->response['pt-content-views-pro/content-views.php'] );
    }
  }
  return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates',999 );
function getPostViewsOnly($postID, $is_single = true, $show_title = true){
   global $post;
   if(!$postID) $postID = $post->ID;
    $count_key = 'mt_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
   
    if($count == "0" || $count == false){
       return '';
    }
	else{
    return '<span class="svl_show_count_only"><i class="fas fa-eye"></i> '.$count . ($show_title==true?' lượt xem':'').'</span>';
	}
}

function getPostViews($postID, $is_single = true, $show_title = true){
   global $post;
   if(!$postID) $postID = $post->ID;
    $count_key = 'mt_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if(!$is_single || is_home()|| is_front_page()){
        return '<span class="svl_show_count_only"><i class="fas fa-eye"></i> '.$count.($show_title==true?' lượt xem':'').'</span>';
    }
	else{
    $nonce = wp_create_nonce('devvn_count_post');
    if($count == "0" || $count == false){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return '<span class="svl_post_view_count" data-id="'.$postID.'" data-title="'. $show_title .'" data-nonce="'.$nonce.'"></span>';
    }
	else{
    // return '<span class="svl_post_view_count" data-id="'.$postID.'" data-title="'. $show_title .'" data-nonce="'.$nonce.'"><i class="fas fa-eye"></i> '.$count . ($show_title==true?' lượt xem':'').'</span>';
     return '<span class="svl_post_view_count" data-id="'.$postID.'" data-title="'. $show_title .'" data-nonce="'.$nonce.'"></span>';
	}}
}
 
function setPostViews($postID) {
    $count_key = 'mt_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count == 0 || empty($count) || !isset($count) || $count == false)
	{
        add_post_meta($postID, $count_key, 1);
        update_post_meta($postID, $count_key, 1);
    }
	else
	{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
 
add_action( 'wp_ajax_mt_ajax_counter', 'svl_ajax_callback' );
add_action( 'wp_ajax_nopriv_mt_ajax_counter', 'svl_ajax_callback' );
function svl_ajax_callback() {
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "devvn_count_post")) 
	{
        exit();
    }
    $count = 0;
   if ( isset( $_GET['p'] ) ) 
   {
      global $post;
      $postID = intval($_GET['p']);
	  $show_title = $_GET['title'];
      $post = get_post( $postID );
      if($post && !empty($post) && !is_wp_error($post))
	  {
         setPostViews($post->ID);
         $count_key = 'mt_post_views_count';
          $count = get_post_meta($postID, $count_key, true);
      }
   }
	if($count == 0 || $count == false){die('');}
	else
	{
   die('<i class="fas fa-eye"></i> '.$count. ($show_title == true?' lượt xem':''));
	}
}
 
add_action( 'wp_footer', 'svl_ajax_script', PHP_INT_MAX );
function svl_ajax_script() {
   if(!is_single() || is_user_logged_in()) return;
   ?>
   <script>
   (function($){
      $(document).ready( function() {
		  if($('.svl_post_view_count').length){
			var t = $('.svl_post_view_count');
			var $id = $(t).data('id');
            var $nonce = $(t).data('nonce');
			var $show_count_title = $(t).data('title');
           
            $.get('<?php echo admin_url( 'admin-ajax.php' ); ?>?action=mt_ajax_counter&nonce='+$nonce+'&title='+$show_count_title+'&p='+$id, function( html ) {
               $(t).html( html );
            });
		  }
         
      });
   })(jQuery);
   </script>
   <?php
}
function new_modify_user_table( $column ) {
    $column['count_re'] = 'Tin đã đăng';
    $column['count_re_published'] = 'Tin đã duyệt';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );
function fnCountRePost($user_id, $post_status)
{
$count_p = 0; 
$args = array();
if($post_status !='')
{	
$args = array('post_type' => 're',
               'post_author' => $user_id,
			    'post_status' => array( $post_status ) );
}
else{
	$args = array('post_type' => 're',
               'post_author' => $user_id );
}	
$loop = new WP_Query( $args );

while ( $loop->have_posts() ) : $loop->the_post();
  $count_p = $count_p + 1;
endwhile;

return $count_p;
}
function new_modify_user_table_row( $val, $column_name, $user_id ) {
	global $wpdb;
    switch ($column_name) {
        case 'count_re' :
			$count_posts = 0;
			
		    $count_postsa = $wpdb->get_var(
        $wpdb->prepare("
            SELECT COUNT(*) 
            FROM $wpdb->posts 
            WHERE
			post_type = %s 
            AND post_author = %s
            ",
            're',
            $user_id
            
        )
    );
			if($count_postsa != null){
				$count_posts = $count_postsa;
			}
            return $count_posts;
        case 'count_re_published' :
		    
           return count_user_posts($user_id,'re');
            
        default:
    }
    return $val;
}

/* Code ẩn menu active license Flatsome */
/* Có mã bản quyền thì xóa đi để active */
function pm_disable_fl_update( $value ) {
    if ( isset( $value ) && is_object( $value ) ) {
        unset( $value->response['flatsome'] );
    }
    return $value;
}
add_action( 'init', 'pm_disable_fl_update' );
function remove_fl_update_server() {
remove_filter( 'pre_set_site_transient_update_themes', 'flatsome_get_update_info', 1, 999999 );
remove_filter( 'pre_set_transient_update_themes', 'flatsome_get_update_info', 1, 999999 );
}
add_action( 'init', 'remove_fl_update_server' );

add_action('admin_enqueue_scripts', 'ds_admin_theme_style');
add_action('login_enqueue_scripts', 'ds_admin_theme_style');
function ds_admin_theme_style() 
{
        echo '<style>#flatsome-notice, ul#wp-admin-bar-root-default li#wp-admin-bar-flatsome-activate , ul li#wp-admin-bar-flatsome_panel_license, #toplevel_page_flatsome-panel ul.wp-submenu.wp-submenu-wrap > li:nth-child(2), #toplevel_page_flatsome-panel ul.wp-submenu.wp-submenu-wrap > li:nth-child(3){ display: none; }</style>';
    
}
add_action( 'init', 'remove_fl_action' );
function remove_fl_action()
{
global $wp_filter;
remove_action( 'admin_notices', 'flatsome_maintenance_admin_notice' );
}
add_action( 'wp_head', function () {
	echo '<style>#flatsome-notice, ul#wp-admin-bar-root-default li#wp-admin-bar-flatsome-activate , ul li#wp-admin-bar-flatsome_panel_license{ display: none; }</style>';
	
});

/* Code ẩn menu active license Flatsome */
/* Có mã bản quyền thì xóa đi để active */



add_action( 'before_delete_post', 'delete_all_attached_media' );

function delete_all_attached_media( $post_id ) {

  if( get_post_type($post_id) == "re" ) 
  {
	 $attachments = get_field('re_gallery', $post_id);
	    foreach($attachments as $attachment) :
		    wp_delete_attachment($attachment, true );
	    endforeach;
	    delete_post_thumbnail($post_id); 
    $attachments = get_attached_media( '', $post_id );

    foreach ($attachments as $attachment) {
      wp_delete_attachment( $attachment->ID, 'true' );
    }
  }

}
 function post_types_author_archives($query) {
        if ($query->is_author)
                // Add 'books' CPT and the default 'posts' to display in author's archive
                $query->set( 'post_type', array( 're') );
        remove_action( 'pre_get_posts', 'custom_post_author_archive' );
    }
add_action('pre_get_posts', 'post_types_author_archives');
add_action('init', 'cng_author_base');
function cng_author_base() {
    global $wp_rewrite;
    $author_slug = 'nguoi-dang-tin'; // change slug name
    $wp_rewrite->author_base = $author_slug;
}
add_action( 'wpcf7_init', 'wpcf7_add_form_tag_current_url' );
function wpcf7_add_form_tag_current_url() {
    // Add shortcode for the form [current_url]
    wpcf7_add_form_tag( 'current_url',
        'wpcf7_current_url_form_tag_handler',
        array(
            'name-attr' => true
        )
    );
}

// Parse the shortcode in the frontend
function wpcf7_current_url_form_tag_handler( $tag ) {
    global $wp;
    $url = home_url( $wp->request );
    return '<input type="hidden" name="'.$tag['name'].'" value="'.$url.'" />';
}