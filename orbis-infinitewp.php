<?php
/*
Plugin Name: Orbis InfiniteWP
Plugin URI: http://www.orbiswp.com/
Description: The Orbis InfiniteWP plugin can check InfiniteWP sites against Orbis subscriptions.

Version: 1.0.0
Requires at least: 3.5

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: orbis_infinitewp
Domain Path: /languages/

License: Copyright (c) Pronamic

GitHub URI: https://github.com/wp-orbis/wp-orbis-infinitewp
*/

function orbis_infinitewp_bootstrap() {
	include 'classes/orbis-infinitewp-plugin.php';

	global $orbis_infinitewp_plugin;
	
	$orbis_infinitewp_plugin = new Orbis_InfiniteWP_Plugin( __FILE__ );
}

add_action( 'orbis_bootstrap', 'orbis_infinitewp_bootstrap' );
