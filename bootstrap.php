<?php
add_action( 'plugins_loaded', function(){
	include __DIR__ . '/vendor/autoload.php';
	new \calderawp\eddslplus\endpoints();
	add_action( 'caldera_forms_pre_load_processors', function(){
		\calderawp\eddslplus\cf\init::setup_processors();
	});

},2);




