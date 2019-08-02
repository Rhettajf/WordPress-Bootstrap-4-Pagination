/**
 * Bootstrap 4 Pagination for Roots Sage 9
 * Description: Follows bootstrap pagination (https://getbootstrap.com/docs/4.0/components/pagination/).
 * Author: Rhett Forbes | http://rhettforbes.com
 * Version: 1.0
 * Optional arguments.
 *      echo: If true, pagination is echoed. Otherwise is is returned. Default true. Accepts true|false.
 *      query: Allows you to specify a custom query to paginate. Defaults to global query. Accepts WP_Query object.
 *      show_all: If set to True, it will show all pages instead of just pages near the current page. Default false.Accepts true|false.
 *      prev_next: Wheter to include the previous and next links in the list or not. Default true. Accepts true|false.
 *      prev_text: The previous page link text which is only shown to screenreaders. Accepts string.
 *      next_text: The next page link text which is only shown to screenreaders. Accepts string.  
 */
function boot_pagination( $args = array() ) {
	$defaults = array(
		'echo' => true,
		'query' => $GLOBALS['wp_query'],
		'show_all' => false,
		'prev_next' => true,
        'mid_size'  => 5,
		'prev_text' => __('Previous Page', 'sage'),
		'next_text' => __('Next Page', 'sage'),
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract($args, EXTR_SKIP);

	// If there's only one page we sure don't need pagination
	if( $query->max_num_pages <= 1 ) {
		return;
	}	
	
	$pagination = '';
	$links = array();
	
	$paged = max( 1, absint( $query->get( 'paged' ) ) );
	$max   = intval( $query->max_num_pages );
    
    //Descriptive aria-label for the <nav> to reflect its purpose.
    //single_post_title( $prefix, $display );
    $page_title = single_post_title( 'Page Navigation: ', false );
	
	if ( $show_all ) {
		$links = range(1, $max);	
	} else {
		// Add some pages before the current page
		if ( $paged >= 3 ) {
			$links[] = $paged - 2;
			$links[] = $paged - 1;
		}		
		// Add the current page
		if ( $paged >= 1 ) {
			$links[] = $paged;
		}		
		// Add some pages after the current page
		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 1;
			$links[] = $paged + 2;
		}
	}
	
    // Use a wrapping <nav> element to identify it as a navigation section to screen readers and other assistive technologies. 
	$pagination .= "\n" . '<nav aria-label="' . $page_title . '"><ul class="pagination justify-content-end">' . "\n";

	// Previous Post Link
	if ( $prev_next && get_previous_posts_link() ) {
		$pagination .= sprintf( '<li class="page-item prev"><a class="page-link" href="%s"><span aria-hidden="true">&laquo;</span><span class="sr-only">' . $prev_text . '</span></a></li>' . "\n", get_previous_posts_page_link() );		
	} else {
        $pagination .= '<li class="page-item prev disabled"><span class="page-link">&laquo;</span></li>';
    }        

	// Link to first page, plus ellipses if necessary
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' active' : '';
		$pagination .= sprintf( '<li class="page-item%s"><a class="page-link" href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( 1 ) ), '1' );
		$pagination .= "\n";	
		if ( ! in_array( 2, $links ) ) {
			$pagination .= '<li class="page-item disabled"><span class="page-link">' . __( '&hellip;' ) . '</span></li>';
		}
		$pagination .= "\n";	
	}

	// Link to current page, plus $mid_size pages in either direction if necessary
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' active' : '';
		$pagination .= sprintf( '<li class="page-item%s"><a class="page-link" href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( $link ) ), $link );
		$pagination .= "\n";
	}

	// Link to last page, plus ellipses if necessary
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) ) {
			$pagination .= '<li class="page-item ellipsis disabled"><span class="page-link">' . __( '&hellip;' ) . '</span></li>';
			$pagination .= "\n";
		}
		$class = $paged == $max ? ' class="page-item active"' : '';
		$pagination .= sprintf( '<li%s><a class="page-link" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
		$pagination .= "\n";	
	}

	// Next Post Link
	if ( $prev_next && get_next_posts_link() && $paged <= $max ) {        
		$pagination .= sprintf( '<li class="page-item next"><a class="page-link" href="%s"><span aria-hidden="true">&raquo;</span> <span class="sr-only">' . $next_text . '</span></a></li>' . "\n", get_next_posts_page_link() );		
	} else {
        $pagination .= '<li class="page-item next disabled"><span class="page-link">&raquo;</span></li>';
    }

	$pagination .= "</ul></nav><!-- /.pagination -->\n";
	
	if ( $echo ) {
		echo $pagination;
	} else {
		return $pagination;
	}
}
