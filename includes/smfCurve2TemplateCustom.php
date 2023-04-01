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

use BaseTemplate;
use File;
use Html;
use Linker;
use MediaWiki\MediaWikiServices;
use MWDebug;
use ResourceLoaderSkinModule;
use Sanitizer;
use SpecialPage;
use Xml;
use Hooks;
Use RequestContext;

/*
 * This is a sample of how to extend and modify the main template, without requiring changes to the main template.
 * Keeps your customizations separated from the logic that builds the base theme
*/
class smfCurve2TemplateCustom extends smfCurve2Template
{
	public function customTopSection()
	{
		// Only show the user page and preferences.
		// Additional options removed: ['uls', 'mytalk', 'watchlist', 'mycontris']
		$this->userMenu(['userpage', 'preferences']);

		// Split the menu.
		echo '<div class="floatright">';
		$this->userMenu(['userpage', 'preferences'], true, 'ue');
		echo '</div>';

	}

	public function customPageFooterExtra()
	{
		echo '
			<div class="clear">
				<ul>
					<li class="copyright">Copyright &copy; ' . date('Y') . ' All Rights Reserved.</li>
					<li class="floatright">Page created in ', sprintf('%01.3f', microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']), ' seconds.</li>
				</ul>
			</div>';
	}
}