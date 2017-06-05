<?php

if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'smfCurve' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['smfCurve'] = __DIR__ . '/i18n';
	/* wfWarn(
		'Deprecated PHP entry point used for smfCurve skin. Please use wfLoadSkin instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	); */
	return true;
} else {
	die( 'This version of the smfCurve skin requires MediaWiki 1.25+' );
}
