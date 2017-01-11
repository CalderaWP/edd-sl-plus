<?php
add_action( 'plugins_loaded', function(){
	include __DIR__ . '/vendor/autoload.php';
	new \calderawp\eddslplus\endpoints();


},2);




