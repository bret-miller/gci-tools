<?php
// ================================================================================
// Iframe embedder
// ================================================================================
function gci_tools_imgcaption( $atts, $content = null ) {
	global $gci_tools_options;
	//echo "\n\n<!-- atts: \n".print_r($atts,true)."\n-->\n\n";
	// Get the current caption
	$caption=$atts['caption'];
	// Handle email links
	$pattern='/mailto:([^:]*):/';
	$replace='<a href="mailto:${1}">${1}</a>';
	$caption=preg_replace($pattern, $replace, $caption);
	// Handle website links
	$pattern='/link:\|([^|]*)\|([^|]*)\|/';
	$replace='<a href="${1}">${2}</a>';
	$caption=preg_replace($pattern, $replace, $caption);
	//echo "\n\n<!-- matches: \n".print_r($matches,true)."\n-->\n\n";
	$caption=str_replace("|", "<br />", $caption);
	$atts['caption']=$caption;
	return img_caption_shortcode($atts,$content);
}
add_shortcode('caption', 'gci_tools_imgcaption' );
?>