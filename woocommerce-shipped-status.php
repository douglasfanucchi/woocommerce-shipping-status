<?php
/**
 * Plugin Name: WooCommerce Shipped Status
 * Version: 1.0.0
 * Description: Adds the Shipped status in the customer's orders
 * Author: Douglas Fanucchi
 * Author URI: https://fanucchi.dev

 * Text Domain: woocommerce-shipped-status
 * Domain Path: /languages

 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use Fanucchi\FN_Create_Shipped_Status;
use Fanucchi\FN_Email_On_Shipped;
use Fanucchi\FN_Order_Status_Tracking;

require_once __DIR__ . '/vendor/autoload.php';

if ( ! defined( 'FN_SHIPPED_STATUS_PATH' ) ) {
	define( 'FN_SHIPPED_STATUS_PATH', dirname( __FILE__ ) );
}

if ( ! \defined( 'FN_SHIPPED_STATUS_TEMPLATES_PATH' ) ) {
	\define( 'FN_SHIPPED_STATUS_TEMPLATES_PATH', FN_SHIPPED_STATUS_PATH . '/src/templates/' );
}

if ( ! class_exists( 'FN_Shipped_Status' ) ) {
	class FN_Shipped_Status {
		public function __construct() {
			$shipped_status   = new FN_Create_Shipped_Status();
			$email_on_shipped = new FN_Email_On_Shipped( $shipped_status->get_shipped_status_id() );
		}
	}
}

new FN_Shipped_Status();
