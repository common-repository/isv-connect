<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_API' ) ) {

	class ISV_API {

		private $_api_url = null;
		private $_raw_xml = null;

		private $_allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'p' => array(),
			'i' => array(),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);

		/**
		 * Add handlers for the ajax requests
		 */
		public function init() {

			//actions
			add_action( 'wp_ajax_isv_get_feed', array( $this, 'get_feed' ) );
			add_action( 'wp_ajax_nopriv_isv_get_feed', array( $this, 'get_feed' ) );

			//filters
			add_filter( 'http_api_transports', array( $this, 'http_curl_transport_last' ) );
		}

		public function http_curl_transport_last( $request_order ) {

			$i = array_search( 'curl', $request_order );
			if ( false !== $i ) {
				unset( $request_order[ $i ] );
				$request_order[] = 'curl';
			}

			return $request_order;
		}

		/**
		 * Perform a remote server request based upon the client paramaters
		 */
		public function get_feed() {

			//start by assembling all the raw data
			$response = $_POST;

			//sanitize post-data
			$filters  = intval( $_POST['filters'] );
			$content  = wp_kses( $_POST['content'], $this->_allowed_html );
			$from     = sanitize_text_field( $_POST['from'] );
			$until    = sanitize_text_field( $_POST['until'] );
			$feedcode = sanitize_text_field( $_POST['feedcode'] );

			//create a unique key for this reuest, so we can use this as an id for our transient cache
			$unique_key = md5( $feedcode . $filters . $from . $until );

			//get settings and define the cache-time we want to set for this transient
			$settings           = new ISV_Settings( 'isv-connect-api-settings' );
			$cache_time_seconds = intval( $settings->get_option( 'cache-time' ) ) * 60;

			//if no cache-time is set, we always want to clear the transient, so we may fetch a live result from the API
			if ( $cache_time_seconds == 0 ) {
				delete_transient( $unique_key );
			}

			// Get any existing copy of our transient data
			if ( false === ( $html = get_transient( $unique_key ) ) ) {

				//no transient data was found, perform a live-fecth of some brand new data
				$html = $this->_create_feed_html( $feedcode, $filters, $content, $from, $until );

				//signal we loaded this result live from the API
				$response['loaded-from'] = 'api';
				$response['api-url']     = $this->_api_url;
				$response['raw-xml']     = $this->_raw_xml;

				//store the result in the transient cache system
				set_transient( $unique_key, $html, $cache_time_seconds );

			} else {

				//signal we loaded this result from the transient cache
				$response['loaded-from'] = 'transient-cache';
			}

			//append the parsed HTML result to the response object
			$response['html'] = $html;

			if ( defined( 'ISV_TESTMODUS' ) && ISV_TESTMODUS ) {
				$response['debug'] = true;
			}

			//send to client as JSON object and die (= exit script )
			wp_send_json( $response );
		}

		/**
		 * Perform all actions needed to create the clientside HTML of a certain feed
		 */
		private function _create_feed_html( $feedcode, $filters, $content, $from, $until ) {

			//get the raw XML data from the external API
			$raw_xml_body = $this->_get_remote_feed( $feedcode, $from, $until );

			$this->_raw_xml = $raw_xml_body;

			//convert XML data to an array
			$xml_data   = simplexml_load_string( $raw_xml_body, 'SimpleXMLElement', LIBXML_NOCDATA );
			$json_data  = json_encode( $xml_data );
			$array_data = json_decode( $json_data, true );

			//use the array-data to create a HTML-layout
			$layout = new ISV_Layout( $array_data, $filters, $content, $from, $until );
			$html   = $layout->get_HTML();

			return $html;
		}

		/**
		 * Perform a remote API call to get the XML-data of a feed
		 */
		private function _get_remote_feed( $feedcode, $from, $until ) {

			//get the settings
			$settings = new ISV_Settings( 'isv-connect-api-settings' );

			//Assemble the API-get-url
			$api_url = trailingslashit( $settings->get_option( 'api-endpoint-uri' ) ) . 'processfeedcode';
			$api_url = add_query_arg( 'FeedCode', $feedcode, $api_url );

			//set a daterange, from
			if ( $from ) {
				$from_array = explode( '-', $from );
				$from       = mktime( 0, 0, 0, $from_array[1], $from_array[0], $from_array[2] ) - 1;
				$api_url    = add_query_arg( 'StartDate', $from, $api_url );
			}

			//set a daterange, until
			if ( $until ) {
				$until_array = explode( '-', $until );
				$until       = mktime( 0, 0, 0, $until_array[1], $until_array[0], $until_array[2] );
				$api_url     = add_query_arg( 'EndDate', $until, $api_url );

				//override the default daterange of 30 days when an end-date is set
				$api_url = add_query_arg( 'StartDateRange', 0, $api_url );
			}

			//Add Authorization for the server
			$args = array(
				'headers' => array(
					'Authorization' => 'AccessKey ' . $settings->get_option( 'api-access-key' ),
				),
			);

			//store the request URI, so we can send it back with the other JSON-params
			$this->_api_url = $api_url;

			//get the data from the remote API
			$response = wp_remote_get( $api_url, $args );

			//extract the actual response body, which is what we need to create a Layout
			$body = wp_remote_retrieve_body( $response );

			return $body;
		}
	}
}
