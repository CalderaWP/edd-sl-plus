<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 8:02 PM
 */

namespace calderawp\eddslplus;


use calderawp\cfeddfields\fields\license;
use calderawp\eddslplus\handlers\all;
use calderawp\eddslplus\handlers\details;
use calderawp\eddslplus\handlers\download;

class endpoints {

	const ROOT = 'licenses';

	const DOWNLOAD = 'download';

	const QUERYVAR = 'cwp-edd-sl-plus-route';

	const LICENSE_QUERYVAR = 'cwp-ed-sl-plus-license-code';


	protected $route;


	public function __construct() {
		$this->add_hooks();
	}

	protected function add_hooks(){
		add_filter( 'parse_request', function( \WP $request ) {

			$parts = explode( '/', $request->request );
			if( ! empty( $parts ) && self::ROOT == $parts[0] ){
				$request->set_query_var( self::QUERYVAR,  self::ROOT );
				if ( isset( $parts[1]) ) {
					$request->set_query_var( self::LICENSE_QUERYVAR, $parts[ 1 ] );
					if( ! empty( $parts[2] ) && self::DOWNLOAD == $parts[2] ){
						$request->set_query_var( self::QUERYVAR, self::DOWNLOAD );
					}else{
						$request->set_query_var( self::QUERYVAR, 'single' );
					}
				}



			}


		} );

		/**
		 *
		 */
		add_filter( 'query_vars', function( array  $query_vars ) {
			return array_merge( $query_vars, [ self::QUERYVAR, self::LICENSE_QUERYVAR ] );

		} );

		add_action( 'template_redirect', function(){

			if(  get_query_var( self::QUERYVAR ) ){

				$response = new download( get_query_var( self::LICENSE_QUERYVAR ), get_user_by( 'ID', get_current_user_id() ) );
				try {
					$file = $response->file();
					wp_redirect( $file );
					exit;
				} catch ( \Exception $e ) {
					wp_die( $e->getMessage() );
				}

			}
		});

	}

	protected function response( $type ){
		$code = get_query_var( self::LICENSE_QUERYVAR );
		$user =  get_user_by( 'ID', get_current_user_id() );
		if( $type === self::DOWNLOAD ){
			$response = new download( $code, $user  );
		}elseif( 'single' == self::DOWNLOAD ){
			$response = new details( $code, $user );
		}else{
			$response = new all( $user );
			add_filter( 'cwp_edd_sl_plus_check_user', '__return_true' );
		}

		if( apply_filters( 'cwp_edd_sl_plus_check_user', false ) ){
			try {
				$response->check_user();
			}catch ( \Exception $e ){
				wp_die( $e->getMessage() );
			}

		}


		return $response;

	}




}