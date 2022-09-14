<?php
function mh_ux_builder(){
  /* Search form */
  add_ux_builder_shortcode('mh_advanced_search', array(
    'name'      => __('Tìm kiếm nâng cao'),
    'category'  => __('MH Builder'),
    'priority'  => 1,
    'options' => array(
	 
    ),
  ));

  /* Bất động sản */
  add_ux_builder_shortcode( 'ux_re', array(
      'name' => __( 'Tin BĐS - Slide', 'flatsome' ),
      'category' => __( 'MH Builder' ),
      'priority' => 1,
      'options' => array(
      
        'post_options' => require_once( __DIR__ . '/ux/commons/repeater-posts.php' ),
		'vip' => array(
			'type'    => 'checkbox',
			'heading' => 'Chỉ hiện tin VIP',
			'default' => 'false',
		) 
      )
  ) );

  add_ux_builder_shortcode( 'ux_re_list', array(
      'name' => __( 'Tin BĐS - List', 'flatsome' ),
      'category' => __( 'MH Builder' ),
      'priority' => 1,
      'options' => array(
        'post_options' => require_once( __DIR__ . '/ux/commons/vertical-option.php' ),
		'vip' => array(
			'type'    => 'checkbox',
			'heading' => 'Chỉ hiện tin VIP',
			'default' => 'false',
		)   
      )
  ) );
  add_ux_builder_shortcode( 'ux_topmoigioi', array(
      'name' => __( 'Top Môi giới', 'flatsome' ),
      'category' => __( 'MH Builder' ),
      'priority' => 1,
		'options' => array(
      'soluong' => array(
    
		
        'type' => 'slider',
        'heading' => 'Số lượng Môi giới',
       
       
        'responsive' => true,
        'max' => '10',
        'min' => '1',
    
      ))
  ) );
  /* Search form */
  // add_ux_builder_shortcode('mh_login_form', array(
  //   'name'      => __('Form Đăng nhập/Đăng ký'),
  //   'category'  => __('MH Builder'),
  //   'priority'  => 1,
  //   'options' => array(
  //   ),
  // ));

}
add_action('ux_builder_setup', 'mh_ux_builder');
