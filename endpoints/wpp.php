<?php
// Args
$args = array(
    'post_type'         => 'post',
    'limit'             => 10,
    'order_by'          => 'views',
    'range'             => 'custom',
    'time_unit'         => 'hour',
    'time_quantity'     => 48,
    'freshness'         => 1,
    'thumbnail_width'   => 690,
    'thumbnail_height'  => 360,
);

// Query
$trending = new WPP_query($args);

// Set $query var
$query = array();

// Change $posts from stdClass to array, so that we can loop through it.
$posts = array_map(function($v) {
    return (array) $v;
}, $trending->get_posts());

// Get the posts.
foreach($posts as $post) {
    // Variables
    $title      = get_the_title($post['id']);
    $link       = get_permalink($post['id']);
    $image      = wp_get_attachment_image_src(get_post_thumbnail_id($post['id']), 'klicked-api-regular');
    $thumb      = wp_get_attachment_image_src(get_post_thumbnail_id($post['id']), 'klicked-api-thumb');
    $excerpt    = get_the_excerpt($post['id']);
    $date       = get_the_date('F jS, Y', $post['id']);
    $rawdate    = get_the_date('YmdHi', $post['id']);
    $site       = get_bloginfo('name');
    $siteurl    = get_bloginfo('url');
    $author     = get_the_author_meta('display_name',get_post_field('post_author', $post['id']));
    $trend      = $post['pageviews'];
    $bitly      = get_post_meta($post['id'], 'bitly_shortlink', true);
    
    // Checks
    if(empty($excerpt)) {
        $excerpt = wp_trim_words(get_post_field('post_content', $post['id']), 60, '...');
    } else {
        $excerpt = $excerpt;
    }

    // Update Bitly
    if(empty($bitly)) {
        $bitly = klicked_api_bitly($post['id']);
    }

    // Check Bitly.
    if(empty($bitly)) {
        $link = $link;
    } else {
        $link = $bitly;
    }

    // Add to $query var
    $query[] = array(
        'title'     => $title,
        'link'      => $link,
        'image'     => $image[0],
        'thumb'     => $thumb[0],
        'excerpt'   => $excerpt,
        'author'    => $author,
        'date'      => $date,
        'rawdate'   => $rawdate,
        'site'      => $site,
        'siteurl'   => $siteurl,
        'trend'     => $trend,
    );
}