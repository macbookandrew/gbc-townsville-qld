<?php
// use minified CSS and dequeue 2013 default fonts
function add_custom_css() {
    wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/style.min.css' );
    wp_dequeue_style( 'twentythirteen-style' );

    // fonts
    wp_dequeue_style( 'twentythirteen-fonts' );
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false ); // dependency workaround from https://gist.github.com/thetrickster/8946567
    wp_dequeue_style( 'bitter' );
    wp_enqueue_style( 'gbcnq-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700|Lora:400,400italic,700,700italic' );
}
add_action( 'wp_print_styles', 'add_custom_css' );

// remove script and style version numbers
function script_loader_src_example( $src ) {
    return remove_query_arg( 'ver', $src );
}
add_filter( 'script_loader_src', 'script_loader_src_example' );
add_filter( 'style_loader_src', 'script_loader_src_example' );

include('functions-branding.php');

// tweak search form placeholder
function tweak_search_form_placeholder( $content ) {
    return str_replace( 'Search &hellip;', 'Search&hellip;', $content );
}
add_filter( 'get_search_form', 'tweak_search_form_placeholder' );

// add doctrinal statement JS
function add_doctrinal_statement_JS() {
    wp_register_script( 'doctrinal-statement', get_stylesheet_directory_uri() . '/JS/doctrine.min.js', array( 'jquery' ) );

    if ( get_the_ID() == '8' ) {
        wp_enqueue_script( 'doctrinal-statement' );
    }
}
add_action( 'wp_enqueue_scripts', 'add_doctrinal_statement_JS' );

// add devotionals post type
function daily_devotional() {

	$labels = array(
		'name'                  => 'Devotionals',
		'singular_name'         => 'Devotional',
		'menu_name'             => 'Devotional',
		'name_admin_bar'        => 'Devotional',
		'archives'              => 'Devotional Archives',
		'parent_item_colon'     => 'Parent Devotional:',
		'all_items'             => 'All Devotionals',
		'add_new_item'          => 'Add New Devotional',
		'add_new'               => 'Add New',
		'new_item'              => 'New Devotional',
		'edit_item'             => 'Edit Devotional',
		'update_item'           => 'Update Devotional',
		'view_item'             => 'View Devotional',
		'search_items'          => 'Search Devotional',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this devotional',
		'items_list'            => 'Devotionals list',
		'items_list_navigation' => 'Devotionals list navigation',
		'filter_items_list'     => 'Filter devotionals list',
	);
	$rewrite = array(
		'slug'                  => 'devotional',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => 'Devotionals',
		'description'           => 'Devotional',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'comments', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-book-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
	);
	register_post_type( 'devotional', $args );

}
add_action( 'init', 'daily_devotional', 0 );

// add dates to devotional archives
function twentythirteen_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . esc_html__( 'Sticky', 'twentythirteen' ) . '</span>';

	if ( ! has_post_format( 'link' ) && in_array( get_post_type(), array( 'post', 'devotional' ) ) )
		twentythirteen_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentythirteen' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentythirteen' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links">' . $tag_list . '</span>';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentythirteen' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
