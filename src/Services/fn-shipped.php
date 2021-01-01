<?php
namespace Fanucchi\Services;

if ( ! class_exists( 'Fanucchi\\Services\\FN_Shipped' ) ) {
	class FN_Shipped extends \WC_Email {
		public function __construct() {
			$this->id             = 'fn-shipped-status';
			$this->title          = __( 'Order was shipped', 'woocommerce-shipped-status' );
			$this->description    = __( 'An email sent to the customer when order is shipped', 'woocommerce-shipped-status' );
			$this->customer_email = true;
			$this->heading        = __( 'Order Shipped', 'woocommerce-shipped-status' );
			$this->template_base  = \FN_SHIPPED_STATUS_PATH . '/src/templates/emails/';

			parent::__construct();
		}

		public function get_content_html() {
			$template_args = array(
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
				'plain_text'    => false,
				'email'         => $this,
			);

			return \wc_get_template_html( 'shipped-status.php', $template_args, '', $this->template_base );
		}

		public function trigger( int $order_id ) {
			$this->object    = \wc_get_order( $order_id );
			$this->recipient = $this->object->get_billing_email();

			if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
				return;
			}

			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}
	}
}
