<?php
global $default_img;
$terms = get_the_terms($post->ID, 're_cat');
$re_gia_thoa_thuan = (get_field('re_gia_thoa_thuan')?1:0);
$re_cat = $terms[1];
$post_thumbnail = get_the_post_thumbnail_url(get_the_ID(),'medium_large');
if(empty($post_thumbnail) || $post_thumbnail == null || $post_thumbnail == '' || $post_thumbnail == false){
  $post_thumbnail = $default_img;
}
?>
<div class="product-small box has-hover box-normal box-text-bottom mh-re-small">
  <!-- Box image -->
  <div class="box-image relative">
    <a href="<?php the_permalink(); ?>">
      <img width="500px" height="300px" src="<?php echo $post_thumbnail; ?>" data-src="<?php echo $post_thumbnail; ?>" alt="<?php esc_attr(the_title()); ?>"/>
    </a>
    <div class="mh-map uppercase">
      <div class="mh-map-inner">
        <i class="fas fa-map-marker-alt fa-fw mr-1"></i>
        <?php
          $districts = willgroup_get_assoc_array_of_districts(get_field('re_province'));
          echo $districts[get_field('re_district')];
        ?>
      </div>
    </div>
    <div class="mh-price-re uppercase">
      <div class="mh-price-re-inner">
        <?php
          if( $re_gia_thoa_thuan==1 ) {
            echo 'Giá: <strong>Thỏa thuận</strong>';
          } else {
            echo 'Giá:' . get_the_price();
          }
        ?>
      </div>
    </div>
  </div>

  <!-- Box text -->
  <div class="box-text text-left">
    <div class="title-wrapper">
      <div class="name re-title">
        <a href="<?php the_permalink(); ?>" title="<?php esc_attr(the_title()); ?>"><?php esc_attr(the_title()); ?></a>
      </div>
    </div>
    <div class="re-info">
      <span>
        <a class="text-body" href="<?php echo get_term_link( $re_cat ); ?>">
          <i class="fas fa-home fa-fw mr-1"></i>
          <?php echo $re_cat->name; ?>
        </a>
      </span>
      <span>
        <i class="fas fa-ruler-combined"></i>
        <?php the_field('re_area'); ?>m<sup>2</sup>
      </span>
    </div>
    <div class="mh-re-action">
      <div class="mh-re-time">
        <i class="fas fa-calendar-alt fa-fw mr-1"></i>
        <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' trước'; ?>
		  <?php global $post;
         $postID = $post->ID;
			   echo getPostViewsOnly($postID, true, false); ?>
      </div>
		
      <div class="mh-re-detail">
        <a href="<?php the_permalink(); ?>" class="button secondary is-outline">
          <span><?php _e( 'Chi tiết', 'flatsome' ); ?></span>
          <i class="fas fa-chevron-right"></i>
        </a>
       
      </div>
    </div>

  </div>

</div>
