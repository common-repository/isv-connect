<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_Admin' ) ) {

	class ISV_Admin {

		private $_capability = 'manage_options';
		private $_tabs = null;

		/**
		 * Adds the ISV menu to the WP-admin
		 */
		public function init() {

			//make the role filterable for site-owners
			$this->_capability = apply_filters( 'isv_manage_options', $this->_capability );

			//actions
			add_action( 'admin_menu', array( $this, 'isv_menu' ) );
			add_action( 'admin_print_scripts', array( $this, 'load_js' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer_js' ) );
			add_action( 'init', array( $this, 'init_tabs' ) );
		}

		public function init_tabs () {
			//define all the tabs on our settings page
			$this->_tabs = array(
				'api'                   => __( 'API', 'isv-connect' ),
				'general'               => __( 'General', 'isv-connect' ),
				'intest_template'       => __( 'Template: Intest', 'isv-connect' ),
				'trainingarea_template' => __( 'Template: Trainingarea', 'isv-connect' ),
				'theory_template'       => __( 'Template: Theory', 'isv-connect' ),
				'education_template'    => __( 'Template: Education', 'isv-connect' ),
			);
        }

		/**
		 * Initialize the admin tabs
		 */
		public function admin_footer_js() {
			?>
            <script>
				jQuery(document).ready(function ($) {
					$('.isv-tabs').tabs();
				});
            </script>
			<?php
		}

		/**
		 * Make sure we load de tabs-script
		 */
		public function load_js() {
			wp_enqueue_script( 'jquery-ui-tabs' );
		}

		/**
		 * Adds the different types of menu pages to the WP-admin
		 */
		public function isv_menu() {

			//add all the menu items
			add_menu_page( __( 'ISV', 'isv-connect' ), __( 'ISV', 'isv-connect' ), $this->_capability, 'isv-connect',
				array( $this, 'isv_main_settings_page' ) );
		}

		/**
		 * Shows the content for the main page
		 */
		public function isv_main_settings_page() {

			//bail if the user is not allowed to edit this
			if ( ! current_user_can( $this->_capability ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.', 'isv-connect' ) );
			}

			//begin output
			$html = '';
			$html .= '<div class="wrap">';
			$html .= '<h2>' . __( 'ISV', 'isv-connect' ) . '</h2>';

			$html .= $this->_create_tabs();

			$html .= '</div>';

			echo $html;
		}

		/**
		 * Create the HTML for all the tabs
		 */
		private function _create_tabs() {

			$html       = '';
			$navigation = '';
			$content    = '';

			if ( is_array( $this->_tabs ) ) {

				$html .= '<div class="isv-tabs">';

				foreach ( $this->_tabs as $tab_index => $tab_label ) {

					$method_name = '_render_tab_' . $tab_index;

					if ( method_exists( $this, $method_name ) ) {
						$navigation .= '<li><a href="#' . $tab_index . '">' . $tab_label . '</a></li>';
						$content    .= '<div id="' . $tab_index . '">' . call_user_func( array(
								$this,
								$method_name
							) ) . '</div>';
					}
				}

				$html .= '<ul>' . $navigation . '</ul>';
				$html .= $content;

				$html .= '</div><!-- .isv-tabs -->';
			}

			return $html;
		}

		private function _render_tab_education_template() {

			//list all the available parameters we might want to show inside our template
			$parameters = array(
				'Key',
				'Type',
				'PackageID',
				'ID',
				'Start',
				'Start_Date',
				'Start_Time',
				'Start_Unix',
				'End',
				'End_Date',
				'End_Time',
				'End_Unix',
				'WeekOfYear',
				'Name',
				'Description',
				'Article_ID',
				'Price',
				'Location',
				'Availability',
				'OnlineAvailability',
				/* These would be the subitems,...not needed for now
				array(
					'Key',
					'PlanType',
					'ID',
					'PackageKey',
					'Type',
					'Index',
					'State',
					'Status',
					'StatusDescription',
					'Start',
					'Start_Date',
					'Start_Time',
					'Start_Unix',
					'End',
					'End_Date',
					'End_Time',
					'End_Unix',
					'Duration',
					'WeekOfYear',
					'Student_ID',
					'Student_Name',
					'Category_ID',
					'Category_Name',
					'TrainingAreaCategory_ID',
					'TrainingAreaCategory_Name',
					'Trainer_ID',
					'Trainer_Name',
					'Vehicle_ID',
					'Vehicle_Name',
					'Description',
					'PickupAddress',
					'TotalCandidateCount',
                ),*/
			);

			//render a generic tab for a template
			return $this->_render_tab_template( 'education_template', $parameters );
		}

		private function _render_tab_intest_template() {

			//list all the available parameters we might want to show inside our template
			$parameters = array(
				'Key',
				'PlanType',
				'ID',
				'Type',
				'State',
				'Status',
				'StatusDescription',
				'Start',
				'Start_Date',
				'Start_Time',
				'Start_Unix',
				'End',
				'End_Date',
				'End_Time',
				'End_Unix',
				'Duration',
				'WeekOfYear',
				'Category_ID',
				'Category_Name',
				'TrainingAreaCategory_ID',
				'TrainingAreaCategory_Name',
				'Trainer_ID',
				'Trainer_Name',
				'Vehicle_ID',
				'Vehicle_Name',
				'Description',
				'PickupAddress',
				'TotalCandidateCount',
				'AvailableCandidateCount',
				'Availability',
				'OnlineAvailability',
			);

			//render a generic tab for a template
			return $this->_render_tab_template( 'intest_template', $parameters );
		}

		private function _render_tab_trainingarea_template() {

			//list all the available parameters we might want to show inside our template
			$parameters = array(
				'Key',
				'PlanType',
				'ID',
				'Type',
				'State',
				'Status',
				'StatusDescription',
				'Start',
				'Start_Date',
				'Start_Time',
				'Start_Unix',
				'End',
				'End_Date',
				'End_Time',
				'End_Unix',
				'Duration',
				'WeekOfYear',
				'Category_ID',
				'Category_Name',
				'TrainingAreaCategory_ID',
				'TrainingAreaCategory_Name',
				'Trainer_ID',
				'Trainer_Name',
				'Vehicle_ID',
				'Vehicle_Name',
				'Description',
				'PickupAddress',
				'TotalCandidateCount',
				'AvailableCandidateCount',
				'Availability',
				'OnlineAvailability',
			);

			//render a generic tab for a template
			return $this->_render_tab_template( 'trainingarea_template', $parameters );
		}

		private function _render_tab_theory_template() {

			//list all the available parameters we might want to show inside our template
			$parameters = array(
				'Key',
				'PlanType',
				'ID',
				'SetKey',
				'Type',
				'Index',
				'BlockIndex',
				'State',
				'Status',
				'StatusDescription',
				'Start',
				'Start_Date',
				'Start_Time',
				'Start_Unix',
				'End',
				'End_Date',
				'End_Time',
				'End_Unix',
				'Duration',
				'WeekOfYear',
				'TheoryGroup_ID',
				'TheoryGroup_Name',
				'Trainer_ID',
				'Trainer_Name',
				'Product_ID',
				'Product_Code',
				'Product_Name',
				'Description',
				'PickupAddress',
				'TotalCandidateCount',
				'AvailableCandidateCount',
				'Availability',
				'OnlineAvailability',
			);

			//render a generic tab for a template
			return $this->_render_tab_template( 'theory_template', $parameters );
		}

		private function _render_tab_template( $template_key, $parameters ) {

			$settings = new ISV_Settings( 'isv-connect-' . $template_key . '-settings' );

			//save data on submit
			if ( isset( $_POST[ 'isv-connect-' . $template_key . '-settings-form' ] ) && ( $_POST[ 'isv-connect-' . $template_key . '-settings-form' ] == 'true' ) ) {

				//sanitize data
				$sanitized_data = array_map( 'sanitize_text_field', $_POST[ $template_key ] );

				//store it
				$settings->store_options( $sanitized_data );

				$feedback = __( 'The data has been saved', 'isv-connect' );
			}

			//begin output
			$html = '';

			// header
			$html .= '<h2>' . __( 'Template settings', 'isv-connect' ) . '</h2>';

			if ( isset( $feedback ) ) {
				$html .= '<p class="isv-saved">' . $feedback . '</p>';
			}

			$html .= '<form method="post" class="isv-form" action="#' . $template_key . '">';

			$html .= '<input type="hidden" name="isv-connect-' . $template_key . '-settings-form" value="true">';

			foreach ( $parameters as $parameter ) {
				$html .= '<div class="switch-field">';
				$html .= '    <div class="switch-title">' . $parameter . ' (' . __( $parameter,
						'isv-connect' ) . '):</div>';
				$html .= '      <input ' . checked( 'show', $settings->get_option( $parameter ),
						false ) . ' type="radio" class="isv-radio" name="' . $template_key . '[' . $parameter . ']" id="' . $template_key . '-' . $parameter . '-show" value="show" /><label class="label-radio-inline" for="' . $template_key . '-' . $parameter . '-show">' . __( 'Show',
						'isv-connect' ) . '</label>';
				$html .= '      <input ' . checked( 'hide', $settings->get_option( $parameter ),
						false ) . ' type="radio" class="isv-radio" name="' . $template_key . '[' . $parameter . ']" id="' . $template_key . '-' . $parameter . '-hide" value="hide" /><label class="label-radio-inline" for="' . $template_key . '-' . $parameter . '-hide">' . __( 'Hide',
						'isv-connect' ) . '</label>';
				$html .= '</div>';
			}

			$html .= '<hr />';
			$html .= '<p class="submit">';
			$html .= '<input type="submit" name="Submit" class="button-primary" value="' . __( 'Save changes',
					'isv-connect' ) . '" />';
			$html .= '</p>';
			$html .= '</form>';

			return $html;
		}

		/**
		 * Shows the content for the 'General' tab, and saves the data upon submit
		 */
		private function _render_tab_general() {

			$settings = new ISV_Settings( 'isv-connect-general-settings' );

			//save data on submit
			if ( isset( $_POST['isv-connect-general-settings-form'] ) && ( $_POST['isv-connect-general-settings-form'] == 'true' ) ) {

				//sanitize submitted data
				$hide_css = isset( $_POST['hide-css'] ) ? sanitize_text_field( $_POST['hide-css'] ) : 'no';

				//store it
				$settings->store_options( array(
					'hide-css' => $hide_css,
				) );

				$feedback = __( 'The data has been saved', 'isv-connect' );
			}

			//begin output
			$html = '';

			// header
			$html .= '<h2>' . __( 'General settings', 'isv-connect' ) . '</h2>';

			if ( isset( $feedback ) ) {
				$html .= '<p class="isv-saved">' . $feedback . '</p>';
			}

			$html .= '<form method="post" class="isv-form" action="#general">';

			$html .= '<input type="hidden" name="isv-connect-general-settings-form" value="true">';

			//API Access key
			$html .= '<p>';
			$html .= '<input type="checkbox" class="isv-input" id="hide-css" name="hide-css" value="yes" ' . checked( 'yes',
					$settings->get_option( 'hide-css' ), false ) . ' /> ';
			$html .= '<label for="hide-css" style="width:400px;max-width:100%;">' . __( 'Hide our CSS stylesheet?',
					'isv-connect' ) . '</label>';
			$html .= '</p>';

			$html .= '<hr />';
			$html .= '<p class="submit">';
			$html .= '<input type="submit" name="Submit" class="button-primary" value="' . __( 'Save changes',
					'isv-connect' ) . '" />';
			$html .= '</p>';
			$html .= '</form>';

			return $html;
		}

		/**
		 * Shows the content for the 'API' tab, and saves the data upon submit
		 */
		private function _render_tab_api() {

			$settings = new ISV_Settings( 'isv-connect-api-settings' );

			//save data on submit
			if ( isset( $_POST['isv-connect-api-settings-form'] ) && ( $_POST['isv-connect-api-settings-form'] == 'true' ) ) {

				//sanitize submitted data
				$api_access_key            = isset( $_POST['api-access-key'] ) ? sanitize_text_field( $_POST['api-access-key'] ) : '';
				$api_endpoint_uri          = isset( $_POST['api-endpoint-uri'] ) ? sanitize_text_field( $_POST['api-endpoint-uri'] ) : '';
				$bookingspage_endpoint_uri = isset( $_POST['bookingspage-endpoint-uri'] ) ? sanitize_text_field( $_POST['bookingspage-endpoint-uri'] ) : '';
				$cache_time                = isset( $_POST['cache-time'] ) ? intval( $_POST['cache-time'] ) : '';

				//store it
				$settings->store_options( array(
					'api-access-key'            => $api_access_key,
					'api-endpoint-uri'          => $api_endpoint_uri,
					'bookingspage-endpoint-uri' => $bookingspage_endpoint_uri,
					'cache-time'                => $cache_time,
				) );

				$feedback = __( 'The data has been saved', 'isv-connect' );
			}

			//begin output
			$html = '';

			// header
			$html .= '<h2>' . __( 'API settings', 'isv-connect' ) . '</h2>';

			if ( isset( $feedback ) ) {
				$html .= '<p class="isv-saved">' . $feedback . '</p>';
			}

			$html .= '<form method="post" class="isv-form" action="#api">';

			$html .= '<input type="hidden" name="isv-connect-api-settings-form" value="true">';

			//API Access key
			$html .= '<p>';
			$html .= '<label for="api-access-key">' . __( 'API Access key:', 'isv-connect' ) . '</label><br />';
			$html .= '<input type="text" class="isv-input" name="api-access-key" value="' . esc_attr( $settings->get_option( 'api-access-key' ) ) . '" /><br />';
			$html .= '</p>';

			$html .= '<p>';
			$html .= '<label for="api-endpoint-uri">' . __( 'API Endpoint URI:', 'isv-connect' ) . '</label><br />';
			$html .= '<input type="url" class="isv-input" name="api-endpoint-uri" value="' . esc_attr( $settings->get_option( 'api-endpoint-uri' ) ) . '" /><br />';
			$html .= '</p>';

			$html .= '<p>';
			$html .= '<label for="bookingspage-endpoint-uri">' . __( 'Bookingspage endpoint URI:',
					'isv-connect' ) . '</label><br />';
			$html .= '<input type="url" class="isv-input" name="bookingspage-endpoint-uri" value="' . esc_attr( $settings->get_option( 'bookingspage-endpoint-uri' ) ) . '" /><br />';
			$html .= '</p>';

			$html .= '<p>';
			$html .= '<label for="cache-time">' . __( 'Cache time (minutes):', 'isv-connect' ) . '</label><br />';
			$html .= '<input type="number" min="0" class="isv-input" name="cache-time" value="' . esc_attr( $settings->get_option( 'cache-time' ) ) . '" /><br />';
			$html .= '<small>' . __( 'Enter 0 to disable cache', 'isv-connect' ) . '</small>';
			$html .= '</p>';

			$html .= '<hr />';
			$html .= '<p class="submit">';
			$html .= '<input type="submit" name="Submit" class="button-primary" value="' . __( 'Save changes',
					'isv-connect' ) . '" />';
			$html .= '</p>';
			$html .= '</form>';

			return $html;
		}
	}
}
