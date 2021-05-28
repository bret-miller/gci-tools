var dhmediaplayerlist=Array();
var dhmediaplayercount=-1;
function dhmediaplayer(mytype,myfile,mywidth,myheight,mycontainer) {
	//var dhmp='dhmediaplayerload("'+mytype+'","'+myfile+'",'+mywidth+','+myheight+',"'+mycontainer+'")';
	var dhmp=mytype+';'+myfile+';'+mywidth+';'+myheight+';'+mycontainer;
	dhmediaplayercount++;
	dhmediaplayerlist[dhmediaplayercount]=dhmp;
	if (dhmediaplayercount==0) {
		jQuery(document).ready(dhmediaplayerstart);
	}
}
function dhmediaplayerstart() {
	for (var x=0;x<=dhmediaplayercount;x++) {
		var dhmp=dhmediaplayerlist[x].split(';');
		var myfile=dhmp[1];
		var mywidth=parseInt(dhmp[2]);
		var myheight=parseInt(dhmp[3]);
		var mycontainer=dhmp[4];
		var divid='dhmediaplayer'+mycontainer;
		var mplid='mpl'+mycontainer;
		var myplayers;
		//if (dhmpGetInternetExplorerVersion()==9.0) {
		//	myplayers=[ { type: "html5" }, { type: "flash", src: "http://media.dreamhost.com/mp5/player.swf" }];
		//} else {
		//	myplayers=[ { type: "flash", src: "http://media.dreamhost.com/mp5/player.swf" }];
		//}
		jwplayer(divid).setup({
			flashplayer: "http://media.dreamhost.com/mp5/player.swf",
			file: myfile,
			height: myheight,
			width: mywidth,
			controlbar: "bottom",
			players: myplayers
		});
	}
}
function dhmediaplayerstart2() {
	for (var x=0;x<=dhmediaplayercount;x++) {
		setTimeout(dhmediaplayerlist[x],500);
	}
}
function dhmediaplayerload(mytype,myfile,mywidth,myheight,mycontainer) {
	var mplid='mpl'+mycontainer;
	var divid='dhmediaplayer'+mycontainer;
	var swf = new SWFObject("http://media.dreamhost.com/mp5/player.swf", mplid, mywidth, myheight, 10);
	swf.addParam("allowfullscreen", "true");
	swf.addParam("allowscriptaccess", "always");
	swf.addVariable("file", myfile);
	swf.addVariable("controlbar", "bottom");
	//swf.addVariable("image", "http://dev.reliance-cc.org/gods_thoughts_conv.jpeg");
	swf.write(divid);
}
function dhmpGetInternetExplorerVersion()
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
{
  var rv = -1; // Return value assumes failure.
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}