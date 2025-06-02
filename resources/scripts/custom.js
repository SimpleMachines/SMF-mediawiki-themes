$(function () {
	// Top Section, User Tools
	$( ".mobile_generic_menu_ue" ).click(function() {
		$( "#mobile_generic_menu_ue" ).show();
		});
	$( ".hide_popUp_ue" ).click(function() {
		$( "#mobile_generic_menu_ue" ).hide();
	});
});

// Open a overlay div
function reqOverlayDiv(desktopURL, sHeader, sIcon)
{
	// Set up our div details
	var sAjax_indicator = '<div class="centertext"><img src="' + smf_images_url + '/loading_sm.gif"></div>';
	var sHeader = typeof(sHeader) == 'string' ? sHeader : help_popup_heading_text;

	var containerOptions;
	if (typeof(sIcon) == 'string' && sIcon.match(/\.(gif|png|jpe?g|svg|bmp|tiff)$/) != null)
		containerOptions = {heading: sHeader, content: sAjax_indicator, icon: smf_images_url + '/' + sIcon};
	else
		containerOptions = {heading: sHeader, content: sAjax_indicator, icon_class: 'main_icons ' + (typeof(sIcon) != 'string' ? 'help' : sIcon)};

	// Create the div that we are going to load
	var oContainer = new smc_Popup(containerOptions);
	var oPopup_body = $('#' + oContainer.popup_id).find('.popup_content');

	// Load the help page content (we just want the text to show)
	$.ajax({
		url: desktopURL + ';ajax',
		headers: {
			'X-SMF-AJAX': 1
		},
		xhrFields: {
			withCredentials: typeof allow_xhjr_credentials !== "undefined" ? allow_xhjr_credentials : false
		},
		type: "GET",
		dataType: "html",
		beforeSend: function () {
		},
		success: function (data, textStatus, xhr) {
			var help_content = $('<div id="temp_help">').html(data).find('a[href$="self.close();"]').hide().prev('br').hide().parent().html();
			oPopup_body.html(help_content);
		},
		error: function (xhr, textStatus, errorThrown) {
			oPopup_body.html(textStatus);
		},
		statusCode: {
			403: function(res, status, xhr) {
				let errorMsg = res.getResponseHeader('x-smf-errormsg');
				oPopup_body.html(errorMsg ?? banned_text);
			},
			500: function() {
				oPopup_body.html('500 Internal Server Error');
			}
		}
	});
	return false;
}

// Create the popup menus for the top level/user menu area.
function smc_PopupMenu(oOptions)
{
	this.opt = (typeof oOptions == 'object') ? oOptions : {};
	this.opt.menus = {};
}

smc_PopupMenu.prototype.add = function (sItem, sUrl)
{
	var $menu = $('#' + sItem + '_menu'), $item = $('#' + sItem + '_menu_top');
	if ($item.length == 0)
		return;

	this.opt.menus[sItem] = {open: false, loaded: false, sUrl: sUrl, itemObj: $item, menuObj: $menu };

	$item.click({obj: this}, function (e) {
		e.preventDefault();

		e.data.obj.toggle(sItem);
	});
}

smc_PopupMenu.prototype.toggle = function (sItem)
{
	if (!!this.opt.menus[sItem].open)
		this.close(sItem);
	else
		this.open(sItem);
}

smc_PopupMenu.prototype.open = function (sItem)
{
	this.closeAll();

	if (!this.opt.menus[sItem].loaded)
	{
		this.opt.menus[sItem].menuObj.html('<div class="loading">' + (typeof(ajax_notification_text) != null ? ajax_notification_text : '') + '</div>');

		$.ajax({
			url: this.opt.menus[sItem].sUrl + ';ajax',
			headers: {
				'X-SMF-AJAX': 1
			},
			xhrFields: {
				withCredentials: typeof allow_xhjr_credentials !== "undefined" ? allow_xhjr_credentials : false
			},
			type: "GET",
			dataType: "html",
			beforeSend: function () {
			},
			context: this.opt.menus[sItem].menuObj,
			success: function (data, textStatus, xhr) {
				this.html(data);

				if ($(this).hasClass('scrollable'))
					$(this).customScrollbar({
						skin: "default-skin",
						hScroll: false,
						updateOnWindowResize: true
					});
			}
		});

		this.opt.menus[sItem].loaded = true;
	}

	this.opt.menus[sItem].menuObj.addClass('visible');
	this.opt.menus[sItem].itemObj.addClass('open');
	this.opt.menus[sItem].open = true;

	// Now set up closing the menu if we click off.
	$(document).on('click.menu', {obj: this}, function(e) {
		if ($(e.target).closest(e.data.obj.opt.menus[sItem].menuObj.parent()).length)
			return;
		e.data.obj.closeAll();
		$(document).off('click.menu');
	});
}

smc_PopupMenu.prototype.close = function (sItem)
{
	this.opt.menus[sItem].menuObj.removeClass('visible');
	this.opt.menus[sItem].itemObj.removeClass('open');
	this.opt.menus[sItem].open = false;
	$(document).off('click.menu');
}

smc_PopupMenu.prototype.closeAll = function ()
{
	for (var prop in this.opt.menus)
		if (!!this.opt.menus[prop].open)
			this.close(prop);
}

// *** smc_Popup class.
function smc_Popup(oOptions)
{
	this.opt = oOptions;
	this.popup_id = this.opt.custom_id ? this.opt.custom_id : 'smf_popup';
	this.show();
}

smc_Popup.prototype.show = function ()
{
	popup_class = 'popup_window ' + (this.opt.custom_class ? this.opt.custom_class : 'description');
	if (this.opt.icon_class)
		icon = '<span class="' + this.opt.icon_class + '"></span> ';
	else
		icon = this.opt.icon ? '<img src="' + this.opt.icon + '" class="icon" alt=""> ' : '';

	// Create the div that will be shown
	$('body').append('<div id="' + this.popup_id + '" class="popup_container"><div class="' + popup_class + '"><div class="catbg popup_heading"><a href="javascript:void(0);" class="main_icons hide_popup"></a>' + icon + this.opt.heading + '</div><div class="popup_content">' + this.opt.content + '</div></div></div>');

	// Show it
	this.popup_body = $('#' + this.popup_id).children('.popup_window');
	this.popup_body.parent().fadeIn(300);

	// Trigger hide on escape or mouse click
	var popup_instance = this;
	$(document).mouseup(function (e) {
		if ($('#' + popup_instance.popup_id).has(e.target).length === 0)
			popup_instance.hide();
	}).keyup(function(e){
		if (e.keyCode == 27)
			popup_instance.hide();
	});
	$('#' + this.popup_id).find('.hide_popup').click(function (){ return popup_instance.hide(); });

	return false;
}

smc_Popup.prototype.hide = function ()
{
	$('#' + this.popup_id).fadeOut(300, function(){ $(this).remove(); });

	return false;
}

const user_menus = new smc_PopupMenu();
$( '#top_info > li' ).each(function(idx) {
	const menuitem =  $(this).attr('data-usermenu');
	const menuhref =  $(this).attr('data-href');

	if (!menuitem || menuitem.length == 0 || menuhref == 0) {
		return;
	}

	user_menus.add(menuitem, menuhref);
});