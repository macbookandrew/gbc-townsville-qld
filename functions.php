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

// add date-based archives for devotionals
add_filter( 'getarchives_where', 'getarchives_where_filter', 10, 2 );
add_filter( 'generate_rewrite_rules', 'generate_devotionals_rewrite_rules' );

function getarchives_where_filter( $where, $args ) {
    if ( isset($args['post_type']) ) {
        $where = "WHERE post_type = '$args[post_type]' AND post_status = 'publish'";
    }
    return $where;
}

function generate_devotionals_rewrite_rules( $wp_rewrite ) {
    $devotional_rules = array(
        'devotional/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$' => 'index.php?post_type=devotional&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]',
        'devotional/([0-9]{4})/([0-9]{1,2})/?$' => 'index.php?post_type=devotional&year=$matches[1]&monthnum=$matches[2]',
        'devotional/([0-9]{4})/?$' => 'index.php?post_type=devotional&year=$matches[1]'
    );
    $wp_rewrite->rules = $devotional_rules + $wp_rewrite->rules;
}

function get_archives_devotionals_link( $link ) {
    return str_replace( get_site_url(), get_site_url() . '/devotional', $link );
};

// add custom header fixes for mobile
function custom_header_styles() { ?>
    <style type="text/css">
        .site-header { background: none !important; }
        .home-link {
            background: url(<?php header_image(); ?>) no-repeat scroll top;
            background-size: cover;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'custom_header_styles', 15 );

// increase header height
function yourchildtheme_custom_header_setup() {
    $args = array( 'height' => 400 );
    add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'yourchildtheme_custom_header_setup' );

// add favicons
function add_favicons() {
    echo '<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#f7f5e7">';
}
add_action( 'wp_head', 'add_favicons' );
