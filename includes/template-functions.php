<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! function_exists( 'isv_parse_parameter' ) ) {
	function isv_parse_parameter( $key, $value ) {

		if ( in_array( $key, array( 'Start_Unix', 'End_Unix' ) ) ) {

			$datetime_string = '';
			$datetime_string .= '<span class="isv-date">' . date_i18n( get_option( 'date_format' ),
					$value ) . '</span> ';
			$datetime_string .= '<span class="isv-time">' . date_i18n( get_option( 'time_format' ),
					$value ) . '</span> ';

			$value = $datetime_string;

		}

		if ( $key == 'Description' ) {
			$value = apply_filters( 'the_content', $value );
		}

		if ( $key == 'Price' ) {
			$value = '&euro; ' . $value;
		}

		return $value;

	}
}
