<?php
// ================================================================================
// Dashboard info
// ================================================================================
global $gci_tools_options;

// Hook into the 'wp_dashboard_setup' action to register our other functions
if ($gci_tools_options['dashboard-enable']=='1') {
	add_action('wp_dashboard_setup', 'gci_add_dashboard_links');
}
if ($gci_tools_options['adminbar-enable']=='1') {
	add_action( 'admin_bar_menu', 'gci_adminbar_menu',140);
}

function gci_dashboard_links() {
	global $gci_tools_options;
	global $gci_tools_plugin_name;
	global $gci_tools_plugin_base;
	/*
	echo "<!-- ================================================================================ \n";
	echo "base = $gci_tools_plugin_base \n";
	echo "name = $gci_tools_plugin_name \n";
	echo "================================================================================= --> \n";
	// */
	echo '<div id="gci-dashboard-links">';
	echo '<ul>'."\n";
	if (($gci_tools_options['show-pendpages']&'1')=='1') {
		echo '<li><a href="/wp-admin/edit.php?post_status=pending&post_type=page">Pending Pages</a></li>'."\n";
	}
	if (($gci_tools_options['show-draftpages']&'1')=='1') {
		echo '<li><a href="/wp-admin/edit.php?post_status=draft&post_type=page">Draft Pages</a></li>'."\n";
	}
	if (($gci_tools_options['show-pendposts']&'1')=='1') {
		echo '<li><a href="/wp-admin/edit.php?post_status=pending&post_type=post">Pending Posts</a></li>'."\n";
	}
	if (($gci_tools_options['show-draftposts']&'1')=='1') {
		echo '<li><a href="/wp-admin/edit.php?post_status=draft&post_type=post">Draft Posts</a></li>'."\n";
	}
	if (($gci_tools_options['show-cache']&'1')=='1') {
		if (defined('W3TC')) {
?>
		<li><a href="admin.php?page=w3tc_general&w3tc_flush_all&_wpnonce=<?php echo wp_create_nonce('w3tc');?>">Clear all caches</a></li>
<?php
		} elseif(class_exists('\\zencache\\plugin')) {
		//echo "<li><a href='/wp-admin/admin.php?page=gci-tools'>Cache Settings</a></li>\n";
?>
		<li><a href="/wp-admin/admin.php?page=zencache&_wpnonce=<?php echo wp_create_nonce('zencache');?>&zencache%5Bclear_cache%5D=1">Clear cache</a></li>
<?php
		} elseif(class_exists('WebSharks\\CometCache\\Plugin')||class_exists('WebSharks\\CometCache\\Classes\\Plugin')) {
		//echo "<li><a href='/wp-admin/admin.php?page=gci-tools'>Cache Settings</a></li>\n";
?>
		<li><a href="/wp-admin/admin.php?page=comet_cache&_wpnonce=<?php echo wp_create_nonce('');?>&comet_cache%5BclearCache%5D=1">Clear cache</a></li>
<?php
		} else {
		echo "<li><a href='/wp-admin/options-general.php?page=wpsupercache&tab=contents'>Cache Contents</a></li>\n";
		}
	}
	if (($gci_tools_options['show-options']&'1')=='1') {
		echo '<a href="'.admin_url("options-general.php?page=$gci_tools_plugin_name").'">Options</a>'."\n";
	}
	$links=array('one','two','three','four','five','six');
	foreach ($links as $linknum) {
		if (($gci_tools_options['show-'.$linknum]&'1')=='1') {
			$dlabel=$gci_tools_options['link-'.$linknum.'-label'];
			$dtarget=$gci_tools_options['link-'.$linknum.'-target'];
			echo "<li><a href='$dtarget'>$dlabel</a></li>\n";
		}
	}
	echo '</ul>';
	echo '</div>'."\n";
} 

/* Create the function to use in the action hook
	Change the title to whatever you want the widget title to be
*/
function gci_add_dashboard_links() {
	global $gci_tools_options;
	$title = $gci_tools_options['dashboard-title'];	
	wp_add_dashboard_widget('gci_dashboard_links', $title, 'gci_dashboard_links');	
}

function gci_adminbar_menu() {
	global $wp_admin_bar;
	global $gci_tools_options;
	global $gci_tools_plugin_name;
	global $hyper_cache;
	global $wp_cache_config_file;
	$wp_admin_bar->add_node( array(
		'id'    => 'gci-adminbar',
		'title' => $gci_tools_options['adminbar-label'],
		'href'  => admin_url('admin.php?page=gci-tools')
		) );
	if (($gci_tools_options['show-pendpages']&'2')=='2') {
		$wp_admin_bar->add_node( array(
			'parent' => 'gci-adminbar',
			'id'     => 'gci-adminbar-pendpages',
			'title'  => 'Pending Pages',
			'href'   => '/wp-admin/edit.php?post_status=pending&post_type=page',
			) );
	}
	if (($gci_tools_options['show-draftpages']&'2')=='2') {
		$wp_admin_bar->add_node( array(
			'parent' => 'gci-adminbar',
			'id'     => 'gci-adminbar-draftpages',
			'title'  => 'Draft Pages',
			'href'   => '/wp-admin/edit.php?post_status=draft&post_type=page',
			) );
	}
	if (($gci_tools_options['show-pendposts']&'2')=='2') {
		$wp_admin_bar->add_node( array(
			'parent' => 'gci-adminbar',
			'id'     => 'gci-adminbar-pendposts',
			'title'  => 'Pending Posts',
			'href'   => '/wp-admin/edit.php?post_status=pending&post_type=post',
			) );
	}
	if (($gci_tools_options['show-draftposts']&'2')=='2') {
		$wp_admin_bar->add_node( array(
			'parent' => 'gci-adminbar',
			'id'     => 'gci-adminbar-draftposts',
			'title'  => 'Draft Posts',
			'href'   => '/wp-admin/edit.php?post_status=draft&post_type=post',
			) );
	}
	if (($gci_tools_options['show-cache']&'2')=='2') {
		$dlabel='';
		$dtarget='';
		if (defined('W3TC')) { //W3 Total Cache
			$dlabel='Clear all caches';
			$dtarget='admin.php?page=w3tc_general&w3tc_flush_all&_wpnonce='.wp_create_nonce('w3tc');
		} elseif(class_exists('\\zencache\\plugin')) {
			$dlabel='Clear cache';
			$dtarget='/wp-admin/admin.php?page=zencache&_wpnonce='.wp_create_nonce().'&zencache%5Bclear_cache%5D=1';			
		} elseif(class_exists('WebSharks\\CometCache\\Plugin')||class_exists('WebSharks\\CometCache\\Classes\\Plugin')) {
			$dlabel='Clear cache';
			$dtarget='/wp-admin/admin.php?page=comet_cache&_wpnonce='.wp_create_nonce().'&comet_cache%5BclearCache%5D=1';			
		} elseif (defined('LITE_CACHE_VERSION')) { // Lite Cache
			$dlabel='Cache options';
			$dtarget='/wp-admin/options-general.php?page=lite-cache/options.php';
		} elseif (isset($hyper_cache)) { // Hyper Cache Extended
			$dlabel='Cache options';
			$dtarget='/wp-admin/admin.php?page=hyper-cache-extended/options.php';
		} elseif (isset($wp_cache_config_file)) { // WP Super Cache
			$dlabel='Cache contents';
			$dtarget='/wp-admin/options-general.php?page=wpsupercache&tab=contents';
		}
		if (($dlabel!='')&&($dtarget!='')) {
			$wp_admin_bar->add_node( array(
				'parent' => 'gci-adminbar',
				'id'     => 'gci-adminbar-cache',
				'title'  => $dlabel,
				'href'   => $dtarget,
				) );
		}
	}
	if (($gci_tools_options['show-options']&'2')=='2') {
		$wp_admin_bar->add_node( array(
			'parent' => 'gci-adminbar',
			'id'     => 'gci-adminbar-options',
			'title'  => 'Options',
			'href'   => admin_url("options-general.php?page=$gci_tools_plugin_name"),
			) );
	}
	$links=array('one','two','three','four','five','six');
	foreach ($links as $linknum) {
		if (($gci_tools_options['show-'.$linknum]&'2')=='2') {
			$dlabel=$gci_tools_options['link-'.$linknum.'-label'];
			$dtarget=$gci_tools_options['link-'.$linknum.'-target'];
			$wp_admin_bar->add_node( array(
				'parent' => 'gci-adminbar',
				'id'     => 'gci-adminbar-'.$linknum,
				'title'  => $dlabel,
				'href'   => $dtarget,
				) );
		}
	}
}

?>