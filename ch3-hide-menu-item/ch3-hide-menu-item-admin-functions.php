<?php

add_action( 'admin_menu', 'ch3hmi_hide_menu_item' );
function ch3hmi_hide_menu_item() {
    remove_menu_page( 'link-manager.php' );
    remove_submenu_page( 'options-general.php',
        'options-permalink.php' );
}
?>