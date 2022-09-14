<?php
/**
 * Table list provinces
 */
function wg_loadoption_pages() {
  add_menu_page('Quản lý danh sách tỉnh thành', 'Tỉnh / thành', 'manage_options', 'mth_provinces', 'mth_provinces_page','dashicons-forms',8);
}
add_action( 'admin_menu', 'wg_loadoption_pages', 1 );

function mth_provinces_page() {
  global $wpdb;
  // Lay danh sach tinh/thanh tu database
  $provinces = $wpdb->get_results( "SELECT * FROM " .$wpdb->prefix ."provinces order by thutu ASC" );
  // access control
  if ( !(isset($_GET['page']) && $_GET['page'] == 'mth_provinces' ))
    return; ?>
  <div style="margin: 10px 0px 10px 0;padding: 10px;" class="notice notice-success">
    <strong>Danh sách tỉnh thành</strong>
  </div>

  <div style="text-align:center;margin-bottom:12px;margin-top:12px;">
    <a href="javascript:void(0);" id="btnUpdate">Cập nhật</a> <span class="spinner mybtnUpdate"></span>
  </div>

  <div class='wrap'>
    <div class="form-table">
      <table id="prov_table" class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width:60px">Mã</th>
            <!--	<th>Loại</th> -->
            <th>Tên</th>
            <th>Tên đầy đủ</th>
            <!--	<th>Slug</th> -->
            <th>Thứ tự</th>
            <th>Sử dụng</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach( $provinces as $province ) {
              echo "<tr>";
                echo "<td>". $province->code."</td>";
                //echo "<td>". $province->type ."</td>";
                echo "<td>". $province->name ."</td>";
                echo "<td>". $province->name_with_type ."</td>";
                //echo "<td>". $province->slug ."</td>";
                echo "<td><input type='number' value='". $province->thutu ."' /></td>";
                echo "<td><input type='checkbox' value='". $province->show_col ."' " . ($province->show_col=='true'?'checked':'') . "></td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <script type="text/javascript">
    jQuery( document ).ready(function() {
      // alert(ajaxurl );
      jQuery("#prov_table tbody tr").each(function (i, row) {
        var show_col = jQuery(row).children("td:nth-child(5)").children("input");
        jQuery(show_col).click(function () {
          var checked = jQuery(this).is(":checked").toString();
          jQuery(this).val(checked);
          if(checked=='false') {
            jQuery(this).removeAttr('checked');
          }
        });
      });

      jQuery("#btnUpdate").click(function() {
        jQuery('.mybtnUpdate').css('visibility','visible');
        var data = [];
        jQuery("#prov_table tbody tr").each(function (i, row) {
          var code = jQuery(row).children("td:nth-child(1)").html();
          var thutu = jQuery(row).children("td:nth-child(4)").children("input").val();
          var show_col = jQuery(row).children("td:nth-child(5)").children("input").val();

    			data.push ({
            "code" : code,
            "thutu" : thutu,
            "show_col" : show_col
    			});

        });

        var provinces = {'data' : data};
          jQuery.ajax({
            type: 'POST', //contentType: 'application/json',
            url: ajaxurl,
            data: {
              'provinces' : JSON.stringify(data)  ,
              'action'   : 'UpdateProvince'
            },

            success: function( data, textStatus, jqXHR ) {
              if(data == 'OK'){
                location.reload();
              } else {
                jQuery('.mybtnUpdate').hide();
              }
            },

            error: function( jqXHR, textStatus, errorThrown ) {
            jQuery('.mybtnUpdate').hide();
            alert( errorThrown );
          }

        });
      });
    });
  </script>
  <?php
}

/**
 * Get array of provinces
 */
function willgroup_get_array_of_provinces() {
	//$str = file_get_contents(get_theme_file_path('json/provinces.json'));
	//$provinces = json_decode($str, true);
	//sort($provinces);
	global $wpdb;

	$provinces = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}provinces where show_col = 'true' order by thutu ASC" , ARRAY_A  );
	return $provinces;
}

function willgroup_get_assoc_array_of_provinces() {
	$provinces = willgroup_get_array_of_provinces();
	$provinces_assoc = array();
	$provinces_assoc[''] = __('Chọn tỉnh/thành', 'flatsome');
	foreach( $provinces as $province ) {
		$provinces_assoc[$province['code']] = $province['name'];
	}
	return $provinces_assoc;
}

/**
 * Get array of districts
 */
function willgroup_get_array_of_districts($province) {
	if ($province) {
		$str = file_get_contents(get_theme_file_path('json/districts/' . $province . '.json'));
    // $str = file_get_contents(get_stylesheet_directory_uri() . '/json/districts/' . $province . '.json');
		$districts = json_decode($str, true);
		sort($districts);
		return $districts;
	} else {
		return false;
	}
}

function willgroup_get_assoc_array_of_districts($province) {
	$districts = willgroup_get_array_of_districts($province);
	$districts_assoc = array();
	$districts_assoc[''] = __('Chọn quận/huyện', 'flatsome');
	if ($districts) {
		foreach( $districts as $district ) {
			$districts_assoc[$district['code']] = $district['name_with_type'];
		}
	}
	return $districts_assoc;
}

/**
 * Get array of wards
 */
function willgroup_get_array_of_wards($district) {
	if ($district) {
		$str = file_get_contents(get_theme_file_path('json/wards/' . $district . '.json'));
		$wards = json_decode($str, true);
		sort($wards);
		return $wards;
	} else {
		return false;
	}
}

function willgroup_get_assoc_array_of_wards($district) {
	$wards = willgroup_get_array_of_wards($district);
	$wards_assoc = array();
	$wards_assoc[''] = __('Chọn phường/xã', 'flatsome');
	if ($wards) {
		foreach( $wards as $ward ) {
			$wards_assoc[$ward['code']] = $ward['name_with_type'];
		}
	}
	return $wards_assoc;
}

/**
 * Human price
 */
function human_price( $number ) {
	if ( !is_numeric( $number ) ) {
		return $number;
	} else {
		if ( $number >= 1000000000 ) {
			$result = '<strong>' . $number / 1000000000 . '</strong>';
			$result .= __( ' tỷ', 'flatsome' );
		} elseif ( $number >= 1000000 ) {
			$result = '<strong>' . $number / 1000000 . '</strong>';
			$result .= __( ' triệu', 'flatsome' );
		} elseif ( $number >= 1000 ) {
			$result = '<strong>' . $number / 1000 . '</strong>';
			$result .= __( ' ngàn', 'flatsome' );
		} else {
			$result = __( 'Liên hệ', 'flatsome' );
		}
		return str_replace( '.', ',', $result );
	}
}

/**
 * Get re price
 */
function get_the_price( $post_id = '' ) {
	if( ! $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$price = get_field( 're_price', $post_id );
	$price = human_price( $price );
	$unit_price = get_field( 're_unit_price', $post_id );

	if( $unit_price == 1 ) {
		return $price;
	} elseif( $unit_price == 2 ) {
		return $price . '/' . __( 'tháng', 'flatsome' );
	} elseif( $unit_price == 3 ) {
		return $price . '/' . 'm<sup>2</sup>';
	} else {
		return __( 'Thỏa thuận', 'flatsome' );
	}

}

/**
 * Get prices
 */
function get_prices() {
	return array( 1000000, 100000000, 200000000, 500000000, 1000000000, 3000000000, 5000000000, 8000000000, 10000000000, 20000000000, 35000000000 );
}

/**
 * Create re price template tag
 */
function the_price() {
	echo get_the_price();
}

/**
 * Get building orientations
 */
function get_building_orientations() {
	return array(
		'1' => __( 'Đông', 'flatsome' ),
		'2' => __( 'Tây', 'flatsome' ),
		'3' => __( 'Nam', 'flatsome' ),
		'4' => __( 'Bắc', 'flatsome' ),
		'5' => __( 'Đông bắc', 'flatsome' ),
		'6' => __( 'Đông nam', 'flatsome' ),
		'7' => __( 'Tây bắc', 'flatsome' ),
		'8' => __( 'Tây nam', 'flatsome' )
	);
}

/**
 * Get re address
 */
function get_the_address( $post_id = '' ) {
	if( ! $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$provinces = willgroup_get_assoc_array_of_provinces();
	$districts = willgroup_get_assoc_array_of_districts(get_field('re_province', $post_id));
	$wards = willgroup_get_assoc_array_of_wards(get_field('re_district', $post_id));
	$address = get_field('re_address', $post_id) . ', ' . $wards[get_field('re_ward', $post_id)] . ', ' . $districts[get_field('re_district', $post_id)] . ', ' . $provinces[get_field('re_province', $post_id)];

	return $address;
}

/**
 * Create re address template tag
 */
function the_address() {
	echo get_the_address();
}

/**
 * Get lat and lng from address
 */
function willgroup_get_lat_lng($address, $post_id) {
	$lat = (empty(get_field('re_lat',$post_id))?'':get_field('re_lat',$post_id));
	$lng = (empty(get_field('re_long',$post_id))?'':get_field('re_long',$post_id));
	if( $lat =='' && $lng =='') {
  	// Google Geocoding API Key
  	$apiKey  = get_field('google_map_api_key', 'customizer');
  	$address = urlencode( $address );
  	$url     = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";
  	$resp    = json_decode( file_get_contents( $url ), true );
    if($resp['results'][0]['geometry']['location']['lat'] != null && $resp['results'][0]['geometry']['location']['lng'] != null) {
    	// Latitude and Longitude (PHP 7 syntax)
    	$lat    = ($resp['results'][0]['geometry']['location']['lat'] ? $resp['results'][0]['geometry']['location']['lat'] : '');
    	$lng   = ($resp['results'][0]['geometry']['location']['lng'] ? $resp['results'][0]['geometry']['location']['lng']  :'');
    	update_field('re_lat', $lat, $post_id);
    	update_field('re_long', $lng, $post_id);
  	}
	}
	return array('lat' => $lat, 'lng' => $lng);
}

/**
 * Add re_demand endpoint
 */
function willgroup_add_re_demand_endpoint() {
	add_rewrite_endpoint( 'tai', EP_ALL );
}
add_action( 'init', 'willgroup_add_re_demand_endpoint' );

function willgroup_re_demand_template_redirect() {
	global $wp_query;
  if ( ! isset( $wp_query->query_vars['tai'] ) ) {
  	return;
  }
  $wp_query->is_404 = false;
  require get_theme_file_path() . '/location.php';
  exit;
}
add_action( 'template_redirect', 'willgroup_re_demand_template_redirect' );

add_filter('pre_get_document_title', 'willgroup_change_document_title');
function willgroup_change_document_title($title) {
 	global $wp_query, $location_title;
  if ( ! isset( $wp_query->query_vars['tai'] ) ) {
      return $title;
  }

  $title = $location_title . ' - ' . get_bloginfo('name');
  return $title;
}

/**
 * Get province slug
 */
function willgroup_get_province_slug($code) {
	$provinces = willgroup_get_array_of_provinces();
	foreach ( $provinces as $province ) {
		if ( $province['code'] == $code ) {
			return 'tai/' . $province['slug'];
			break;
		}
	}
	return;

}

/**
 * Get district slug
 */
function willgroup_get_district_slug($district_code, $province_code) {
	$districts = willgroup_get_array_of_districts($province_code);
	$provinces = willgroup_get_array_of_provinces();
	foreach ( $districts as $district ) {
		if ( $district['code'] == $district_code ) {
			foreach ( $provinces as $province ) {
				if ( $province['code'] == $province_code ) {
					return 'tai/' . $province['slug'] . '/' . $district['slug'];
					break;
				}
			}
		}
	}
	return;
}

/**
 * Get district slug
 */
function willgroup_get_ward_slug($ward_code, $district_code, $province_code) {
	$wards = willgroup_get_array_of_wards($district_code);
	$districts = willgroup_get_array_of_districts($province_code);
	$provinces = willgroup_get_array_of_provinces();
	foreach ( $wards as $ward ) {
		if ( $ward['code'] == $ward_code ) {
			foreach ( $districts as $district ) {
				if ( $district['code'] == $district_code ) {
					foreach ( $provinces as $province ) {
						if ( $province['code'] == $province_code ) {
							return 'tai/' . $province['slug'] . '/' . $district['slug'] . '/' . $ward['slug'];
							break;
						}
					}
				}
			}
		}
	}
	return;
}

/**
 * Get list Province
 */
function fnGetProvince($atts){
	extract( shortcode_atts( array(
		'all'       => 'false',
		'rows' => 10,
		'limit' => 20
		), $atts )
	);
	$hide_class = '';
	if($all== 'false') {
		$hide_class = 'class="tem_hide"';
	}
	$provinces = willgroup_get_array_of_provinces();

	$html = '<ul class="menu">';
  	$dem = 1;
  	foreach ( $provinces as $value ) {
  		$html .='<li '. ($all=='false'?($dem>$rows?$hide_class:''):'') .'><a title="Mua bán cho thuê bất động sản tại '. $value['name_with_type'] .'" href="/tai/' . $value['slug'] .'/"><i class="fas fa-chevron-right"></i> '. $value['name_with_type'] .'</a></li>';
  		$dem++;
  		if($dem > $limit){
  			break;
  		}
  	}
  	$html .= '</ul>';
	// if($all== 'false') {
	//    $html .= '<div class="btn_xemtatcaprovince"><a href="javascript:void(0);">Xem tất cả <i class="fas fa-chevron-down"></i></a></div>';
	// }
	return $html;
}
add_shortcode('provincelist','fnGetProvince');

/**
 * ID video
 */
function getYouTubeVideoId($url) {
     $video_id = false;
     $url = parse_url($url);
     if (strcasecmp($url['host'], 'youtu.be') === 0)
     {
          #### (dontcare)://youtu.be/<video id>
          $video_id = substr($url['path'], 1);
     }
     elseif (strcasecmp($url['host'], 'www.youtube.com') === 0)
     {
          if (isset($url['query']))
          {
               parse_str($url['query'], $url['query']);
               if (isset($url['query']['v']))
               {
                    #### (dontcare)://www.youtube.com/(dontcare)?v=<video id>
                    $video_id = $url['query']['v'];
               }
           }
           if ($video_id == false)
           {
               $url['path'] = explode('/', substr($url['path'], 1));
               if (in_array($url['path'][0], array('e', 'embed', 'v')))
               {
                    #### (dontcare)://www.youtube.com/(whitelist)/<video id>
                    $video_id = $url['path'][1];
               }
            }
     } else {
         return false;
     }
     return $video_id;
}

/**
 * Home sidebar
 */
register_sidebar(array(
  'name' => 'Home sidebar',
  'id' => 'mh-home-sidebar',
  'description' => 'Khu vực sidebar hiển thị Trang chủ',
  'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  'after_widget' => '</aside>',
  'before_title' => '<span class="widget-title"><span>',
  'after_title' => '</span></span>'
));
