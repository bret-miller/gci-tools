<?php
/*
 This module handles simple form field anti-spam. It works by inserting two hidden
 fields into the comment form. The first should be blank. The second should contain
 a specified value. If one or the other doesn't match, we consider it spam.
*/
 
 function gci_antispam_head() {
	echo '<style type="text/css"><!-- .gcias-fields { display:none; } --></style>'."\n";
}
 
function gci_antispam_comment_form() {
	global $gci_tools_options;
	$blankfld=$gci_tools_options['antispam-blankfld'];
	$filledfld=$gci_tools_options['antispam-filledfld'];
	$filledval=$gci_tools_options['antispam-filledval'];
	echo '<div class="gcias-fields"><input type="text" name="'.$filledfld.'" value="'.$filledval.'">'."\n".'<input type="text" name="'.$blankfld.'" value=""></div>'."\n";
}

function gci_antispam_check_comment($comment) { 
	global $gci_tools_options;
	$blankfld=$gci_tools_options['antispam-blankfld'];
	$filledfld=$gci_tools_options['antispam-filledfld'];
	$filledval=$gci_tools_options['antispam-filledval'];
	$gci_isspam=false;
	if ($_POST[$filledfld]!=$filledval) {
		$comment['comment_content'] .= "\n\n[GCI AntiSpam] Field value for $filledfld does not match and so is spam. [".$_POST[$filledfld]."]!=[$filledval]\n\n".print_r($_POST,true);
		$gci_isspam=true;
	}
	if ($_POST[$blankfld]!='') {
		$comment['comment_content'] .= "\n\n[GCI AntiSpam] Field value not empty and so is spam.";
		$gci_isspam=true;
	}
	if ($gci_isspam) {
		if(function_exists('akismet_init')) {
			add_filter('pre_comment_approved', create_function('$a', 'return \'spam\';'));
		} else {
			add_filter('comment_post', create_function('$id', 'wp_delete_comment($id); die(\'This comment has been deleted by GCI-AntiSpam\');'));
		}
	}
	return $comment;
}
global $gci_tools_options;
if ($gci_tools_options['antispam-enable']=='1') {
	add_action('wp_head', 'gci_antispam_head');
	add_action('comment_form', 'gci_antispam_comment_form');
	add_filter('preprocess_comment', 'gci_antispam_check_comment');
}
 ?>