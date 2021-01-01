<?php

namespace Fanucchi\Services;

if ( ! class_exists( 'Fanucchi\\Services\\Email' ) ) {
	class Email {
		private string $email_from;
		private string $email_to;
		private string $subject;
		private string $content;

		public function __construct( \WC_Order $order ) {
			$this->email_from = \get_option( 'fn_email_tab_email_from' );
			$this->email_to   = $order->get_billing_email();
			$this->subject    = \get_option( 'fn_email_tab_email_subject' );
			$this->content    = $this->replace_placeholders( \get_option( 'fn_email_tab_email_content' ), $order );
		}

		public function send() {
			$headers = array(
				"From: {$this->email_from}",
				"Cc: {$this->email_from}",
				"Bcc: {$this->email_from}",
			);

			\wp_mail( $this->email_to, $this->subject, $this->content, $headers );
		}

		private function replace_placeholders( string $email_content, \WC_Order $order ) : string {
			$placeholders = array(
				'[PH_BUYER]' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
			);

			$placeholders = \apply_filters( 'fn_email_content_placeholders', $placeholders );

			foreach ( $placeholders as $placeholder => $value_to_be_replaced ) {
				$email_content = str_replace( $placeholder, $value_to_be_replaced, $email_content );
			}

			return \apply_filters( 'fn_email_content', $email_content );
		}
	}
}
