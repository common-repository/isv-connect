<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_Layout' ) ) {

	class ISV_Layout {

		private $_data = null;
		private $_from = null;
		private $_until = null;
		private $_items = null;
		private $_content = null;
		private $_filters = null;
		private $_feedcode = null;
		private $_template = null;
		private $_item_sets = null;
		private $_feed_type = null;

		public function __construct( $data, $filters, $content, $from, $until ) {
			$this->_data    = $data;
			$this->_from    = $from;
			$this->_until   = $until;
			$this->_content = $content;
			$this->_filters = $filters;
		}

		public function get_HTML() {

			//store the feedcode, so we can use this later as a data-attribute for our booking-butons
			$this->_feedcode = $this->_data['Header']['FeedCode'];

			//store the default start and end ranges
			$this->_from  = date( 'd-m-Y', $this->_data['Header']['Filter']['DateRange']['StartDate_Unix'] );
			$this->_until = date( 'd-m-Y', $this->_data['Header']['Filter']['DateRange']['EndDate_Unix'] );

			//define the type of feed we need to parse
			$this->_feed_type = isset( $this->_data['Header']['ViewParams']['FeedType'] ) ? $this->_data['Header']['ViewParams']['FeedType'] : '';
			$this->_template  = isset( $this->_data['Header']['ViewParams']['LayoutType'] ) ? $this->_data['Header']['ViewParams']['LayoutType'] : '';

			//parse different feed types
			switch ( $this->_feed_type ) {

				case 'Trainingarea';
					$html = $this->_parse_layout_trainingarea();
					break;
				case 'Education';
					$html = $this->_parse_layout_education();
					break;
				case 'Theory';
					$html = $this->_parse_layout_theory();
					break;
				case 'Intest';
					$html = $this->_parse_layout_intest();
					break;
				default :
					$html = $this->_parse_layout_theory();
					break;
			}

			if ( defined( 'ISV_TESTMODUS' ) && ISV_TESTMODUS ) {
				return var_export( $this->_data, true );
			}

			return $html;
		}

		private function _parse_layout_intest() {
			$html = '';

			//define the loopable items
			$this->_item_sets = $this->_data['PlanItems']['PlanItem'];
			if ( is_array( $this->_item_sets ) && $this->_is_array_associative( $this->_item_sets ) ) {
				$this->_item_sets = array();
				$this->_item_sets[] = $this->_data['PlanItems']['PlanItem'];
			}
			$this->_items = array();

			$html .= $this->_loop();

			return $html;
		}


		private function _parse_layout_trainingarea() {
			$html = '';

			//define the loopable items
			$this->_item_sets = $this->_data['PlanItems']['PlanItem'];
			if ( is_array( $this->_item_sets ) && $this->_is_array_associative( $this->_item_sets ) ) {
				$this->_item_sets = array();
				$this->_item_sets[] = $this->_data['PlanItems']['PlanItem'];
			}
			$this->_items = array();

			$html .= $this->_loop();

			return $html;
		}


		private function _parse_layout_education() {
			$html = '';

			//define the loopable items
			$this->_item_sets = $this->_data['Packages']['Package'];
			if ( is_array( $this->_item_sets ) && $this->_is_array_associative( $this->_item_sets ) ) {
				$this->_item_sets = array();
				$this->_item_sets[] = $this->_data['Packages']['Package'];
			}
			$this->_items = array();

			$html .= $this->_loop();

			return $html;
		}

		private function _parse_layout_theory() {

			$html = '';

			//define the loopable items
			$this->_item_sets = $this->_data['PlanItemSets']['PlanItemSet'];
			if ( is_array( $this->_item_sets ) && $this->_is_array_associative( $this->_item_sets ) ) {
				$this->_item_sets = array();
				$this->_item_sets[] = $this->_data['PlanItemSets']['PlanItemSet'];
			}
			$this->_items = $this->_data['PlanItems']['PlanItem'];
			if ( is_array( $this->_items ) && $this->_is_array_associative( $this->_items ) ) {
				$this->_items = array();
				$this->_items[] = $this->_data['PlanItems']['PlanItem'];
			}

			$html .= $this->_loop();

			return $html;
		}

		private function _is_array_associative( array $arr ) {
			if ( array() === $arr ) {
				return false;
			}

			return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
		}

		private function _loop() {

			global $template_html, $items, $item_set, $feedcode, $parameters;

			$settings   = new ISV_Settings( 'isv-connect-' . $this->_feed_type . '_template-settings' );
			$parameters = $settings->get_all_values();

			$feedcode = $this->_feedcode;
			$items    = $this->_items;

			$template_html = '';

			if ( defined( 'ISV_TESTMODUS' ) && ISV_TESTMODUS ) {
				$template_html .= '<pre>' . var_export( $parameters, true ) . '</pre>';
			}

			if ( $this->_filters ) {
				$template_html .= $this->_add_filter_bar();
			}

			if ( is_array( $this->_item_sets ) && ( ! empty( $this->_item_sets ) ) ) {

				$template_html .= '<div class="isv-items">';

				foreach ( $this->_item_sets as $item_set ) {
					$this->_get_template_part( $this->_feed_type, $this->_template );
				}

				$template_html .= '</div>';

			} else {

				$template_html .= '<div class="isv-items">' . apply_filters( 'the_content', $this->_content ) . '</div>';

			}

			return $template_html;
		}

		private function _add_filter_bar() {

			$html = '';

			$html .= '<ul class="isv-filters">';
			$html .= '<li>';
			$html .= '<span class="filter-from">' . __( 'From', 'isv-connect' ) . '</span>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<input type="text" id="isv-from-' . esc_attr( $this->_feedcode ) . '" class="isv-datepicker" value="' . esc_attr( $this->_from ) . '">';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<span class="filter-until">' . __( 'Until', 'isv-connect' ) . '</span>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<input type="text" id="isv-until-' . esc_attr( $this->_feedcode ) . '" class="isv-datepicker" value="' . esc_attr( $this->_until ) . '">';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="#" class="isv-button isv-button-fetch-filtered" data-feedcode="' . esc_attr( $this->_feedcode ) . '" data-filters="' . esc_attr( $this->_filters ) . '" data-id="feed-' . esc_attr( $this->_feedcode ) . '" data-content="' . esc_attr( $this->_content )  .'">' . __( 'Fetch',
					'isv-connect' ) . '</a>';
			$html .= '</li>';

			$html .= '</ul>';

			return $html;
		}

		/**
		 * Retrieves a template part
		 *
		 * @since v1.5
		 *
		 * Taken from bbPress
		 *
		 * @param string $slug
		 * @param string $name Optional. Default null
		 *
		 * @uses  _locate_template()
		 * @uses  load_template()
		 * @uses  get_template_part()
		 *
		 * @return string The template filename if one is located.
		 */
		private function _get_template_part( $slug, $name = null, $load = true ) {

			// Execute code for this part
			do_action( 'get_template_part_' . $slug, $slug, $name );

			// Setup possible parts
			$templates = array();
			if ( isset( $name ) ) {
				$templates[] = $slug . '-' . $name . '.php';
			}

			$templates[] = $slug . '.php';

			// Allow template parts to be filtered
			$templates = apply_filters( 'isv_get_template_part', $templates, $slug, $name );

			// Return the part that is found
			return $this->_locate_template( $templates, $load, false );
		}

		/**
		 * Retrieve the name of the highest priority template file that exists.
		 *
		 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
		 * inherit from a parent theme can just overload one file. If the template is
		 * not found in either of those, it looks in the theme-compat folder last.
		 *
		 * Taken from bbPress
		 *
		 * @since v1.5
		 *
		 * @param string|array $template_names Template file(s) to search for, in order.
		 * @param bool $load If true the template file will be loaded if it is found.
		 * @param bool $require_once Whether to require_once or require. Default true.
		 *                            Has no effect if $load is false.
		 *
		 * @return string The template filename if one is located.
		 */
		private function _locate_template( $template_names, $load = false, $require_once = true ) {

			// No file found yet
			$located = false;

			// Try to find a template file
			foreach ( (array) $template_names as $template_name ) {

				// Continue if template is empty
				if ( empty( $template_name ) ) {
					continue;
				}

				// Trim off any slashes from the template name
				$template_name = ltrim( $template_name, '/' );

				// Check child theme first
				if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'isv/' . $template_name ) ) {
					$located = trailingslashit( get_stylesheet_directory() ) . 'isv/' . $template_name;
					break;

					// Check parent theme next
				} elseif ( file_exists( trailingslashit( get_template_directory() ) . 'isv/' . $template_name ) ) {
					$located = trailingslashit( get_template_directory() ) . 'isv/' . $template_name;
					break;

					// Check this plugin's templates folder last
				} elseif ( file_exists( ISV_TEMPLATES_PATH . $template_name ) ) {
					$located = ISV_TEMPLATES_PATH . $template_name;
					break;
				}
			}

			if ( ( true == $load ) && ! empty( $located ) ) {
				load_template( $located, $require_once );
			}

			return $located;
		}
	}
}

