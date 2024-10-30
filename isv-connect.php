<?php

/**
 * Plugin Name: ISV Connect
 * Version: 1.4
 * Plugin URI: https://www.radishconcepts.com/isv-connect
 * Description: This plugin connects your website to the ISV Software system
 * Author: Radish Concepts
 * Author URI: https://www.radishconcepts.com
 * Text Domain: isv-connect
 * Domain Path: /languages/
 * License: GPL v3
 */

/**
 * ISV Connect Plugin
 * Copyright (C) 2018, Radish Concepts BV - support@radishconcepts.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

define( 'ISV_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'ISV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ISV_CLASS_PATH', ISV_PLUGIN_PATH . '/classes/' );
define( 'ISV_INCLUDES_PATH', ISV_PLUGIN_PATH . '/includes/' );
define( 'ISV_TEMPLATES_PATH', ISV_PLUGIN_PATH . '/templates/' );
define( 'ISV_LANGUAGE_PATH', ISV_DIRNAME . '/languages' ); ///needs to be relative to the plugins-base-dir
define( 'ISV_PLUGIN_URI', WP_PLUGIN_URL . '/' . ISV_DIRNAME );

if ( ! defined( 'ISV_TESTMODUS' ) ) {
	define( 'ISV_TESTMODUS', false);
}

/**
 * Read the classes directory and include all php-files inside by 'require_once'.
 */
foreach ( glob( ISV_CLASS_PATH . '*.php' ) as $file ) {
	require_once( $file );
}

/**
 * Read the includes directory and include all php-files inside by 'require_once'.
 */
foreach ( glob( ISV_INCLUDES_PATH . '*.php' ) as $file ) {
	require_once( $file );
}

//if it exists, create it!
if ( class_exists( 'ISV_Connect' ) ) {
	global $isv_connect;
	$isv_connect = new ISV_Connect();
	$isv_connect->init();
}