<?php
get_header();
global $default_img;
$kieu_hien_thi = get_field('kieu_hien_thi_bds', 'customizer');
$show_vip_in_archive = get_field('show_vip_in_archive', 'customizer');
$class_grd = 'col medium-6 small-12 large-4';
$term_id =  get_queried_object()->term_id;
if($kieu_hien_thi == '2')
{
	$class_grd = 'col medium-12 small-12 large-12';
}
?>

<?php do_action( 'flatsome_before_page' ); ?>

<div class="page-wrapper page-right-sidebar">
<div class="row">

<div id="content" class="large-9 left col" role="main">
	<div class="page-inner">
    <?php
		if($show_vip_in_archive == true) { 
			$args = array(
				'post_type' 	 => 're',
				'posts_per_page' => -1,
				'tax_query'		 => array(
					array(
						'taxonomy' => 're_cat',
						'field'	   => 'term_id',
						'terms'    => array($term_id ),
					)
				),
				'meta_query'	 => array(
					array(
						'key'	 => 're_vip',
						'value'  => 1
					)
				)
			);

			$lazyplaceholder = $default_img;
			$query = new WP_Query( $args );
			if( $query->have_posts() ) : ?>
				<div class="container section-title-container mh-title-label" style="margin-bottom:0px;">
					<h3 class="section-title section-title-normal">
						<b></b>
						<span class="section-title-main">
							<?php
								echo get_queried_object()->name;
								_e( ' - Tin vip', 'flatsome' );
							?>
							</span>
						<b></b>
					</h3>
				</div>
				<div class="row row-small <?php echo ($query->post_count>=3 ? "mh-carousel mh-slider-arrow-top slider": ""); ?>">
					<?php while( $query->have_posts() ) : $query->the_post(); ?>
						<div class="col medium-6 small-12 large-4">
							<div class="col-inner">
								<?php get_template_part( 'content', 're' ); ?>
							</div>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php endif; 
		}
		   ?>

			<div class="container section-title-container mh-title-label" style="margin-bottom: 0px; margin-top: 10px;">
				<h3 class="section-title section-title-normal">
					<b></b>
					<span class="section-title-main">
						<?php echo get_queried_object()->name; ?>
						</span>
					<b></b>
				</h3>
			</div>

			<?php if( have_posts() ) : ?>
				<div class="row row-small">
					
					<?php while( have_posts() ) : the_post(); ?>
						<div class="<?php echo $class_grd; ?>">
							<div class="col-inner">
						  	<?php
								if($kieu_hien_thi == '2')
								{
	                              get_template_part( 'content', 're-list' );
                                }
								else
								{
									get_template_part( 'content', 're' );
								}
								 ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php willgroup_pagination();
			else : ?>
		    <?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
       <div class="divDescRe rounded pt-3 px-3 mb-4">
		   <?php 
		    $description = term_description();
		   echo $description; ?>
		</div>

  </div>
</div>

<div class="large-3 col">
	<?php get_sidebar(); ?>
</div>

</div>
</div>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>
