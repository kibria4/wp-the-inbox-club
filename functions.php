<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once __DIR__ . '/vendor/autoload.php';

// Instantiate your theme bootstrap
$theme = \Boogiewoogie\Theme\Bootstrap::init();


//Enable/disable core features
add_filter('boogiewoogie_core_features', function (array $features): array {
    // Turn off GDPR tooling if this project doesn’t need it yet
    // $features[ \Boogiewoogie\Core\Config\CoreFeatures::GDPR ] = false;

    // Turn off health check pings
    // $features[ \Boogiewoogie\Core\Config\CoreFeatures::HEALTH_CHECK ] = false;

    // Remove optimisation + security off (explicitly)
    // $features[ \Boogiewoogie\Core\Config\CoreFeatures::SECURITY ] = false;
    // $features[ \Boogiewoogie\Core\Config\CoreFeatures::OPTIMISATION ] = false;

    return $features;
});


$postsEndpoint = new \Boogiewoogie\Theme\Api\PostsEndpoint();
$postsEndpoint->register();

add_action('wp_enqueue_scripts', function(){
    if(is_admin()) {
        return;
    }
    
	wp_dequeue_style('classic-theme-styles');
	wp_dequeue_style('global-styles');
	wp_dequeue_style('wp-block-library');	
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('core-block-supports');
}, 99999);