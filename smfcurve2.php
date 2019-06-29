<?php

if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'smfCurve2' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['smfCurve2'] = __DIR__ . '/i18n';
	/* wfWarn(
		'Deprecated PHP entry point used for smfCurve2 skin. Please use wfLoadSkin instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	); */
	return true;
} else {
	die( 'This version of the smfCurve2 skin requires MediaWiki 1.25+' );
}
