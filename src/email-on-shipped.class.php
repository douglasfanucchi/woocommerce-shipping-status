<?php
namespace Fanucchi;

use Fanucchi\Services\Email;

if ( ! class_exists( 'Fanucchi\\FN_Email_On_Shipped' ) ) {
	class FN_Email_On_Shipped {
		private string $shipped_status_id;

		public function __construct( string $shipped_status_id ) {
			$this->shipped_status_id = $shipped_status_id;
			$this->add_actions();
			$this->add_filters();
		}

		private function add_actions() {
			\add_action( 'transition_post_status', array( $this, 'send_email' ), 10, 3 );
			\add_action( 'woocommerce_settings_tabs_fn_email_tab', array( $this, 'output_settings' ), 50 );
			\add_action( 'woocommerce_update_options_fn_email_tab', array( $this, 'save_settings' ) );
		}

		private function add_filters() {
			\add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_email_settings_tab' ), 50 );
		}

		public function send_email( $_, string $old_status, \WP_Post $post ) {
			$new_status = null;

			if ( ! empty( $_POST['order_status'] ) ) {
				$new_status = \sanitize_text_field( $_POST['order_status'] );
			}

			if ( $new_status !== $this->shipped_status_id || $old_status === $this->shipped_status_id || 'shop_order' !== $post->post_type ) {
				return;
			}

			$order = new \WC_Order( $post->ID );

			$email_service = new Email( $order );
			$email_service->send();
		}

		public function add_email_settings_tab( array $tabs ) : array {
			$tabs['fn_email_tab'] = __( 'Email Settings', 'woocommerce-shipped-status' );
			return $tabs;
		}

		public function output_settings() {
			\woocommerce_admin_fields( $this->get_settings() );
		}

		public function save_settings() {
			\woocommerce_update_options( $this->get_settings() );
		}

		private function get_settings() {
			$settings = array(
				'section_title' => array(
					'name' => __( 'E-mail Settings', 'woocommerce-shipped-status' ),
					'type' => 'title',
					'desc' => __( 'Define settings to be used on email that is fired when order status becomes "Shipped"', 'woocommerce-shipped-status' ),
					'id'   => 'fn_email_tab_section_title',
				),
				'email_from'    => array(
					'name' => __( 'E-mail From', 'woocommerce-shipped-status' ),
					'desc' => __( 'Defines the e-mail from to be used', 'woocommerce-shipped-status' ),
					'type' => 'email',
					'id'   => 'fn_email_tab_email_from',
				),
				'email_subject' => array(
					'name' => __( 'E-mail Subject', 'woocommerce-shipped-status' ),
					'desc' => __( 'Defines the subject to be shown on e-mail', 'woocommerce-shipped-status' ),
					'type' => 'text',
					'id'   => 'fn_email_tab_email_subject',
				),
				'email_content' => array(
					'name'    => __( 'E-mail Content', 'woocommerce-shipped-status' ),
					'desc'    => __( 'Defines the e-mail content to be sent', 'woocommerce-shipped-status' ),
					'type'    => 'textarea',
					'id'      => 'fn_email_tab_email_content',
					'default' => 'Hello, [PH_BUYER] your order has already been sent to the delivery company!',
				),
				'section_end'   => array(
					'type' => 'sectionend',
					'id'   => 'fn_email_tab_section_end',
				),
			);

			return \apply_filters( 'woocommerce_settings_fn_email_tab', $settings );
		}
	}
}
