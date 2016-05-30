<?php
/**
 * SMF Curve
 
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
 * Copyright 2013, Simple Machines and Individual Contributors
 *
 * Thanks to Labradoodle-360
 *    for creating and donating this script to Simple Machines
 *
 * Images under separate license
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 */
 
// remove the comment on the below if you are adding the menu from smf to your 
// MW theme and correct the path to where your SSI.php file can be found
//require("path/to/SSI.php"); 
if (!defined('MEDIAWIKI'))
	die(-1);

class Skinsmfcurve extends SkinTemplate
{
	function initPage (OutputPage $out)
	{
		parent::initPage($out);
		$this->skinname = 'smfcurve';
		$this->stylename = 'smfcurve';
		$this->template = 'smfcurveTemplate';
		$this->useHeadElement = true;

	}
	/*var $skinname = 'SMF_Curve';
	var $stylename = 'SMF_Curve';
	var $template = 'SMF_CurveTemplate';
	var $useHeadElement = true;*/

	function setupSkinUserCss (OutputPage $out)
	{
		global $wgHandheldStyle;

		parent::setupSkinUserCss($out);

		// Append to the default screen common & print styles...
		$out->addStyle('smfcurve/css/main.css', 'screen');
		if ($wgHandheldStyle)
			$out->addStyle($wgHandheldStyle, 'handheld');
		$out->addStyle('smfcurve/css/rtl.css', 'screen', '', 'rtl');

	}
}

class smfcurveTemplate extends QuickTemplate
{
	var $skin;

	function execute ()
	{
		global $wgRequest, $imagesurl;

		$this->skin = $skin = $this->data['skin'];
		$action = $wgRequest->getText('action');

		// Suppress warnings to prevent notices about missing indexes in $this->data
		//wfSuppressWarnings();

		$this->html('headelement');

		// if wanting to use the SMF menu in your theme remove the /* and */ in the following block 
		// and enable the SSI.php with correct path at the top of this file
		echo '
<div id="wrapper">
	<div id="header">
		<div class="frame">
			<div id="top_section">
			', /* ssi_menubar(), */ '
		
			</div> 

			<div id="userarea" class="upper_section">
			
				<div id="upper_search">', $this->template_search_box(), '</div>
				<div id="main_menu">
					<ul class="dropmenu" id="menu_nav">';

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
							<a href="', htmlspecialchars($tab['href']), '"', (in_array($action, array('edit', 'submit')) && in_array($key, array('edit', 'watch', 'unwatch' )) ), ' class="firstlevel', $tab['class'] == 'selected' ? ' active' : '', '"><span class="firstlevel">', htmlspecialchars($tab['text']), '</span></a>
						</li>';
		}
		echo '
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div id="content_section">
		<div class="frame">
			<div id="main_content_section">
				<div class="floatleftleft">
					<div id="column-one"', $this->html('userlangattributes'), '>
					<!-- This is the header, login, register... -->
						<h3 class="catbg"><span class="left"></span>User Info</h3>
						<div class="windowbg2">
							<span class="topslice"><span></span></span>
							<ul', $this->html('userlangattributes') , '>';

		foreach ($this->data['personal_urls'] as $key => $item)
		{
			echo '
								<li id="', Sanitizer::escapeId('pt-' . $key), '"', ($item['active'] ? ' class="active"' : ''), '>
									<a href="', htmlspecialchars($item['href']) , '"', (!empty($item['class']) ? ' class="' . htmlspecialchars($item['class']) . '"' : ''), '>', htmlspecialchars($item['text']), '</a>
								</li>';
		}
		echo '
							</ul>
							<span class="botslice"><span></span></span>
						</div>
						<br />';

		$sidebar = $this->data['sidebar'];
		if (!isset($sidebar['SEARCH']))
			$sidebar['SEARCH'] = true;
		if (!isset($sidebar['TOOLBOX']))
			$sidebar['TOOLBOX'] = true;
		if (!isset($sidebar['LANGUAGES']))
			$sidebar['LANGUAGES'] = true;
		foreach ($sidebar as $boxName => $cont)
		{
			if ($boxName === 'SEARCH')
				$this->searchBox();
			elseif ($boxName === 'TOOLBOX')
				$this->toolbox();
			elseif ($boxName === 'LANGUAGES')
				$this->languageBox();
			else
				$this->customBox($boxName, $cont);
		}

		echo '
					</div>
					<!-- end of the left (by default at least) column -->
				</div>
				<div class="floatrightright">
					<div id="column-content">
						<div id="content" ', $this->html('specialpageattributes') , '>
							<a id="top"></a>', ($this->data['sitenotice'] ? '
							<div id="siteNotice">' . $this->html('sitenotice') . '</div>' : ''), '
							<h1 class="catbg">
								<span class="left"></span>', $this->html('title'), '
							</h1> 
							<h3 id="siteSub">', $this->msg_ret('tagline'), '</h3>
							<div id="contentSub"', $this->html('userlangattributes'), '>', $this->html('subtitle'), '</div>

							<!-- start content -->
							<span class="clear upperframe"><span></span></span>
							<div class="roundframe flow_auto">
								<div class="innerframe">', ($this->data['undelete'] ? '
								<div id="contentSub2">' . $this->html('undelete') . '</div>' : ''), ($this->data['newtalk'] ? '
								<div class="usermessage">' . $this->html('newtalk') . '</div>' : ''), ($this->data['showjumplinks'] ? '
								<div id="jump-to-nav">
									' . $this->msg_ret('jumpto') . ' <a href="#column-one">' . $this->msg_ret('jumptonavigation') . '</a>, <a href="#searchInput">' . $this->msg_ret('jumptosearch') . '</a>
								</div>' : ''),
								$this->html('bodytext'), ($this->data['catlinks'] ? $this->html('catlinks') : ''), '
							</div>
						</div>
						<span class="lowerframe"><span></span></span>
						<br />
						<!-- end content -->', ($this->data['dataAfterContent'] ?
						$this->html ('dataAfterContent') : '') , '
						<div class="visualClear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="footer_section">
	<div class="frame">
		<div class="smalltext floatleft">', 
			$this->html('viewcount') , '
			', $this->html('poweredbyico') , '<br />
			
		</div>
	</div>
</div>
<br class="clear" />

</div>

', $this->html('bottomscripts') /* JS call to runBodyOnloadHook */ , '
', $this->html('reporttime'), '
', ($this->data['debug'] ? '
<!-- Debug output:
' . $this->text_ret('debug') . '
-->
' : ''), '
</body></html>';
//wfRestoreWarnings();
	}
// end of execute() method

	function template_search_box()
	{
		global $wgUseTwoButtonsSearchForm;

		echo '
		<form action="', $this->text_ret('wgScript'), '" id="searchform1" class="middletext">
				<input type="hidden" name="title" value="', $this->text_ret('searchtitle'), '"/>
				', Html::input('search', (isset($this->data['search']) ? $this->data['search'] : ''), 'search',
				array(
					'id' => 'searchInput',
					'title' => $this->skin->titleAttrib('search'),
					'accesskey' => $this->skin->accesskey('search')
				)), '
				<input type="submit" name="go" class="button_submit" value="', $this->msg_ret('searcharticle'), '" ', ' />';
				if ($wgUseTwoButtonsSearchForm)
					echo '&nbsp;
				<input type="submit" name="fulltext" class="button_submit" value="', $this->msg_ret('searchbutton'), '" ' . ' />';
				else
					echo '
				<div>
					<a href="' . $this->text_ret('searchaction') . '" rel="search">', $this->msg_ret('powersearch-legend'), '</a>
				</div>';
				echo '
			</form>';
	}
	
	// Search Box
	function searchBox ()
	{
		global $wgUseTwoButtonsSearchForm;

		echo '
	<h3 class="catbg"><span class="left"></span>Search</h3>
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<form action="', $this->text_ret('wgScript'), 
		'" id="searchform">
			<input type="hidden" name="title" value="', $this->text_ret('searchtitle') , '"/>',
			Html::input(
				'search',
				isset($this->data['search']) ? $this->data['search'] : '', 'search',
				array(
					'id' => 'searchInput1',
					'title' => $this->skin->titleAttrib('search'),
					'accesskey' => $this->skin->accesskey('search')
				)
			), '

			<br /><br />
			<input type="submit" name="go" class="button_submit" value="', $this->msg_ret('searcharticle'), '"',  ' />', ($wgUseTwoButtonsSearchForm ? '&nbsp;
			<input type="submit" name="fulltext" class="button_submit" value="' . $this->msg_ret('searchbutton') . '"' . ' />' : '
			<div>
				<a href="' . $this->text_ret('searchaction') . '" rel="search">' . $this->msg_ret('powersearch-legend') . '</a>
			</div>'), '
		</form>
		<span class="botslice"><span></span></span>
	</div>
	<br />';
	}

	/*************************************************************************************************/
	function toolbox ()
	{
		echo '
	<h3 class="catbg"><span class="left"></span>', $this->msg_ret('toolbox'), '</h3>
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<ul>';

		if ($this->data['notspecialpage'])
			echo '
			<li id="t-whatlinkshere">
				<a href="', htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href']), '"',  '>', 
					$this->msg_ret('whatlinkshere'), '
				</a>
			</li>', ($this->data['nav_urls']['recentchangeslinked'] ? '
			<li id="t-recentchangeslinked">
				<a href="' . htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href']) . '"' . '>' . 
					$this->msg_ret('recentchangeslinked-toolbox') . '
				</a>
			</li>' : '');

		if (isset($this->data['nav_urls']['trackbacklink']) && $this->data['nav_urls']['trackbacklink'])
			echo '
			<li id="t-trackbacklink">
				<a href="', htmlspecialchars($this->data['nav_urls']['trackbacklink']['href']), '"',  '>
					', $this->msg_ret('trackbacklink'), '
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
				<a href="', htmlspecialchars($this->data['nav_urls'][$special]['href']), '"',  '>', $this->msg_ret($special), '</a>
			</li>';

		if (!empty($this->data['nav_urls']['print']['href']))
			echo '
			<li id="t-print">
				<a href="', htmlspecialchars($this->data['nav_urls']['print']['href']), '" rel="alternate"', '>', $this->msg_ret('printableversion'), '</a>
			</li>';

		if (!empty($this->data['nav_urls']['permalink']['href']))
			echo '
			<li id="t-permalink">
				<a href="', htmlspecialchars($this->data['nav_urls']['permalink']['href']), '"','>', $this->msg_ret('permalink'), '</a></li>';
		elseif ($this->data['nav_urls']['permalink']['href'] === '')
			echo '
			<li id="t-ispermalink"', $this->skin->tooltip('t-ispermalink'), '>', $this->msg_ret('permalink'), '</li>';

		wfRunHooks('SMF_CurveTemplateToolboxEnd', array(&$this));
		wfRunHooks('SkinTemplateToolboxEnd', array(&$this));
		
		echo '
		</ul>
		<span class="botslice"><span></span></span>
	</div>
	<br />';
	}

	/*************************************************************************************************/
	function languageBox ()
	{
		if(!$this->data['language_urls'])
			return;

		echo '
	<h3', $this->html('userlangattributes'), ' class="catbg"><span class="left"></span>', $this->msg_ret('otherlanguages'), '</h3>
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<ul>';

		foreach($this->data['language_urls'] as $langlink)
		{
			echo '
            <li class="', htmlspecialchars($langlink['class']), '">
				<a href="', htmlspecialchars($langlink['href']), '">', $langlink['text'], '</a>
			</li>';
		}

		echo '
		</ul>
		<span class="botslice"><span></span></span>
	</div>
	<br />';
	}

	/*************************************************************************************************/
	function customBox ($bar, $cont)
	{
		$out = wfMsg($bar);
		echo '
	<h3 class="catbg"><span class="left"></span>', (wfEmptyMsg($bar, $out) ? htmlspecialchars($bar) : htmlspecialchars($out)), '</h3>
	<div class="windowbg2" id="', Sanitizer::escapeId('p-' . $bar), '"', $this->skin->tooltip('p-' . $bar), '>
		<span class="topslice"><span></span></span>';

		if (is_array($cont))
		{
			echo '
         <ul>';

			foreach($cont as $key => $val)
			{
				echo '
			<li id="', Sanitizer::escapeId($val['id']), '"', (!empty($val['active']) ? ' class="active" ' : ''), '>
				<a href="', htmlspecialchars($val['href']), '"', '>', htmlspecialchars($val['text']), '</a></li>';
			}

			echo '
         </ul>';
		} 
		else
			// allow raw HTML block to be defined by extensions
			echo $cont;

		echo '
		<span class="botslice"><span></span></span>
	</div>
	<br />';
	}

	// Why is there no function that does what msg() does but returns it?
	function msg_ret ($str)
	{
		return htmlspecialchars($this->translator->translate($str));
	}

	// Same thing for text()!
	function text_ret ($str)
	{
		return htmlspecialchars($this->data[$str]);
	}
} // end of class
