<?php
// ================================================================================
// Cache primer
// ================================================================================
global $gci_tools_cacheprimer_lasturl;
$gci_tools_cacheprimer_lasturl=get_option('gci-tools-cacheprimer-lasturl');
if (!$gci_tools_cacheprimer_lasturl) {
	$gci_tools_cacheprimer_lasturl='';
}

add_filter('cron_schedules', 'gci_tools_cacheprimer_fivemin');
add_action('gci-tools-cacheprimer', 'gci_tools_cacheprimer');

$gci_tools_cacheprimer_time = wp_next_scheduled('gci-tools-cacheprimer');
if (!$gci_tools_cacheprimer_time) {
	wp_schedule_event(time()+60,'gci-tools-fivemin','gci-tools-cacheprimer');
}

function gci_tools_cacheprimer( ) {
	global $gci_tools_cacheprimer_lasturl;
	global $gci_tools_options;
	global $gci_tools_cacheprimer_starttime;
	global $gci_tools_cacheprimer_endtime;
	global $gci_tools_cacheprinter_log;
	$gci_tools_cacheprimer_lasturl=get_option('gci-tools-cacheprimer-lasturl');
	$sitemap_url=$gci_tools_options['cacheprimer-sitemap'];
	$gci_tools_cacheprimer_starttime=time();
	$logfile=WP_CONTENT_DIR.'/gci_tools_cacheprimer.log';
	unlink($logfile);
	$gci_tools_cacheprinter_log=fopen($logfile,"w");
	gci_tools_cacheprimer_process($sitemap_url);
	if (($gci_tools_cacheprimer_endtime-$gci_tools_cacheprimer_starttime)<$gci_tools_options['cacheprimer-timelimit']) {
		update_option('gci-tools-cacheprimer-lasturl','');
	}
	fwrite($gci_tools_cacheprinter_log,'Run time '.($gci_tools_cacheprimer_endtime-$gci_tools_cacheprimer_starttime)." seconds.\n");
	fclose($gci_tools_cacheprinter_log);
}

function gci_tools_cacheprimer_process( $sitemap_url ) {
	global $gci_tools_cacheprimer_lasturl;
	global $gci_tools_options;
	global $gci_tools_cacheprimer_starttime;
	global $gci_tools_cacheprimer_endtime;
	global $gci_tools_cacheprinter_log;
	fwrite($gci_tools_cacheprinter_log,'Processing sitemap: '.$sitemap_url."\n");
	$xmldata = wp_remote_retrieve_body(wp_remote_get($sitemap_url));
	$xml = simplexml_load_string($xmldata);
	$cnt = count($xml->url);
	fwrite($gci_tools_cacheprinter_log,'Found '.$cnt." URL's to process.\n");
	if ($gci_tools_cacheprimer_lasturl!='') {
		$skip=1; 
	} else {
		$skip=0;
	}
	if($cnt > 0) {
		for($i = 0;$i < $cnt;$i++){	
			$page = (string)$xml->url[$i]->loc;
			if ($skip==1) {
				fwrite($gci_tools_cacheprinter_log,strftime('%H:%M:%S').' Skipping '.$page."\n");
				if ($gci_tools_cacheprimer_lasturl==$page) {
					$skip=0;
				}
			} else {
				fwrite($gci_tools_cacheprinter_log,strftime('%H:%M:%S').' Loading '.$page."\n");
				$gci_tools_cacheprimer_lasturl=$page;
				update_option('gci-tools-cacheprimer-lasturl',$gci_tools_cacheprimer_lasturl);
				$tmp = wp_remote_get($page);
				//$curl=curl_init($page);
				//curl_exec($curl);
				//curl_close($curl);
				$gci_tools_cacheprimer_endtime=time();
				if (($gci_tools_cacheprimer_endtime-$gci_tools_cacheprimer_starttime)>=$gci_tools_options['cacheprimer-timelimit']) {
					break;
				}
			}
		}
	} else {
		$cnt = count($xml->sitemap);
		if($cnt > 0) {
			for($i = 0;$i < $cnt;$i++){
				$sub_sitemap_url = (string)$xml->sitemap[$i]->loc;
				gci_tools_cacheprimer_process($sub_sitemap_url);
				if (($gci_tools_cacheprimer_endtime-$gci_tools_cacheprimer_starttime)>=$gci_tools_options['cacheprimer-timelimit']) {
					break;
				}
			}				
		}
	}
	
}
 
function gci_tools_cacheprimer_fivemin( $schedules ) {
	// Adds once weekly to the existing schedules.
	$schedules['gci-tools-fivemin'] = array(
		'interval' => 300,
		'display' => __( 'Every 5 minutes' )
	);
	return $schedules;
}

?>