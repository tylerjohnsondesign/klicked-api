<?php
/**
Publish
**/
function klicked_api_bitly($id) {
    // Check if we already have a shortlink.
    $check = get_post_meta($id, 'bitly_shortlink');
    
    // If we don't have a Bitly link, get one.
    if(empty($check)) {
        // Set the variables.
        $link   = get_permalink($id);
        $key    = 'c325e1b147a68f331e5df400568a9574329ac4f2';
        $guid   = 'Bib2f7EmWyD';
        $bit    = 'https://api-ssl.bitly.com/v4/bitlinks';

        // Compose payload.
        $payload = array(
            'guid'      => $guid,
            'long_url'  => $link,
        );

        // Compose headers.
        $headers = array(
            'Authorization: Bearer c325e1b147a68f331e5df400568a9574329ac4f2',
            'Content-Type: application/json',
        );

        // Start cURL.
        $ch = curl_init();

        // Set cURL options.
        curl_setopt($ch, CURLOPT_URL, $bit);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute cURL and receive the result.
        $raw = curl_exec($ch);

        // Decode the result.
        $result = json_decode($raw, true);

        // Close cURL.
        curl_close($ch);

        // Check if the link has been returned.
        if(empty($result['link'])) {
            update_post_meta($id, 'bitly_error', $raw);
        } else {
            // Store the link for the post.
            update_post_meta($id, 'bitly_shortlink', $result['link']);
            delete_post_meta($id, 'bitly_error');
            return $result['link'];
        }
    }
}