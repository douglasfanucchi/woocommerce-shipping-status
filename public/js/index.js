(function ($) {
	$(function () {
		function getOrderStatus() {
			const ul = $("#status-tracking");

			return ul.data("order-status");
		}

		function getCurrentStatusElement(orderStatus) {
			return $(`#${orderStatus}`);
		}

		const orderStatus = getOrderStatus();
    const currentStatusElement = getCurrentStatusElement(orderStatus);

		currentStatusElement.addClass("status-tracking__item--active");
	});
})(jQuery);
