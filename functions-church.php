<?php

// Add soundboard custom role
function add_soundboard_role() {
	// Add the new role.
	$soundboard_role = get_role( 'soundboard' );
	// create if neccesary
	if ( !$soundboard_role ) { $soundboard_role = add_role('soundboard', 'Soundboard', array (
		'delete_posts' => true,
		'delete_others_posts' => true,
		'delete_published_posts' => true,
		'edit_posts' => true,
		'edit_others_posts' => true,
		'edit_published_posts' => true,
		'publish_posts' => true,
		'read' => true,
		'upload_files' => true,
		'manage_options' => true
	));}
}

add_action( 'admin_init', 'add_soundboard_role' );
// end Add soundboard custom role


// Add church member custom role
function add_church_member_role() {
	// Add the new role.
	$church_member_role = get_role( 'church_member' );
	// create if neccesary
	if ( !$church_member_role ) { $church_member_role = add_role( 'church_member', 'Church Member', array (
		'read' => true,
		'read_private_pages' => true
	)); }
}

add_action( 'admin_init', 'add_church_member_role' );
// end Add church member custom role


// hide unnecessary options from users
global $user_login;
get_currentuserinfo();

if ( $user_login == 'soundboard' ) {

	// hide some admin menu options
	function my_remove_menu_pages() {
		remove_menu_page( 'upload.php' );                 // Media
		remove_menu_page( 'edit-comments.php' );          // Comments
		remove_menu_page( 'edit.php?post_type=slide' );   // Slides
		remove_menu_page( 'options-general.php' );        // Settings
		remove_menu_page( 'tools.php' );                  // Tools
		remove_menu_page( 'edit.php' );                   // Posts
	}
	add_action( 'admin_menu', 'my_remove_menu_pages' );

}
