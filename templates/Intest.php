<?php

global $template_html, $items, $item_set, $feedcode, $parameters;

$template_html .= '<div class="isv-item">';

//Loop through all the available params
foreach( $parameters as $parameter_name => $parameter_value ) {
	$template_html .= '<div class="isv-row isv-row-' . esc_attr( $parameter_name ) .  ' isv-row-' . esc_attr( $parameter_value ) .  '">';
	$template_html .= '<span class="isv-row-content" data-title="' . esc_attr__( $parameter_name, 'isv-connect' ) . '">' . isv_parse_parameter( $parameter_name, $item_set[ $parameter_name ] ). '</span>';
	$template_html .= '</div><!-- .isv-row -->';
}

//Add the call to action
$template_html .= '<div class="isv-row isv-row-cta">';
$template_html .= '<a class="isv-button isv-book-button" href="#" data-key="' . esc_attr( $item_set['Key'] ) . '" data-feedcode="' . esc_attr( $feedcode ) . '">' . __( 'Book now', 'isv-connect' ) . '</a>';
$template_html .= '</div><!-- .isv-row -->';

$template_html .= '</div><!-- .isv-item -->';