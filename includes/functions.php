<?php
/**
Register API Routes
**/
function klicked_api_endpoint() {
    // Register trending route.
    register_rest_route('klicked/v2', 'trending', array(
        'methods'   => 'GET',
        'callback'  => 'klicked_trending_api_articles',
    ));
    
    // Register latest route.
    register_rest_route('klicked/v2', 'latest', array(
        'methods'   => 'GET',
        'callback'  => 'klicked_latest_api_articles',
    ));
}
add_action('rest_api_init', 'klicked_api_endpoint');

/**
Check for Trending Endpoint
**/
function klicked_trending_api_articles() {
    // Variables
    $folders = array('/api-by-klicked-master\//', '/api-by-klicked\//');
    $replace = array('', '');
    $filedir = preg_replace($folders, $replace, plugin_dir_path(__DIR__));
    
    // Check for trending API endpoint.
    if(is_dir($filedir.'wordpress-popular-posts')) {
        // Get the WordPress Popular Posts query endpoint.
        include(KLICKEDAPI_BASE_PATH.'endpoints/wpp.php');
    } elseif(is_dir($filedir.'easy-social-share-buttons-3.2.4') || is_dir($filedir.'easy-social-share-buttons3') || is_dir($filedir.'easy-social-share-buttons')) {
        // Get the Easy Social Share Buttons query endpoint.
        include(KLICKEDAPI_BASE_PATH.'endpoints/essb.php');
    } elseif(is_dir($filedir.'mashsharer')) {
        // Get MashShare query endpoint.
        include(KLICKEDAPI_BASE_PATH.'endpoints/mashshare.php');
    } else {
        // There aren't any trending endpoints, so let's show the latest posts instead.
        include(KLICKEDAPI_BASE_PATH.'endpoints/latest.php');
    }
    
    // Return the data.
    return $query;
}

/**
Check for Latest Endpoint
**/
function klicked_latest_api_articles() {
    // Get the latest API endpoint.
    include(KLICKEDAPI_BASE_PATH.'endpoints/latest.php');
    
    // Return the data.
    return $query;
}

/**
Create Image Sizes
**/
add_image_size('klicked-api-regular', 690, 360, true, array('center', 'center'));
add_image_size('klicked-api-thumb', 200, 160, true, array('center', 'center'));