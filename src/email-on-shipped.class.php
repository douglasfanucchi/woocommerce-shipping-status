<?php
namespace Fanucchi;

use Fanucchi\Services\FN_Shipped;

if ( ! class_exists( 'Fanucchi\\FN_Email_On_Shipped' ) ) {
	class FN_Email_On_Shipped {
		private string $shipped_status_id;

		public function __construct( string $shipped_status_id ) {
			$this->shipped_status_id = $shipped_status_id;
			$this->add_actions();
			$this->add_filters();
		}

		private function add_actions() {
			\add_action( 'woocommerce_order_status_' . $this->shipped_status_id, array( $this, 'send_email' ), 10 );
		}

		private function add_filters() {
			\add_filter( 'woocommerce_email_actions', array( $this, 'register_email_action' ) );
			\add_filter( 'woocommerce_email_classes', array( $this, 'register_shipped_mail' ) );
		}

		public function register_email_action( array $actions ) : array {
			$actions[] = "woocommerce_order_status_{$this->shipped_status_id}";

			return $actions;
		}

		public function register_shipped_mail( array $emails ) {
			$emails['FN_Shipped'] = new FN_Shipped();

			return $emails;
		}

		public function send_email( int $order_id ) {
			$emails = WC()->mailer()->get_emails();
			$email  = $emails['FN_Shipped'];
			$email->trigger( $order_id );
		}
	}
}
