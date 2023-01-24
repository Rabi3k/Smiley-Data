<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rab3.ml
 * @since             1.0.0
 * @package           Smiley_Data
 *
 * @wordpress-plugin
 * Plugin Name:       Smiley Data
 * Plugin URI:        https://rabi3.ml/smiley-data.zip
 * Description:       get smiley data for dk restaurants
 * Version:           1.0.0
 * Author:            Rabih
 * Author URI:        https://rab3.ml
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       smiley-data
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SMILEY_DATA_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smiley-data-activator.php
 */
function activate_smiley_data() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smiley-data-activator.php';
	Smiley_Data_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smiley-data-deactivator.php
 */
function deactivate_smiley_data() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smiley-data-deactivator.php';
	Smiley_Data_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_smiley_data' );
register_deactivation_hook( __FILE__, 'deactivate_smiley_data' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-smiley-data.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_smiley_data() {

	$plugin = new Smiley_Data();
	$plugin->run();

}
run_smiley_data();




// Function to execute the query and return the result
function get_smiley_data( $atts ) {
    global $wpdb;
    global $post, $gd_post;
    // Sanitize shortcode attributes
    $condition = shortcode_atts( array(
        'pnr' => ! empty( $gd_post->pnr ) ? absint( $gd_post->pnr ) : 0,
    ), $atts );
    $pnr = sanitize_text_field( $condition['pnr'] );
    
    // Prepare the query to prevent SQL injection
    $query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}smiley_data WHERE pnr = %s", $pnr );
    // Execute the query and check for errors
    $results = $wpdb->get_row( $query );
    if ( $results === null ) {
        return "<p>No results found.</p>";
    } elseif ( $wpdb->last_error !== '' ) {
        return "<p>An error occurred: " . $wpdb->last_error . "</p>";
    } else {
        return "<div>
        <a href='{$results->URL}' target='_blank' rel='nofollow'>
<img src='/wp-includes/images/Smiley/{$results->seneste_kontrol}Smiley.png' title='' alt='' loading='lazy'><span>{$results->seneste_kontrol_dato}</span> </a>
        </div>";
    }
}

// Shortcode to execute the query
add_shortcode( 'smiley_data', 'get_smiley_data' );

/*
[smiley_data]
*/