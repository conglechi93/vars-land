<?php
/**
 * Template Name: Page - User list post
 *
 * @package willgroup
 */

if( ! is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}

$current_user = wp_get_current_user();
$current_link = get_the_permalink();

if( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) {
	$id = $_GET['id'];

	$args = array(
		'post_type'      => 're',
		'posts_per_page' => 1,
		'p'				 => $id,
		'author'		 => $current_user->ID
	);
	$query = new WP_Query($args);
	if ( ! $query->have_posts() ) {
		wp_redirect( home_url() );
		exit();
	}

	$attachments = get_field('re_gallery', $id);
	foreach($attachments as $attachment) :
		wp_delete_attachment($attachment['ID'], true );
	endforeach;
	delete_post_thumbnail($id);
	wp_delete_post($id);
	wp_redirect( $current_link );
	exit();
}

get_header(); ?>

	<div class="row row-small">
		<div class="col medium-12 small-12 large-12">
			<div class="col-inner">
				<?php
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				$args = array(
					'post_type' 	 => 're',
					'posts_per_page' => 20,
					'post_status'	 => 'any',
					'paged'	         => $paged,
					'author' 		 => $current_user->ID
				);
				$query = new WP_Query( $args );
				if( $query->have_posts() ) : ?>
					<table id="table-list-post" class="table table-list-post">
						<thead>
							<tr>
								<th class="hide-for-medium d-sm-table-cell"><?php _e( 'STT', 'flatsome' ); ?></th>
								<th class="hide-for-small d-sm-table-cell mh-col-thumbnail"><?php _e( 'Hình ảnh', 'flatsome' ); ?></th>
								<th style="width: 17.5%;"><?php _e( 'Tiêu đề', 'flatsome' ); ?></th>
								<th class="hide-for-medium d-lg-table-cell" style="width: 10%;"><?php _e( 'Nhu cầu', 'flatsome' ); ?></th>
								<th class="hide-for-medium d-lg-table-cell" style="width: 10%;"><?php _e( 'Loại nhà đất', 'flatsome' ); ?></th>
								<th class="hide-for-medium d-lg-table-cell" style="width: 10%;"><?php _e( 'Tỉnh/thành', 'flatsome' ); ?></th>
								<th class="hide-for-medium d-lg-table-cell" style="width: 10%;"><?php _e( 'Diện tích', 'flatsome' ); ?></th>
								<th class="hide-for-medium d-lg-table-cell" style="width: 10%;"><?php _e( 'Giá', 'flatsome' ); ?></th>
								<th class="text-center" style="width: 10%;"><?php _e( 'Trạng thái', 'flatsome' ); ?></th>
								<th class="text-center" style="width: 10%;"><?php _e( 'Hành động', 'flatsome' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php while( $query->have_posts() ) : $query->the_post(); ?>
							<tr>
								<td class="hide-for-medium d-sm-table-cell"><?php echo ((int)$query->current_post) + 1; ?></td>
								<td class="hide-for-small d-sm-table-cell mh-col-thumbnail">
									<a href="<?php the_permalink(); ?>">
										<?php if ( get_the_post_thumbnail_url() ) { ?>
											<img class="rounded" style="width: 3.125rem;" src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" />
										<?php } else {
											$re_thumbnail_default = get_field('re_thumbnail_default', 'customizer');
								      $size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)
								      $thumb = $re_thumbnail_default['sizes'][ $size ]; ?>
											<img width="50px" src="<?php echo esc_url($thumb); ?>" />
										<?php } ?>
									</a>
								</td>
								<td class="re-name">
									<a href="<?php the_permalink(); ?>"><strong><?php the_title(); ?></strong></a>
								</td>
								<td class="hide-for-medium d-lg-table-cell">
									<?php echo get_term(get_field('re_demand'))->name; ?>
								</td>
								<td class="hide-for-medium d-lg-table-cell">
									<?php echo get_term(get_field('re_cat'))->name; ?>
								</td>
								<td class="hide-for-medium d-lg-table-cell">
									<?php
									$provinces = willgroup_get_assoc_array_of_provinces();
									echo $provinces[get_field('re_province')];
									?>
								</td>
								<td class="hide-for-medium d-lg-table-cell"><?php the_field('re_area'); ?>m<sup>2</sup></td>
								<td class="hide-for-medium d-lg-table-cell"><?php the_price(); ?></td>
								<td class="text-center">
									<?php if( get_post_status( get_the_ID() ) == 'publish' ) : ?>
										<i class="far fa-check-circle" title="<?php _e( 'Đã duyệt', 'flatsome' ); ?>"></i>
									<?php else : ?>
										<i class="fas fa-ellipsis-h" style="width: 1.25rem; height: 1.25rem;" title="<?php _e( 'Chờ xét duyệt', 'flatsome' ); ?>"></i>
									<?php endif; ?>
								</td>
								<td class="text-center action-post">
									<a class="action" href="<?php echo home_url('nguoi-dung/dang-tin'); ?>?action=edit&id=<?php the_ID(); ?>" title="<?php _e( 'Sửa', 'flatsome' ); ?>">
										<i class="far fa-edit"></i>
									</a>
									<a class="action delete" data-id="<?php the_ID(); ?>" href="javascript:void(0);" title="<?php _e( 'Xóa', 'flatsome' ); ?>">
										<i class="far fa-times-circle"></i>
									</a>
								</td>
							</tr>
							<?php endwhile; wp_reset_postdata(); ?>
						</tbody>
					</table>
					<?php willgroup_pagination( $query->max_num_pages ); ?>
				<?php else : ?>
					<div class="alert alert-danger"><?php _e( 'Bạn chưa có tin đăng nào.', 'flatsome' ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php
get_footer();
