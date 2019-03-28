<?php
// Args
$args = array(
    'post_type'         => array('post'),
    'post_status'       => array('publish'),
    'posts_per_page'    => 10,
    'orderby'           => 'meta_value_num',
    'date_query'        => array(
        array(
            'after'     => '48 hours ago',
            'inclusive' => true,
        ),
    ),
    'meta_key'          => 'mashsb_shares',
);

// Query
$trending = new WP_Query($args);

// Set $query var
$query = array();

// Loop
if($trending->have_posts()) {
    while($trending->have_posts()) {
        $trending->the_post();
        
        // Variables
        $title      = get_the_title();
        $link       = get_permalink();
        $image      = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'klicked-api-regular');
        $thumb      = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'klicked-api-thumb');
        $excerpt    = get_the_excerpt();
        $date       = get_the_date('F jS, Y');
        $rawdate    = get_the_date('YmdHi');
        $site       = get_bloginfo('name');
        $siteurl    = get_bloginfo('url');
        $author     = get_the_author();
        $trend      = get_post_meta(get_the_ID(), 'mashsb_shares', true);
        $bitly      = get_post_meta(get_the_ID(), 'bitly_shortlink', true);
        
        // Checks
        if(empty($excerpt)) {
            $excerpt = wp_trim_words(get_the_content(), 60, '...');
        } else {
            $excerpt = $excerpt;
        }
        
        // Update Bitly
        if(empty($bitly)) {
            $bitly = klicked_api_bitly(get_the_ID());
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
}