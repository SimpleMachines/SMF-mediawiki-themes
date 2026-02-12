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
		$this->smfSetup();
		parent::initPage($out);

		global $context;

		// We want it responsive
		$out->addMeta('viewport', 'width=device-width, initial-scale=1');

		// What is your Lollipop's color?
		$out->addMeta('theme-color', '#557EA0');

		// Default JS variables for SMF
		if (defined('SMF') && !empty($context['javascript_vars']) && is_array($context['javascript_vars'])) {
			$script = "<script>";

			foreach ($context['javascript_vars'] as $key => $value) {
				if (!is_string($key) || is_numeric($key))
					continue;

				if (!is_string($value) && !is_numeric($value))
					$value = null;

				$script .= "\n\tvar {$key} = " . ($value ?? 'null') . ";";
			}

			$script .= "\n</script>";
			$out->addHeadItem('smfcurve2-inline-vars', $script);
		}

		// CSS & Less Files
		$out->addModuleStyles([
			'mediawiki.skinning.content.externallinks',
			'skins.smfcurve2'
		]);

		// Right to left ?
		$out->addStyle('smfcurve2/css/rtl.css', 'screen', '', 'rtl');

		// Load other scripts
		$out->addModules([
			'skins.smfcurve2.js'
		]);
	}

	/**
	 * Set up the SMF environment.
	 */
	private function smfSetup()
	{
		// SMF's massive globals.
		global $maintenance, $msubject, $mmessage, $mbname, $language;
		global $boardurl, $boarddir, $sourcedir, $webmaster_email, $cookiename, $db_character_set;
		global $db_type, $db_server, $db_name, $db_user, $db_prefix, $db_persist, $db_error_send, $db_last_error, $db_show_debug;
		global $db_connection, $db_port, $modSettings, $context, $sc, $user_info, $topic, $board, $txt;
		global $smcFunc, $ssi_db_user, $scripturl, $ssi_db_passwd, $db_passwd, $cache_enable, $cachedir;
		global $auth_secret, $cache_accelerator, $cache_memcached;

		// Add to your LocalSettings: $wgsmfRoot = '';
		// If you have the ForumSSoProvider installed you could do: $wgsmfRoot = $wgFSPPath;
		$ssi = $this->getConfig()->get('smfRoot');

		if (!empty($ssi) && is_string($ssi) && file_exists($ssi . '/SSI.php')) {
			include $ssi . '/Settings.php';

			require_once $ssi . '/SSI.php';
		}
	}
}
