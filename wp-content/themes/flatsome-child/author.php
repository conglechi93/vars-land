<?php
get_header();
global $default_img;
$kieu_hien_thi = get_field('kieu_hien_thi_bds', 'customizer');
$show_vip_in_archive = get_field('show_vip_in_archive', 'customizer');
$class_grd = 'col medium-6 small-12 large-4';
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


  </div>
</div>

<div class="large-3 col">
	<?php get_sidebar(); ?>
</div>

</div>
</div>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>
