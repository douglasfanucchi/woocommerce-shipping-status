<?php
namespace Fanucchi;

if ( ! class_exists( 'Fanucchi\\FN_Order_Status_Tracking' ) ) {
	class FN_Order_Status_Tracking {
		public function __construct() {
			$this->add_shortcode();
		}

		private function add_shortcode() {
			\add_shortcode( 'fn_status_tracking', array( $this, 'generate_shortcode' ) );
		}

		public function generate_shortcode( $args ) {
			$order_id = apply_filters( 'fn_track_status_order_id', $args['order_id'] );

			if ( empty( $order_id ) ) {
				return;
			}

			$order = \wc_get_order( $order_id );

			\wc_get_template(
				'shortcodes/status-tracking.php',
				\compact( 'order' ),
				'',
				\FN_SHIPPED_STATUS_TEMPLATES_PATH
			);
		}
	}
}
