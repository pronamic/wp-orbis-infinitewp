<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php

	$websites = $this->plugin->get_websites();

	?>

	<p>
		<a href="<?php echo esc_url( \add_query_arg( 'format', 'json' ) ); ?>" download="websites-orbis.json"><?php \esc_html_e( 'Download JSON', 'orbis_infinitewp' ); ?></a>
	</p>

	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e( 'Name', 'orbis_infinitewp' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Orbis', 'orbis_infinitewp' ); ?></th>
				<th scope="col"><?php esc_html_e( 'InfiniteWP', 'orbis_infinitewp' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $websites as $website ) : ?>

				<tr>
					<td>
						<?php echo wp_kses_post( $website['website'] ); ?>
					</td>
					<td>
						<?php

						$dashicon = ( null === $website['orbis_subscription_id'] ? 'no' : 'yes' );

						printf( '<span class="dashicons dashicons-%s"></span>', wp_kses_post( $dashicon ) );

						?>
					</td>
					<td>
						<?php

						$dashicon = ( null === $website['infinitewp_id'] ? 'no' : 'yes' );

						printf( '<span class="dashicons dashicons-%s"></span>', wp_kses_post( $dashicon ) );

						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</div>
