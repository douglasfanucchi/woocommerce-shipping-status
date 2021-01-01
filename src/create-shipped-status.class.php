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
			\add_filter( 'wc_order_statuses', array( $this, 'register_shipped_status' ) );
			\add_filter( 'bulk_actions-edit-shop_order', array( $this, 'add_shipped_status_to_bulk_actions' ) );
		}

		public function create_order_status() {
			$options = array(
				'label'       => __( 'Shipped', 'woocommerce-shipped-status' ),
				'label_count' => _n_noop( 'Shipped (%s)', 'Shipped (%s)', 'woocommerce-shipped-status' ),
				'public'      => true,
			);

			\register_post_status( $this->shipped_status_id, $options );
		}

		public function register_shipped_status( array $statuses ) : array {
			$new_statuses = array();

			foreach ( $statuses as $status_key => $status ) {
				$new_statuses[ $status_key ] = $status;

				if ( 'wc-on-hold' === $status_key ) {
					$new_statuses[ $this->shipped_status_id ] = _x( 'Shipped', 'Order Status', 'woocommerce-shipped-status' );
				}
			}

			return $new_statuses;
		}

		public function add_shipped_status_to_bulk_actions( array $bulk_actions ) : array {
			$bulk_actions['mark_shipped-status'] = __( 'Change status to shipped', 'woocommerce-shipped-status' );

			return $bulk_actions;
		}

		public function get_shipped_status_id() : string {
			return $this->shipped_status_id;
		}
	}

}
