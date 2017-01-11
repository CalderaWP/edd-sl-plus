<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 9:11 PM
 */

namespace calderawp\eddslplus\cf;


class init {

	protected static $slug = 'cwp-edd-sl-plus';

	public static function setup_processors(){

		new processor( self::config_by_code(), self::fields_by_code(), self::$slug );
	}

	public static function fields_by_code(){
		return [
			[
				'id'       => 'code-field',
				'label'    => __( 'Code Field', 'cwp-edd-sl-plus' ),
				'desc'      => __( 'Set the field that the user will enter their EDD license code.', 'cwp-edd-sl-plus'),
				'type'     => 'text',
				'magic'    => 'true',
				'required' => 'true'
			],
			[
				'id'       => 'user-check',
				'label'    => __( 'Check User?', 'cwp-edd-sl-plus' ),
				'desc'      => __( 'If selected, user must be logged in and be the owner of the license code.', 'cwp-edd-sl-plus'),
				'type'     => 'checkbox',
				'magic'    => 'true',
				'required' => 'true'
			],


		];
	}

	public static function config_by_code(){
		return [
			'name' => __( 'EDD Licensed Downloader', 'cwp-edd-sl-plus' ),
			'description' => __( 'Download Easy Digital Downloads Files by License Code', 'cwp-edd-sl-plus' ),
			'cf_ver' => '1.4.6',
			'author' => 'Josh Pollock',
			///'icon' => CF_EDD_PRO_URL . '/icon.png',
			'template' => __DIR__ . '/includes/config-by-code.php',
			'single' => true,
		];
	}

}