<?php
// ================================================================================
// Iframe embedder
// ================================================================================
global $gci_ifid;
$gci_ifid=0;
function gci_tools_iframe( $atts ) {
	global $gci_tools_options;
	global $gci_ifid;
	$gci_ifid=$gci_ifid+1;
	echo "\n\n<!-- atts: \n".print_r($atts,true)."\n-->\n\n";
	$attcount=count($atts);
	$ifurl=$atts[0];
	if ($attcount>1) {
		$ifwidth=$atts[1];
	} else {
		$ifwidth=$gci_tools_options['iframe-width'];
	}
	if ($attcount>2) {
		$ifheight=$atts[2];
	} else {
		$ifheight=$gci_tools_options['iframe-height'];
	}
	if ($attcount>3) {
		$ifwid=$atts[3];
	} else if ($attcount<1) {
		$ifwid=$gci_tools_options['iframe-relwidth'];
	}
	if ($gci_tools_options['iframe-forcemobile-enable']) {
		if ($ifwid == "") {
			if ($gci_tools_options['iframe-relwidth'] != "") {
				$ifwid=$gci_tools_options['iframe-relwidth'];
			} else {
				$ifwid="100%";
			}
		}
	}
	if ($ifwid != "") { // Use relative width with aspect ratio
		$ifratio1=$ifheight/$ifwidth;
		$ifratio2=intval($ifratio1*10000);
		$ifratio=$ifratio2/100;
		$stylesheet="<!--
		width=$ifwidth, height=$ifheight, ratio1=$ifratio1, ratio2=$ifratio2, ratio=$ifratio
-->
<style type='text/css'>
#gci-iframe-$gci_ifid {
	position: relative;
	width: $ifwid;		/* desired width */
	margin-bottom: 10px;
}
#gci-iframe-$gci_ifid:before{
	content: \"\";
	display: block;
	padding-top: $ifratio%; 	/* Aspect ratio as a percent */
}
#gci-iframe-$gci_ifid div {
	display:block;
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    height: 100%;
}
#gci-iframe-$gci_ifid div iframe { width:100%; height:100%; border-style:none; }
</style>\n";
	} else {
	$stylesheet="<style type='text/css'>
#gci-iframe-$gci_ifid div iframe { width:".$ifwidth."px; height:".$ifwidth."px; border-style:none; }
</style>\n";
	}
	return $stylesheet."<div id='gci-iframe-$gci_ifid' class='gci-iframe'><div><iframe src='$ifurl'></iframe></div></div>";
}
add_shortcode( 'iframe', 'gci_tools_iframe' );
?>