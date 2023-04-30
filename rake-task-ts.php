<?php

/*  Plugin Name: Rake Task TS
    Description: Rake Task Tiago Santos
    Version: 1.0
    Author: Tiago Santos
    Author URI: https://github.com/forbee-dev
    License: GPLv2 or later
*/     

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// Add Shortcode
add_shortcode('rake_task_ts', 'rake_task_ts_shortcode');

// Load CSS 
function rake_task_ts_load_css() {
    wp_register_style('rake-style', plugin_dir_url(__FILE__) . 'css/rake-task.css');
    wp_enqueue_style('rake-style');
}
add_action( 'wp_enqueue_scripts', 'rake_task_ts_load_css' );

// Load API
function rake_task_ts_shortcode() {
    $url = 'https://b9247f49-0f6a-4af7-a447-47dbb5bf059d.mock.pstmn.io/';
    $response = wp_remote_get($url);

// $response Error Handling
    if (is_wp_error($response)) {
        return 'Error retrieving data. Please try again later.';
    }

    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
        return 'Error retrieving data. HTTP response code: ' . $response_code;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

// $data Error Handling
    if (is_null($data)) {
        return 'Error decoding JSON data.';
    }

// Get Reviews Data filtered by "575" key
    $reviews_data = $data['toplists']['575'];

// Sort by "Position"
    usort($reviews_data, function($a, $b) {
        return $a['position'] <=> $b['position'];
    });

    //var_dump($reviews_data);

// HTML for the Table    
    $html = '<table>';
        $html .= '<thead>';
            $html .= '<tr>';
                $html .= '<th>Casino</th>';
                $html .= '<th>Bonus</th>';
                $html .= '<th>Features</th>';
                $html .= '<th>Play</th>';
            $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        foreach ($reviews_data as $item) {
            $html .= '<tr>';
                $html .= '<td data-cell="casino"><a href="' . site_url() . '/' . $item['brand_id'] . '"><img src="' . $item['logo'] . '"/></a><p><a href="' . site_url() . '/' . $item['brand_id'] . '">Reviews</a></p></td>';
// Rating Stars
                $rating = $item['info']['rating'];
                $full_stars = floor($rating);
                $half_stars = ceil($rating - $full_stars);
                $empty_stars = 5 - $full_stars - $half_stars;
                $html .= '<td data-cell="bonus"><p>';
                for ($i = 0; $i < $full_stars; $i++) {
                    $html .= '<img src="' . plugin_dir_url(__FILE__) . 'assets/full_rating_star_icon.png' . '" />';
                }
                for ($i = 0; $i < $half_stars; $i++) {
                    $html .= '<img src="' . plugin_dir_url(__FILE__) . 'assets/rating_star_half_icon.png' . '" />';
                }
                for ($i = 0; $i < $empty_stars; $i++) {
                    $html .= '<img src="' . plugin_dir_url(__FILE__) . 'assets/empty_rating_star_icon.png' . '" />';
                }
// End of Rating Stars 
                $html .= '</p><p>' . $item['info']['bonus'] . '</p></td>';
                $features = $item['info']['features'];
                $html .= '<td data-cell="features">';
                    $html .= '<ul>';
                    foreach ($features as $feature) {
                        $html .= '<li>' . $feature . '</li>';
                    }  
                    $html .= '</ul>';
                $html .= '</td>';
                $html .= '<td data-cell="play"><button onclick="window.open(\'' . $item['play_url'] . '\')">Play Now</button><p>' . $item['terms_and_conditions'] . '</p></td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
    $html .= '</table>';

    return $html;

} 

?>

