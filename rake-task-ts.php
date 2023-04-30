<?php

/*  Plugin Name: Rake Task TS
    Plugin URI:
    Description: Rake Task TS
    Version: 1.0
    Author: Tiago Santos
    Author URI: https://github.com/forbee-dev
    License: GPLv2 or later
*/     

// Exit if accessed directly.
/* if ( ! defined( 'ABSPATH' ) ) {
    exit; 
} */

// Add Shortcode
add_shortcode('rake_task_ts', 'rake_task_ts_shortcode');

// Load CSS 
function load_css() {
    wp_register_style('rake-style', plugin_dir_url(__FILE__) . 'css/rake-task.css');
    wp_enqueue_style('rake-style');
}
add_action( 'wp_enqueue_scripts', 'load_css' );

// Load API
function rake_task_ts_shortcode($atts) {
    $url = 'https://b9247f49-0f6a-4af7-a447-47dbb5bf059d.mock.pstmn.io/';
    $response = wp_remote_get($url);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);


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
            $html .= '<tr>';
                $html .= '<td><p>LOGO</p><p>SITE_URL</p></td>';
                $html .= '<td><p>RATING</p><p>BONUS</p></td>';
                $html .= '<td>Features</td>';
                $html .= '<td><p>play-url</p><p>Terms_and_conditions</p></td>';
            $html .= '</tr>';
        $html .= '</tbody>';
    $html .= '</table>';

    return $html;

}

?>

