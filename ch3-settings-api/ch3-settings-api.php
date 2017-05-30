<?php
/*
Plugin Name: Chapter 3 – Settings API
Plugin URI:
Description: Declares a plugin that will be visible in the WordPress admin interface
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca
License: GPLv2
*/

define( "VERSION", "1.1" );

register_activation_hook( __FILE__, 'ch3sapi_set_default_options' );
function ch3sapi_set_default_options() {
    if ( get_option( 'ch3sapi_options' ) === false ) {
        $new_options['ga_account_name'] = "UA-000000-0";
        $new_options['track_outgoing_links'] = false;
        $new_options['version'] = VERSION;
        add_option( 'ch3sapi_options', $new_options );
    }
}

add_action( 'admin_init', 'ch3sapi_admin_init' );
function ch3sapi_admin_init() {
    // Register a setting group with a validation function so that post data handling is done automatically for us
    register_setting( 
        'ch3sapi_settings',         // unique id
        'ch3sapi_options',          // options array
        'ch3sapi_validate_options'  // sanitize function
    );

    // Add a new settings section within the group
    add_settings_section( 
        'ch3sapi_main_section',     // unique id
        'Main Settings',            // display text
        'ch3sapi_main_setting_section_callback', // callback to display desccription
        'ch3sapi_settings_section' // page id
    );

    // Add each field with its name and function to use for our new settings, put them in our new section
    add_settings_field( 
        'ga_account_name',              // unique id
        'Account Name',                 // label
        'ch3sapi_display_text_field',   // function that outputs the field's html
        'ch3sapi_settings_section',     // page that the field belongs to
        'ch3sapi_main_section',         // parent section
        array( 'name' => 'ga_account_name' ) ); // array of additional data

    add_settings_field( 'track_outgoing_links', 'Track Outgoing Links',
        'ch3sapi_display_check_box',
        'ch3sapi_settings_section',
        'ch3sapi_main_section',
        array( 'name' => 'track_outgoing_links' ) );
    /*
    add_settings_field( 'Select_List', 'Select List',
        'ch3sapi_select_list',
        'ch3sapi_settings_section', 'ch3sapi_main_section',
        array( 'name' => 'Select_List',
        'choices' => array( 'First', 'Second', 'Third' ) ) );
    */
}

function ch3sapi_validate_options( $input ) {
    $input['version'] = VERSION;
    return $input;
}

function ch3sapi_main_setting_section_callback() { ?>
    <p>This is the main configuration section.</p>
<?php }

function ch3sapi_display_text_field( $data = array() ) {
    extract( $data );
    $options = get_option( 'ch3sapi_options' );
    ?>
        <input type="text" 
            name="ch3sapi_options[<?php echo $name;?>]" 
            value="<?php echo esc_html( $options[$name] );?>"/>
        <br />
<?php }

function ch3sapi_display_check_box( $data = array() ) {
    extract ( $data );
    $options = get_option( 'ch3sapi_options' );
    ?>
        <input type="checkbox"
            name="ch3sapi_options[<?php echo $name; ?>]"
            <?php if ( $options[$name] ) echo ' checked="checked"';?>/>
<?php }

function ch3sapi_display_text_area( $data = array() ) {
    extract ( $data );
    $options = get_option( 'ch3sapi_options' );
    ?>
    <textarea type="text"
        name="ch3sapi_options[<?php echo $name; ?>]"
        rows="5" cols="30">
        <?php echo esc_html ( $options[$name] ); ?>
    </textarea>
<?php }
/*
function ch3sapi_select_list( $data = array() ) {
    extract ( $data );
    $options = get_option( 'ch3sapi_options' );
    ?>
        <select name="ch3sapi_options[<?php echo $name; ?>]'>
        <?php foreach( $choices as $item ) { ?>
            <option value="<?php echo $item; ?>"
                <?php selected( $options[$name] == $item ); ?>>
                <?php echo $item; ?>
            </option>;
        <?php } ?>
        </select>
<?php }
*/

add_action( 'admin_menu', 'ch3sapi_settings_menu' );
function ch3sapi_settings_menu() {
    add_options_page( 'My Google Analytics Configuration',
    'My Google Analytics - Settings API', 'manage_options',
    'ch3sapi-my-google-analytics',
    'ch3sapi_config_page' );
}

function ch3sapi_config_page() { ?>
    <div id="ch3sapi-general" class="wrap">
    <h2>My Google Analytics – Settings API</h2>
    <form name="ch3sapi_options_form_settings_api" method="post" action="options.php">
        <?php settings_fields( 'ch3sapi_settings' ); ?>
        <?php do_settings_sections( 'ch3sapi_settings_section' ); ?>
        <input type="submit" value="Submit" class="button-primary" />
    </form>
    </div>
<?php }

?>