<?php
/**
 * Class: WAI_Model_Overview
 *
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package AI Content
 */

if ( ! class_exists( 'WAI_Model_Overview' ) ) {

	/**
	 * Overview model for Plugin Overview.
	 *
	 * @package AI Content
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WAI_Model_Overview extends FlipperCode_Model_Base {
		/**
		 * Intialize Model object.
		 */
		function __construct() {
		}
		/**
		 * Admin menu for Settings Operation
		 */
		function navigation() {

			return array( 'wai_how_overview' => esc_html__( 'How to Use', 'text-prompter' ) );
		}
	}
}
