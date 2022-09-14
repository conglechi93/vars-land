<?php
return array(
  'type' => 'group',
  'heading' => __( 'Bất động sản', 'flatsome' ),
  'options' => array(
    'ids' => array(
      'type' => 'select',
      'heading' => 'Custom Posts',
      'param_name' => 'ids',
      'config' => array(
        'multiple' => true,
        'placeholder' => 'Select..',
        'postSelect' => array(
          'post_type' => 're'
        ),
      )
    ),
    'cat' => array(
      'type' => 'select',
      'heading' => 'Category',
      'param_name' => 'cat',
      'conditions' => 'ids === ""',
      'default' => '',
      'config' => array(
        'multiple' => true,
        'placeholder' => 'Select...',
        'termSelect' => array(
          'post_type' => 're',
          'taxonomies' => 're_cat'
        ),
      )
    ),
	  'tinhthanh' => array(
	  'type' => 'select',
      'heading' => 'Tỉnh thành',
      'param_name' => 'tinhthanh',
	  'default' => '',
      'options' =>	$tinhthanh 
	 ),
    'res' => array(
      'type' => 'textfield',
      'heading' => 'Total Posts',
      'conditions' => 'ids === ""',
      'default' => '8',
    ),
	 'layout' => array(
     'type' => 'select',
      'heading' => 'Dạng',
      'options' => array(
		'row' => 'Dạng hàng',
        'grid' => 'Dạng ô lưới (Grid)'
		  
      )
    ),
  )
);
