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
		if (defined('SMF') && $this->showSMFmenu) {
			$this->smfUserMenu();
			$this->userMenu(['preferences']);
		} else {
			$this->userMenu(['userpage', 'preferences']);
		}

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

	public function smfUserMenu()
	{
		global $settings, $txt, $context, $scripturl;

		if (!(defined('SMF') && $this->showSMFmenu)) {
			return;
		}

		// If the user is logged in, display some things that might be useful.
		if ($context['user']['is_logged']) {
			// Firstly, the user's menu
			echo '
				<ul class="floatleft" id="top_info">
					<li data-usermenu="profile" data-href="', $scripturl, '?action=profile;area=popup">
						<a href="', $scripturl, '?action=profile"', !empty($context['self_profile']) ? ' class="active"' : '', ' id="profile_menu_top">';

			if (!empty($context['user']['avatar'])) {
				echo $context['user']['avatar']['image'];
			}

			echo '<span class="textmenu">', $context['user']['name'], '</span></a>
						<div id="profile_menu" class="top_menu"></div>
					</li>';

			// Secondly, PMs if we're doing them
			if ($context['allow_pm']) {
				echo '
					<li data-usermenu="pm" data-href="', $scripturl, '?action=profile;area=alerts_popup">
						<a href="', $scripturl, '?action=pm"', !empty($context['self_pm']) ? ' class="active"' : '', ' id="pm_menu_top">
							<span class="main_icons inbox"></span>
							<span class="textmenu">', $txt['pm_short'], '</span>', !empty($context['user']['unread_messages']) ? '
							<span class="amt">' . $context['user']['unread_messages'] . '</span>' : '', '
						</a>
						<div id="pm_menu" class="top_menu scrollable"></div>
					</li>';
			}

			// Thirdly, alerts
			echo '
					<li data-usermenu="alerts" data-href="', $scripturl, '?action=pm;sa=popup">
						<a href="', $scripturl, '?action=profile;area=showalerts;u=', $context['user']['id'], '"', !empty($context['self_alerts']) ? ' class="active"' : '', ' id="alerts_menu_top">
							<span class="main_icons alerts"></span>
							<span class="textmenu">', $txt['alerts'], '</span>', !empty($context['user']['alerts']) ? '
							<span class="amt">' . $context['user']['alerts'] . '</span>' : '', '
						</a>
						<div id="alerts_menu" class="top_menu scrollable"></div>
					</li>';

			// A logout button for people without JavaScript.
			if (empty($settings['login_main_menu'])) {
				echo '
					<li id="nojs_logout">
						<a href="', $scripturl, '?action=logout;', $context['session_var'], '=', $context['session_id'], '">', $txt['logout'], '</a>
						<script>document.getElementById("nojs_logout").style.display = "none";</script>
					</li>';
			}

			// And now we're done.
			echo '
				</ul>';
		} else {
			// Some people like to do things the old-fashioned way.
			if (!empty($settings['login_main_menu'])) {
				echo '
				<ul class="floatleft">
					<li class="welcome">', sprintf($txt[$context['can_register'] ? 'welcome_guest_register' : 'welcome_guest'], $context['forum_name_html_safe'], $scripturl . '?action=login', 'return reqOverlayDiv(this.href, ' . JavaScriptEscape($txt['login']) . ', \'login\');', $scripturl . '?action=signup'), '</li>
				</ul>';
			} else {
				echo '
				<ul class="floatleft" id="top_info">
					<li class="welcome">
						', sprintf($txt['welcome_to_forum'], $context['forum_name_html_safe']), '
					</li>
					<li class="button_login">
						<a href="', $scripturl, '?action=login" class="', $context['current_action'] == 'login' ? 'active' : 'open','" onclick="return reqOverlayDiv(this.href, ' . JavaScriptEscape($txt['login']) . ', \'login\');">
							<span class="main_icons login"></span>
							<span class="textmenu">', $txt['login'], '</span>
						</a>
					</li>';

				if ($context['can_register']) {
					echo '
					<li class="button_signup">
						<a href="', $scripturl, '?action=signup" class="', $context['current_action'] == 'signup' ? 'active' : 'open','">
							<span class="main_icons regcenter"></span>
							<span class="textmenu">', $txt['register'], '</span>
						</a>
					</li>';
				}

				echo '
				</ul>';
			}
		}
	}
}
