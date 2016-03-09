<?php

// AndrewRMinion branding
	// change login page link
	function wpc_url_login(){
		return home_url(); // your URL here
	}
	add_filter( 'login_headerurl', 'wpc_url_login' );

	// customize login page logo
	function login_css() {
		echo "<style type=\"text/css\" media=\"screen\">
		#login h1 a {
			background: url('" . get_bloginfo( 'stylesheet_directory' ) . "/images/logo.png') center center / cover !important;
			width: 450px;
			height: 394px;
			position: relative;
			left: -65px;
			margin-bottom: 15px;
			box-shadow: rgba(200,200,200,0.7) 0 4px 10px -1px;
		}
		</style>";
	}
	add_action( 'login_head', 'login_css' );

	// custom admin footer
	function remove_footer_admin() {
		echo '&copy; ' . date( 'Y' ) . ' by <a href="https://andrewrminion.com/?utm_source=client-site&utm_content=admin-copyright&utm_campaign=GBCNQ.com" target="_blank">AndrewRMinion Design</a>.';
	}
	add_filter( 'admin_footer_text', 'remove_footer_admin' );

	// technical info widget
	function armd_dashboard_widget_function() {
		// Entering the text between the quotes
		echo "<ul>
		<li>Release Date: March 2016</li>
		<li>Developer: <a href=\"https://andrewrminion.com/?utm_source=client-site&utm_content=admin-details&utm_campaign=GBCNQ.com\" target=\"_blank\">AndrewRMinion Design</a></li>
		<li>Hosting provider: AndrewRMinion Design</li>
		</ul>";
	}
	function armd_add_dashboard_widgets() {
		wp_add_dashboard_widget( 'wp_dashboard_widget', 'Technical information', 'armd_dashboard_widget_function' );
	}
	add_action( 'wp_dashboard_setup', 'armd_add_dashboard_widgets' );

// end AndrewRMinion branding

// remove WP dashboard widgets
function remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

}
if (!current_user_can( 'manage_options' )) {
	add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );
}
// end remove WP dashboard widgets

// remove "Private:" from page titles
function armd_title_trim($title) {
	$title = esc_attr($title);
	$needles = array(__('Protected: '),__('Private: '));
	$title = str_replace($needles,'',$title);
	return $title;
}
add_filter( 'protected_title_format','armd_title_trim' );
add_filter( 'private_title_format','armd_title_trim' );
// end remove "Private:" from page titles
