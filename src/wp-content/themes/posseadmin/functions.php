<?php
/**
 * Posse theme functions and definitions
 *
 */


require get_template_directory() . '/inc/posse-ms-route.php';
add_filter('query_vars', 'posse_custom_query_vars');
add_action('init', 'posse_theme_functionality_urls');
add_action('parse_request', 'posse_custom_requests');
