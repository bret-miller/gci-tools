<?php
// ================================================================================
// DreamHost Media Player (For Wordpress 3.6 forward, uses WordPress media)
// ================================================================================
global $gci_tools_options;
global $dhmpcontainer;
global $dhmpvjsswf;
global $dhmpfilteradded;
$dhmpcontainer=1;
$dhmpvjsswf=0;
$dhmpfilteradded=0;

function dhmediaplayer_init() {
	global $gci_tools_options;
	if (!is_admin()) {
	if ($GLOBALS['wp_version'] < '3.6') {
		if (($gci_tools_options['media-library']=='dh')||($gci_tools_options['media-library']=='default')) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'dhmediaplayer', plugins_url('js/dhmediaplayer.js', __FILE__ ));
			wp_enqueue_script( 'dhjwplayer', plugins_url('js/jwplayer.js', __FILE__ ));
			wp_enqueue_style( 'dhmpstyle', plugins_url( 'css/dhmediaplayer.css' , __FILE__ ));
		} elseif (($gci_tools_options['media-library']=='me')||($gci_tools_options['media-library']=='wp/me')) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'mediaelementplayer', plugins_url('mediaelement/mediaelement-and-player.js', __FILE__ ));
			wp_enqueue_script( 'mediaelementfire', plugins_url('js/mediaelementfire.js', __FILE__ ));
			wp_enqueue_style( 'mediaelementstyle', plugins_url( 'mediaelement/mediaelementplayer.css' , __FILE__ ));
		} else {
			wp_enqueue_script( 'vjs', plugins_url('js/video.js', __FILE__ ));
			wp_enqueue_style( 'vjsstyle', plugins_url( 'css/video-js.css' , __FILE__ ));
		}
	} else {
		add_filter('wp_audio_shortcode_class','dhmediaplayer_shortcode_class_filter');
	}
	wp_enqueue_style( 'gcimediaelementstyle', plugins_url( 'css/gcimediaelement.css' , __FILE__ ),array('wp-mediaelement')); ///
	}
} 
add_action('init', 'dhmediaplayer_init');


function dhmediaplayer_video( $atts ) {
	return dhmediaplayer_insert('video', $atts);
}
add_shortcode( 'dhvideo', 'dhmediaplayer_video' );
add_shortcode( 'gcivideo', 'dhmediaplayer_video' );

function dhmediaplayer_audio( $atts ) {
	return dhmediaplayer_insert('audio', $atts);
}
add_shortcode( 'dhaudio', 'dhmediaplayer_audio' );
add_shortcode( 'gciaudio', 'dhmediaplayer_audio' );

function dhmediaplayer_insert($playertype,$atts) {
	global $dhmpcontainer;
	global $gci_tools_options;
	//echo('<!--');
	//print_r($atts);
	//echo('-->');
	$base=$gci_tools_options['media-base'];
	if ($playertype=='audio') {
		$height='24';
		$width=$gci_tools_options['audio-width'];
		$containerclass='dhaudioplayer';
	} else {
		$height=$gci_tools_options['video-height'];;
		$width=$gci_tools_options['video-width'];;
		$containerclass='dhvideoplayer';
	}
	$showdownload=$gci_tools_options['media-download-enable'];
	extract( shortcode_atts( array(
		'height' => $height,
		'width' => $width,
		'base' => $base,
		'file' => 'no-file-specified.mp4',
		'showdownload' => $showdownload
	), $atts ) );
	if ($showdownload=='1') {
		$containerwidth=$width+24;
		$downloadstyle='display:inline;';
	} else {
		$containerwidth=$width;
		$downloadstyle='display:none;';
	}
	if (substr($base,0,4)!='http') {
		$fileurl=home_url($base.$file);
	}
	// __FILE__ exists in /.../wp-content/plugin/gci-tools
	if (substr($file,-3)=='mp3') {
		$filetype='audio/mpeg';
	} elseif (substr($file,-3)=='flv') {
		$filetype='video/x-flv';
	} else {
		$filetype='video/'.substr($file,-3);
	}
	$sitebase=dirname(dirname(dirname(dirname(__FILE__))));
	if (file_exists($sitebase.$base.$file)) {
		$filelen=filesize($sitebase.$base.$file);
		$encstr=$fileurl."\n".$filelen."\n".$filetype;
		$found = false;
		foreach ( (array) get_post_custom($post_ID) as $key => $val) {
			if ($key == 'enclosure') {
				foreach ( (array) $val as $enc ) {
					if ($enc == $encstr) {
						$found = true;
						break 2;
					}
				}
			}
		}
		if (!$found)
			add_post_meta(get_the_ID(),'enclosure',$encstr);
		//echo '<!-- '.get_the_ID().': enclosure : '.$encstr.' -->'."\n";
	} else {
		echo '<!-- '.$sitebase.$base.$file.' not found -->'."\n";
	}
	echo '<!-- version='.$GLOBALS['wp_version'].' -->'."\n";
	$mejstheme=$gci_tools_options['media-mejs-theme'];
	if ($mejstheme == '') { $mejstheme = 'mejs-default'; }
	if ( (($GLOBALS['wp_version'] < '3.6')&&($gci_tools_options['media-library']=='default'))||($gci_tools_options['media-library']=='dh') ) {
		$dhmpcode=
		'<div class="dhmediaplayer '.$containerclass.'" style="height:'.$height.'px;">'.
			'<div id="dhmediaplayer'.$dhmpcontainer.'_content" class="dhmpcontent" style="width:'.$containerwidth.'px; height:'.$height.'px;">'.
				'<div id="dhmediaplayer'.$dhmpcontainer.'"></div>'.
				'<div id="dhmediaplayer'.$dhmpcontainer.'_dl" class="dhmpdownload" style="'.$downloadstyle.'">'.
					'<a href="'.$base.$file.'"><img alt="download" title="click here to download" src="'.plugins_url('images/download-icon.png',__FILE__).'" width=24 height=24 style="position:relative;top:'.($height-24).'px;"></a>'.
				'</div>'.
			'</div>'.
		'</div>'."\n".
		'<script type="text/javascript">'."\n".
		'	dhmediaplayer("video","'.$base.$file.'","'.$width.'","'.$height.'","'.$dhmpcontainer.'");'."\n".
		'</script>'."\n";
		$dhmpcontainer=$dhmpcontainer+1;
	} elseif ( (($GLOBALS['wp_version'] < '3.6')&&($gci_tools_options['media-library']=='wp/vjs'))||($gci_tools_options['media-library']=='vjs') ) {
		$dhmpcode='<'.$playertype.' id="dhmediaplayer'.$dhmpcontainer.'" width="'.$width.'" height="'.$height.'" controls="controls" preload="none"><source type="'.$filetype.'" src="'.$base.$file.'" /></'.$playertype.'>'.'<div class="navigation">[<a href="'.$base.$file.'">Download</a>]</div>';
		$dhmpcontainer=$dhmpcontainer+1;
		if ($dhmpvjsswf==0) {
			$dhmpcode.='<script>videojs.options.flash.swf = "'.plugins_url("js/video-js.swf").'";</script>';
			$dhmpvgsswf=1;
		}
	} elseif ( (($GLOBALS['wp_version'] < '3.6')&&($gci_tools_options['media-library']=='wp/me'))||($gci_tools_options['media-library']=='me') ) {
		$dhmpcode='<'.$playertype.' id="dhmediaplayer'.$dhmpcontainer.'" width="'.$width.'" height="'.$height.'" controls="controls" preload="none"><source type="'.$filetype.'" src="'.$base.$file.'" /></'.$playertype.'>'.'<div class="navigation">[<a href="'.$base.$file.'">Download</a>]</div>';
		$dhmpcontainer=$dhmpcontainer+1;
	} else { 
		$dhmpshortcode='['.$playertype.' width='.$width;
		if ($playertype=='video') {
			$dhmpshortcode.=' height='.$height;
		}
		$dhmpshortcode.=' src='.$base.$file.']';
		$dhmpcode=do_shortcode($dhmpshortcode).'<div class="navigation">[<a href="'.$base.$file.'">Download</a>]</div>';
		if ($playertype=='audio' && $mejstheme!='mejs-default' && !$dhmpfilteradded) {
			add_filter('wp_audio_shortcode','dhmediaplayer_shortcode_filter');
			$dhmpfilteradded=1;
		}
	}
	return $dhmpcode;
}

function dhmediaplayer_shortcode_filter($html) {
	return substr($html,0,6).' height="65"'.substr($html,6);
}

function dhmediaplayer_shortcode_class_filter($class) {
	global $gci_tools_options;
	return $class.' '.$gci_tools_options['media-mejs-theme'];
}

if (($gci_tools_options['audio-player-enable']=='1')) { //&&($GLOBALS['wp_version'] < '3.6')
	//echo("<!--\n================================================================================\n");
	//echo "Adding audio shortcode.\n";
	//echo("\n================================================================================\n-->\n");
	if (($GLOBALS['wp_version'] < '3.6')) {
		add_shortcode( 'audio', 'dhmediaplayer_audio_compatible' );
	}
	add_filter("the_content", "dhmediaplayer_audio_compatible_processContent", 2);

}
function dhmediaplayer_audio_compatible($atts) {
	echo("<!--\n================================================================================\n");
	print_r($atts);
	echo("\n================================================================================\n-->\n");
	$file=substr($atts[0],1);
	$newatts=array('file'=>$file,'base'=>'','width'=>'300');
	return (dhmediaplayer_insert('audio',$newatts));
}
function dhmediaplayer_audio_compatible_processContent($content = '') {
	//echo("<!--\n================================================================================\n");
	//echo "Media player processing content.\n";
	//echo("\n================================================================================\n-->\n");
	// Replace [audio syntax]
	$pattern = "/(<p>)?\[audio:(([^]]+))\](<\/p>)?/i";
	$content = preg_replace_callback( $pattern, "dhmediaplayer_audio_compatible_parseCallback", $content );
	return $content;
}

		/**
		 * Callback function for preg_replace_callback
		 * @return string to replace matches with
		 * @param $matches Array
		 */
function dhmediaplayer_audio_compatible_parseCallback($matches) {
	$atts = explode("|", $matches[3]);
	$data[0] = $atts[0];
	for ($i = 1; $i < count($atts); $i++) {
		$pair = explode("=", $atts[$i]);
		$data[trim($pair[0])] = trim($pair[1]);
	}
	$newvalue='<p class="audioplayer_container"><span style="display:block;padding:5px;border:1px solid #dddddd;background:#f8f8f8">'."\n";
	for ($i = 0; $i < count($data); $i++) { 
		$newvalue.='[audio src="'.$data[$i].'"]'."\n";
	}
	$newvalue.="</span></p>\n";
	//echo("<!--\n================================================================================\n");
	//print_r($atts);
	//echo("\n================================================================================\n");
	//print_r($newvalue);
	//echo("\n================================================================================\n-->\n");
	//return (dhmediaplayer_insert('audio',$newatts));
	//$newvalue="<!--\n================================================================================\n DHMEDIAPLAYER OUTPUT:\n"."\n================================================================================\n-->\n".$newvalue;
	return $newvalue;
}

?>
