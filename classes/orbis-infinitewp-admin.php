<?php

/**
 * Title: Orbis InfiniteWP admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Orbis_InfiniteWP_Admin {
	/**
	 * Plugin
	 *
	 * @var Orbis_InfiniteWP_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an Orbis core admin
	 *
	 * @param Orbis_Plugin $plugin
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initalize
	 */
	public function admin_init() {
		add_settings_section(
			'orbis_infinitewp',
			__( 'InfiniteWP', 'orbis_infinitewp' ),
			'__return_false',
			'orbis'
		);

		add_settings_field(
			'orbis_infinitewp_db_name',
			__( 'Database Name', 'orbis_infinitewp' ),
			array( $this, 'input_text' ),
			'orbis',
			'orbis_infinitewp',
			array( 'label_for' => 'orbis_infinitewp_db_name' )
		);

		add_settings_field(
			'orbis_infinitewp_db_user',
			__( 'Database User', 'orbis_infinitewp' ),
			array( $this, 'input_text' ),
			'orbis',
			'orbis_infinitewp',
			array( 'label_for' => 'orbis_infinitewp_db_user' )
		);

		add_settings_field(
			'orbis_infinitewp_db_password',
			__( 'Database Password', 'orbis_infinitewp' ),
			array( $this, 'input_text' ),
			'orbis',
			'orbis_infinitewp',
			array( 'label_for' => 'orbis_infinitewp_db_password' )
		);

		add_settings_field(
			'orbis_infinitewp_db_host',
			__( 'Database Host', 'orbis_infinitewp' ),
			array( $this, 'input_text' ),
			'orbis',
			'orbis_infinitewp',
			array( 'label_for' => 'orbis_infinitewp_db_host' )
		);

		register_setting( 'orbis', 'orbis_infinitewp_db_name' );
		register_setting( 'orbis', 'orbis_infinitewp_db_user' );
		register_setting( 'orbis', 'orbis_infinitewp_db_password' );
		register_setting( 'orbis', 'orbis_infinitewp_db_host' );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=orbis_subscription',
			__( 'Orbis InfiniteWP', 'orbis_infinitewp' ),
			__( 'InfiniteWP', 'orbis_infinitewp' ),
			'orbis_read_infinitewp',
			'orbis_infinitewp',
			array( $this, 'page_orbis_infinitewp' )
		);
	}

	/**
	 * Page Orbis InfiniteWP
	 */
	public function page_orbis_infinitewp() {
		include plugin_dir_path( $this->plugin->file ) . 'admin/page-orbis-infinitewp.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Input text
	 *
	 * @param array $args
	 */
	public function input_text( $args = array() ) {
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text code'
		);

		if ( isset( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				wp_kses_post( $args['description'] )
			);
		}
	}
}
