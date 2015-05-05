<?php

class Orbis_InfiniteWP_Plugin extends Orbis_Plugin {
	public function __construct( $file ) {
		parent::__construct( $file );

		$this->set_name( 'orbis_infinitewp' );
		$this->set_db_version( '1.0.0' );
	}

	public function loaded() {
		$this->load_textdomain( 'orbis_infinitewp', '/languages/' );
	}

	public function install() {
		

		parent::install();
	}
}
