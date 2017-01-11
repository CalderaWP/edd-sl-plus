<?php
/**
 Plugin Name: EDD SL +
 Version: 0.0.1
 */

add_action( 'plugins_loaded', 'cwp_edd_sl_plus_init',0);
function cwp_edd_sl_plus_init(){
	include  dirname( __FILE__ ) . '/bootstrap.php';
}



