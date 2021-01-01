<?php

namespace Fanucchi;

if ( ! class_exists( 'Fanucchi\\FN_Create_Shipped_Status' ) ) {

	class FN_Create_Shipped_Status {
		private string $shipped_status_id = 'wc-shipped-status';

		public function __construct() {
			$this->add_actions();
			$this->add_filters();
		}

		private function add_actions() {
			\add_action( 'init', array( $this, 'create_order_status' ) );
		}

		private function add_filters() {
			\add_filter( 'wc_order_statuses', array( $this, 'register_order_status' ) );
		}

		public function create_order_status() {
			$options = array(
				'label'       => __( 'Shipped', 'woocommerce-shipped-status' ),
				'label_count' => _n_noop( 'Shipped (%s)', 'Shipped (%s)', 'woocommerce-shipped-status' ),
				'public'      => true,
			);

			\register_post_status( $this->shipped_status_id, $options );
		}

		public function register_order_status( array $statuses ) : array {
			$new_statuses = array();

			foreach ( $statuses as $status_key => $status ) {
				$new_statuses[ $status_key ] = $status;

				if ( 'wc-on-hold' === $status_key ) {
					$new_statuses[ $this->shipped_status_id ] = _x( 'Shipped', 'Order Status', 'woocommerce-shipped-status' );
				}
			}

			return $new_statuses;
		}

		public function get_shipped_status_id() : string {
			return $this->shipped_status_id;
		}
	}

}
