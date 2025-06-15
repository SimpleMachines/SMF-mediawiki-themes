$(function () {
	// Top Section, User Tools
	$(".mobile_generic_menu_u").click(function () {
		$("#mobile_generic_menu_u").show();
	});

	// MW Menu
	$(".mobile_generic_menu_0").click(function () {
		$("#mobile_generic_menu_0").show();
	});

	// SMF Menu
	$(".mobile_user_menu").click(function () {
		$("#mobile_user_menu").show();
	});

	// Close All Popups
	$(".hide_popup").click(function () {
		$("#mobile_generic_menu_u").hide();		// Top Section, User Tools
		$("#mobile_generic_menu_0").hide();		// MW Menu
		$("#mobile_user_menu").hide();			// SMF Menu
	});
});