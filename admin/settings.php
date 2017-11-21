<?php
/**
 * Registers and manages the setting for the input field position.
 *
 * The setting is named `wp_subtitle_field_location` and is stored
 * in the options table. If the setting's value is "above_main", the
 * input field will be rendered above the main title field instead
 * below it.
 *
 * @return void
 */
function wp_subtitle_settings_init() {
    add_settings_section(
        'wp_subtitle_setting_section',
        __( 'WP Subtitle Settings', 'wp-subtitle' ), // section title, will be rendered
        function () {
            echo '<p>' . __("It's possible to move the subtitle input field above the main title field by activating the checkbox", 'wp-subtitle' ) . '</p>';
        },
        'writing'
    );

    add_settings_field(
        'wp_subtitle_field_location',
        __( 'Position of input field', 'wp-subtitle' ), // field description, will be rendered
        function () {
            echo '<input name="wp_subtitle_field_location" id="wp_subtitle_field_location" type="checkbox" value="above_main" '
                 . checked( 'above_main', get_option( 'wp_subtitle_field_location' ), false )
                 . '>';
            echo '<label for="wp_subtitle_field_location">' . __('Move input field above main title', 'wp-subtitle' ) . '</label>';
        },
        'writing',
        'wp_subtitle_setting_section'
    );

    register_setting( 'writing', 'wp_subtitle_field_location' );
}

add_action( 'admin_init', 'wp_subtitle_settings_init' );
