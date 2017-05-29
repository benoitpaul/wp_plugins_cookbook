<?php
/*
Plugin Name: Chapter 2 - Email Page Link
Plugin URI:
Description: Declares a plugin that will be visible in the WordPress admin interface
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca
License: GPLv2
*/

add_filter( 'the_content', 'ch2epl_email_page_filter' );
function ch2epl_email_page_filter ( $the_content ) {
    // build url to mail message icon downloaded from iconarchive.com
    $mail_icon_url = plugins_url( 'mailicon.png', __FILE__ );
    // Set initial value of $new_content variable to previous content
    $new_content = $the_content;

    // Append image with mailto link after content, including the item title and permanent URL
    $new_content .= "<a title='E-mail article link' href='mailto:benoit.paul@gmail.com?subject=Check out this interesting article entitled ";
    $new_content .= get_the_title();
    $new_content .= "&body=Hi!%0A%0AI thought you would enjoy this article entitled ";
    $new_content .= get_the_title();
    $new_content .= ".%0A%0A";
    $new_content .= get_permalink();
    $new_content .= "%0A%0AEnjoy!'> <img alt='' src='" . $mail_icon_url. "' /></a>";
    // Return filtered content for display on the site
    return $new_content;
}
?>