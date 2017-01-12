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

	const USER_CHECK_FILTER = 'cwp_edd_sl_plus_check_user';


	protected $route;

	/**
	 * @var array
	 */
	protected $options;

	protected $defaults = [
		'show_license_list' => true,
		'show_license_single' => true,
		'allow_license_downloads' => true,
		'check_user_for_license_downloads' => false,
	];


	public function __construct( array $options = [] ) {
		$this->options = wp_parse_args( $options, $this->defaults );
		$this->add_hooks();
	}

	/**
	 * Setup hooks to handle request parsing
	 */
	protected function add_hooks(){
		/**
		 * Check if this is a license request
		 *
		 * @TODO FIX THIS HACKY SHIT -- Use proper WordPress hacky shit.
		 */
		add_filter( 'parse_request', function( \WP $request ) {

			$parts = explode( '/', $request->request );
			if( ! empty( $parts ) && self::ROOT == $parts[0] ){

				$request->set_query_var( 'page', self::ROOT );
				$request->set_query_var( self::QUERYVAR,  self::ROOT );
				if ( isset( $parts[1]) ) {
					$request->set_query_var( self::LICENSE_QUERYVAR, $parts[ 1 ] );
					if( ( ! empty( $parts[2] ) && self::DOWNLOAD == $parts[2] ) ){
						$request->set_query_var( self::QUERYVAR, self::DOWNLOAD );
					}else{
						$request->set_query_var( self::QUERYVAR, 'single' );
					}
				} elseif( isset( $_GET[ 'code' ] ) && is_string( $_GET[ 'code' ] ) ){
					$request->set_query_var( self::QUERYVAR, self::DOWNLOAD );
					$request->set_query_var( self::LICENSE_QUERYVAR, strip_tags( $_GET[ 'code' ] ) );
				}



			}


		} );

		/**
		 * Add our query vars
		 */
		add_filter( 'query_vars', function( array  $query_vars ) {
			return array_merge( $query_vars, [ self::QUERYVAR, self::LICENSE_QUERYVAR ] );

		} );

		add_action( 'template_redirect', function(){
			if( get_query_var( self::QUERYVAR ) ){
				//@TODO use response factory here
			}

			if( $this->options[ 'show_license_list' ] ){
				//@TODO license list

			}

			if( $this->options[ 'show_license_single'] ){
				//@TODO license single
			}


			if( self::DOWNLOAD == get_query_var( self::QUERYVAR )  ){
				//@TODO should be set above.
				$response = new download( get_query_var( self::LICENSE_QUERYVAR ), get_user_by( 'ID', get_current_user_id() ) );
				if( apply_filters( self::USER_CHECK_FILTER, $this->options[ 'check_user_for_license_downloads' ]  ) ){
					try {
						$response->check_user();
					}catch( \Exception $e) {
						wp_die( $e->getMessage() );
					}
				}
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


	/**
	 * Factory for response classes
	 *
	 * @param string $type
	 *
	 * @return all|details|download
	 */
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

		if( apply_filters( self::USER_CHECK_FILTER, false ) ){
			try {
				$response->check_user();
			}catch ( \Exception $e ){
				wp_die( $e->getMessage() );
			}

		}


		return $response;

	}



}