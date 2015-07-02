<?php
/*I want this to display after the content for either posts or pages. Successfully used the genesis_after_post_content (takes the dotted underline styling of content) and genesis_post_content (doesn't have the dotted underlines) hooks in Simple Hooks, but thought you might want to do something that's not Genesis dependent. Your choice. */

/*I only want this part to appear on PAGES*/
add_action( 'init', 'add_umw_report_info' );
function add_umw_report_info() {
	if ( function_exists( 'umw_do_footer' ) ) {
		add_filter( 'genesis_structural_wrap-umw-footer-content', 'umw_official_report_a_problem', 11, 2 );
		add_filter( 'genesis_structural_wrap-umw-footer-content', 'umw_official_shortcode_instructions', 10, 2 );
	} else if ( function_exists( 'genesis' ) ) {
		add_action( 'genesis_footer', 'umw_report_a_problem' );
		add_action( 'genesis_footer', 'umw_shortcode_instructions' );
	} else {
		add_filter( 'wp_footer', 'get_umw_report_a_problem', 11 );
		add_filter( 'wp_footer', 'get_umw_shortcode_instructions', 12 );
	}
}

function umw_official_report_a_problem( $output='', $original_output='' ) {
	if ( 'close' != $original_output )
		return $output;
	
	return get_umw_report_a_problem( '' ) . $output;
}

function umw_official_shortcode_instructions( $output='', $original_output='' ) {
	if ( 'close' != $original_output )
		return $output;
	
	return get_umw_shortcode_instructions( '' ) . $output;
}

function umw_report_a_problem() {
	if ( ! is_page() || ! is_main_query() )
		return;
		
	echo get_umw_report_a_problem( '' );
}

function get_umw_report_a_problem( $content='' ) {
	if ( ! is_singular() || ! is_main_query() )
		return $content;
	
	$weburl = get_blog_option( 3981, 'siteurl', '/' );
	$formslug = 'report-a-problem';
	$text = sprintf( __( '<div id="reportProblem" style="clear:both; padding-top: 20px">Outdated? Incorrect? Broken? <a href="%s" title="Tell the web staff about problems with the website.">Report a problem with this page</a>.</div>' ), trailingslashit( trailingslashit( $weburl ) . $formslug ) . '?ref=' . urlencode( get_permalink() ) );
	return $content . $text;
}

/*I want this to appear on posts and pages if the user is logged in*/
function umw_shortcode_instructions() {
	if ( ! is_user_logged_in() || ! is_singular() || ! is_main_query() )
		return;
	
	echo get_umw_shortcode_instructions( '' );
}

function get_umw_shortcode_instructions( $content='' ) {
	if ( ! is_user_logged_in() || ! is_singular() || ! is_main_query() )
		return $content;
	
	$text = sprintf( __( '<div id="loggedInUser">
<div id="modified" style="clear:both; padding-top: 10px">Originally published: %3$s. Last Modified: %4$s<!-- by %5$s -->.</div>
</div>' ), $GLOBALS['blog_id'], get_the_ID(), get_the_time('F j, Y'), get_the_modified_date( 'F j, Y' ), get_the_modified_author() );
	return $content . $text;
}