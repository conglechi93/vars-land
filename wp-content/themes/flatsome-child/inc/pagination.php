<?php
/*
 * Pagination
 */
if( !function_exists('willgroup_pagination') ){
  function willgroup_pagination($pages = '', $range = 2) {
    global $paged;
    $showitems = ( $range * 2 )+1;

    if( empty( $paged) ) $paged = 1;

    if($pages == '') {
      global $wp_query;
      $pages = $wp_query->max_num_pages;
      if(!$pages) {
        $pages = 1;
      }
    }

  if(1 != $pages) {
    echo "<ul class='page-numbers nav-pagination links text-center'>";
    if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a class='page-link' href='".get_pagenum_link(1)."'>&laquo;</a></li>";
    if($paged > 1 && $showitems < $pages) echo "<li><a class='page-link' aria-label=\"Previous\" href='".get_pagenum_link($paged - 1)."'><span aria-hidden=\"true\">&laquo;</span></a></li>";
    for ($i=1; $i <= $pages; $i++) {
      if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
        echo ($paged == $i)? "<li><span aria-current='page' class='page-number current' href='#'>" . $i . "</span></li>" : "<li><a class='page-link' href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a></li>";
      }
    }

    if ($paged < $pages && $showitems < $pages) echo "<li><a class='page-link' aria-label=\"Next\" href='".get_pagenum_link($paged + 1)."'><span aria-hidden=\"true\">&raquo;</span></a></li>";
    if ( $paged < $pages - 1 ) {
      if ( $paged + $range - 1 < $pages ) {
        if ( $showitems < $pages ) {
          echo "<li><a class='page-link' href='" . get_pagenum_link($pages) . "'>&raquo;</a></li>";
        }
      }
    }
    echo "</ul>";
    }
  }
}
