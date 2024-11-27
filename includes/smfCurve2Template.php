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
use RequestContext;
use Sanitizer;
use SpecialPage;
use Xml;

/**
 * If you wish to add your SMF forum's Menu bar :
 *
 * Remove the Comment,
 * Correct the path to the SSI.php file located in the SMF software directory.
 * And Set $showSMFmenu to true in line 94
 */
//	require_once("path/to/SSI.php");
class smfCurve2Template extends BaseTemplate
{
	/**
	 * @var array
	 */
	protected $pileOfTools;

	/**
	 * @var (array|false)[]
	 */
	protected $sidebar;

	/**
	 * @var array|null
	 */
	protected $otherProjects;

	/**
	 * @var array|null
	 */
	protected $collectionPortlet;

	/**
	 * @var array[]
	 */
	protected $languages;

	/**
	 * @var string
	 */
	protected $afterLangPortlet;
	protected $skin;
	protected $currentAction;

	/**
	 * @var bool
	 */
	protected $showSMFmenu = false;
	protected $useLogoImage = false;
	protected $useSideSearchBox = false;

	/**
	 * @return Config
	 */
	protected function getConfig()
	{
		return $this->config;
	}

	/**
	 * Setup customizations
	 */
	private function setupCustomization()
	{
		global $wgRequest;

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

		// Add to your LocalSettings: $wgshowSMFmenu = true;
		if (is_bool($this->getConfig()->get('showSMFmenu'))) {
			$this->showSMFmenu = $this->getConfig()->get('showSMFmenu');
		}

		// Add to your LocalSettings: $wguseLogoImage = true;
		if (is_bool($this->getConfig()->get('useLogoImage'))) {
			$this->useLogoImage = $this->getConfig()->get('useLogoImage');
		}

		// Add to your LocalSettings: $wguseSideSearchBox = true;
		if (is_bool($this->getConfig()->get('useSideSearchBox'))) {
			$this->useSideSearchBox = $this->getConfig()->get('useSideSearchBox');
		}

		$this->skin = $skin = $this->data['skin'];

		$this->currentAction = $wgRequest->getText('action');
	}

	/**
	 * Outputs the entire contents of the page
	 */
	public function execute()
	{
		$this->setupCustomization();

		echo '
		<!-- Start smfcuve2 -->
		<div id="footerfix">';

			echo '
			<div id="top_section">
				<div class="inner_wrap">
					', $this->customTopSection(), '
				</div>
			</div>
			<!-- #top_section -->
			<div id="header">';

			// Logo or Title
			if (method_exists($this, 'customHeaderSection')) {
				$this->customHeaderSection();
			}

			// Customization...
			if (method_exists($this, 'customHeaderContent')) {
				$this->customHeaderContent();
			}

			echo '
			</div>
			<!-- #header -->
			<div id="wrapper">
				<div id="upper_section">
					<div id="inner_section">

						<div class="inner_wrap">';

						// Do we have an SMF Menu ?
						$this->smfMenu();

						echo '
						</div>
						<hr class="clear ', (defined('SMF') && $this->showSMFmenu) ? '' : 'hidden', '">

						<a class="menu_icon mobile_generic_menu_0"></a>
						<div id="genericmenu" class="floatleft">
							<div id="mobile_generic_menu_0" class="popup_container">
								<div class="popup_window description">
									<div class="popup_heading">
										', $this->getMsg('smfcurve2-mw-menu')->text(), '
										<a href="javascript:void(0);" class="generic_icons delete hide_popUp"></a>
									</div>
									<div class="genericmenu">
										<ul class="dropmenu dropmenu_menu_0">';

										foreach ($this->data['content_actions'] as $key => $tab) {
											/*
												We don't want to give the watch tab an accesskey if the
												page is being edited, because that conflicts with the
												accesskey on the watch checkbox.  We also don't want to
												give the edit tab an accesskey, because that's fairly su-
												perfluous and conflicts with an accesskey (Ctrl-E) often
												used for editing in Safari.
											*/
											echo '
											<li id="', Sanitizer::escapeIdForAttribute('ca-' . $key), '"', (!empty($tab['class']) ? ' class="' . htmlspecialchars($tab['class']) . '"' : ''), '>
												<a href="', htmlspecialchars($tab['href']), '"', (in_array($this->currentAction, ['edit', 'submit']) && in_array($key, ['edit', 'watch', 'unwatch' ])), ' class="firstlevel', $tab['class'] == 'selected' ? ' active' : '', '"><span class="generic_icons ', Sanitizer::escapeIdForAttribute($key), '"></span><span class="firstlevel">' . htmlspecialchars($tab['text']) . '</span></a>
											</li>';
										}
										echo '
										</ul>
									</div>
								</div>
							</div>
						</div>';

					// Let's get dem Indicators, such as the Help link.
					if (is_callable([ $this, 'getIndicators' ])) {
						echo '<div class="indicator floatright">',$this->getIndicators(),'</div>';
					}

				echo '
					</div>
				</div>
				<!-- #upper_section -->';

				// Customization...
				if (method_exists($this, 'customUserAreaAddon')) {
					$this->customUserAreaAddon();
				}

				echo '
				<div id="content_section">
					<!-- #content_section .frame -->
					<div class="frame">
						<div id="main_content_section">
							<div id="sleft-side" class="floatleft clear_left">
								<div id="column-one"', $this->html('userlangattributes'), '>';

					$sidebar = $this->data['sidebar'];

					// Force the rendering of the following boxes
					if (!isset($sidebar['SEARCH'])) {
						$sidebar['SEARCH'] = true;
					}

					if (!isset($sidebar['TOOLBOX'])) {
						$sidebar['TOOLBOX'] = true;
					}

					if (!isset($sidebar['LANGUAGES'])) {
						$sidebar['LANGUAGES'] = true;
					}

					foreach ($sidebar as $boxName => $cont) {
						if ($cont === false) {
							continue;
						}

						// Numeric strings gets an integer when set as key, cast back - T73639
						$boxName = (string) $boxName;

						switch ($boxName) {
							case 'SEARCH':
								if ($this->useSideSearchBox) {
									$this->buildBox('sb', $this->searchBox(), 'search');
								}
								break;

							case 'TOOLBOX':
								$this->buildBox('tb', $this->get('sidebar')['TOOLBOX'], 'toolbox', 'SkinTemplateToolboxEnd');
								(MediaWikiServices::getInstance()->getHookContainer())->run('smfCurve2AfterToolbox');
								break;

							case 'LANGUAGES':
								if ($this->data['language_urls'] !== false) {
									$this->buildBox('lang', $this->data['language_urls'], 'otherlanguages');
								}
								break;

							default:
								$this->buildBox($boxName, $cont);
								break;
						}
					}

					// Customization...
					if (method_exists($this, 'customSideBarLower')) {
						$this->customSideBarLower();
					}

					echo '
								</div>
								<!-- end of the left (by default at least) column -->
							</div>
							<div id="sright-side" class="clear_right">
								<div id="column-content">';

					// Customization...
					if (method_exists($this, 'customPageContentUpper')) {
						$this->customPageContentUpper();
					}

					echo '
									<div id="content" ', $this->html('specialpageattributes') , '>
										<a id="top"></a>', ($this->data['sitenotice'] ? '
										<div id="siteNotice">' . $this->html('sitenotice') . '</div>' : ''), '
										<div class="cat_bar">
											<h3 class="catbg">
												<span class="left"></span>', $this->html('title'), '
												<span id="siteSub" class="floatright">', $this->getMsg('tagline')->text(), '</span>
											</h3>
											', ($this->data['subtitle'] ? '<div id="contentSub" class="desc">' . $this->data['subtitle'] . '</div>' : ''), '
										</div>

										<!-- start content -->

										<div class="roundframe flow_auto">
											<div class="innerframe">', ($this->data['undelete'] ? '
												<div id="contentSub2">' . $this->html('undelete') . '</div>' : ''), ($this->data['newtalk'] ? '
												<div class="usermessage">' . $this->html('newtalk') . '</div>' : ''), ($this->data['showjumplinks'] ? '
												<div id="jump-to-nav">
													' . $this->getMsg('jumpto')->text() . ' <a href="#column-one">' . $this->getMsg('jumptonavigation')->text() . '</a>, <a href="#searchInput">' . $this->getMsg('jumptosearch')->text() . '</a>
												</div>' : ''),
												$this->html('bodytext'), ($this->data['catlinks'] ? $this->html('catlinks') : ''), '
											</div>
										</div>
									</div>

									<br />
									<!-- end content -->', ($this->data['dataAfterContent'] ?
									$this->html('dataAfterContent') : '') , '
									<div class="visualClear"></div>';

					// Customization...
					if (method_exists($this, 'customPageContentLower')) {
						$this->customPageContentLower();
					}

					echo '
								</div>
							</div>
						</div>
					</div>
					<!-- #content_section .frame -->
					<br class="clear">
				</div>
			</div>
			<!-- #wrapper -->
		</div>
		<!-- #footerfix -->';

		(MediaWikiServices::getInstance()->getHookContainer())->run('smfcurve2BeforeFooter');

		if (method_exists($this, 'customPagePreFooter')) {
			$this->customPagePreFooter();
		}

		echo '
		<div id="footer">
			<div class="frame">';

				// Customization...
				if (method_exists($this, 'customPageFooter')) {
					$this->customPageFooter();
				} else {
					echo '
					<ul class="footer-links floatleft">';

					foreach ($this->getFooterLinks() as $cat => $links) {
						foreach ($links as $link) {
							echo'
							<li class="ft-', $link, '', $link == 'lastmod' ? ' block' : '', '', !empty($link) ? '' : ' hidden', '">', $this->html($link) , '</li>';
						}
					}

					echo'
					</ul>
					<ul class="footer-icons floatright">';

					foreach ($this->get('footericons') as $icon_groups => $icons) {
						foreach ($icons as $icon) {
							echo'
							<li ', !empty($icon) ? '' : 'class="hidden"', '>', $this->getSkin()->makeFooterIcon($icon) , '</li>';
						}
					}

					echo'
					</ul>';
				}

				// Customization...
				if (method_exists($this, 'customPageFooterExtra')) {
					$this->customPageFooterExtra();
				}

				echo '
			</div>
		</div>
		<!-- #footer -->';

		// Customization...
		if (method_exists($this, 'customBodyLower')) {
			$this->customBodyLower();
		}

		echo '
		<!-- End smfcuve2 -->';
	}

	/**
	 * User Menu
	 */
	public function userMenu($limitUrls = [], $inverseLimit = false, $menuID = 'u')
	{
		echo '
		<a class="menu_icon mobile_generic_menu_', $menuID, '"></a>
		<div id="genericmenu">
			<div id="mobile_generic_menu_', $menuID, '" class="popup_container">
				<div class="popup_window description">
					<div class="popup_heading">
						', $this->getMsg('smfcurve2-user-menu')->text(), '
						<a href="javascript:void(0);" class="main_icons delete hide_popUp_', $menuID, '"></a>
					</div>
					<div class="genericmenu">
						<ul', $this->html('userlangattributes') , ' class="floatleft dropmenu dropmenu_menu_', $menuID, '" id="top_info">';

						foreach ($this->data['personal_urls'] as $key => $item) {
							if (!empty($limitUrls) && empty($inverseLimit) && !in_array($key, $limitUrls)) {
								continue;
							}

							if (!empty($limitUrls) && !empty($inverseLimit) && in_array($key, $limitUrls)) {
								continue;
							}

							if (empty($item['href'])) {
								continue;
							}

							echo '
							<li data-key="', $key, '" id="', Sanitizer::escapeIdForAttribute('pt-' . $key), '"', (!empty($item['active']) ? ' class="active"' : ''), '>
								<a href="', htmlspecialchars($item['href']) , '"', (!empty($item['class']) ? ' class="' . htmlspecialchars($item['class']) . '"' : ''), '><span class="main_icons ' . Sanitizer::escapeIdForAttribute($key) . '"></span><span class="pt-itemText">', htmlspecialchars($item['text']), '</span></a>
							</li>';
						}

					echo '			
						</ul>
					</div>
				</div>
			</div>
		</div>';
	}

	/**
	 * Quick Search Box
	 */
	public function quickSearch()
	{
		global $wgUseTwoButtonsSearchForm;

		echo '
		<form id="search_form" class="search_form floatright" action="', $this->get('wgScript'), '">
			<input type="hidden" name="title" value="', $this->get('searchtitle'), '"/>';

		Html::input(
			'search',
			($this->data['search'] ?? ''),
			'search',
			[
				'id' => 'searchInput',
				'title' => 'search',
				'accesskey' => 'search',
			]
		);

		echo '
			<input type="submit" name="go" class="button" value="', $this->getMsg('searcharticle')->text(), '" ', ' />
		</form>';
	}

	/**
	 * Search Block Content
	 *
	 * @returns string $output
	 */
	public function searchBox()
	{
		$output = '
			<form action="' . $this->get('wgScript') . '" id="searchform">
				<input type="hidden" name="title" value="' . $this->get('searchtitle') . '"/>';

		$output .=	Html::input(
			'search',
			($this->data['search'] ?? ''),
			'search',
			[
				'id' => 'searchInput1',
				'title' => 'search',
				'accesskey' => 'search',
				'class' => 'block',
			]
		);

		$output .= '
				<input type="submit" name="fulltext" class="button" value="' . $this->getMsg('searchbutton')->text() . '"' . ' />
				<a href="' . SpecialPage::newSearchPage(RequestContext::getMain()->getUser()) . '" rel="search" class="button">' . $this->getMsg('powersearch-legend')->text() . '</a>
			</form>';

		return $output;
	}

	/**
	 * Tool Box
	 */
	public function toolbox()
	{
		echo '
		<div class="side-block">
			<div class="cat_bar">
				<h3 class="catbg">
					<span class="left"></span>
					', $this->getMsg('toolbox')->text(), '
				</h3>
			</div>
			<div class="windowbg">

				<ul>';

				if ($this->data['notspecialpage']) {
					echo '
					<li id="t-whatlinkshere">
						<a href="', htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href']), '"',  '>',
							$this->getMsg('whatlinkshere')->text(), '
						</a>
					</li>', ($this->data['nav_urls']['recentchangeslinked'] ? '
					<li id="t-recentchangeslinked">
						<a href="' . htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href']) . '"' . '>' .
							$this->getMsg('recentchangeslinked-toolbox')->text() . '
						</a>
					</li>' : '');
				}

				if (isset($this->data['nav_urls']['trackbacklink']) && $this->data['nav_urls']['trackbacklink']) {
					echo '
					<li id="t-trackbacklink">
						<a href="', htmlspecialchars($this->data['nav_urls']['trackbacklink']['href']), '"',  '>
							', $this->getMsg('trackbacklink')->text(), '
						</a>
					</li>';
				}

				if ($this->data['feeds']) {
					echo '
					<li id="feedlinks">';

					foreach ($this->data['feeds'] as $key => $feed) {
						echo '
						<a id="', Sanitizer::escapeIdForAttribute('feed-' . $key) . '" href="', htmlspecialchars($feed['href']), '" rel="alternate" type="application/', $key, '+xml" class="feedlink"', '>', htmlspecialchars($feed['text']), '</a>&nbsp;';
					}
					echo '
					</li>';
				}

				foreach (['contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages'] as $special) {
					if ($this->data['nav_urls'][$special]) {
						echo '
					<li id="t-', $special, '">
						<a href="', htmlspecialchars($this->data['nav_urls'][$special]['href']), '"',  '>', $this->getMsg($special)->text(), '</a>
					</li>';
					}
				}

				if (!empty($this->data['nav_urls']['print']['href'])) {
					echo '
					<li id="t-print">
						<a href="', htmlspecialchars($this->data['nav_urls']['print']['href']), '" rel="alternate"', '>', $this->getMsg('printableversion')->text(), '</a>
					</li>';
				}

				if (!empty($this->data['nav_urls']['permalink']['href'])) {
					echo '
					<li id="t-permalink">
						<a href="', htmlspecialchars($this->data['nav_urls']['permalink']['href']), '"','>', $this->getMsg('permalink')->text(), '</a></li>';
				} elseif (empty($this->data['nav_urls']['permalink']['href'])) {
					echo '
					<li id="t-ispermalink">', $this->getMsg('permalink')->text(), '</li>';
				}

				(MediaWikiServices::getInstance()->getHookContainer())->run('smfCurve2AfterToolboxEnd');

				echo '
				</ul>

			</div>
		</div>';
	}

	/**
	 * @param null|string $msg
	 * @param null|string|array $hook
	 * @param string $name
	 * @param array|string $content
	 */
	protected function buildBox($boxName, $cont, $msg = null, $hook = null)
	{
		if ($msg === null) {
			$msg = $boxName;
		}

		$msgObj = wfMessage($msg);
		$labelId = Sanitizer::escapeIdForAttribute("p-{$boxName}-label");

		echo '
		<div class="side-block" role="navigation" id="', htmlspecialchars(Sanitizer::escapeIdForAttribute("p-{$boxName}")), '" aria-labelledby="', htmlspecialchars($labelId), '">
			<div class="cat_bar">
				<h3 id="', htmlspecialchars($labelId), '" class="catbg" ', $this->html('userlangattributes'), '>
					', htmlspecialchars($msgObj->exists() ? $msgObj->text() : $msg), '
				</h3>
			</div>
			<div class="windowbg">';

				if (is_array($cont)) {
					echo '
					<ul>';

					foreach ($cont as $key => $val) {
						echo $this->makeListItem($key, $val);
					}

					if ($hook !== null) {
						// Avoid PHP 7.1 warning
						$skin = $this;
						(MediaWikiServices::getInstance()->getHookContainer())->run($hook, [ &$skin, true ]);
					}

					echo '
					</ul>';
				} else { // Allow raw HTML block to be defined by extensions
					echo $cont;
				}

				echo '

			</div>
		</div>';
	}

	/*
		The custom top section
	*/
	public function customTopSection()
	{
		$this->userMenu();
		$this->quickSearch();
	}

	/**
	 * The One and only Logo
	 */
	public function customHeaderSection()
	{
		global $wgLogo;

		echo '
		<h1 class="forumtitle">
			<a id="top" href="', htmlspecialchars($this->data['nav_urls']['mainpage']['href']), '" ', Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('p-logo')), '>
				', $this->useLogoImage ? '<img src="' . $wgLogo . '" alt="" title=""/>' : $this->data['sitename'], '
			</a>
		</h1>';
	}

	/**
	 * SMF Related Integrations
	 * Works after correctly filling the SSI.php path on top of this file
	 * And settings showSMFmenu to true.
	 *
	 * smfMenu() --> Loads Forum Menu.
	 */
	public function smfMenu()
	{
		if ((defined('SMF') && $this->showSMFmenu)) {
			echo'
			<a class="menu_icon mobile_generic_menu_main"></a>
			<div id="genericmenu">
				<div id="mobile_generic_menu_main" class="popup_container">
					<div class="popup_window description">
						<div class="popup_heading">
							', $this->getMsg('smfcurve2-mobile-menu')->text(), '
							<a href="javascript:void(0);" class="generic_icons delete hide_popUp_main"></a>
						</div>
						', ssi_menubar(), '
					</div>
				</div>
			</div>';
		}
	}
}
