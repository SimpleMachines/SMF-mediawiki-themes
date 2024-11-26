<?php
/**
 * SMF Curve 2
 *
 ** This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * https://www.gnu.org/copyleft/gpl.html
 *
 * Copyright 2023, Simple Machines and Individual Contributors
 *
 * Based On smfcurve by Labradoodle-360
 *
 * Images under separate license
 * @license https://www.simplemachines.org/about/smf/license.php BSD
 */

namespace MediaWiki\Skin\smfcurve2;

use SkinTemplate;
use OutputPage;

/*
 * This is a sample of how to extend and modify the main template, without requiring changes to the main template.
 * Keeps your customizations separated from the logic that builds the base theme
*/
class smfCurve2SkinCustom extends smfCurve2Skin
{
    public $skinname        = 'smfcurve2custom';
    public $stylename       = 'smfcurve2custom';
    public $template        = 'smfCurve2TemplateCustom';
    public $useHeadElement  = true;

	/**
	 * @inheritDoc
	 */
    public function initPage(OutputPage $out)
    {
        parent::initPage($out);

		/**
		* Some shortcuts to more commonly used/needed methods.
		* This is a incomplete list, see /includes/OutputPage.php for the complete list.
		*
		* $out->addStyle
		 * 		@param string $style URL to the file
		 * 		@param string $media To specify a media type, 'screen', 'printable', 'handheld' or any.
		 * 		@param string $condition For IE conditional comments, specifying an IE version
		 * 		@param string $dir Set to 'rtl' or 'ltr' for direction-specific sheets
		 *
		 * $out->addInlineStyle
		 * 		@param mixed $style_css Inline CSS
		 * 		@param string $flip Set to 'flip' to flip the CSS if needed
		 *
		 * $out->addScript
		 * 	!!! Add your own <script> tags, added just before </body>.
	 	 * 		@param string $script Raw HTML
		 *
		 * $out->addScriptFile
		 *  !!! Added just before </body>
		 * 		@param string $file URL to file (absolute path, protocol-relative, or full url)
		 * 		@param string|null $unused Previously used to change the cache-busting query parameter
		 *
		 * $out->addInlineScript
		 *  !!! Wrapped in <script> tags, no additional paramters. Added just before </body>
		 * 		@param string $script JavaScript text, no script tags
		 *
		 * $out->addMeta
		 * 		@param string $name Name of the meta tag
		 * 		@param string $val Value of the meta tag
		 *
		 * $out->addLink
		 * 		@param array $linkarr Associative array of attributes.
		 *
		 * $out->setCanonicalUrl
		 * 		@param string $url
		 *
		 * $out->addBodyClasses
		 * 		@param string|string[] $classes One or more classes to add
		 */

		$out->addModuleStyles( [
			'skins.smfcurve2custom'
		] );

		$out->addModules( [
			'skins.smfcurve2custom.js'
		] );

		// Add some additional css.
		$out->addStyle('https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap', 'screen', '');
	}
}