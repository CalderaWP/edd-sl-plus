<?php


namespace calderawp\eddslplus\cf;
use calderawp\cfeddfields\setup;

/**
 * Class init
 *
 * Make Caldera Forms processors go
 *
 * @package calderawp\eddslplus\cf
 */
class init {

	/**
	 * Slug for processor
	 *
	 * @var string
	 */
	protected static $slug = 'cwp-edd-sl-plus';

	public static function setup_processors(){

		new processor( self::config_by_code(), self::fields( true ), static::get_slug( true ) );

		new processor( self::config_by_name(), self::fields( false ), static::get_slug( false ) );

	}


	public static function add_hooks(){
		add_filter( 'caldera_forms_render_get_field', [ __CLASS__, 'setup_field' ], 10 ,2 );
		add_filter( 'caldera_forms_processor_value', [ __CLASS__, 'fix_required_for_user_check'], 50, 2 );
	}

	public static function get_slug( $code = true ){
		if( $code ){
			return static::$slug;
		}

		return static::$slug . '-list';
	}
	/**
	 * Field for proccesor
	 *
	 * @param bool $code Optional. True, the default for by code processor. False for by field processor
	 * @return array
	 */
	public static function fields( $code = true ){
		if( $code ){
			$fields = [
				[
					'id'       => 'code-field',
					'label'    => __( 'Code', 'cwp-edd-sl-plus' ),
					'desc'      => __( 'The field to enter EDD license code.', 'cwp-edd-sl-plus'),
					'type'     => 'text',
					'magic'    => 'true',
					'required' => 'true'
				]
			];
		}else{
			$fields[] = [
				'id'       => 'download-field',
				'label'    => __( 'Download', 'cwp-edd-sl-plus' ),
				'desc'      => __( 'The field for selecting which file to download.', 'cwp-edd-sl-plus'),
				'type'     => 'advanced',
				'allow_types'    =>[ 'filtered_select2', 'dropdown' ],
				'required' => 'true'
			];
		}

			$fields[] = [
				'id'       => 'user-check',
				'label'    => __( 'Check User?', 'cwp-edd-sl-plus' ),
				'desc'      => __( 'If selected, user must be logged in and be the owner of the license code.', 'cwp-edd-sl-plus'),
				'type'     => 'checkbox',
				'magic'    => 'true',
				'required' => 'false'
			];

		return $fields;

	}

	/**
	 * Config for processor by code processor
	 *
	 * @return array
	 */
	public static function config_by_code(){
		return [
			'name' => __( 'EDD Downloader (By Code)', 'cwp-edd-sl-plus' ),
			'description' => __( 'Download Easy Digital Downloads Files by License Code', 'cwp-edd-sl-plus' ),
			'cf_ver' => '1.4.6',
			'author' => 'Josh Pollock',
			///'icon' => CF_EDD_PRO_URL . '/icon.png',
			'template' => __DIR__ . '/includes/config-by-code.php',
			'single' => true,
		];
	}


	/**
	 * Config for processor by code processor
	 *
	 * @return array
	 */
	public static function config_by_name(){
		return [
			'name' => __( 'EDD Downloader (By Name)', 'cwp-edd-sl-plus' ),
			'description' => __( 'Download Easy Digital Downloads Files by Name', 'cwp-edd-sl-plus' ),
			'cf_ver' => '1.4.6',
			'author' => 'Josh Pollock',
			///'icon' => CF_EDD_PRO_URL . '/icon.png',
			'template' => __DIR__ . '/includes/config-by-name.php',
			'single' => true,
		];
	}

	public static function setup_field( $field, $form ){
		$processors = \Caldera_Forms::get_processor_by_type( \calderawp\eddslplus\cf\init::get_slug( false ), $form );
		if ( empty( $processors ) ) {

			return $field;
		}
		foreach( $processors as $processor ){
			if( $field['ID'] === $processor['config']['download-field'] ){
				$field = setup::populate_field_by_licenses( $field, get_current_user_id(), false );

				break;
			}
		}

		return $field;
	}

	public static function fix_required_for_user_check( $value, $field ){
		if( 'user-check' == $field && ! in_array( $value, [ 'on', true, 'true' ] )  ){
			$value = '---';
		}

		return $value;
	}

}