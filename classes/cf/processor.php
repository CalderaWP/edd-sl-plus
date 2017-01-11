<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 9:17 PM
 */

namespace calderawp\eddslplus\cf;


use calderawp\eddslplus\handlers\download;

class processor extends \Caldera_Forms_Processor_Processor {

	protected $file;
	public function pre_processor( array $config, array $form, $proccesid ) {
		$this->set_data_object_initial( $config, $form );
		$check_user = $this->data_object->get_value( 'user-check' );
		$code = $this->data_object->get_value( 'code-field' );
		$user = null;
		if( 'on' === $check_user || true === $check_user ){
			$user = apply_filters( 'cwp_edd_sl_plus_check_user_id', get_current_user_id() );
			$user = get_user_by( 'ID', $user );
		}
		$downloader = new download( $code, $user );
		if( $check_user ){
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


	public function processor( array $config, array $form, $proccesid ) {
		$this->set_data_object_from_transdata( $proccesid );
		$code = $this->data_object->get_value( 'code-field' );
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
}