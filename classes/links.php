<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 9:18 PM
 */

namespace calderawp\eddslplus;


class links {


	public static function details( $code ){
		return home_url( sprintf( '/%s/%s' ), endpoints::ROOT, $code );
	}

	public static function download( $code ){
		return home_url( sprintf( '/%s/%s/%s' ), endpoints::ROOT, $code, endpoints::DOWNLOAD );
	}

}