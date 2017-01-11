<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 8:17 PM
 */

namespace calderawp\eddslplus\handlers;


use CalderaWP\EDD\SL\file;

class download extends license {

	public function file(){
		$download_id = $this->get_download_id();
		$payment_id = \EDD_Software_Licensing::instance()->get_payment_id( $this->license_id );
		$payment_key = edd_get_payment_key( $payment_id );
		$file_key  = get_post_meta( $download_id, '_edd_sl_upgrade_file_key', true );
		$email       = edd_get_payment_user_email( $payment_id );

		$file = edd_get_download_file_url( $payment_key, $email, $file_key, $download_id );


		if( filter_var( $file, FILTER_VALIDATE_URL ) ){
			return $file;
		}else{
			throw new \Exception( __( 'File not found', 'cwp-edd-sl-plus' ) );
		}
	}

	public function get_template(){

	}

	public function download(){
		$files = $this->download();
	}

}