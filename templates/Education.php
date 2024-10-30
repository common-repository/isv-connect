<?php

global $template_html, $items, $item_set, $feedcode, $parameters;

$template_html .= '<div class="isv-item">';

//show the name of this set
$template_html .= '<div class="isv-row isv-row-Product_Name">';
$template_html .= '<h2>' . $item_set['Name'] . '</h2>';
$template_html .= '</div><!-- .isv-row -->';

//Loop through all the available params
foreach( $parameters as $parameter_name => $parameter_value ) {
	$template_html .= '<div class="isv-row isv-row-' . esc_attr( $parameter_name ) .  ' isv-row-' . esc_attr( $parameter_value ) .  '">';
	$template_html .= '<span class="isv-row-content" data-title="' . esc_attr__( $parameter_name, 'isv-connect' ) . '">' . isv_parse_parameter( $parameter_name, $item_set[ $parameter_name ] ). '</span>';
	$template_html .= '</div><!-- .isv-row -->';
}

//Loop through all the available plan-items
$plan_items = $item_set['PlanItems']['PlanItem'];
if ( is_array( $plan_items ) && ( ! empty( $plan_items ) ) ) {

	$template_html .= '<div class="sub-items">';
	$template_html .= '<h3>' . __( 'Roster', 'isv-connect' ) . '</h3>';
	foreach( $plan_items  as $plan_item ) {
		foreach( $parameters as $parameter_name => $parameter_value ) {

			if ( key_exists( $parameter_name, $plan_item ) ) {
				$template_html .= '<div class="isv-row isv-row-' . esc_attr( $parameter_name ) .  ' isv-row-' . esc_attr( $parameter_value ) .  '">';
				$template_html .= '<span class="isv-row-content" data-title="' . esc_attr__( $parameter_name, 'isv-connect' ) . '">' . isv_parse_parameter( $parameter_name, $plan_item[ $parameter_name ] ). '</span>';
				$template_html .= '</div><!-- .isv-row -->';
			}

		}
	}

	$template_html .= '</div>';
}

if ( $item_set['OnlineAvailability'] == 'Booked' ) {

	$template_html .= '<div class="isv-row isv-row-cta">';
	$template_html .= '<a class="isv-button isv-booked-button">' . __( 'Booked', 'isv-connect' ) . '</a>';
	$template_html .= '</div><!-- .isv-row -->';
} else {

	//Add the call to action
	$template_html .= '<div class="isv-row isv-row-cta">';
	$template_html .= '<a class="isv-button isv-book-button" href="#" data-key="' . esc_attr( $item_set['Key'] ) . '" data-feedcode="' . esc_attr( $feedcode ) . '">' . __( 'Add to cart',
			'isv-connect' ) . '</a>';
	$template_html .= '</div><!-- .isv-row -->';

}

$template_html .= '</div><!-- .isv-item -->';