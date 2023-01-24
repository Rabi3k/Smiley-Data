<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rab3.ml
 * @since      1.0.0
 *
 * @package    Smiley_Data
 * @subpackage Smiley_Data/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Smiley_Data
 * @subpackage Smiley_Data/admin
 * @author     Rabih <rabih@kbs-leb.com>
 */
class Smiley_Data_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smiley_Data_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smiley_Data_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/smiley-data-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smiley_Data_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smiley_Data_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/smiley-data-admin.js', array('jquery'), $this->version, false);
	}

	public function smiley_data_admin_menu()
	{
		//add_menu_page( page_title, menu_title, capability, menu_slug, function, icon_url, position )
		add_menu_page($this->plugin_name, $this->plugin_name, 'manage_options', 'smiley-data', array(
			$this, 'smiley_data_admin_page'
		), 'dashicons-admin-generic', 20);
		// add_menu_page(
		// 	'Smiley Data',
		// 	'Smiley Data',
		// 	'manage_options',
		// 	'smiley-data',
		// 	'smiley_data_admin_page',
		// 	'dashicons-admin-generic',
		// 	20
		// );
	}

	public function smiley_data_admin_page()
	{
?>
		<div class="wrap">
			<h1>Smiley Data</h1>
			<form method="post">
				<?php wp_nonce_field('smiley_data_run', 'smiley_data_nonce'); ?>
				<input type="submit" class="button-primary" name="smiley_data_submit" value="Run Function">
			</form>
		</div>
<?php
		if (isset($_POST['smiley_data_submit']) && wp_verify_nonce($_POST['smiley_data_nonce'], 'smiley_data_run')) {
			$this->smiley_data_run_function();
		}
	}
	public function update_data_in_the_background()
	{
		$this->smiley_data_run_function();
	}
	public function smiley_data_run_function()
	{
		// Function code goes here
		echo "Function has been run.";
		global $wpdb;


		$table_name = $wpdb->prefix . 'smiley_data';
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://www.foedevarestyrelsen.dk/_layouts/15/sdata/smiley_xml.xml',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);

		//$xml = simplexml_load_string ($response);

		$xml = simplexml_load_string($response);

		//var_dump($xml_string);
		$error_messages = array();
		$affected_rows = 0;

		echo "<p>delete data from table $table_name</p>";
		$delete = $wpdb->query("DELETE FROM  $table_name");
		//echo "$delete.";
		foreach ($xml->row as $item) {
			//$wpdb->delete($table_name);
			if ($item->pnr && $item->pnr != null && !empty($item->pnr)) {
				//echo "<p>insert 'pnr' => $item->pnr.</p>";
				$a = array(
					'navnelbnr' => (int)$item->navnelbnr,
					'cvrnr' => (int)$item->cvrnr,
					'pnr' => (int)$item->pnr,
					'brancheKode' => (string)$item->brancheKode,
					'branche' => (string)$item->branche,
					'virksomhedstype' => (string)$item->virksomhedstype,
					'navn1' => (string)$item->navn1,
					'adresse1' => (string)$item->adresse1,
					'postnr' => (int)$item->postnr,
					'By' => (string)$item->By,
					'seneste_kontrol' => (int)$item->seneste_kontrol,
					'seneste_kontrol_dato' => date("Y-m-d", strtotime($item->seneste_kontrol_dato)),
					'naestseneste_kontrol' => (int)$item->naestseneste_kontrol,
					'naestseneste_kontrol_dato' => date("Y-m-d", strtotime($item->naestseneste_kontrol_dato)),
					'tredjeseneste_kontrol' => (int)$item->tredjeseneste_kontrol,
					'tredjeseneste_kontrol_dato' => date("Y-m-d", strtotime($item->tredjeseneste_kontrol_dato)),
					'fjerdeseneste_kontrol' => (int)$item->fjerdeseneste_kontrol,
					'fjerdeseneste_kontrol_dato' => date("Y-m-d", strtotime($item->fjerdeseneste_kontrol_dato)),
					'URL' => (string)$item->URL,
					'reklame_beskyttelse' => (int)$item->reklame_beskyttelse,
					'Elite_Smiley' => (int)$item->Elite_Smiley,
					'Geo_Lng' => (float)$item->Geo_Lng,
					'Geo_Lat' => (float)$item->Geo_Lat
				);
				if (!$wpdb->insert($table_name, $a)) {
					$pnr = (int)$item->pnr;
					$str = $wpdb->last_error;

					$dt = json_encode($a);
					$error_messages[] = array(
						'pnr' => $pnr,
						'error_message' => $str,
						'class_data' => $dt
					);
				} else {
					//echo "success";
					$affected_rows += 1;
				}
			}
		}
		echo "<p>{$affected_rows} row(s) affected, " . count($error_messages) . " error(s)</p>";
		if (count($error_messages) > 0) {
			foreach ($error_messages as $msg) {
				echo "<p>navnelbnr' => (int)$msg->navnelbnr, 'error_message' => $msg->error_message</p>";
			}
		}
	}
}
