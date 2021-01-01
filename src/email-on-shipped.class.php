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
			\add_action( 'woocommerce_settings_tabs_fn_email_tab', array( $this, 'output_settings' ), 50 );
			\add_action( 'woocommerce_update_options_fn_email_tab', array( $this, 'save_settings' ) );
		}

		private function add_filters() {
			\add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_email_settings_tab' ), 50 );
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
