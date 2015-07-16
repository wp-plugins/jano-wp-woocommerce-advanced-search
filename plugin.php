<?php
/*
 Plugin Name: jano wp and woocommerce advanced search
 
 Description: Advance wordpress and WooCommerce product Seach through jquery,Ajax.
 
 Version: 1.0
 
 Author: Allah Noor Burki
 
 Author URI: http://w3beginner.com/
 
*/
define('BTSEARCH_VERSION', '1.0');

define('BTSEARCH_DIR', dirname(__FILE__));

define('BTSEARCH_URL', plugins_url('',__FILE__));

define('PLUGIN_BASENAME', dirname( plugin_basename( __FILE__ ) ));

require_once (BTSEARCH_DIR."/template/load.php");

require_once (BTSEARCH_DIR."/bt-search.php");

$btsearch = new BTsearch;

global $formID;