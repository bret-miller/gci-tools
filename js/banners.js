var gciBannerCount=0;
var gciBannerTimeout;
var gciBannerBase;
var gciBannerWidth;
var gciBannerHeight;
var gciBannerTheme;
var gciBanners='';

function gciBannerAdd(myimg,mylnk,mydesc) {
	var endlnk='';
	var beglnk='';
	if (myimg!='') {
		gciBannerCount++;
		if (mylnk!='') {
			beglnk='<a href="'+mylnk+'">';
			endlnk='</a>';
		}
		gciBanners+='<img src="'+gciBannerBase+myimg+'" title="'+mydesc+'" longdesc="'+mylnk+'">'
	}
}
function gciBannerStart() {
	jQuery('#gcibanners').html(gciBanners);
	Galleria.loadTheme(gciBannerTheme);
	jQuery("#gcibanners").galleria({
		width: gciBannerWidth,
		height: gciBannerHeight,
		autoplay: gciBannerTimeout,
		transition: 'fade',
		transitionSpeed: 1000,
		thumbMargin: 5,
		showInfo: true,
		_toggleInfo: false
	});	
	jQuery('#gcibanners').css('visibility','visible');
}

