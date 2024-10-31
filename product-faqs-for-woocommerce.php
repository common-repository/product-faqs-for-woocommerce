<?php
/**
 * Plugin Name:       Product FAQ's For WooCommerce
 * Plugin URI:        https://burhandodhy.me/product-faqs-for-woocommerce
 * Description:       Add FAQ's options for the WooCommerce Products.
 * Version:           1.1.4
 * Stable Tag:        1.1.4
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Burhan Nasir
 * Author URI:        https://burhandodhy.me
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       product-faqs-for-woocommerce
 * Domain Path:       /languages
 *
 * @package product-faqs-for-woocommerce
 */

/**
 * Main Class.
 */
class PRODUCT_FAQS_WOO {

	/**
	 * Hold class object.
	 *
	 *  @var Object
	 */
	protected static $instance = null;

	/**
	 * Hold admin class object.
	 *
	 *  @var Object
	 */
	public $admin = null;

	/**
	 * Hold front class object.
	 *
	 * @var Object
	 */
	public $front = null;

	/**
	 * Constructor of class.
	 */
	public function __construct() {
		$this->define_constants();
		$this->load_files();
		$this->admin = new PRODUCT_FAQS_WOO_ADMIN();
		$this->front = new PRODUCT_FAQS_WOO_FRONT();
	}

	/**
	 * Return class instance.
	 *
	 * @return PRODUCT_FAQS_WOO instance
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load all required files.
	 *
	 * @return void
	 */
	protected function load_files() {

		require_once PFFW_DIR_PATH . '/classes/class-product-faqs-woo-admin.php';
		require_once PFFW_DIR_PATH . '/classes/class-product-faqs-woo-front.php';
	}

	/**
	 * Define all constants.
	 *
	 * @return void
	 */
	protected function define_constants() {

		define( 'PFFW_VERSION', '1.1.4' );
		define( 'PFFW_DIR_PATH', plugin_dir_path( __FILE__ ) );
		define( 'PFFW_DIR_URL', plugin_dir_url( __FILE__ ) );
	}
}

PRODUCT_FAQS_WOO::instance();
