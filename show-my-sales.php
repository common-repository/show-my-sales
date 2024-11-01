<?php
/*
  Plugin Name: Show My Sales
  Plugin URI: http://www.mindstien.com/
  Version: 1.1
  Author: Mindstien Technologies
  Author URI: http://www.mindstien.com/
  Description: A WebApp to get Sales data from your WP E-commerce site on mobile devices. Supports WP e-commerce plugin, 5 different currencies, performance optimized basic template to support on old mobile device and for slow data connections and much more...
  Text Domain: show-my-sales
  Copyright @2013 Mindstien Technologies
 */
 
function init_msms() {

	// Include Mpf Plugin Framework class
	require_once 'mpf/mpf.class.php';

	// Create plugin instance
	global $msms;
	$msms = new MPF_FRAMEWORK( __FILE__ );

	// Include options set
	include_once 'mpf/options.php';

	// Create options page
	$msms->add_options_page( array(), $msms_options );

	// Make plugin meta translatable
	__( 'Plugin Name', $msms->textdomain );
	__( 'Author Name', $msms->textdomain );
	__( 'Plugin description', $msms->textdomain );
	
}

add_action( 'init', 'init_msms' );


//include custom plugin (main code) below
require_once 'inc/core.php';
?>