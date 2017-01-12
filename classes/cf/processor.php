<?php

namespace calderawp\eddslplus\cf;


use calderawp\cfeddfields\licenses\query;
use calderawp\eddslplus\handlers\download;

/**
 * Class processor
 *
 * Caldera Forms processor base class
 *
 * @package calderawp\eddslplus\cf
 */
class processor extends \Caldera_Forms_Processor_Processor {

	/**
	 * @var string
	 */
	protected $file;

	/**
	 * @inheritdoc
	 */
	public function pre_processor( array $config, array $form, $proccesid ) {
		$this->set_data_object_initial( $config, $form );
		$code = $this->find_code();

		$check_user = $this->data_object->get_value( 'user-check' );
		$user = null;
		if( 'on' === $check_user || true === $check_user ){

			$user       = $this->get_user();
			$downloader = new download( $code, $user );

			try{
				$downloader->check_user();
			}catch( \Exception $e ){
				$this->data_object->add_error( $e->getMessage() );
			}



		}


		$this->setup_transata( $proccesid );

		$errors = $this->data_object->get_errors();
		if( ! empty( $errors ) ){

			return $errors;
		}

	}

	/**
	 * @inheritdoc
	 */
	public function processor( array $config, array $form, $proccesid ) {
		$this->set_data_object_from_transdata( $proccesid );
		$code = $this->find_code();
		if( false == $code ){
			$this->data_object->add_error( __( 'Download not found or authorized', 'cwp-edd-sl-plus' ) );

		}else{
			$downloader = new download( $code, null );
			try{
				$this->file = $file = $downloader->file();
				global  $transdata;
				if( ! isset( $transdata[ $proccesid ][ 'meta' ] ) ){
					$transdata[ $proccesid ][ 'meta' ] = [];
				}
				$transdata[ $proccesid ][ 'meta' ][ 'file' ] = $file;

			}catch( \Exception $e ){
				$this->data_object->add_error( $e->getMessage() );
			}
		}



		$errors = $this->data_object->get_errors();
		if( ! empty( $errors ) ){
			return $errors;
		}

		add_filter( 'caldera_forms_redirect_url_complete', function( $url ){
			if( isset( $this->file ) && filter_var( $this->file, FILTER_VALIDATE_URL ) ){
				$url = $this->file;
			}

			return $url;

		},2);


		add_filter( 'caldera_forms_render_notices', function( $notices ){
			if( isset( $this->file ) && filter_var( $this->file, FILTER_VALIDATE_URL ) && isset( $notices[ 'success' ], $notices[ 'success' ][ 'note' ] ) ){
				$notices[ 'success' ][ 'note' ] = __( 'Downloading', 'cwp-edd-sl-plus' );
			}

			return $notices;
		});

	}


	protected function find_code(){
		if( init::get_slug( true ) == $this->slug ){
			$code = $this->data_object->get_value( 'code-field' );
		}else{
			( is_object( $user = $this->get_user() )  ? $user = $user->ID : $user = null );
			$code = false;
			global $wpdb;
			$querier = new query( $user, true, $wpdb );
			$licenses = $querier->get_licenses();
			$download = $this->data_object->get_value( 'download-field' );
			if( ! empty( $licenses ) ){
				foreach ( $licenses as   $license ) {
					if( $license[ 'download_id' ] == $download ){
						$code = $license[ 'key' ];
						break;
					}
				}

			}


		}


		return $code;
	}

	/**
	 * Get user -- probably current
	 *
	 * @return false|\WP_User
	 */
	protected function get_user() {
		$user = get_user_by( 'ID', apply_filters( 'cwp_edd_sl_plus_check_user_id', get_current_user_id() ) );

		return $user;
	}
}