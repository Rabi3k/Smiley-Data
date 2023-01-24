<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://rab3.ml
 * @since      1.0.0
 *
 * @package    Smiley_Data
 * @subpackage Smiley_Data/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Smiley_Data
 * @subpackage Smiley_Data/includes
 * @author     Rabih <rabih@kbs-leb.com>
 */
class Smiley_Data_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'smiley_data';
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		delete_option( 'SMILEY_DATA_VERSION' );
	}

}
