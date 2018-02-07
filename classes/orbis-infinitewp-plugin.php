<?php

class Orbis_InfiniteWP_Plugin extends Orbis_Plugin {
	public function __construct( $file ) {
		parent::__construct( $file );

		$this->set_name( 'orbis_infinitewp' );
		$this->set_db_version( '1.0.0' );

		// Admin
		if ( is_admin() ) {
			$this->admin = new Orbis_InfiniteWP_Admin( $this );
		}
	}

	public function loaded() {
		$this->load_textdomain( 'orbis_infinitewp', '/languages/' );
	}

	public function install() {

		parent::install();
	}

	//////////////////////////////////////////////////

	/**
	 * Get Orbis subscriptions
	 */
	public function get_orbis_subscriptions() {
		global $wpdb;

		$subscriptions = array();

		// Query
		$sql = "
			SELECT
				subscription.name AS subscription_name
			FROM
				$wpdb->orbis_subscriptions AS subscription
					LEFT JOIN
				$wpdb->orbis_subscription_products AS product
						ON subscription.type_id = product.id
			WHERE
				subscription.expiration_date > NOW()
					AND
				subscription.type_id IN ( 4, 5, 14, 16, 57, 60 )
			;
		";

		$results = $wpdb->get_results( $sql );

		foreach ( $results as $result ) {
			if ( ! isset( $subscriptions[ $result->subscription_name ] ) ) {
				$subscriptions[ $result->subscription_name ] = array();
			}

			$subscriptions[ $result->subscription_name ][] = $result;
		}

		return $subscriptions;
	}

	//////////////////////////////////////////////////

	/**
	 * Get InfiniteWP PDO object
	 */
	public function get_infinitewp_pdo() {
		$db_name     = get_option( 'orbis_infinitewp_db_name' );
		$db_user     = get_option( 'orbis_infinitewp_db_user' );
		$db_password = get_option( 'orbis_infinitewp_db_password' );
		$db_host     = get_option( 'orbis_infinitewp_db_host' );

		$dsn = 'mysql:dbname=%s;host=%s';

		$dsn = sprintf( $dsn, $db_name, $db_host );

		$pdo = new PDO( $dsn, $db_user, $db_password );

		return $pdo;
	}

	/**
	 * Get InfiniteWP sites
	 */
	public function get_infinitewp_sites() {
		$sites = array();

		$pdo = $this->get_infinitewp_pdo();

		$sql = 'SELECT name FROM iwp_sites;';

		$results = $pdo->query( $sql );

		foreach ( $results as $result ) {
			$name = $result['name'];

			$sites[ $name ] = $name;
		}

		return $sites;
	}
}
