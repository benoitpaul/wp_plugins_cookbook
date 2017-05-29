<?php
/*
Plugin Name: Chapter 2 - Favicon
Plugin URI:
Description: Declares a plugin that will be visible in the WordPress admin interface
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca
License: GPLv2
*/

add_action( 'wp_head', 'ch2fi_page_header_output' );
function ch2fi_page_header_output() {
    $plugins_url = plugins_url();
    $icon_url1 = plugins_url( 'favicon.ico');
    $icon_url = plugins_url( 'favicon.ico', __FILE__ );
    ?>
        <link rel="shortcut icon" href="<?php echo $icon_url; ?>" type="image/x-icon" />
    <?php 
}
?>