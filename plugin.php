<?php
/*
 Plugin Name: jano wp and woocommerce advanced search
 
 Description: Advance wordpress and WooCommerce product Seach through jquery,Ajax.
 
 Version: 1.5
 
 Author: Allah Noor Burki
 
 Author URI: http://janothemes.com/
 
*/
define('BTSEARCH_VERSION', '1.5');

define('BTSEARCH_DIR', dirname(__FILE__));

define('BTSEARCH_URL', plugins_url('',__FILE__));

define('PLUGIN_BASENAME', dirname( plugin_basename( __FILE__ ) ));

require_once (BTSEARCH_DIR."/template/load.php");

require_once (BTSEARCH_DIR."/bt-search.php");

$btsearch = new BTsearch;

global $formID;

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'btsearch_add_plugin_action_links' );

function btsearch_add_plugin_action_links( $links ) {

	return array_merge( $links,
		array(
			'DD' => '<a target="_blank" href="http://janothemes.com">Demo & Docs</a>'
		)
		
	);
}