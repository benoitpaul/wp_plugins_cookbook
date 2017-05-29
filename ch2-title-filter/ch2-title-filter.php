<?php
/*
Plugin Name: Chapter 2 - Title Filter
Plugin URI:
Description: Declares a plugin that will be visible in the WordPress admin interface
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca
License: GPLv2
*/

add_filter( 'document_title_parts', 'dq_override_post_title' );
function dq_override_post_title ( $title ) {
    //Select new title based on item type
    if ( is_front_page() )
        $new_title = 'Front Page >> ';
    elseif ( get_post_type() == 'page' )
        $new_title = 'Page >> ';
    elseif ( get_post_type() == 'post' )
        $new_title = 'Post >> ';
    // Append previous title to title prefix
    if ( isset( $new_title ) ) {
        $new_title .= $title['title'];
        $title['title'] = $new_title;
    } 
    return $title;
}
?>