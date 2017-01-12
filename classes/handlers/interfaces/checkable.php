<?php

namespace calderawp\eddslplus\handlers\interfaces;

/**
 * Interface checkable
 *
 * For responses or whatever that require checking of user's ownership of code
 * @package calderawp\eddslplus\handlers\interfaces
 */
interface checkable {
	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function check_user();

}