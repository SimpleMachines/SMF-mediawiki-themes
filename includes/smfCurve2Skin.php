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

use OutputPage;
use SkinTemplate;

/**
 * SkinTemplate class for the Timeless skin
 *
 * @ingroup Skins
 */
class smfCurve2Skin extends SkinTemplate
{
	public $skinname        = 'smfcurve2';
	public $stylename       = 'smfcurve2';
	public $template        = 'smfCurve2Template';
	public $useHeadElement  = true;

	/**
	 * @inheritDoc
	 */
	public function initPage(OutputPage $out)
	{
		parent::initPage($out);

		// We want it responsive
		$out->addMeta(
			'viewport',
			'width=device-width, initial-scale=1.0, ' .
			'user-scalable=yes, minimum-scale=0.25, maximum-scale=5.0',
		);

		// CSS & Less Files
		$out->addModuleStyles([
			'mediawiki.skinning.content.externallinks',
			'skins.smfcurve2',
		]);

		// Right to left ?
		$out->addStyle('smfcurve2/css/rtl.css', 'screen', '', 'rtl');

		// Load other scripts
		$out->addModules([
			'skins.smfcurve2.js',
		]);
	}
}
