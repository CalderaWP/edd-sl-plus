<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 8:18 PM
 */

namespace calderawp\eddslplus\handlers;


use calderawp\eddslplus\handlers\interfaces\checkable;
use calderawp\eddslplus\handlers\interfaces\downloadable;

abstract class license extends base implements checkable, downloadable {


	public function __construct( $code, \WP_User $user = null ) {
		$this->code = $code;
		$this->user = $user;
		$this->set_license_id();
	}





}