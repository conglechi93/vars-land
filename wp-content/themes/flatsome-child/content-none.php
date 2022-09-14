<section class="error-404 not-found mt mb">
  <div class="row">
    <div class="col medium-3"><span class="header-font" style="font-size: 6em; font-weight: bold; opacity: .3">404</span></div>
    <div class="col medium-9">
      <header class="page-title">
        <h1 class="page-title"><?php esc_html_e( 'Oops! Không tìm thấy kết quả.', 'flatsome' ); ?></h1>
      </header>
      <div class="page-content">

        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
          <p><?php printf( wp_kses( __( 'Sẵn sàng viết bài đầu tiên của bạn? <a href="%1$s">Bắt đầu ở đây.</a>.', 'trustweb' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
        <?php elseif ( is_search() ) : ?>
          <p><?php esc_html_e( 'Không có kết quả nào thỏa mãn yêu cầu của bạn. Vui lòng thử lại với ô tìm kiếm.', 'trustweb' ); ?></p>
          <?php
          get_search_form();
        else : ?>
          <p><?php esc_html_e( 'Không có kết quả nào thỏa mãn yêu cầu của bạn. Có lẽ tìm kiếm sẽ giúp ích cho bạn.', 'trustweb' ); ?></p>
          <?php get_search_form();
        endif; ?>

      </div>
    </div>
  </div>
</section>
