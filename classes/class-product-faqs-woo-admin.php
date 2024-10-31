<?php
/**
 * File handles backend logic.
 *
 *  @package Woo Product Faqs
 */

/**
 * Class that handle all the admin panel logic.
 */
class PRODUCT_FAQS_WOO_ADMIN {

	/**
	 * Constructor of class.
	 */
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'add_script' ) );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_tab' ) );
		add_filter( 'woocommerce_product_data_panels', array( $this, 'add_panel' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_faqs' ) );
	}

	/**
	 * Enqueue scripts.
	 */
	public function add_script() {

		$asset_file = include PFFW_DIR_PATH . 'build/admin.asset.php';
		wp_enqueue_script(
			'woo-product-faqs',
			PFFW_DIR_URL . 'build/admin.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		wp_enqueue_style( 'woo-product-faqs', PFFW_DIR_URL . 'build/admin.css', array(), PFFW_VERSION );
	}

	/**
	 * Add tab in product page.
	 *
	 * @param array $tabs All registered woocommerce tabs.
	 * @return array
	 */
	public function add_tab( $tabs ) {

		$tabs['woo_product_faqs'] = array(
			'label'  => 'FAQs',
			'target' => 'woo-product-faqs-panel',
		);

		return $tabs;
	}

	/**
	 * Add content for the faqs tab.
	 */
	public function add_panel() {

		global $post;

		$faqs = get_post_meta( $post->ID, '_woo_product_faqs', true );
		if ( ! $faqs ) {
			$faqs = array();
		}

		wp_localize_script(
			'woo-product-faqs',
			'product_faqs',
			array(
				'faqs' => $faqs,
			)
		);

		?>
		<div id='woo-product-faqs-panel' class='panel woocommerce_options_panel'>
			<div class='options_group' id="pffw-option-group"></div>
		</div>
		<?php
	}

	/**
	 * Save content of faqs in meta.
	 */
	public function save_faqs() {
		global $post;

		if ( ! isset( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) {  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		// Delete meta if empty.
		if ( empty( $_POST['pffw_faqs'] ) ) {
			delete_post_meta( $post->ID, '_woo_product_faqs' );
			return;
		}

		$faqs = array_map(
			function ( $faq ) {
				return array(
					'question' => sanitize_text_field( $faq['question'] ),
					'answer'   => sanitize_text_field( $faq['answer'] ),
				);
			},
			wp_unslash( $_POST['pffw_faqs'] ) //phpcs:ignore
		);

		update_post_meta( $post->ID, '_woo_product_faqs', $faqs );
	}
}
