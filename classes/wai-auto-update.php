<?php
/**
 * Auto Update notification Class File.
 *
 * @author flippercode
 * @package Updates
 * @version 1.0.0
 */
if ( ! class_exists( 'WAI_Auto_Update' ) && class_exists( 'Flippercode_Product_Auto_Update' ) ) {

	class WAI_Auto_Update extends Flippercode_Product_Auto_Update {

		function __construct() {
			$this->wai_current_version = '3.0.4'; }
	}
	return new WAI_Auto_Update();

}

