<?php
/**
 * File handles frontend logic.
 *
 *  @package Woo Product Faqs
 */

/**
 * Class that handle all the front end logic.
 */
class PRODUCT_FAQS_WOO_FRONT {

	/**
	 * Constructor of class.
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_tabs', array( $this, 'add_faqs_tab' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_script' ) );
	}

	/**
	 * Enqueue scripts.
	 */
	public function add_script() {

		$asset_file = include PFFW_DIR_PATH . 'build/front.asset.php';
		wp_register_script(
			'woo-product-faqs',
			PFFW_DIR_URL . 'build/front.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		wp_register_style( 'woo-product-faqs', PFFW_DIR_URL . 'build/front.css', array( 'dashicons' ), $asset_file['version'] );
	}

	/**
	 * Add Faqs tab.
	 *
	 * @param array $tabs All registered woocommerce tabs.
	 * @return array
	 */
	public function add_faqs_tab( $tabs ) {

		global $post;
		$faqs = get_post_meta( $post->ID, '_woo_product_faqs', true );

		if ( ! empty( $faqs ) ) {
			$tabs['woo_product_faqs'] = array(
				'title'    => 'FAQs',
				'priority' => 50,
				'callback' => array( $this, 'faqs_tab_content' ),
			);
		}

		return $tabs;
	}

	/**
	 * Add faqs content.
	 */
	public function faqs_tab_content() {

		global $post;
		$faqs = get_post_meta( $post->ID, '_woo_product_faqs', true );

		if ( ! empty( $faqs ) ) {

			wp_enqueue_style( 'woo-product-faqs' );
			wp_enqueue_script( 'woo-product-faqs' );
			?>
			<h2><?php echo esc_html( 'FAQs' ); ?></h2>
			<div class="pffw-product-faqs">
				<?php foreach ( $faqs as $faq ) : ?>
					<?php if ( ! empty( $faq['question'] ) && ! empty( $faq['answer'] ) ) : ?>
						<div class="pffw-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
							<div class="pffw-question">
								<p class="pffw-question-heading" itemprop="name"><?php echo esc_html( $faq['question'] ); ?></p>
							</div>
							<div class="pffw-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
								<p itemprop="text"><?php echo esc_html( $faq['answer'] ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}
}
