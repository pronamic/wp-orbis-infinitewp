<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php

	$orbis_subscriptions = $this->plugin->get_orbis_subscriptions();
	$infinitewp_sites    = $this->plugin->get_infinitewp_sites();

	// Sites
	$sites = array_unique(
		array_merge(
			array_keys( $orbis_subscriptions ),
			array_keys( $infinitewp_sites )
		)
	);

	$websites = array();

	foreach ( $orbis_subscriptions as $subscription ) :

		$name = $subscription[0]->subscription_name;

		$website = [
			'website' => $name,
		];

		if ( array_key_exists( $name, $infinitewp_sites ) ) {
			$infinitewp_site = $infinitewp_sites[ $name ];

			$website['infinitewp_id'] = $infinitewp_site['id'];
		}

		$websites[] = $website;

	endforeach;

	$website = array_column( $websites, 'website' );

	array_multisort( $website, \SORT_ASC, $websites );

	?>

	<pre><?php

	echo wp_json_encode( $websites, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES );

	?></pre>
</div>
