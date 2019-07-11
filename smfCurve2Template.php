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
 * http://www.gnu.org/copyleft/gpl.html
 *
 * Copyright 2018, Simple Machines and Individual Contributors
 *
 * Based On smfcurve by Labradoodle-360
 *
 * Images under separate license
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 */


/** 
 * If you wish to add your SMF forum's Menu bar :
 *
 * Remove the Comment,
 * Correct the path to the SSI.php file located in the SMF software directory.
 * And Set $showSMFmenu to true in line 94
 */

//	require_once("path/to/SSI.php");


if (!defined('MEDIAWIKI'))
	die(-1);

class Skinsmfcurve2 extends SkinTemplate
{	
	/**
	 * @param OutputPage $out
	 */
	public function initPage(OutputPage $out)
	{
		parent::initPage($out);

		$this->skinname = 'smfcurve2';
		$this->stylename = 'smfcurve2';
		$this->template = 'smfcurve2Template';
		$this->useHeadElement = true;

		// We want it responsive
		$out->addMeta( 'viewport',
			'width=device-width, initial-scale=1.0, ' .
			'user-scalable=yes, minimum-scale=0.25, maximum-scale=5.0'
		);

		// CSS & Less Files
		$out->addModuleStyles( [
			'mediawiki.skinning.content.externallinks',
			'skins.smfcurve2'
		] );

		// Right to left ?
		$out->addStyle('smfcurve2/css/rtl.css', 'screen', '', 'rtl');

		// Load other scripts
		$out->addModules( [
			'skins.smfcurve2.js'
		] );

	}

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param OutputPage $out
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
	}
}

class smfcurve2Template extends BaseTemplate
{
	var $skin;

	/* SMF Menu Bar, change to true to show. */
	var $showSMFmenu = false;

	// Use a logo or plain text ?
	var $useLogoImage = true;
	// Use another search box placed on the sidebar ?
	var $useSideSearchBox = true;

	/**
	 * Outputs the entire contents of the page
	 */
	public function execute()
	{
		global $wgRequest, $imagesurl;

		$this->skin = $skin = $this->data['skin'];
		$action = $wgRequest->getText('action');

		$this->html('headelement');

		echo '
		<div id="footerfix">';

			echo '
			<div id="top_section">
				<div class="inner_wrap">
					', $this->userMenu(), '
					', $this->quickSearch(), '
				</div>
			</div>
			<!-- #top_section -->
			<div id="header">';

			// Logo or Title
			if (method_exists($this, 'customHeaderSection'))
				$this->customHeaderSection();

			// Customization...
			if (method_exists($this, 'customHeaderContent'))
				$this->customHeaderContent();

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

										foreach ($this->data['content_actions'] as $key => $tab)
										{
											/*
												We don't want to give the watch tab an accesskey if the
												page is being edited, because that conflicts with the
												accesskey on the watch checkbox.  We also don't want to
												give the edit tab an accesskey, because that's fairly su-
												perfluous and conflicts with an accesskey (Ctrl-E) often
												used for editing in Safari.
											*/
											echo '
											<li id="', Sanitizer::escapeId('ca-' . $key), '"', (!empty($tab['class']) ? ' class="' . htmlspecialchars($tab['class']) . '"' : ''), '>
												<a href="', htmlspecialchars($tab['href']), '"', (in_array($action, array('edit', 'submit')) && in_array($key, array('edit', 'watch', 'unwatch' )) ), ' class="firstlevel', $tab['class'] == 'selected' ? ' active' : '', '"><span class="generic_icons ', Sanitizer::escapeId($key), '"></span><span class="firstlevel">'.htmlspecialchars($tab['text']).'</span></a>
											</li>';
										}
										echo '
										</ul>
									</div>
								</div>
							</div>
						</div>';

					// Let's get dem Indicators, such as the Help link.
					if ( is_callable( [ $this, 'getIndicators' ] ) ) {
						echo '<div class="indicator floatright">',$this->getIndicators(),'</div>';
					}

				echo '
					</div>
				</div>
				<!-- #upper_section -->';

				// Customization...
				if (method_exists($this, 'customUserAreaAddon'))
					$this->customUserAreaAddon();

				echo '
				<div id="content_section">
					<div class="frame">
						<div id="main_content_section">
							<div id="sleft-side" class="floatleft clear_left">
								<div id="column-one"', $this->html('userlangattributes'), '>';

					$sidebar = $this->data['sidebar'];

					// Force the rendering of the following boxes
					if (!isset($sidebar['SEARCH']))
						$sidebar['SEARCH'] = true;
					if (!isset($sidebar['TOOLBOX']))
						$sidebar['TOOLBOX'] = true;
					if (!isset($sidebar['LANGUAGES']))
						$sidebar['LANGUAGES'] = true;

					foreach ($sidebar as $boxName => $cont)
					{
						if ( $cont === false ) {
							continue;
						}

						// Numeric strings gets an integer when set as key, cast back - T73639
						$boxName = (string)$boxName;

						switch ($boxName) {
							case 'SEARCH':
								if($this->useSideSearchBox)
									$this->buildBox('sb', $this->searchBox(), 'search');
								break;

							case 'TOOLBOX':
								$this->buildBox('tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
								Hooks::run( 'smfCurve2AfterToolbox' );
								break;

							case 'LANGUAGES':
								if ($this->data['language_urls'] !== false)
									$this->buildBox('lang', $this->data['language_urls'], 'otherlanguages');
								break;

							default:
								$this->buildBox($boxName, $cont);
								break;
						}
					}

					// Customization...
					if (method_exists($this, 'customSideBarLower'))
						$this->customSideBarLower();

					echo '
								</div>
								<!-- end of the left (by default at least) column -->
							</div>
							<div id="sright-side" class="clear_right">
								<div id="column-content">';

					// Customization...
					if (method_exists($this, 'customPageContentUpper'))
						$this->customPageContentUpper();

					echo '
									<div id="content" ', $this->html('specialpageattributes') , '>
										<a id="top"></a>', ($this->data['sitenotice'] ? '
										<div id="siteNotice">' . $this->html('sitenotice') . '</div>' : ''), '
										<div class="cat_bar">
											<h3 class="catbg">
												<span class="left"></span>', $this->html('title'), '
												<span id="siteSub" class="floatright">', $this->getMsg('tagline')->text(), '</span>
											</h3>
											', ($this->data['subtitle'] ? '<div id="contentSub" class="desc">'.$this->data['subtitle'].'</div>' : ''), '
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
									$this->html ('dataAfterContent') : '') , '
									<div class="visualClear"></div>';

					// Customization...
					if (method_exists($this, 'customPageContentLower'))
						$this->customPageContentLower();

					echo '
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- #wrapper -->
		</div>
		<!-- #footerfix -->';

		Hooks::run( 'smfCurve2BeforeFooter' );

		echo '
		<div id="footer">
			<div class="frame">';

				// Customization...
				if (method_exists($this, 'customPageFooter'))
					$this->customPageFooter();
				else {
					echo '
					<ul class="footer-links floatleft">';

					foreach($this->getFooterLinks() as $cat=>$links) {
						foreach($links as $link) {							
							echo'
							<li class="ft-', $link, '', $link=="lastmod" ? ' block' : '', '', !empty($link) ? '' : ' hidden', '">', $this->html($link) , '</li>';
						}
					}

					echo'
					</ul>
					<ul class="footer-icons floatright">';

					foreach($this->getFooterIcons('icononly') as $icon_groups => $icons) {
						foreach($icons as $icon) {
							echo'
							<li ', !empty($icon) ? '' : 'class="hidden"', '>', $this->getSkin()->makeFooterIcon($icon) , '</li>';
						}
					}

					echo'
					</ul>';
				}

				// Customization...
				if (method_exists($this, 'customPageFooterExtra'))
					$this->customPageFooterExtra();

				echo '
			</div>
		</div>
		<!-- #footer -->';

		// Debug Toolbar, scripts and stuff
		$this->printTrail();

		// Customization...
		if (method_exists($this, 'customBodyLower'))
			$this->customBodyLower();

		echo '
		</body></html>';
	}

	/**
	 * User Menu
	 */
	public function userMenu()
	{
		echo '
		<a class="menu_icon mobile_generic_menu_u"></a>
		<div id="genericmenu">
			<div id="mobile_generic_menu_u" class="popup_container">
				<div class="popup_window description">
					<div class="popup_heading">
						', $this->getMsg('smfcurve2-user-menu')->text(), '
						<a href="javascript:void(0);" class="generic_icons delete hide_popUp_u"></a>
					</div>
					<div class="genericmenu">
						<ul', $this->html('userlangattributes') , ' class="floatleft dropmenu dropmenu_menu_u" id="top_info">';

						foreach ($this->data['personal_urls'] as $key => $item)
						{
							echo '
							<li id="', Sanitizer::escapeId('pt-' . $key), '"', ($item['active'] ? ' class="active"' : ''), '>
								<a href="', htmlspecialchars($item['href']) , '"', (!empty($item['class']) ? ' class="' . htmlspecialchars($item['class']) . '"' : ''), '><span class="generic_icons '.Sanitizer::escapeId($key).'"></span><span class="pt-itemText">', htmlspecialchars($item['text']), '</span></a>
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
			<input type="hidden" name="title" value="', $this->get('searchtitle'), '"/>
			', Html::input('search', (isset($this->data['search']) ? $this->data['search'] : ''), 'search',
			array(
				'id' => 'searchInput',
				'title' => 'search',
				'accesskey' => 'search',
			)), '
			<input type="submit" name="go" class="button" value="', $this->getMsg('searcharticle')->text(), '" ', ' />';

			echo '
		</form>';
	}

	/**
	 * Search Block Content
	 *
	 * @returns string $output
	 */
	function searchBox()
	{
		$output = '
			<form action="'.$this->get('wgScript').'" id="searchform">
				<input type="hidden" name="title" value="'.$this->get('searchtitle').'"/>';

		$output .=	Html::input(
						'search',
						(isset($this->data['search']) ? $this->data['search'] : ''),
						'search',
						array(
							'id' => 'searchInput1',
							'title' => 'search',
							'accesskey' => 'search',
							'class' => 'block',
						)
					);

		$output .= '
				<input type="submit" name="fulltext" class="button" value="' . $this->getMsg('searchbutton')->text() . '"' . ' />
				<a href="' . $this->get('searchaction') . '" rel="search" class="button">' . $this->getMsg('powersearch-legend')->text() . '</a>
			</form>';

		return $output;
	}

	/**
	 * Tool Box
	 */
	function toolbox()
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

				if ($this->data['notspecialpage'])
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

				if (isset($this->data['nav_urls']['trackbacklink']) && $this->data['nav_urls']['trackbacklink'])
					echo '
					<li id="t-trackbacklink">
						<a href="', htmlspecialchars($this->data['nav_urls']['trackbacklink']['href']), '"',  '>
							', $this->getMsg('trackbacklink')->text(), '
						</a>
					</li>';

				if ($this->data['feeds'])
				{
					echo '
					<li id="feedlinks">';
					foreach($this->data['feeds'] as $key => $feed)
						echo '
						<a id="', Sanitizer::escapeId('feed-' . $key). '" href="', htmlspecialchars($feed['href']), '" rel="alternate" type="application/', $key, '+xml" class="feedlink"', '>', htmlspecialchars($feed['text']), '</a>&nbsp;';
					echo '
					</li>';
				}

				foreach (array('contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages') as $special)
					if($this->data['nav_urls'][$special])
						echo '
					<li id="t-', $special, '">
						<a href="', htmlspecialchars($this->data['nav_urls'][$special]['href']), '"',  '>', $this->getMsg($special)->text(), '</a>
					</li>';

				if (!empty($this->data['nav_urls']['print']['href']))
					echo '
					<li id="t-print">
						<a href="', htmlspecialchars($this->data['nav_urls']['print']['href']), '" rel="alternate"', '>', $this->getMsg('printableversion')->text(), '</a>
					</li>';

				if (!empty($this->data['nav_urls']['permalink']['href']))
					echo '
					<li id="t-permalink">
						<a href="', htmlspecialchars($this->data['nav_urls']['permalink']['href']), '"','>', $this->getMsg('permalink')->text(), '</a></li>';
				elseif ($this->data['nav_urls']['permalink']['href'] === '')
					echo '
					<li id="t-ispermalink">', $this->getMsg('permalink')->text(), '</li>';

				Hooks::run('smfCurve2AfterToolboxEnd');

				echo '
				</ul>

			</div>
		</div>';
	}

	/**
	 * @param string $name
	 * @param array|string $content
	 * @param null|string $msg
	 * @param null|string|array $hook
	 */
	protected function buildBox($boxName, $cont, $msg = null, $hook = null)
	{
		if ($msg === null)
			$msg = $boxName;

		$msgObj = wfMessage( $msg );
		$labelId = Sanitizer::escapeIdForAttribute( "p-$boxName-label" );

		echo '
		<div class="side-block" role="navigation" id="', htmlspecialchars(Sanitizer::escapeIdForAttribute("p-$boxName")), '" aria-labelledby="', htmlspecialchars($labelId), '">
			<div class="cat_bar">
				<h3 id="', htmlspecialchars($labelId), '" class="catbg" ', $this->html('userlangattributes'), '>
					', htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg ), '
				</h3>
			</div>
			<div class="windowbg">';

				if (is_array($cont))
				{
					echo '
					<ul>';

					foreach($cont as $key => $val)
					{
						echo $this->makeListItem( $key, $val );
					}

					if ($hook !== null) {
						// Avoid PHP 7.1 warning
						$skin = $this;
						Hooks::run($hook, [ &$skin, true ]);
					}

					echo '
					</ul>';
				} else
					// Allow raw HTML block to be defined by extensions
					echo $cont;

				echo '

			</div>
		</div>';
	}

	/**
	 * The One and only Logo
	 */
	function customHeaderSection()
	{
		global $wgLogo;

		echo '
		<h1 class="forumtitle">
			<a id="top" href="', htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ), '" ', Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ), '>
				', $this->useLogoImage ? '<img src="'.$wgLogo.'" alt="" title=""/>' : $this->data['sitename'], '
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
	function smfMenu()
	{
		if((defined('SMF') && $this->showSMFmenu))
		{
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