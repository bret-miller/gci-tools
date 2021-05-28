<?php	
/*
 Plugin Name:       GCI Tools
 Plugin URI:        https://github.com/bret-miller/gci-tools
 Description:       GCI Tools for WordPress: <br>AntiSpam, Admin Links, Cache Primer, Media Player, Iframe, Banners, Bible, PHP Info
 Author:            Bret Miller, Grace Communion International
 Author URI:        https://www.facebook.com/profile.php?id=100000028974466
 Version:           1.6.5.1
 Tested up to:      5.7.-
 GitHub Plugin URI: bret-miller/gci-tools
 Primary Branch:    main

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 */
// ================================================================================
// Initialization
// ================================================================================
global $gci_tools_plugin_base;
global $gci_tools_plugin_name;
$gci_tools_plugin_base=plugin_basename(__FILE__);
$gci_tools_plugin_name=dirname($gci_tools_plugin_base);


require_once(dirname(__FILE__) . '/options.php');

global $gci_tools_options;

//if ($gci_tools_options['antispam-enable']=='1') {
//	require_once(dirname(__FILE__) . '/antispam.php');
//}

if (($gci_tools_options['dashboard-enable']|$gci_tools_options['adminbar-enable'])=='1') {
	require_once(dirname(__FILE__) . '/adminlinks.php');
}

if ($gci_tools_options['votd-enable']=='1') {
	require_once(dirname(__FILE__) . '/votd.php');
}

if ($gci_tools_options['cacheprimer-enable']=='1') {
	require_once(dirname(__FILE__) . '/cacheprimer.php');
}

if ($gci_tools_options['media-enable']=='1') {
	require_once(dirname(__FILE__) . '/dhmediaplayer.php');
}

if ($gci_tools_options['iframe-enable']=='1') {
	require_once(dirname(__FILE__) . '/iframe.php');
}

if ($gci_tools_options['imgcaption-enable']=='1') {
	require_once(dirname(__FILE__) . '/imagecaption.php');
}

if ($gci_tools_options['banner-enable']=='1') {
	require_once(dirname(__FILE__) . '/banners.php');
}

if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'gci_tools_add_menu' );
  add_action( 'admin_init', 'gci_tools_reg_settings' );
  add_filter('plugin_action_links_'.$gci_tools_plugin_base, 'gci_tools_plugin_actions', 10, 1);
  add_filter( 'plugin_row_meta', 'gci_tools_plugin_meta', 10, 2);
}

function gci_tools_plugin_actions($actions) {
	global $gci_tools_plugin_name;
	array_unshift($actions, '<a href="'.admin_url("options-general.php?page=$gci_tools_plugin_name").'">Settings</a>');
	return $actions;
}

function gci_tools_plugin_meta($links,$file) {
	global $gci_tools_plugin_base;
	global $gci_tools_plugin_name;
	//echo "\n<!-- $base $plugin $file -->\n";
	if ($gci_tools_plugin_base==$file) {
		$links[]='<a href="'.admin_url("options-general.php?page=$gci_tools_plugin_name").'">Settings</a>';
	}
	return $links;
}

// TinyMCE: First line toolbar customizations
/*function gci_tools_extended_editor_mce_buttons_1($buttons) {
	// The settings are returned in this array. Customize to suite your needs.
	$btns=array(
		'bold', 'italic', 'underline', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'hr',
		'alignleft', 'aligncenter', 'alignright','alignfull', 'link', 'unlink', 'wp_more', 'fullscreen', 'wp_adv'
		);
	return $btns;
}
function gci_tools_extended_editor_mce_buttons_2($buttons) {
	// The settings are returned in this array. Customize to suite your needs.
	$btns=array( 'formatselect', 'forecolor', 'pastetext', 'pasteword', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'hr', 'spellchecker', 'wp_help' );
	return $buttons;
}
add_filter("mce_buttons",   "gci_tools_extended_editor_mce_buttons_1", 0);
add_filter("mce_buttons_2", "gci_tools_extended_editor_mce_buttons_2", 0);
*/
function gci_tools_force_kitch_sink_on(){
  set_user_setting('hidetb', 1);
}
add_action('auth_redirect', 'gci_tools_force_kitch_sink_on');
?>