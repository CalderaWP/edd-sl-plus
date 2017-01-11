<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 9:01 PM
 */

namespace calderawp\eddslplus\handlers;


use calderawp\eddslplus\handlers\interfaces\checkable;

abstract class base implements checkable{
	/** @var  \WP_User */
	protected $user;

	/** @var  int */
	protected $license_id;
	/** @var  string */
	protected $code;

	protected function set_license_id(){
		$this->license_id = \EDD_Software_Licensing::instance()->get_license_by_key( $this->code );
	}

	public function check_user() {
		if ( ! is_object( $this->user ) ) {
			throw new \Exception( __( 'No user provided to verify code', 'cwp-edd-sl-pro' ) );
		}

		$user_id = \EDD_Software_Licensing::instance()->get_user_id( $this->license_id );
		if( $user_id != $this->user->ID ){
			throw new \Exception( __( 'This download is not allowed for this user', 'cwp-edd-sl-pro' ) );
		}

		return true;
	}


	protected function get_download_id(){
		return \EDD_Software_Licensing::instance()->get_download_id_by_license( $this->code );
	}


}