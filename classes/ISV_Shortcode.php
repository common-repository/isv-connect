<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_Shortcode' ) ) {

	class ISV_Shortcode {

		/**
		 * Initialize all elements of this class
		 */
		public function init() {

			add_shortcode( 'isv-feed', array( $this, 'render_isv_feed' ) );
		}

		/**
		 * Adds the Shortcode to the system
		 */
		public function render_isv_feed( $atts, $content = '' ) {

			//start the output
			$html = '';

			$atts = shortcode_atts( array(
				'feedcode' => false,
				'filters'  => true,
			), $atts );

			extract( $atts );

			//if a feedcode was entered, go fetch
			if ( $feedcode ) {
				$isv_feed = new ISV_Feedcontainer( $feedcode, $filters, $content );
				$html .= $isv_feed->get_HTML();
			}

			return $html;
		}
	}
}