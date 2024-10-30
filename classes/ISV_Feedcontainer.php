<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_Feedcontainer' ) ) {

	class ISV_Feedcontainer {

		private $_feedcode;

		/**
		 * Construct this class, set the feedcode (string, required) and the filters (true/false)
		 */
		public function __construct( $feedcode, $filters = true, $content = '' ) {

			$this->_feedcode = $feedcode;
			$this->_content = $content;
			$this->_filters  = ( $filters == 'true' ) ? 1 : 0;
		}

		/**
		 * Create the required frontend HTML for a proper ISV-feed
		 */
		public function get_HTML() {

			$html = '';

			$html .= '<div class="isv-feed" id="feed-' . esc_attr( $this->_feedcode ) . '" data-feedcode="' . esc_attr( $this->_feedcode ) . '" data-filters="' . intval( $this->_filters ) . '" data-content="' . esc_attr( $this->_content ) . '"></div>';

			return $html;
		}
	}
}