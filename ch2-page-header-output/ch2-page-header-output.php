<?php
/*
Plugin Name: Chapter 2 - Plugin Header Output
Plugin URI:
Description: Declares a plugin that will be visible in the WordPress admin interface
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca
License: GPLv2
*/

add_action( 'wp_head', 'ch2pho_page_header_output' );
function ch2pho_page_header_output() { ?>
    <script type="text/javascript">
        var gaJsHost = ( ( "https:" == document.location.protocol ) ? "https://ssl." : "http://www." );
        document.write( unescape( "%3Cscript src='" + gaJsHost +  "google-analytics.com/ga.js' \n\ type='text/javascript'%3E%3C/script%3E" ) );
    </script>
    <script type="text/javascript">
        try {
            var pageTracker = _gat._getTracker( "UA-xxxxxx-x" );
            pageTracker._trackPageview();
            } catch( err ) {}
    </script>
<?php }

add_filter( 'the_content', 'ch2lfa_link_filter_analytics' );
function ch2lfa_link_filter_analytics ( $the_content ) {
    $new_content = str_replace( "href", "onClick=\"recordOutboundLink(this, 'Outbound Links', '" . home_url() . "' );return false;\" href", $the_content );
    return $new_content;
}

add_action( 'wp_footer', 'ch2lfa_footer_analytics_code' );
function ch2lfa_footer_analytics_code() { ?>
    <script type="text/javascript">
    function recordOutboundLink( link, category, action ) {
        _gat._getTrackerByName()._trackEvent( category, action );
        setTimeout( 'document.location = "' + link.href + '"', 100 );
    }
    </script>
<?php }

register_activation_hook( __FILE__, 'ch2pho_set_default_options_array' );
function ch2pho_set_default_options_array() {
    if ( get_option( 'ch2pho_options' ) === false ) {
        $new_options['ga_account_name'] = "UA-000000-0";
        $new_options['track_outgoing_links'] = false;
        $new_options['version'] = "1.1";
        add_option( 'ch2pho_options', $new_options );
    } else {
        $existing_options = get_option( 'ch2pho_options' );
        if ( $existing_options['version'] < 1.1 ) {
            $existing_options['track_outgoing_links'] = false;
            $existing_options['version'] = "1.1";
            update_option( 'ch2pho_options', $existing_options );
        }
    }
}

?>