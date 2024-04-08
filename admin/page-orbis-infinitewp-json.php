<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<pre><?php

	$websites = $this->plugin->get_websites();

	echo wp_json_encode( $websites, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES );

	?></pre>
</div>
