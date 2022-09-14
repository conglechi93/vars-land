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

<div class="box box-vertical box-text-bottom mh-box-re-vertical">
  <div class="box-image" style="width: 30%;">
    <a href="<?php the_permalink(); ?>">
      <div class="image-cover" style="padding-top: 60%;">
		   <?php global $post;
         $postID = $post->ID;
			   echo getPostViewsOnly($postID, false, true); ?>
        <img src="<?php echo $post_thumbnail; ?>" data-src="<?php echo $post_thumbnail; ?>" alt="<?php esc_attr(the_title()); ?>"/>
      </div>
    </a>
  </div>
  <div class="box-text text-left relative">
    <div class="box-text-inner blog-post-inner">
      <div class="title-wrapper">
        <div class="name re-title">
          <a href="<?php the_permalink(); ?>" title="<?php esc_attr(the_title()); ?>"><?php esc_attr(the_title()); ?></a>
        </div>
      </div>
      <div class="re-info row row-collapse">
        <div class="col medium-12 small-12 large-6">
          <a class="text-body" href="<?php echo get_term_link( $re_cat ); ?>">
            <i class="fas fa-home fa-fw mr-1"></i>
            <?php echo $re_cat->name; ?>
          </a>
        </div>
        <div class="col medium-12 small-12 large-6">
          <i class="fas fa-ruler-combined"></i>
          <?php the_field('re_area'); ?>m<sup>2</sup>
        </div>
        <div class="col medium-12 small-12 large-6">
          <i class="fas fa-map-marker-alt fa-fw mr-1"></i><?php
          $districts = willgroup_get_assoc_array_of_districts(get_field('re_province'));
          echo $districts[get_field('re_district')]; ?>
        </div>
        <div class="col medium-12 small-12 large-6">
          <i class="fas fa-calendar-alt"></i>
          <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __( 'trước', 'flatsome' ); ?>
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
  </div>
</div>
