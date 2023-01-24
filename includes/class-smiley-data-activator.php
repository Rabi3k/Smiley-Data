<?php

/**
 * Fired during plugin activation
 *
 * @link       https://rab3.ml
 * @since      1.0.0
 *
 * @package    Smiley_Data
 * @subpackage Smiley_Data/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Smiley_Data
 * @subpackage Smiley_Data/includes
 * @author     Rabih <rabih@kbs-leb.com>
 */
class Smiley_Data_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'smiley_data';
	
		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			    `navnelbnr` int(11) NOT NULL,
				`cvrnr` int(11) NOT NULL,
				`pnr` int(11) NOT NULL,
				`brancheKode` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`branche` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`virksomhedstype` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`navn1` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`adresse1` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`postnr` int(11) DEFAULT NULL,
				`By` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				`seneste_kontrol` bit(1) DEFAULT NULL,
				`seneste_kontrol_dato` date DEFAULT NULL,
				`naestseneste_kontrol` bit(1) DEFAULT NULL,
				`naestseneste_kontrol_dato` date DEFAULT NULL,
				`tredjeseneste_kontrol` bit(1) DEFAULT NULL,
				`tredjeseneste_kontrol_dato` date DEFAULT NULL,
				`fjerdeseneste_kontrol` bit(1) DEFAULT NULL,
				`fjerdeseneste_kontrol_dato` date DEFAULT NULL,
				`URL` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				`reklame_beskyttelse` bit(1) DEFAULT NULL,
				`Elite_Smiley` bit(1) DEFAULT NULL,
				`Geo_Lng` decimal(9,6) DEFAULT NULL,
				`Geo_Lat` decimal(9,6) DEFAULT NULL,
				PRIMARY KEY (`navnelbnr`)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
		add_option( 'SMILEY_DATA_VERSION', SMILEY_DATA_VERSION );
		
		if ( ! wp_next_scheduled( 'gpfa_cron_refresh_smiley_data' ) ) {
			wp_schedule_event( strtotime('midnight'), 'daily', 'gpfa_cron_refresh_smiley_data' );
		  }
	}

}


/*
CREATE TABLE IF NOT EXISTS mytable(
  `inavnelbnr` int(11) NOT NULL,
  `cvrnr` int(11) NOT NULL,
  `pnr` int(11) NOT NULL,
  `brancheKode` varchar(11) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `branche` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `virksomhedstype` varchar(6) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `navn1` varchar(31) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `adresse1` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `postnr` int(11) DEFAULT NULL,
  `By` varchar(12) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `seneste_kontrol` bit(1) DEFAULT NULL,
  `seneste_kontrol_dato` date DEFAULT NULL,
  `naestseneste_kontrol` int(11) DEFAULT NULL,
  `naestseneste_kontrol_dato` date DEFAULT NULL,
  `tredjeseneste_kontrol` bit(1) DEFAULT NULL,
  `tredjeseneste_kontrol_dato` date DEFAULT NULL,
  `fjerdeseneste_kontrol` bit(1) DEFAULT NULL,
  `fjerdeseneste_kontrol_dato` date DEFAULT NULL,
  `URL` varchar(69) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `reklame_beskyttelse` bit(1) DEFAULT NULL,
  `Elite_Smiley` bit(1) DEFAULT NULL,
  `Geo_Lng` decimal(9,6) DEFAULT NULL,
  `Geo_Lat` decimal(9,6) DEFAULT NULL,
  PRIMARY KEY (`inavnelbnr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
);
 */