<?php
namespace Fanucchi;

use Fanucchi\Services\Email;

if ( ! class_exists( 'Fanucchi\\Email_On_Shipped' ) ) {
	class Email_On_Shipped {
		private string $shipped_status_id;

		public function __construct( string $shipped_status_id ) {
			$this->shipped_status_id = $shipped_status_id;
			$this->add_actions();
		}

		private function add_actions() {
			\add_action( 'transition_post_status', array( $this, 'send_email' ), 10, 3 );
		}

		public function send_email( $_, string $old_status, \WP_Post $post ) {
			$new_status = null;

			if ( ! empty( $_POST['order_status'] ) ) {
				$new_status = \sanitize_text_field( $_POST['order_status'] );
			}

			if ( $new_status !== $this->shipped_status_id || $old_status === $this->shipped_status_id || 'shop_order' !== $post->post_type ) {
				return;
			}

			$email_service = new Email( $post );
			$email_service->send();
		}
	}
}
