<?php

if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'smfcurve2' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['smfcurve2'] = __DIR__ . '/i18n';
	/* wfWarn(
		'Deprecated PHP entry point used for smfcurve2 skin. Please use wfLoadSkin instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	); */
	return true;
} else {
	die( 'This version of the smfcurve2 skin requires MediaWiki 1.31+' );
}
