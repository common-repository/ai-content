<?php
/**
 * Form class
 *
 * @author Flipper Code<hello@flippercode.com>
 * @version 1.0.2
 * @package AI Content
 */

if ( ! class_exists( 'WAI_FORM' ) ) {

	class WAI_FORM extends FlipperCode_HTML_Markup {

		function __construct( $options = array() ) {

			$productOverview = array(
				'subscribe_mailing_list'   => esc_html__( 'Subscribe to our mailing list', 'text-prompter' ),
				'product_info_heading'     => esc_html__( 'Product Information', 'text-prompter' ),
				'get_started_heading'      => esc_html__( 'Getting Started Guide', 'text-prompter' ),
				'get_started_btn_text'     => esc_html__( 'View Docs', 'text-prompter' ),
				'product_info_desc'        => esc_html__( 'Consult the user guide for guidance on getting started with the plugin.', 'text-prompter' ),
				'live_demo_caption'        => esc_html__( 'Getting Started', 'text-prompter' ),
				'installed_version'        => esc_html__( 'Installed version :', 'text-prompter' ),
				'latest_version_available' => esc_html__( 'Latest Version Available : ', 'text-prompter' ),
				'updates_available'        => esc_html__( 'Update Available', 'text-prompter' ),
				'subscribe_now'            => array(

					'heading' => esc_html__( 'Subscribe Now', 'text-prompter' ),
					'desc1'   => esc_html__( 'Stay informed about new product features and updates by receiving notifications about our new products and updates.', 'text-prompter' ),
					'desc2'   => esc_html__( 'Rest assured that we will never share your email address with any third party.', 'text-prompter' ),

				),

				'product_support'          => array(
					'heading'    => esc_html__( 'Product Support', 'text-prompter' ),
					'desc'       => esc_html__( 'Refer to the user guide for instructions on how to begin using the plugin.', 'text-prompter' ),
					'click_here' => esc_html__( ' Click Here', 'text-prompter' ),
					'desc2'      => esc_html__( 'Refer to the user guide for instructions on how to begin using the plugin.', 'text-prompter' ),
				),

				'create_support_ticket'    => array(

					'heading' => esc_html__( 'Create Support Ticket', 'text-prompter' ),
					'desc1'   => esc_html__( 'If you have any questions or need assistance, please click the button below to create a support ticket. Our support team will be happy to assist you.', 'text-prompter' ),

					'link'    => array(
						'label' => esc_html__( 'Create Ticket', 'text-prompter' ),
						'url'   => 'https://www.flippercode.com/support',

					),

				),

				'hire_wp_expert'           => array(

					'heading' => esc_html__( 'Hire Wordpress Expert', 'text-prompter' ),
					'desc'    => esc_html__( 'Do you have a specific need that is not currently offered by this plugin?', 'text-prompter' ),
					'desc1'   => esc_html__( 'We can tailor this plugin to your specific needs. Click the button below to request a quote for customization.', 'text-prompter' ),
					'link'    => array(

						'label' => esc_html__( 'Request a quotation', 'text-prompter' ),
						'url'   => 'https://www.flippercode.com/contact/',

					),

				),
				'plugin_css_path'          => WAI_CSS,

			);

			$productInfo = array(
				'productName'          => __( 'Text Prompter', 'text-prompter' ),
				'productSlug'          => 'aicontent',
				'productTagLine'       => 'Unlimited text prompts for OpenAI Tasks',
				'productTextDomain'    => 'text-prompter',
				'productIconImage'     => WAI_URL . 'core/core-assets/images/wp-poet.png',
				'productVersion'       => WAI_VERSION,
				'docURL'               => admin_url( 'admin.php?page=wai_how_overview' ),
				'demoURL'              => admin_url( 'admin.php?page=wai_how_overview' ),
				'getting_started_link' => admin_url( 'admin.php?page=wai_how_overview' ),
				'productImagePath'     => WAI_URL . 'core/core-assets/product-images/',
				'productSaleURL'       => 'https://www.flippercode.com/',
				'multisiteLicence'     => 'https://www.flippercode.com/',
				'productOverview'      => $productOverview,
			);

			$productInfo = array_merge( $productInfo, $options );
			parent::__construct( $productInfo );

		}

	}

}
