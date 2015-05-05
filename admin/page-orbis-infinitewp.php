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

	?>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th scope="col"><?php _e( 'Name', 'orbis_infinitewp' ); ?></th>
				<th scope="col"><?php _e( 'Orbis', 'orbis_infinitewp' ); ?></th>
				<th scope="col"><?php _e( 'InfiniteWP', 'orbis_infinitewp' ); ?></th>
			</tr>
		</thead>

		<tbody>
			
			<?php foreach ( $sites as $name ) : ?>

				<tr>
					<td>
						<?php echo $name; ?>
					</td>
					<td>
						<?php

						$dashicon = isset( $orbis_subscriptions[ $name ] ) ? 'yes' : 'no';

						printf( '<span class="dashicons dashicons-%s"></span>', $dashicon );

						?>
					</td>
					<td>
						<?php

						$dashicon = isset( $infinitewp_sites[ $name ] ) ? 'yes' : 'no';

						printf( '<span class="dashicons dashicons-%s"></span>', $dashicon );

						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</div>
