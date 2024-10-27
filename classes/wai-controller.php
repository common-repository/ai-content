<?php
/**
 * Controller class
 *
 * @author Flipper Code<hello@flippercode.com>
 * @version 3.0.0
 * @package Posts
 */

if ( ! class_exists( 'WAI_Controller' ) ) {

	/**
	 * Controller class to display views.
	 *
	 * @author: Flipper Code<hello@flippercode.com>
	 * @version: 3.0.0
	 * @package: Maps
	 */

	class WAI_Controller extends Flippercode_Factory_Controller {


		function __construct() {

			parent::__construct( WAI_Model, 'WAI_Model_' );

		}

		public function needs_license_verification() {
			return true; }

	}

}
