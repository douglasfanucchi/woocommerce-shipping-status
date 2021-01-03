<ul id="status-tracking" class="status-tracking" data-order-status=wc-<?php echo $order->get_status(); ?>>
	<li id="wc-ordered" class="status-tracking__item"><?php _e( 'Ordered', 'woocommerce-shipped-status' ); ?></li>
	<li id="wc-pending" class="status-tracking__item"><?php _e( 'Pending Payment', 'woocommerce-shipped-status' ); ?></li>
	<li id="wc-processing" class="status-tracking__item"><?php _e( 'Processing', 'woocommerce-shipped-status' ); ?></li>
	<li id="wc-shipped-status" class="status-tracking__item"><?php _e( 'Shipped', 'woocommerce-shipped-status' ); ?></li>
	<li id="wc-completed" class="status-tracking__item"><?php _e( 'Completed', 'woocommerce-shipped-status' ); ?></li>
</ul>
