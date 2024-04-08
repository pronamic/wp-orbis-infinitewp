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

	/**
	 * Get Orbis subscriptions
	 */
	public function get_orbis_subscriptions() {
		global $wpdb;

		// Query
		$sql = "
			SELECT
				subscription.id AS subscription_id,
				subscription.name AS subscription_name
			FROM
				$wpdb->orbis_subscriptions AS subscription
					LEFT JOIN
				$wpdb->orbis_products AS product
						ON subscription.product_id = product.id
			WHERE
				(
					subscription.expiration_date > NOW()
						OR
					(
						product.price = 0
							AND
						subscription.cancel_date IS NULL
					)
				)
					AND
				product.type = 'wp_support'
			;
		";

		$results = $wpdb->get_results( $sql );

		$subscriptions = [];

		foreach ( $results as $result ) {
			$subscriptions[ $result->subscription_name ] = $result;
		}

		return $subscriptions;
	}

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

		$sql = 'SELECT siteID, name FROM iwp_sites;';

		$results = $pdo->query( $sql );

		foreach ( $results as $result ) {
			$name = $result['name'];

			$sites[ $name ] = [
				'id'   => $result['siteID'],
				'name' => $name,
			];
		}

		return $sites;
	}

	/**
	 * Get websites.
	 *
	 * @return array<int, array>
	 */
	public function get_websites() {
		$orbis_subscriptions = $this->get_orbis_subscriptions();
		$infinitewp_sites    = $this->get_infinitewp_sites();

		$sites = \array_unique(
			\array_merge(
				\array_keys( $orbis_subscriptions ),
				\array_keys( $infinitewp_sites )
			)
		);

		\sort( $sites );

		$websites = [];

		foreach ( $sites as $site ) {
			if ( \str_starts_with( $site, '*.' ) ) {
				continue;
			}

			$infinitewp_site    = $this->get_array_item_by_site( $site, $infinitewp_sites );
			$orbis_subscription = $this->get_array_item_by_site( $site, $orbis_subscriptions );

			$websites[] = [
				'website'               => $site,
				'infinitewp_id'         => null === $infinitewp_site ? null : $infinitewp_site['id'],
				'orbis_subscription_id' => null === $orbis_subscription ? null : $orbis_subscription->subscription_id,
			];
		}

		return $websites;
	}

	/**
	 * Get item by site.
	 *
	 * @param string $site  Site.
	 * @param array  $array Array to search in with wildcards.
	 * @return mixed
	 */
	private function get_array_item_by_site( $site, $array ) {
		if ( \array_key_exists( $site, $array ) ) {
			return $array[ $site ];
		}

		$parts = explode( '.', $site );

		$count_parts = count( $parts );

		for ( $i = 0; $i < ( $count_parts - 1); $i++ ) {
			$tests = [
				[
					[ '*' ],
					array_slice( $parts, ( $i + 1 ) ),
				],
			];

			// Test *.example.com for example.com.
			if ( 2 === $count_parts ) {
				$tests[] = [
					[ '*' ],
					array_slice( $parts, $i ),
				];
			}

			if ( $i > 0 ) {
				$tests[] = [
					array_slice( $parts, 0, $i ),
					[ '*' ],
					array_slice( $parts, ( $i + 1 ) ),
				];
			}

			foreach ( $tests as $test ) {
				$key = implode( '.', array_merge( ...$test ) );

				if ( \array_key_exists( $key, $array ) ) {
					return $array[ $key ];
				}
			}
		}

		return null;
	}
}
