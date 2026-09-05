<?php
/**
 * Plugin Name: Bettycoder Privacy Hardening
 * Description: Small privacy/security hardening plugin for a personal WordPress blog.
 * Version: 1.0.0
 * Author: Bettycoder
 * License: MIT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * 1) Block unauthenticated WordPress REST API user enumeration.
 * Logged-in users keep normal REST access so the editor/admin area is less likely to break.
 */
add_filter( 'rest_pre_dispatch', function ( $result, $server, $request ) {
    if ( is_user_logged_in() ) {
        return $result;
    }

    $route = $request->get_route();

    if ( preg_match( '#^/wp/v2/users(?:/|$)#', $route ) ) {
        return new WP_Error(
            'rest_forbidden',
            'Not available.',
            array( 'status' => 403 )
        );
    }

    return $result;
}, 10, 3 );

/*
 * 2) Disable public author archives.
 * This blocks common ?author=1 enumeration and /author/username/ pages.
 */
add_action( 'template_redirect', function () {
    if ( is_author() ) {
        global $wp_query;

        $wp_query->set_404();
        status_header( 404 );
        nocache_headers();

        $template = get_404_template();

        if ( $template ) {
            include $template;
        }

        exit;
    }
}, 0 );

/*
 * Keep generated author links from pointing to a public author archive.
 */
add_filter( 'author_link', function ( $link ) {
    return home_url( '/' );
} );

/*
 * 3) Remove users from the WordPress XML sitemap.
 */
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
    if ( 'users' === $name ) {
        return false;
    }

    return $provider;
}, 10, 2 );

/*
 * 4) Disable XML-RPC completely.
 * xmlrpc_enabled alone only disables authenticated methods, so XML-RPC requests
 * are blocked and the exposed XML-RPC method list is removed as well.
 */
add_action( 'init', function () {
    if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
        status_header( 403 );
        header( 'Content-Type: text/plain; charset=UTF-8' );
        exit( 'XML-RPC disabled.' );
    }
}, 0 );

add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );

/*
 * Disable pingbacks/trackbacks and remove the X-Pingback header.
 */
add_filter( 'pings_open', '__return_false', 20, 2 );

add_filter( 'wp_headers', function ( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

/*
 * 5) Remove common WordPress fingerprinting metadata.
 */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

/*
 * 6) Make login errors generic so the login page gives less help with username guessing.
 */
add_filter( 'login_errors', function () {
    return 'Login failed.';
} );

/*
 * 7) Conservative privacy/security headers.
 */
add_action( 'send_headers', function () {
    if ( ! headers_sent() ) {
        header( 'X-Content-Type-Options: nosniff' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        header( 'Permissions-Policy: geolocation=(), camera=(), microphone=()' );
        header( 'X-Frame-Options: SAMEORIGIN' );
    }
} );
