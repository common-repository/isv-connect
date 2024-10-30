<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_Connect' ) ) {

	class ISV_Connect {

		/**
		 * Init everything
		 */
		public function init() {

			//actions
			add_action( 'init', array( $this, 'load_languagefile' ) );
			add_action( 'widgets_init', array( $this, 'load_widget' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_js' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_css' ) );

			//start them separate classes

			//Create Admin settings page
			$isv_admin = new ISV_Admin();
			$isv_admin->init();

			//Let's have a handy dandy shortcode
			$isv_admin = new ISV_Shortcode();
			$isv_admin->init();

			//And initialize the API handler
			$isv_api = new ISV_API();
			$isv_api->init();

			//And initialize the API handler
			$isv_remote_strings = new ISV_Remote_Strings();
			$isv_remote_strings->init();
		}

		/**
		 * Register our Widget
		 */
		public function load_widget() {
			register_widget( 'isv_widget' );
		}

		/**
		 * Enqueue frontend styles
		 */
		public function enqueue_css() {

			// We need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
			wp_register_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' );
			wp_enqueue_style( 'jquery-ui' );

			$settings = new ISV_Settings( 'isv-connect-general-settings' );
			$hide_css  = $settings->get_option( 'hide-css' );

			if ( $hide_css != 'yes' ) {
				if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
					$file = '/assets/css/isv-styles.css';
				} else {
					$file = '/assets/css/isv-styles.min.css';
				}

				$version = filemtime( ISV_PLUGIN_PATH . $file );
				wp_enqueue_style( 'isv-css', ISV_PLUGIN_URI . $file, array(), $version );
			}
		}

		/**
		 * Enqueue frontend javascripts
		 */
		public function enqueue_js() {

			wp_enqueue_script( 'jquery-ui-datepicker' );

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				$file = '/assets/js/script.js';
			} else {
				$file = '/assets/js/script.min.js';
			}

			$version = filemtime( ISV_PLUGIN_PATH . $file );
			wp_register_script( 'isv-js', ISV_PLUGIN_URI . $file, array( 'jquery', 'jquery-ui-datepicker' ), $version, true );

			$settings = new ISV_Settings( 'isv-connect-api-settings' );

			//Create an array with some handy dandy parameters for our JS-application
			$isv_params = array(
				'ajax_url'                  => admin_url( 'admin-ajax.php' ),
				'loader'                    => '<img src="' . ISV_PLUGIN_URI . '/assets/images/ajax-loader.gif" class="isv-loader" />',
				'bookingspage_endpoint_uri' => $settings->get_option( 'bookingspage-endpoint-uri' ),
			);

			wp_localize_script( 'isv-js', 'isv_params', $isv_params );

			wp_enqueue_script( 'isv-js' );
		}

		/**
		 * Enqueue everything (CSS/JS) we need in the backend
		 */
		public function enqueue_admin_css() {

			//load and cache-buste the backend styling
			$file = '/assets/css/isv-admin-styles.css';
			wp_enqueue_style( 'isv-admin-styles', ISV_PLUGIN_URI . $file, array(),
				filemtime( ISV_PLUGIN_PATH . $file ) );
		}

		/**
		 * Load translations
		 */
		public function load_languagefile() {

			load_plugin_textdomain( 'isv-connect', false, ISV_LANGUAGE_PATH );
		}
	}
}
