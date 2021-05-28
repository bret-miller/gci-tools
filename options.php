<?php
// Globals and defaults
global $gci_tools_plugin_base;
global $gci_tools_section_count;
$gci_tools_section_count=0;
global $gci_tools_options;
$gci_tools_options = get_option('gci-tools-options');
$gci_tools_options_defaults=array(
	'debug-enable' => '0',
	'cacheprimer-enable' => '0',
	'cacheprimer-sitemap' => site_url('/sitemap.xml'),
	'cacheprimer-cron' => '5',
	'cacheprimer-timelimit' => '10',
	'dashboard-enable' => '1',
	'dashboard-title' => 'GCI Links',
	'adminbar-enable' => '1',
	'adminbar-label' => 'Tools',
	'show-pendposts' => '3',
	'show-draftposts' => '3',
	'show-pendpages' => '3',
	'show-draftpages' => '3',
	'show-cache' => '3',
	'show-one' => '0',
	'link-one-label' => 'Plugins',
	'link-one-target' => '/wp-admin/plugins.php',
	'show-two' => '0',
	'link-two-label' => '',
	'link-two-target' => '',
	'show-three' => '0',
	'link-three-label' => '',
	'link-three-target' => '',
	'show-four' => '0',
	'link-four-label' => '',
	'link-four-target' => '',
	'show-five' => '0',
	'link-five-label' => '',
	'link-five-target' => '',
	'show-six' => '0',
	'link-six-label' => '',
	'link-six-target' => '',
	'show-options' => '2',
	'media-enable' => '0',
	'media-library' => 'default',
	'media-mejs-theme' => '',
	'media-base' => '',
	'media-download-enable' => '1',
	'video-width' => '480',
	'video-height' => '360',
	'audio-width' => '480',
	'audio-player-enable' => '0',
	'iframe-enable' => '1',
	'iframe-width' => '800',
	'iframe-height' => '600',
	'iframe-relwidth' => '',
	'iframe-forcemobile-enable' => '0',
	'imgcaption-enable' => '0',
	'imgcaption-email-enable' => '0', 
	'banner-enable' => '0',
	'banner-time' => '3',
	'banner-width' => '400',
	'banner-height' => '300',
	'banner-base' => '',
	'banner-compatible-enable' => '0',
	'banner-size-is-pic-enable' => '1',
	'votd-enable' => '0',
	'votd-reftagger-enable' => '0',
	'votd-source' => 'biblia',
	'votd-version' => 'NLT',
	'votd-shortcode-votd-enable' => '1',
	'votd-shortcode-bibleverseoftheday-enable' => '0',
	'votd-shortcode-randombibleverse-enable' => '0',
	'votd-shortcode-js-bible-verses-enable' => '0',
	'votd-shortcode-dailychristianbibleverse-enable' => '0',
	'votd-shortcode-biblevotd-enable' => '0'
);

	//'editor-row-1' => array('bold', 'italic', 'strikethrough', 'separator', 'bullist', 'numlist', 'blockquote', 'separator', 'justifyleft', 'justifycenter', 'justifyright', 'separator', 'link', 'unlink', 'wp_more', 'hr', 'separator', 'spellchecker', 'fullscreen', 'wp_adv'),
	//'editor-row-2' => array( 'formatselect', 'underline', 'justifyfull', 'forecolor', 'pastetext', 'pasteword', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'wp_help' )

foreach ($gci_tools_options_defaults as $dkey => $dval) {
	if (!isset($gci_tools_options[$dkey])) {
		$gci_tools_options[$dkey]=$dval;
	}
}
global $gci_tools_options_help;
$lastpage=get_option('gci-tools-cacheprimer-lasturl');
if ($lastpage!='') {
	$cachemsg='The last page visited was: '.get_option('gci-tools-cacheprimer-lasturl');
} elseif ($gci_tools_options['cacheprimer-enable']) {
	$cachemsg='The cache is fully primed.';
} else {
	$cachemsg='The cache primer is disabled.';
}
$gci_tools_options_help=array(
	'The admin links modules provides useful links to the site administrator on the dashboard and/or on the admin toolbar. You may even add a few of your own links in the options above.',
	'The Bible module provides the verse of the day, currently by using a shortcode. For Example:<br><br>[votd]<br>[votd version=VOICE]<br><br>Note: not all versions are available in every Bible source so if it doesn\'t display the version you selected, try a different one.',
	'The cache primer reads the sitemap and attempts to pre-load each page into the cache by visiting each page. The primer cycles through the sitemap visiting as many pages as it can in each cron run before the time limit is reached. It resumes from where it left on on the next cron run.<br><br>'.$cachemsg,
	'The media player module uses the DreamHost-supplied media player (JW Player) to play audio and/or video files. To use it, enable the feature, upload your media to your site and insert it using the dhaudio or dhvideo shortcodes. For example:<br><br>[dhaudio file=uploads/2011/12/myaudio.mp3]',
	'The iframe module allows you to include another page into a page or post your site. You might use this to embed a Google Calendar or other externally-supplied resource. To use it, enable the feature and insert it using the iframe shortcode. For example:<br><br>[iframe http://calendar.google.com/...]<br><br>You may also specify the height and width like this (if the width was 800 and height was 600):<br><br>[iframe http://calendar.google.com/... 800 600]<br><br>You may also specify a relative like this (the 800 width and 600 height are used to calculate an aspect ratio):<br><br>[iframe http://calendar.google.com/... 800 600 100%]',
	'The caption enhancement allows you to break the caption into lines and add links.<br>Use | to start a new line.<br>Email links like this: mailto:support@gci.org:<br>Website links like this: link:|http://www.gci.org/|GCI Website|<br><br>The banner module allows you to insert rotating image banners into a page or post on your site. To use it, first enable the module here, then on your page, use the shortcode to embed it. Here are a couple examples: <br><br>[gcibanner] <br>image="uploads/2011/12/frontbanner.jpg" <br>image="uploads/2011/12/potluckbanner.jpg",title="Click to learn more",link="/2011/12/christmast-potluck/" <br>[/gcibanner]<br><br>[gcibanner time=7 width=400 height=300 base=/wp-content/uploads/2011/12/] <br>image="frontbanner.jpg" <br>image="potluckbanner.jpg",title="Click to learn more",link="/2011/12/christmast-potluck/" <br>[/gcibanner] <br><br>[gcibanner time=7 image1=frontbanner.jpg image2=potluckbanner.jpg title2="Click to learn more" link2="/2011/12/christmast-potluck/"]'
	);

function gci_tools_display_options() {
	global $gci_tools_plugin_base;
	global $gci_tools_options_help;
	global $gci_tools_section_count;
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_style('wp-jquery-ui-tabs',plugins_url( 'css/jquery-ui-1.8.16.custom.css' , __FILE__ ));
?>
<div>
<h2>GCI Tools Options</h2>
<form action="options.php" method="post">
<?php settings_fields('gci-tools-options'); ?>
<div id="tabs"><!--start of tabs-->
<ul>
<li><a href="#tabs-1">Admin Links</a></li>
<li><a href="#tabs-2">Bible</a></li>
<li><a href="#tabs-3">Cache Primer</a></li>
<li><a href="#tabs-4">Media Player</a></li>
<li><a href="#tabs-5">Iframe</a></li>
<li><a href="#tabs-6">Images</a></li>
<li><a href="#tabs-7">PHP Info</a></li>
</ul>
<?php do_settings_sections('gci-tools'); ?>
<?php echo '<br><br>'.$gci_tools_options_help[$gci_tools_section_count-1]."\n</div>\n"; ?>

<div id="tabs-7">
	<iframe id="gciphpinfo" src="<?php echo plugins_url('info.php',__FILE__); ?>" style="width:100%; height:700px;"></iframe>
</div></div><!--end of tabs-->
<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form></div>
<script type="text/javascript">jQuery(document).ready(function($){ $("#tabs").tabs({selected:0}); });</script>
<?php
} 

function gci_tools_options_section() {
	global $gci_tools_options_help;
	global $gci_tools_section_count;
	if ($gci_tools_section_count!=0) { 
		echo '<br><br>'.$gci_tools_options_help[$gci_tools_section_count-1]."\n";
		echo "</div>\n"; 
	}
	$gci_tools_section_count++;
	echo "<div id='tabs-$gci_tools_section_count'>";
}

// ================================================================================
// Admin links settings

function gci_tools_setting_debug() {
	gci_tools_setting_sel_enable('debug');
}

function gci_tools_setting_linkdash() {
	gci_tools_setting_sel_enable('dashboard');
}

function gci_tools_setting_dashtitle() {
	gci_tools_setting_textfld('dashboard-title');
}

function gci_tools_setting_linkbar() {
	gci_tools_setting_sel_enable('adminbar');
}

function gci_tools_setting_adminbar_label() {
	gci_tools_setting_textfld('adminbar-label');
}

function gci_tools_setting_linkpendposts() {
	gci_tools_setting_sel_showlink('pendposts');
}

function gci_tools_setting_linkdraftposts() {
	gci_tools_setting_sel_showlink('draftposts');
}

function gci_tools_setting_linkpendpages() {
	gci_tools_setting_sel_showlink('pendpages');
}

function gci_tools_setting_linkdraftpages() {
	gci_tools_setting_sel_showlink('draftpages');
}

function gci_tools_setting_linkcache() {
	gci_tools_setting_sel_showlink('cache');
}

function gci_tools_setting_link($args) {
	gci_tools_setting_sel_showlink($args[0]);
}

function gci_tools_setting_linklabel($args) {
	gci_tools_setting_textfld('link-'.$args[0].'-label');
}

function gci_tools_setting_linktarget($args) {
	gci_tools_setting_textfld('link-'.$args[0].'-target');
}

function gci_tools_setting_showoptions() {
	gci_tools_setting_sel_showlink('options');
}

// ================================================================================
// Bible Verse of the Day settings

function gci_tools_setting_votd() {
	gci_tools_setting_sel_enable('votd');
}

function gci_tools_setting_votd_reftagger() {
	gci_tools_setting_sel_enable('votd-reftagger');
}

function gci_tools_setting_votd_source() {
	$srclist=array(
		'biblia' => 'Biblia',
		'biblegateway' => 'Bible Gateway'
	);
	gci_tools_setting_selector('votd-source',$srclist);
}
function gci_tools_setting_votd_version() {
	$verlist=array(
		'KJ21' => '21st Century King James Version (KJ21)',
		'ASV' => 'American Standard Version (ASV)',
		'AMP' => 'Amplified Bible (AMP)',
		'AMPC' => 'Amplified Bible, Classic Edition (AMPC)',
		'BRG' => 'BRG Bible (BRG)',
		'CEB' => 'Common English Bible (CEB)',
		'CJB' => 'Complete Jewish Bible (CJB)',
		'CEV' => 'Contemporary English Version (CEV)',
		'DARBY' => 'Darby Translation (DARBY)',
		'DLNT' => 'Disciples’ Literal New Testament (DLNT)',
		'DRA' => 'Douay-Rheims 1899 American Edition (DRA)',
		'ERV' => 'Easy-to-Read Version (ERV)',
		'ESV' => 'English Standard Version (ESV)',
		'ESVUK' => 'English Standard Version Anglicised (ESVUK)',
		'EXB' => 'Expanded Bible (EXB)',
		'GNV' => '1599 Geneva Bible (GNV)',
		'GW' => 'GOD’S WORD Translation (GW)',
		'GNT' => 'Good News Translation (GNT)',
		'HCSB' => 'Holman Christian Standard Bible (HCSB)',
		'ICB' => 'International Children’s Bible (ICB)',
		'ISV' => 'International Standard Version (ISV)',
		'PHILLIPS' => 'J.B. Phillips New Testament (PHILLIPS)',
		'JUB' => 'Jubilee Bible 2000 (JUB)',
		'KJV' => 'King James Version (KJV)',
		'AKJV' => 'Authorized (King James) Version (AKJV)',
		'LEB' => 'Lexham English Bible (LEB)',
		'TLB' => 'Living Bible (TLB)',
		'MSG' => 'The Message (MSG)',
		'MEV' => 'Modern English Version (MEV)',
		'MOUNCE' => 'Mounce Reverse-Interlinear New Testament (MOUNCE)',
		'NOG' => 'Names of God Bible (NOG)',
		'NABRE' => 'New American Bible (Revised Edition) (NABRE)',
		'NASB' => 'New American Standard Bible (NASB)',
		'NCV' => 'New Century Version (NCV)',
		'NET' => 'New English Translation (NET Bible)',
		'NIRV' => 'New International Readers Version (NIRV)',
		'NIV' => 'New International Version (NIV)',
		'NIVUK' => 'New International Version - UK (NIVUK)',
		'NKJV' => 'New King James Version (NKJV)',
		'NLV' => 'New Life Version (NLV)',
		'NLT' => 'New Living Translation (NLT)',
		'NRSV' => 'New Revised Standard Version (NRSV)',
		'NRSVA' => 'New Revised Standard Version, Anglicised (NRSVA)',
		'NRSVACE' => 'New Revised Standard Version, Anglicised Catholic Edition (NRSVACE)',
		'NRSVCE' => 'New Revised Standard Version Catholic Edition (NRSVCE)',
		'OJB' => 'Orthodox Jewish Bible (OJB)',
		'RSV' => 'Revised Standard Version (RSV)',
		'RSVCE' => 'Revised Standard Version Catholic Edition (RSVCE)',
		'TLV' => 'Tree of Life Version (TLV)',
		'VOICE' => 'The Voice (VOICE)',
		'WEB' => 'World English Bible (WEB)',
		'WE' => 'Worldwide English (New Testament) (WE)',
		'WYC' => 'Wycliffe Bible (WYC)',
		'YLT' => 'Youngs Literal Translation (YLT)'
	);
	gci_tools_setting_selector('votd-version',$verlist);
}

function gci_tools_setting_votd_shortcode_votd() {
	gci_tools_setting_sel_enable('votd-shortcode-votd');
}

function gci_tools_setting_votd_shortcode_bibleverseoftheday() {
	gci_tools_setting_sel_enable('votd-shortcode-bibleverseoftheday');
}

function gci_tools_setting_votd_shortcode_randombibleverse() {
	gci_tools_setting_sel_enable('votd-shortcode-randombibleverse');
}

function gci_tools_setting_votd_shortcode_js_bible_verses() {
	gci_tools_setting_sel_enable('votd-shortcode-js-bible-verses');
}

function gci_tools_setting_votd_shortcode_dailychristianbibleverse() {
	gci_tools_setting_sel_enable('votd-shortcode-dailychristianbibleverse');
}

function gci_tools_setting_votd_shortcode_biblevotd() {
	gci_tools_setting_sel_enable('votd-shortcode-biblevotd');
}

// ================================================================================
// Cache primer settings

function gci_tools_setting_cacheprimer() {
	gci_tools_setting_sel_enable('cacheprimer');
}

function gci_tools_setting_cacheprimer_sitemap() {
	gci_tools_setting_textfld('cacheprimer-sitemap');
}

function gci_tools_setting_cacheprimer_cron() {
	gci_tools_setting_textfld('cacheprimer-cron');
}

function gci_tools_setting_cacheprimer_timelimit() {
	gci_tools_setting_textfld('cacheprimer-timelimit');
}

// ================================================================================
// Media player settings

function gci_tools_setting_mediaplayer() {
	gci_tools_setting_sel_enable('media');
}

function gci_tools_setting_medialibrary() {
	$liblist=array(
		"default" => "Choose the media player automatically",
		"wp/me" => "Use WordPress default or MediaElement.js",
		"wp/vjs" => "Use WordPress default or Video.js",
		"dh" => "Use DreamHost's JW Player",
		"me" => "Use MediaElement.js",
		"vjs" => "Use Video.js"
	);
	gci_tools_setting_selector('media-library',$liblist);
}

function gci_tools_setting_mejstheme() {
	$liblist=array(
		"mejs-default" => "Default",
		"mejs-ted" => "Ted",
		"mejs-wmp" => "WMP"
	);
	gci_tools_setting_selector('media-mejs-theme',$liblist);
}

function gci_tools_setting_mediabase() {
	gci_tools_setting_textfld('media-base');
}

function gci_tools_setting_mediadownload() {
	gci_tools_setting_sel_enable('media-download');
}

function gci_tools_setting_videowidth() {
	gci_tools_setting_textfld('video-width',5);
}

function gci_tools_setting_videoheight() {
	gci_tools_setting_textfld('video-height',5);
}

function gci_tools_setting_audiowidth() {
	gci_tools_setting_textfld('audio-width',5);
}

function gci_tools_setting_audioplayer() {
	gci_tools_setting_sel_enable('audio-player');
}

// ================================================================================
// Iframe embedder settings

function gci_tools_setting_iframe() {
	gci_tools_setting_sel_enable('iframe');
}

function gci_tools_setting_iframewidth() {
	gci_tools_setting_textfld('iframe-width',5);
}

function gci_tools_setting_iframeheight() {
	gci_tools_setting_textfld('iframe-height',5);
}

function gci_tools_setting_iframerelwidth() {
	gci_tools_setting_textfld('iframe-relwidth',5);
}

function gci_tools_setting_iframeforcemobile() {
	gci_tools_setting_sel_enable('iframe-forcemobile');
}

// ================================================================================
// Banner settings

function gci_tools_setting_imgcaption() {
	gci_tools_setting_sel_enable('imgcaption');
}

function gci_tools_setting_banner() {
	gci_tools_setting_sel_enable('banner');
}

function gci_tools_setting_bannertime() {
	gci_tools_setting_textfld('banner-time',5);
}

function gci_tools_setting_bannerwidth() {
	gci_tools_setting_textfld('banner-width',5);
}

function gci_tools_setting_bannerheight() {
	gci_tools_setting_textfld('banner-height',5);
}

function gci_tools_setting_bannersizeispic() {
	gci_tools_setting_sel_enable('banner-size-is-pic');
}

function gci_tools_setting_bannerbase() {
	gci_tools_setting_textfld('banner-base');
}

function gci_tools_setting_bannercompatible() {
	gci_tools_setting_sel_enable('banner-compatible');
}

// ================================================================================
// Editor settings (this doesn't work, so ignored for now

function gci_tools_setting_editorbuttons() {
	global $gci_tools_options;
	gci_tools_setting_editorbuttonbox('editor-row-1',$gci_tools_options['editor-row-1']);
	echo"</td><td>";
	gci_tools_setting_editorbuttonbox('editor-row-2',$gci_tools_options['editor-row-2']);
}

function gci_tools_setting_editorbuttonbox($namekey,$selectedbuttons) {
	echo "\n\n<!--\n";
	print_r($selectedbuttons);
	echo "\n-->\n\n";
	$gci_tools_mce_buttonlist = array('bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','justifyfull','bullist','numlist','outdent','indent','cut','copy','paste','undo','redo','link','unlink','image','cleanup','help','code','hr','removeformat','formatselect','fontselect','fontsizeselect','styleselect','sub','sup','forecolor','backcolor','forecolorpicker','backcolorpicker','charmap','visualaid','anchor','newdocument','blockquote','separator');
	$x=count($gci_tools_mce_buttonlist);
	echo "<select id=gci-tools-$namekey name='gci-tools-options[$namekey]' multiple=multiple size=$x>";
	foreach($gci_tools_mce_buttonlist as $button) {
		echo "<option";
		if (array_search($button,$selectedbuttons)!==false) {
			echo " selected";
		}
		echo ">$button</option>";
	}
	echo "</select>";
}


// ================================================================================
// General settings functions

// Create an Enabled/Disabled dropdown select
function gci_tools_setting_sel_enable($namekey) {
	global $gci_tools_options;
	$setname="$namekey-enable";
	$setid="gci-tools-$namekey";
	if ($gci_tools_options[$setname]=='1') {
		$selectone='selected';
		$selecttwo='';
	} else {
		$selectone='';
		$selecttwo='selected';
	}
	echo "<select id='$setid' name='gci-tools-options[$setname]'><option value='1' $selectone>Enabled</option><option value='0' $selecttwo>Disabled</option></select>";
}

// Create an Enabled/Disabled dropdown select
function gci_tools_setting_selector($namekey,$values) {
	global $gci_tools_options;
	$setname="$namekey";
	$setid="gci-tools-$namekey";
	$selcode="<select id='$setid' name='gci-tools-options[$setname]'>";
	foreach ($values as $v => $d) {
		$selcode.="<option value='$v'";
		if ($gci_tools_options[$setname]==$v) {
			$selcode.=" selected";
		}
		$selcode.=">$d</option>";
	}
	$selcode.="</select>";
	echo $selcode;
}

// Create an Hide/Dashboard/Admin Bar/Both dropdown select
function gci_tools_setting_sel_showlink($namekey) {
	global $gci_tools_options;
	$setname="show-$namekey";
	$setid="gci-tools-show-$namekey";
	$setval=$gci_tools_options[$setname];
	$sela='';
	$selb='';
	$selc='';
	$seld='';
	if ($setval=='0') {
		$sela='selected';
	} elseif ($setval=='1') {
		$selb='selected';
	} elseif ($setval=='2') {
		$selc='selected';
	} elseif ($setval=='3') {
		$seld='selected';
	}
	echo "<select id='$setid' name='gci-tools-options[$setname]'><option value='0' $sela>Hide</option><option value='1' $selb>Dashboard</option><option value='2' $selc>Admin Bar</option><option value='3' $seld>Both</option></select>\n";
}

// Create an input text field
function gci_tools_setting_textfld($namekey, $size='50') {
	global $gci_tools_options;
	$val=$gci_tools_options[$namekey];
	echo "<input id='gci-tools-$namekey' name='gci-tools-options[$namekey]' type=text size='$size' value='$val'>";
}

function gci_tools_add_menu() {
	add_options_page('GCI Tools Options', 'GCI Tools', 'manage_options', 'gci-tools', 'gci_tools_display_options');
}

function gci_tools_reg_settings() {
	register_setting( 'gci-tools-options', 'gci-tools-options');
	//add_settings_section('gci-tools-antispam-section', '', 'gci_tools_options_section', 'gci-tools');
	//add_settings_field('gci-tools-antispam', 'Enable AntiSpam?', 'gci_tools_setting_antispam', 'gci-tools', 'gci-tools-antispam-section');
	//add_settings_field('gci-tools-antispam-blankfld', 'Blank field name (no spaces):', 'gci_tools_setting_antispam_blankfld', 'gci-tools', 'gci-tools-antispam-section');
	//add_settings_field('gci-tools-antispam-filledfld', 'Filled field name (no spaces):', 'gci_tools_setting_antispam_filledfld', 'gci-tools', 'gci-tools-antispam-section');
	//add_settings_field('gci-tools-antispam-filledval', 'Filled field value:', 'gci_tools_setting_antispam_filledval', 'gci-tools', 'gci-tools-antispam-section');

	add_settings_section('gci-tools-links-section', '', 'gci_tools_options_section', 'gci-tools');
	add_settings_field('gci-tools-debug', 'Enable debug comments?', 'gci_tools_setting_debug', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-dashboard', 'Enable Dashboard Widget?', 'gci_tools_setting_linkdash', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-dashboard-title', 'Dashboard Widget Title:', 'gci_tools_setting_dashtitle', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-adminbar', 'Enable Admin Bar?', 'gci_tools_setting_linkbar', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-adminbar-label', 'Admin Bar Label:', 'gci_tools_setting_adminbar_label', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-show-draftposts', 'Show Draft Posts?', 'gci_tools_setting_linkdraftposts', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-show-pendposts', 'Show Pending Posts?', 'gci_tools_setting_linkpendposts', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-show-draftpages', 'Show Draft Pages?', 'gci_tools_setting_linkdraftpages', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-show-pendpages', 'Show Pending Pages?', 'gci_tools_setting_linkpendpages', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-show-cache', 'Show Clear Cache?', 'gci_tools_setting_linkcache', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-show-options', 'Show Options?', 'gci_tools_setting_showoptions', 'gci-tools', 'gci-tools-links-section');
	add_settings_field('gci-tools-show-one', 'Show Link 1?', 'gci_tools_setting_link', 'gci-tools', 'gci-tools-links-section',array('one'));
	add_settings_field('gci-tools-link-one-label', 'Link 1 Label:', 'gci_tools_setting_linklabel', 'gci-tools', 'gci-tools-links-section',array('one'));
	add_settings_field('gci-tools-link-one-target', 'Link 1 Target:', 'gci_tools_setting_linktarget', 'gci-tools', 'gci-tools-links-section',array('one'));
	add_settings_field('gci-tools-show-two', 'Show Link 2?', 'gci_tools_setting_link', 'gci-tools', 'gci-tools-links-section',array('two'));
	add_settings_field('gci-tools-link-two-label', 'Link 2 Label:', 'gci_tools_setting_linklabel', 'gci-tools', 'gci-tools-links-section',array('two'));
	add_settings_field('gci-tools-link-two-target', 'Link 2 Target:', 'gci_tools_setting_linktarget', 'gci-tools', 'gci-tools-links-section',array('two'));
	add_settings_field('gci-tools-show-three', 'Show Link 3?', 'gci_tools_setting_link', 'gci-tools', 'gci-tools-links-section',array('three'));
	add_settings_field('gci-tools-link-three-label', 'Link 3 Label:', 'gci_tools_setting_linklabel', 'gci-tools', 'gci-tools-links-section',array('three'));
	add_settings_field('gci-tools-link-three-target', 'Link 3 Target:', 'gci_tools_setting_linktarget', 'gci-tools', 'gci-tools-links-section',array('three'));
	add_settings_field('gci-tools-show-four', 'Show Link 4?', 'gci_tools_setting_link', 'gci-tools', 'gci-tools-links-section',array('four'));
	add_settings_field('gci-tools-link-four-label', 'Link 4 Label:', 'gci_tools_setting_linklabel', 'gci-tools', 'gci-tools-links-section',array('four'));
	add_settings_field('gci-tools-link-four-target', 'Link 4 Target:', 'gci_tools_setting_linktarget', 'gci-tools', 'gci-tools-links-section',array('four'));
	add_settings_field('gci-tools-show-five', 'Show Link 5?', 'gci_tools_setting_link', 'gci-tools', 'gci-tools-links-section',array('five'));
	add_settings_field('gci-tools-link-five-label', 'Link 5 Label:', 'gci_tools_setting_linklabel', 'gci-tools', 'gci-tools-links-section',array('five'));
	add_settings_field('gci-tools-link-five-target', 'Link 5 Target:', 'gci_tools_setting_linktarget', 'gci-tools', 'gci-tools-links-section',array('five'));
	add_settings_field('gci-tools-show-six', 'Show Link 6?', 'gci_tools_setting_link', 'gci-tools', 'gci-tools-links-section',array('six'));
	add_settings_field('gci-tools-link-six-label', 'Link 6 Label:', 'gci_tools_setting_linklabel', 'gci-tools', 'gci-tools-links-section',array('six'));
	add_settings_field('gci-tools-link-six-target', 'Link 6 Target:', 'gci_tools_setting_linktarget', 'gci-tools', 'gci-tools-links-section',array('six'));

	add_settings_section('gci-tools-votd-section', '', 'gci_tools_options_section', 'gci-tools');
	add_settings_field('gci-tools-votd', 'Enable the Bible module?', 'gci_tools_setting_votd', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-reftagger', 'Enable automatic scripture reference display?', 'gci_tools_setting_votd_reftagger', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-source', 'Verse of the day source:', 'gci_tools_setting_votd_source', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-version', 'Default Bible version:', 'gci_tools_setting_votd_version', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-shortcode-votd', 'Enable [votd] shortcode?', 'gci_tools_setting_votd_shortcode_votd', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-shortcode-bibleverseoftheday', 'Enable [bibleverseoftheday] shortcode?', 'gci_tools_setting_votd_shortcode_bibleverseoftheday', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-shortcode-randombibleverse', 'Enable [randombibleverse] shortcode?', 'gci_tools_setting_votd_shortcode_randombibleverse', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-shortcode-js-bible-verses', 'Enable [js-bible-verses] shortcode?', 'gci_tools_setting_votd_shortcode_js_bible_verses', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-shortcode-dailychristianbibleverse', 'Enable [dailychristianbibleverse] shortcode?', 'gci_tools_setting_votd_shortcode_dailychristianbibleverse', 'gci-tools', 'gci-tools-votd-section');
	add_settings_field('gci-tools-votd-shortcode-biblevotd', 'Enable [biblevotd] shortcode?', 'gci_tools_setting_votd_shortcode_biblevotd', 'gci-tools', 'gci-tools-votd-section');
	
	add_settings_section('gci-tools-cacheprimer-section', '', 'gci_tools_options_section', 'gci-tools');
	add_settings_field('gci-tools-cacheprimer', 'Enable cache primer?', 'gci_tools_setting_cacheprimer', 'gci-tools', 'gci-tools-cacheprimer-section');
	add_settings_field('gci-tools-cacheprimer-sitemap', 'Sitemap URL:', 'gci_tools_setting_cacheprimer_sitemap', 'gci-tools', 'gci-tools-cacheprimer-section');
	add_settings_field('gci-tools-cacheprimer-cron', 'Cron frequency (minutes):', 'gci_tools_setting_cacheprimer_cron', 'gci-tools', 'gci-tools-cacheprimer-section');
	add_settings_field('gci-tools-cacheprimer-timelimit', 'Time limit (seconds)', 'gci_tools_setting_cacheprimer_timelimit', 'gci-tools', 'gci-tools-cacheprimer-section');
	
	add_settings_section('gci-tools-media-section', '', 'gci_tools_options_section', 'gci-tools');
	add_settings_field('gci-tools-media', 'Enable Media Player?', 'gci_tools_setting_mediaplayer', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-media-medialibrary', 'Use which media player library?', 'gci_tools_setting_medialibrary', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-media-mejstheme', 'MediaElement/WordPress Player Theme:', 'gci_tools_setting_mejstheme', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-media-base', 'Media base folder:', 'gci_tools_setting_mediabase', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-media-download', 'Show download link?', 'gci_tools_setting_mediadownload', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-video-width', 'Default video width:', 'gci_tools_setting_videowidth', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-video-height', 'Default video height:', 'gci_tools_setting_videoheight', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-audio-width', 'Default audio width:', 'gci_tools_setting_audiowidth', 'gci-tools', 'gci-tools-media-section');
	add_settings_field('gci-tools-audio-player', 'Enable Audio Player Compatibility?', 'gci_tools_setting_audioplayer', 'gci-tools', 'gci-tools-media-section');

	add_settings_section('gci-tools-iframe-section', '', 'gci_tools_options_section', 'gci-tools');
	add_settings_field('gci-tools-iframe', 'Enable iframe embedder?', 'gci_tools_setting_iframe', 'gci-tools', 'gci-tools-iframe-section');
	add_settings_field('gci-tools-iframe-width', 'Default iframe width:', 'gci_tools_setting_iframewidth', 'gci-tools', 'gci-tools-iframe-section');
	add_settings_field('gci-tools-iframe-height', 'Default iframe height:', 'gci_tools_setting_iframeheight', 'gci-tools', 'gci-tools-iframe-section');
	add_settings_field('gci-tools-iframe-relwidth', 'Default relative width (makes it mobile-responsive):', 'gci_tools_setting_iframerelwidth', 'gci-tools', 'gci-tools-iframe-section');
	add_settings_field('gci-tools-iframe-forcemobile', 'Force mobile responsive?', 'gci_tools_setting_iframeforcemobile', 'gci-tools', 'gci-tools-iframe-section');

	add_settings_section('gci-tools-banner-section', '', 'gci_tools_options_section', 'gci-tools');
	add_settings_field('gci-tools-imgcaption', 'Enable image caption enhancement?', 'gci_tools_setting_imgcaption', 'gci-tools', 'gci-tools-banner-section');
	add_settings_field('gci-tools-banner', 'Enable banner module?', 'gci_tools_setting_banner', 'gci-tools', 'gci-tools-banner-section');
	add_settings_field('gci-tools-banner-time', 'Default rotation time:', 'gci_tools_setting_bannertime', 'gci-tools', 'gci-tools-banner-section');
	add_settings_field('gci-tools-banner-width', 'Default banner width:', 'gci_tools_setting_bannerwidth', 'gci-tools', 'gci-tools-banner-section');
	add_settings_field('gci-tools-banner-height', 'Default banner height:', 'gci_tools_setting_bannerheight', 'gci-tools', 'gci-tools-banner-section');
	add_settings_field('gci-tools-banner-size-is-pic', 'Size is picture size?', 'gci_tools_setting_bannersizeispic', 'gci-tools', 'gci-tools-banner-section');
	add_settings_field('gci-tools-banner-base', 'Banner base folder:', 'gci_tools_setting_bannerbase', 'gci-tools', 'gci-tools-banner-section');
	add_settings_field('gci-tools-banner-compatible', 'Enable rccbanner compatibility?', 'gci_tools_setting_bannercompatible', 'gci-tools', 'gci-tools-banner-section');
	
	//add_settings_section('gci-tools-editor-section', '', 'gci_tools_options_section', 'gci-tools');
	//add_settings_field('gci-tools-editorbuttons', 'Editor buttons rows 1 and 2', 'gci_tools_setting_editorbuttons', 'gci-tools', 'gci-tools-editor-section');
}
?>