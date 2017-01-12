<?php
add_action( 'plugins_loaded', function(){
	include __DIR__ . '/vendor/autoload.php';

	/** Setup routing */
	new \calderawp\eddslplus\endpoints(
		apply_filters( 'cwp_edd_sl_plus_routing_config', [] )
	);

	/** Setup Caldera Forms Processors */
	add_action( 'caldera_forms_pre_load_processors', function(){
		\calderawp\eddslplus\cf\init::setup_processors();
	});

	/** Add our shortcode -- this placement kind of sucks */
	/** Assumes EDD SL API plugin */
	add_shortcode( 'cwp_edd_sl_pro', function(){
		$view = new \calderawp\eddslplus\views\licenses();
		$view->enqueue_scripts();
		return $view->content();
	});

	/** Setup download by name field */
	add_action( 'init', function(){
		\calderawp\eddslplus\cf\init::add_hooks();
	});

}, 2 );




