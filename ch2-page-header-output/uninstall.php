<?php
// Check that code was called from WordPress withuninstallation constant declared
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit;
// Check if options exist and delete them if present
if ( get_option( 'ch2pho_options' ) != false ) {
    delete_option( 'ch2pho_options' );
}
?>