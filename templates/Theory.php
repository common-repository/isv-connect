<?php

global $template_html, $items, $item_set, $feedcode, $parameters;

//first we harvest all the sub-items for this set.
$set_key = $item_set['Key'];
$sub_items = array();
foreach( $items as $item ) {
	if ( $item['SetKey'] == $set_key ) {
		$sub_items[] = $item;
	}
}

//if there where subitems found, we proceed
if ( ! empty( $sub_items ) ) {

	$template_html .= '<div class="isv-item">';

	//show the name of this set
	$template_html .= '<div class="isv-row isv-row-Product_Name">';
	$template_html .= '<h2>' . $item_set['Product_Name'] . '</h2>';
	$template_html .= '</div><!-- .isv-row -->';

	//loop through al the subitems
	foreach( $sub_items as $item ) {

		//Loop through all the available params
		foreach( $parameters as $parameter_name => $parameter_value ) {
			$template_html .= '<div class="isv-row isv-row-' . esc_attr( $parameter_name ) .  ' isv-row-' . esc_attr( $parameter_value ) .  '">';
			$template_html .= '<span class="isv-row-content" data-title="' . esc_attr__( $parameter_name, 'isv-connect' ) . '">' . isv_parse_parameter( $parameter_name, $item[ $parameter_name ] ). '</span>';
			$template_html .= '</div><!-- .isv-row -->';

		}

		$template_html .= '<br/>';
	}

	if ( $item_set['OnlineAvailability'] == 'Booked' ) {

		$template_html .= '<div class="isv-row isv-row-cta">';
		$template_html .= '<a class="isv-button isv-booked-button">' . __( 'Booked', 'isv-connect' ) . '</a>';
		$template_html .= '</div><!-- .isv-row -->';
	} else {

		//Add the call to action = booking button for this entire set
		$template_html .= '<div class="isv-row isv-row-cta">';
		$template_html .= '<a class="isv-button isv-book-button" href="#" data-key="' . esc_attr( $item['Key'] ) . '" data-feedcode="' . esc_attr( $feedcode ) . '">' . __( 'Add to cart', 'isv-connect' ) . '</a>';
		$template_html .= '</div><!-- .isv-row -->';
	}

	$template_html .= '</div><!-- .isv-item -->';
}