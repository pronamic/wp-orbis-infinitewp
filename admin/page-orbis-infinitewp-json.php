<?php

\ob_clean();

\header( 'content-type: application/json' );

echo \wp_json_encode(
	$this->plugin->get_websites(),
	\JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES
);

\ob_end_flush();

exit;
