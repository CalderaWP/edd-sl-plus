<?php

namespace calderawp\eddslplus\handlers;


use calderawp\eddslplus\handlers\interfaces\checkable;
use calderawp\eddslplus\handlers\interfaces\downloadable;

/**
 * Class license
 *
 * Base class that single license view and download response classes use
 *
 * @package calderawp\eddslplus\handlers
 */
abstract class license extends base implements checkable, downloadable {


	public function __construct( $code, \WP_User $user = null ) {
		$this->code = $code;
		$this->user = $user;
		$this->set_license_id();
	}


}