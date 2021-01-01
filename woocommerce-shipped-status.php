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

use Fanucchi\Create_Shipped_Status;
use Fanucchi\Send_Email_On_Shipped;

require_once __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( 'FN_Shipped_Status' ) ) {
	class FN_Shipped_Status {
		public function __construct() {
			$shipped_status        = new Create_Shipped_Status();
			$send_email_on_shipped = new Send_Email_On_Shipped( $shipped_status->get_shipped_status_id() );
		}
	}
}

new FN_Shipped_Status();
