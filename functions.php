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
