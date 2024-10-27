<?php
/**
 * Class: WAI_Model_Settings
 *
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package AI Content
 */

if ( ! class_exists( 'WAI_Model_Settings' ) ) {

	/**
	 * Setting model for Plugin Options.
	 *
	 * @package AI Content
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WAI_Model_Settings extends FlipperCode_Model_Base {
		/**
		 * Intialize Settings Model object.
		 */
		function __construct() {
		}
		/**
		 * Admin menu for Settings Operation
		 *
		 * @return array Admin menu navigation(s).
		 */
		function navigation() {
			return array(
				'wai_manage_settings' => esc_html__( 'Settings', 'text-prompter' ),
				'wai_test_settings'   => esc_html__( 'Testing', 'text-prompter' ),
			);
		}
		/**
		 * Add or Edit Operation.
		 */
		function save() {

			// Nonce Verification
			if ( ! isset( $_REQUEST['_wpnonce'] ) || ( isset( $_REQUEST['_wpnonce'] ) && empty( $_REQUEST['_wpnonce'] ) ) ) {
				die( 'You are not allowed to save changes!' );
			}
			if ( isset( $_REQUEST['_wpnonce'] ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpgmp-nonce' ) ) {
				die( 'You are not allowed to save changes!' );
			}

			// Permission Verification
			$page_capability = sanitize_text_field( $_GET['page'] );
			if ( ! current_user_can( $page_capability ) ) {
				die( 'You are not allowed to save changes!' );
			}

			$this->verify( $_POST );

			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}

			$openai_settings['wai_openai_key'] = sanitize_text_field( wp_unslash( $_POST['wai_openai_key'] ) );

			update_option( 'wai_settings', $openai_settings );
			// update_option( 'wpr_security_ques_setting',$settings );
			$response['success'] = esc_html__( 'Plugin settings were saved successfully.', 'text-prompter' );
			return $response;

		}
	}
}
