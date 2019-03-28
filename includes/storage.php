<?php
/**
Data Update
**/
function klicked_api_update() {
    delete_transient('revere_check_update');
    // Items to check.
    $storage = array(
        'revere'    => array(
            'name'  => 'revere',
            'url'   => 'https://reverereport.com/wp-json/widget/v2/',
            'type'  => 'widget',
        ),
    );
    
    // Run through the items.
    foreach($storage as $stored) {
        // Check if we should update.
        $update = get_transient($stored['name'].'_check_update');
        
        if(empty($update)) {
            // Use cURL.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $stored['url'].$stored['type']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $raw = curl_exec($ch);
            curl_close($ch);

            // Store the data.
            update_option($stored['name'].'_articles', $raw, true);

            // Set the transient.
            set_transient($stored['name'].'_check_update', '1', 600);  
        } else {
            // Do nothing at all bro.
        }
    }
}
add_action('init', 'klicked_api_update');