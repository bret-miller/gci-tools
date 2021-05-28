<?php
// ================================================================================
// Banners
// ================================================================================
global $gci_tools_options;
add_shortcode( 'gcibanner', 'gci_banner_shortcode' );
if ($gci_tools_options['banner-compatible-enable']=='1') {
	add_shortcode( 'rccbanner', 'gci_banner_shortcode' );
}

// Scripts for the front end
function gci_banner_scripts_and_styles() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('gci_banner',plugins_url('js/banners.js',__FILE__));
	wp_enqueue_script('gci_banner_galleria',plugins_url('js/galleria-1.3.3.min.js',__FILE__));
}
add_action( 'wp_enqueue_scripts', 'gci_banner_scripts_and_styles' );

// Scripts for the back end
function gci_banner_admin_scripts_and_styles($hook) {
	// Widget settings
	if ($hook=='widgets.php') {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script("jquery-ui-core");
		wp_enqueue_script("jquery-ui-accordion");
		wp_enqueue_style('jquery-ui-smoothness',plugins_url('css/smoothness/jquery-ui-1.10.3.custom.min.css',__FILE__));
	}
}
add_action( 'admin_enqueue_scripts', 'gci_banner_admin_scripts_and_styles' );

function gci_banner_shortcode( $atts, $content=null) {
	global $gci_tools_options;
	$shortmode=(is_null($content)||($content==''));
	$ispicsize=$gci_tools_options['banner-size-is-pic-enable'];
	
	// Extract shortcode attributes
	$baseatts=array(
		'time' => $gci_tools_options['banner-time'],
		'base' => $gci_tools_options['banner-base'],
		'width' => $gci_tools_options['banner-width'],
		'height' => $gci_tools_options['banner-height']
	);
	$rccatts=array(
		'image1' => '', 'link1' => '', 'title1' => '',
		'image2' => '', 'link2' => '', 'title2' => '',
		'image3' => '', 'link3' => '', 'title3' => '',
		'image4' => '', 'link4' => '', 'title4' => '',
		'image5' => '', 'link5' => '', 'title5' => '',
		'image6' => '', 'link6' => '', 'title6' => '',
		'image7' => '', 'link7' => '', 'title7' => '',
		'image8' => '', 'link8' => '', 'title8' => '',
		'image9' => '', 'link9' => '', 'title9' => ''
	);
	$attstoget=$baseatts;
	if ($shortmode) {
		$attstoget=array_merge($attstoget,$rccatts);
	}
	extract( shortcode_atts( $attstoget, $atts ) );
	
	// Parse content if open form is used
	if (!$shortmode) {
		$banners=gci_banner_parse_content($content);
	}
	
	// Build banner code
	$time=$time*1000;
	if ($ispicsize) {
		$width=$width+20;
		$height=$height+70;
	}
	$bannercode="<div id=gcibanners style=\"visibility:hidden;\"></div>\n".
		'<script type="text/javascript">'."\n".
		'gciBannerBase="'.$base.'";'."\n".
		'gciBannerTimeout='.$time.';'."\n".
		'gciBannerWidth='.$width.';'."\n".
		'gciBannerHeight='.$height.';'."\n".
		"gciBannerTheme='".plugins_url('themes/classic/galleria.classic.min.js',__FILE__)."';\n";
	if (!$shortmode) {
		foreach($banners as $b) {
			$img=$b['image'];
			$ttl=$b['title'];
			$lnk=$b['link'];
			$bannercode.="gciBannerAdd('$img','$lnk','$ttl');\n";
		}
	} else {
		$bannercode.="gciBannerAdd('$image1','$link1','$title1');\n".
			"gciBannerAdd('$image2','$link2','$title2');\n".
			"gciBannerAdd('$image3','$link3','$title3');\n".
			"gciBannerAdd('$image4','$link4','$title4');\n".
			"gciBannerAdd('$image5','$link5','$title5');\n".
			"gciBannerAdd('$image6','$link6','$title6');\n".
			"gciBannerAdd('$image7','$link7','$title7');\n".
			"gciBannerAdd('$image8','$link8','$title8');\n".
			"gciBannerAdd('$image9','$link9','$title9');\n";
	}
	$bannercode.='jQuery(document).ready(function($) { gciBannerStart(); });'."\n".
		'</script>';
	return $bannercode;
}

function gci_banner_parse_content( $content ) {
	//This converts image=one.jpg;image=two.jpg,title=hello to an array of images
	global $gci_tools_options;
	if ($gci_tools_options['debug-enable']=='1') {
		echo "\n\n<!--\n$content\n-->\n\n";
	}
	$banners=array();
	$c = str_replace("<br />","",$content); //wp inserts <br /> in place of line feed in html
	$c = str_replace("<p>","",$c); //wp inserts <p> in place of line feed in visual
	$c = str_replace("</p>","",$c); //wp inserts <\p> in place of line feed in visual
	$c = str_replace("&#8230;","...",$c); //wp inserts &#8230; in place of ...
	$c = str_replace("&#8221;",'"',$c); //wp inserts &#8221; in place of "
	$c = str_replace("&#8243;",'"',$c); //wp inserts &#8221; in place of close " sometimes
	$imagelist=explode("\n",$c);
	foreach ($imagelist as $i) {
		if ($i!='') {
			$i=trim($i);
			preg_match_all('/(([^=]+)="([^"]*)",?)/', $i, $matches, PREG_SET_ORDER); 
			if ($gci_tools_options['debug-enable']=='1') {
				echo "\n\n<!--\n$i\n".print_r($matches,true)."\n-->\n\n";
			}
			$banner=array();
			foreach ($matches as $m) {
				$banner[$m[2]]=$m[3];
			}
			//$i=str_replace(",","&",$i);
			//$i=str_replace("& ","&",$i);
			//parse_str($i,$banner);
			$banners[]=$banner;
		}
	}
	return $banners;
}
/**
 * Adds Foo_Widget widget.
 */
class gci_banner_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'gci_banner_widget', // Base ID
			__('GCI Banner', 'text_domain'), // Name
			array( 'description' => __( 'GCI Image Rotator', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $gci_tools_options;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$time = $instance['time'];
		if ($time=='') {
			$time=$gci_tools_options['banner-time'];
		}
		$bannercode="<div id=gcibanners style=\"visibility:hidden;\"></div>\n".
			'<script type="text/javascript">'."\n".
			'	gciBannerBase="'.$gci_tools_options['banner-base'].'";'."\n".
			'	gciBannerTimeout='.($time*1000).';'."\n".
			'	gciBannerWidth='.($instance['width']+20).';'."\n".
			'	gciBannerHeight='.($instance['height']+70).';'."\n".
			"	gciBannerTheme='".plugins_url('themes/classic/galleria.classic.min.js',__FILE__)."';\n";
		if ($instance['image1']!='') {
			$bannercode.="	gciBannerAdd('".$instance['image1']."','".$instance['link1']."','".$instance['caption1']."');\n";
		}
		if ($instance['image2']!='') {
			$bannercode.="	gciBannerAdd('".$instance['image2']."','".$instance['link2']."','".$instance['caption2']."');\n";
		}
		if ($instance['image3']!='') {
			$bannercode.="	gciBannerAdd('".$instance['image3']."','".$instance['link3']."','".$instance['caption3']."');\n";
		}
		if ($instance['image4']!='') {
			$bannercode.="	gciBannerAdd('".$instance['image4']."','".$instance['link4']."','".$instance['caption4']."');\n";
		}
		if ($instance['image5']!='') {
			$bannercode.="	gciBannerAdd('".$instance['image5']."','".$instance['link5']."','".$instance['caption5']."');\n";
		}
		if ($instance['image6']!='') {
			$bannercode.="	gciBannerAdd('".$instance['image6']."','".$instance['link6']."','".$instance['caption6']."');\n";
		}
		$bannercode.='	jQuery(document).ready(function($) { gciBannerStart(); });'."\n".
			'</script>';
	
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		echo $bannercode;
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		global $gci_banner_form_num;
		$gci_banner_form_num++;
		$title=$this->gci_form_val($instance,'title');
		$width=$this->gci_form_val($instance,'width');
		$height=$this->gci_form_val($instance,'height');
		$time=$this->gci_form_val($instance,'time');
		$image1=$this->gci_form_val($instance,'image1');
		$caption1=$this->gci_form_val($instance,'caption1');
		$link1=$this->gci_form_val($instance,'link1');
		$image2=$this->gci_form_val($instance,'image2');
		$caption2=$this->gci_form_val($instance,'caption2');
		$link2=$this->gci_form_val($instance,'link2');
		$image3=$this->gci_form_val($instance,'image3');
		$caption3=$this->gci_form_val($instance,'caption3');
		$link3=$this->gci_form_val($instance,'link3');
		$image4=$this->gci_form_val($instance,'image4');
		$caption4=$this->gci_form_val($instance,'caption4');
		$link4=$this->gci_form_val($instance,'link4');
		$image5=$this->gci_form_val($instance,'image5');
		$caption5=$this->gci_form_val($instance,'caption5');
		$link5=$this->gci_form_val($instance,'link5');
		$image6=$this->gci_form_val($instance,'image6');
		$caption6=$this->gci_form_val($instance,'caption6');
		$link6=$this->gci_form_val($instance,'link6');
		echo '<div id="gci_banner_options_'.$gci_banner_form_num.'">'."\n";
		$this->gci_form_out_section('Options');
		$this->gci_form_out_text('title','Title:',$title);
		$this->gci_form_out_widhgt($width,$height);
		$this->gci_form_out_text_cust('time','Rotation time in seconds:',$time,'size=5');
		$this->gci_form_out_section('Image 1');
		$this->gci_form_out_text('image1','Image:',$image1);
		$this->gci_form_out_text('caption1','Caption:',$caption1);
		$this->gci_form_out_text('link1','Link:',$link1);
		$this->gci_form_out_section('Image 2');
		$this->gci_form_out_text('image2','Image:',$image2);
		$this->gci_form_out_text('caption2','Caption:',$caption2);
		$this->gci_form_out_text('link2','Link:',$link2);
		$this->gci_form_out_section('Image 3');
		$this->gci_form_out_text('image3','Image:',$image3);
		$this->gci_form_out_text('caption3','Caption:',$caption3);
		$this->gci_form_out_text('link3','Link:',$link3);
		$this->gci_form_out_section('Image 4');
		$this->gci_form_out_text('image4','Image:',$image4);
		$this->gci_form_out_text('caption4','Caption:',$caption4);
		$this->gci_form_out_text('link4','Link:',$link4);
		$this->gci_form_out_section('Image 5');
		$this->gci_form_out_text('image5','Image:',$image5);
		$this->gci_form_out_text('caption5','Caption:',$caption5);
		$this->gci_form_out_text('link5','Link:',$link5);
		$this->gci_form_out_section('Image 6');
		$this->gci_form_out_text('image6','Image:',$image6);
		$this->gci_form_out_text('caption6','Caption:',$caption6);
		$this->gci_form_out_text('link6','Link:',$link6);
		$this->gci_form_out_section_done();
		echo '</div><script type="text/javascript">'."\n".
			'	jQuery( document ).ready(function( $ ) {'."\n".
			'		$( "#gci_banner_options_'.$gci_banner_form_num.'" ).accordion({heightStyle: "content"});'."\n".
			'	});'."\n".
			'	jQuery("#gci_banner_options_'.$gci_banner_form_num.'" ).accordion({heightStyle: "content"});'."\n".
			'</script>'."\n";
	}
	
	private function gci_form_val($instance,$fld) {
		$val='';
		if ( isset( $instance[ $fld ] ) ) {
			$val = $instance[ $fld ];
		}
		return $val;
	}
	
	private function gci_form_out_section($secname) {
		global $gci_banner_widget_form_insec;
		if ($gci_banner_widget_form_insec) {
			echo "	</div>\n";
		}
		$gci_banner_widget_form_insec=1;
		echo "	<h3>$secname</h3>\n	<div>\n";
	}
	
	private function gci_form_out_section_done() {
		global $gci_banner_widget_form_insec;
		if ($gci_banner_widget_form_insec) {
			echo "	</div>\n";
		}
		$gci_banner_widget_form_insec=0;
	}
	
	private function gci_form_out_text($fld,$lbl,$val) {
		$this->gci_form_out_text_cust($fld,$lbl,$val,'class="widefat"');
	}
	
	private function gci_form_out_text_cust($fld,$lbl,$val,$fldsize) {
		?>
		<p>
		<label for="<?php echo $this->get_field_id( $fld ); ?>"><?php _e( $lbl ); ?></label> 
		<input <?=$fldsize?> id="<?php echo $this->get_field_id( $fld ); ?>" name="<?php echo $this->get_field_name( $fld ); ?>" type="text" value="<?php echo esc_attr( $val ); ?>" />
		</p>
		<?php
	}
	
	private function gci_form_out_widhgt($wid,$hgt) {
		?>
		<p>Note: This is <i>picture</i> width and height. Actual widget size will be 20 pixels wider and 70 pixels higher.<br />
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width:' ); ?></label> 
		<input size=5 id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $wid ); ?>" />
		&nbsp;&nbsp;
		<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height:' ); ?></label> 
		<input size=5 id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $hgt ); ?>" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = $this->gci_update_val($new_instance,'title');
		$instance['width'] = $this->gci_update_val($new_instance,'width');
		$instance['height'] = $this->gci_update_val($new_instance,'height');
		$instance['time'] = $this->gci_update_val($new_instance,'time');
		$instance['image1'] = $this->gci_update_val($new_instance,'image1');
		$instance['caption1'] = $this->gci_update_val($new_instance,'caption1');
		$instance['link1'] = $this->gci_update_val($new_instance,'link1');
		$instance['image2'] = $this->gci_update_val($new_instance,'image2');
		$instance['caption2'] = $this->gci_update_val($new_instance,'caption2');
		$instance['link2'] = $this->gci_update_val($new_instance,'link2');
		$instance['image3'] = $this->gci_update_val($new_instance,'image3');
		$instance['caption3'] = $this->gci_update_val($new_instance,'caption3');
		$instance['link3'] = $this->gci_update_val($new_instance,'link3');
		$instance['image4'] = $this->gci_update_val($new_instance,'image4');
		$instance['caption4'] = $this->gci_update_val($new_instance,'caption4');
		$instance['link4'] = $this->gci_update_val($new_instance,'link4');
		$instance['image5'] = $this->gci_update_val($new_instance,'image5');
		$instance['caption5'] = $this->gci_update_val($new_instance,'caption5');
		$instance['link5'] = $this->gci_update_val($new_instance,'link5');
		$instance['image6'] = $this->gci_update_val($new_instance,'image6');
		$instance['caption6'] = $this->gci_update_val($new_instance,'caption6');
		$instance['link6'] = $this->gci_update_val($new_instance,'link6');

		return $instance;
	}
	
	private function gci_update_val( $ni, $fld) {
		return ( ! empty( $ni[$fld] ) ) ? strip_tags( $ni[$fld] ) : '';
	}

} // class gci_banner_widget
// register gci_banner_widget
function register_gci_banner_widget() {
    register_widget( 'gci_banner_widget' );
}
add_action( 'widgets_init', 'register_gci_banner_widget' );
?>