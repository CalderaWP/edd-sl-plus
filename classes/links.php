<?php

namespace calderawp\eddslplus;

/**
 * Class links
 *
 * Links to our
 * @package calderawp\eddslplus
 */
class links {


	public static function details( $code ){
		return home_url( sprintf( '/%s/%s' ), endpoints::ROOT, $code );
	}

	public static function download( $code ){
		return home_url( sprintf( '/%s/%s/%s' ), endpoints::ROOT, $code, endpoints::DOWNLOAD );
	}

	public static function download_alt( $code ){
		return add_query_arg( 'code', $code, home_url( endpoints::ROOT ) );
	}

}