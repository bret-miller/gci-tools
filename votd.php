<?php
// ================================================================================
// Verse of the Day
// ================================================================================
global $gci_tools_options;

function gci_tools_votd_shortcode ( $atts ) {
	global $gci_tools_options;
	
	// Extract shortcode attributes
	$attstoget=array(
		'version' => $gci_tools_options['votd-version'],
		'class' => ''
	);
	extract( shortcode_atts( $attstoget, $atts ) );
	
	$votdcode = '';
	
	// Debug info
	$votdcode.= "\n\n<!-- \n    version=$version, class=$class\n    atts: \n".print_r($atts,true)."\n-->\n\n";
	
	if ($gci_tools_options['votd-source']=='biblia') {
		$votdcode.=
			'<!-- Verse of the Day. https://biblia.com/plugins/verseoftheday -->'."\n".
			'<biblia:verseoftheday resource="'.$version.'" width="300" height="250" singleImage="false" theme="colorbar" variant="brown"></biblia:verseoftheday>'."\n".
			'<script src="//biblia.com/api/logos.biblia.js"></script>'."\n".
			'<script>logos.biblia.init();</script>';
	} else { //biblegateway
		$votdcode.= 
			'<script type="text/javascript" language="JavaScript" src="https://www.biblegateway.com/votd/votd.write.callback.js"></script>'."\n".
			'<script type="text/javascript" language="JavaScript" src="https://www.biblegateway.com/votd/get?format=json&version='.$version.'&callback=BG.votdWriteCallback"></script>';
	}
		
	//script code displays better than this
	//$votdcode='<iframe framespacing="0" frameborder="no" src="https://www.biblegateway.com/votd/get?format=html&version='.$version.'">View Verse of the Day</iframe>';

	return $votdcode;
}

function gci_tools_votd_reftagger_nq() {
	wp_enqueue_script('gci-tools-reftagger','https://api.reftagger.com/v2/RefTagger.js',NULL,false,true);
}
function gci_tools_votd_reftagger() {
	global $gci_tools_options;
	$jsdir=dirname(__FILE__) . '/js';
	$code=
		"\n".'<!-- RefTagger inserted by GCI Tools. -->'."\n".
		'<!-- RefTagger from Logos. Visit https://reftagger.com/customize/. This code should appear directly before the </body> tag. -->'."\n".
		'<script data-noptimize="1">'."\n".
		'var refTagger = { settings: { bibleVersion: "'.$gci_tools_options['votd-version'].'", socialSharing: [] } };'."\n".
		'</script>'."\n";
	echo $code;
}

if ($gci_tools_options['votd-reftagger-enable']=='1') {
	add_action( 'wp_footer', 'gci_tools_votd_reftagger');
	wp_enqueue_script('gci-tools-reftagger','https://api.reftagger.com/v2/RefTagger.js',NULL,false,true);
}

if ($gci_tools_options['votd-shortcode-votd-enable']=='1') {
	add_shortcode( 'votd', 'gci_tools_votd_shortcode' );
}
if ($gci_tools_options['votd-shortcode-bibleverseoftheday-enable']=='1') {
	add_shortcode( 'bibleverseoftheday', 'gci_tools_votd_shortcode' );
}
if ($gci_tools_options['votd-shortcode-randombibleverse-enable']=='1') {
	add_shortcode( 'randombibleverse', 'gci_tools_votd_shortcode' );
}
if ($gci_tools_options['votd-shortcode-js-bible-verses-enable']=='1') {
	add_shortcode( 'js-bible-verses', 'gci_tools_votd_shortcode' );
}
if ($gci_tools_options['votd-shortcode-dailychristianbibleverse-enable']=='1') {
	add_shortcode( 'dailychristianbibleverse', 'gci_tools_votd_shortcode' );
}
if ($gci_tools_options['votd-shortcode-biblevotd-enable']=='1') {
	add_shortcode( 'biblevotd', 'gci_tools_votd_shortcode' );
}

add_filter('widget_text', 'do_shortcode');
?>