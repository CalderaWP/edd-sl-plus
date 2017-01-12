<?php

namespace calderawp\eddslplus\handlers\interfaces;

/**
 * Interface downloadable
 *
 * For responses or whatever that can generate a URL for a download from code or whatever
 *
 * @package calderawp\eddslplus\handlers\interfaces
 */
interface downloadable {

	/**
	 * Return URL of file
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function file();
}