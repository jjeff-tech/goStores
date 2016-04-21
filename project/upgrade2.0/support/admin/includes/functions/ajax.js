
var xmlHttp


try {
	xmlHttp = new ActiveXObject("Msxml2.XMLHTTP")
} catch (e) {
	  try {
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")
	  } catch (E) {
	        xmlHttp = false;
	  }
}
if (!xmlHttp && typeof XMLHttpRequest!='undefined') {
 try {
  xmlHttp = new XMLHttpRequest();
 } catch (e) {
  xmlHttp=false;
 }
}


function LTrim(str)
/*
   PURPOSE: Remove leading blanks from our string.
   IN: str - the string we want to LTrim
*/
{
   var whitespace = new String(" \t\n\r");

   var s = new String(str);

   if (whitespace.indexOf(s.charAt(0)) != -1) {
      // We have a string with leading blank(s)...

      var j=0, i = s.length;

      // Iterate from the far left of string until we
      // don't have any more whitespace...
      while (j < i && whitespace.indexOf(s.charAt(j)) != -1)
         j++;

      // Get the substring from the first non-whitespace
      // character to the end of the string...
      s = s.substring(j, i);
   }
   return s;
}

/*
==================================================================
RTrim(string) : Returns a copy of a string without trailing spaces.
==================================================================
*/
function RTrim(str)
/*
   PURPOSE: Remove trailing blanks from our string.
   IN: str - the string we want to RTrim

*/
{
   // We don't want to trip JUST spaces, but also tabs,
   // line feeds, etc.  Add anything else you want to
   // "trim" here in Whitespace
   var whitespace = new String(" \t\n\r");

   var s = new String(str);

   if (whitespace.indexOf(s.charAt(s.length-1)) != -1) {
      // We have a string with trailing blank(s)...

      var i = s.length - 1;       // Get length of string

      // Iterate from the far right of string until we
      // don't have any more whitespace...
      while (i >= 0 && whitespace.indexOf(s.charAt(i)) != -1)
         i--;


      // Get the substring from the front of the string to
      // where the last non-whitespace character is...
      s = s.substring(0, i+1);
   }

   return s;
}
function trim(str)
/*
   PURPOSE: Remove trailing and leading blanks from our string.
   IN: str - the string we want to Trim

   RETVAL: A Trimmed string!
*/
{
   return RTrim(LTrim(str));
}







//FIREFOX STYLE TWEAK
if(navigator.appName == "Netscape"){
	document.write("<style>.intellitextLink{padding-bottom: 1px;}</style>");
}

function changeStyle(objectID, propertyName, propertyValue){
	document.getElementById(objectID).style[propertyName] = propertyValue;
}
function changeProperty(objectID, propertyName, propertyValue){
	document.getElementById(objectID)[propertyName] = propertyValue;
}
function getStyleValue(objectID, propertyName){
	return document.getElementById(objectID).style[propertyName];
}
function getPropertyValue(objectID, propertyName){
	return document.getElementById(objectID)[propertyName];
}

// PROCEDURAL FUNCTIONS
var hideID = 0;
var lastToolNum = 0;
var Xposition = 30;
var Yposition = 45;
var matter="";

function getRealPos(ele,dir)
{
	(dir=="x") ? pos = ele.offsetLeft : pos = ele.offsetTop;
	tempEle = ele.offsetParent;
	while(tempEle != null)
	{
		pos += (dir=="x") ? tempEle.offsetLeft : tempEle.offsetTop;
		tempEle = tempEle.offsetParent;
	}
	return pos;
}

function getScrollY(){
	if(window.pageYOffset != null) {
		return window.pageYOffset;
	} else {
		return document.body.scrollTop;
	}
}

function getScrollX(){
	if(window.pageXOffset != null){
		return window.pageXOffset;
	} else {
		return document.body.scrollLeft;
	}
}

function adDelay(){
	//close box
	changeStyle('tooltipBox', 'visibility', 'hidden');
	//clear ID
	clearInterval(hideID);
	//clear status message
}

function clearAdInterval(){
	clearInterval(hideID);
}

function hideAd(){
	clearInterval(hideID);
	hideID = setInterval(adDelay, 400);
	
	//THIN DOUBLE UNDERLINE
	linkRefString = "link" + lastToolNum;
	changeStyle(linkRefString, 'borderBottomWidth', '1px');
}


function displayAd(indexNum,TicketId){

	if (!xmlHttp && typeof XMLHttpRequest!='undefined') {
		return;
	}
	
	var url="ajax.php?act=ticketdetails&id=" + TicketId;

	xmlHttp.open("GET", url , true);
	changeProperty('tooltipBox', 'innerHTML', '');
	xmlHttp.onreadystatechange=function() { 
		if(xmlHttp.readyState==4 ){ 
					

			matter = trim(xmlHttp.responseText);
	
			var linkRefString = "link" + indexNum;
			var linkRef =  document.getElementById(linkRefString);
	

			//clear id
			clearInterval(hideID);

			//update global link number variable
			lastToolNum = indexNum;



			//COMPOSE TIP
			var displayString = '';
			displayString += '<table cellspacing="0" cellpadding="0" border="0" width="100%" style="width:100%;">';
			displayString += 	'<tr>'
			displayString += 		'<td bgcolor=#000000>'
			displayString += 		'<img src=images.gif width=0 height=1 ></td>'
			displayString += 	'</tr>'
			displayString += 	'<tr>'
			displayString += 		'<td id="cZn1" name="cZn1" style="display:block; background:#EEEEEE; border-left:1px solid #000000; border-right:1px solid #000000;padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;">'
			displayString += 				'<table cellpadding="0" cellspacing="0" border="0" style="position:relative;width:100%;">'
			displayString += 					'<tr>'
			displayString += 						'<td style="padding-left:7px;padding-right:7px;padding-top:7px;padding-bottom:2px;vertical-align:top;text-align:left;">'
			displayString += 							'<span style="color:#000000;display:block;padding-top:7px;padding-bottom:7px;height:100px;font-size:11px;display:block;font-family:arial;overflow:scroll">' + matter + '</span>'
			displayString += 						'</td>'
			displayString += 					'</tr>'
			displayString += 				'</table>'
			displayString += 		'</td>'
			displayString += 	'</tr>'
			displayString += 	'<tr>'
			displayString += 		'<td bgcolor=#000000><img src=images.gif width=0 height=1 >'
			displayString += 		'</td>'
			displayString += 	'</tr>'
			displayString += '</table>'

	
			//RENDER TIP
			changeProperty('tooltipBox', 'innerHTML', displayString);
		
			//RESIZE TOOLTIP BOX
			var tempWidth = "420px";
			changeStyle('tooltipBox', 'width', tempWidth);
		
			//POSITION TOOL TIP
			var toolTipBoxWidth = getPropertyValue('tooltipBox', 'offsetWidth')
			var toolTipBoxHeight = getPropertyValue('tooltipBox', 'offsetHeight');
			var linkPosX = getRealPos(linkRef,'x') + Xposition;
			var linkPosY = getRealPos(linkRef,'y') - toolTipBoxHeight + Yposition;

			//Account for page scrolling. Reposition tooltip as neccesary
			if((getScrollX() + document.body.clientWidth) < (linkPosX + toolTipBoxWidth)){
				var tempOffset = (linkPosX + toolTipBoxWidth) - (getScrollX() + document.body.clientWidth);
				linkPosX -= tempOffset + 6;
			}
			if(getScrollY() > linkPosY){
				var tempName = document.getElementById("link" + lastToolNum);
				var tempOffset = tempName.offsetHeight;
				linkPosY += toolTipBoxHeight - (2*Yposition) + tempOffset + 4;
			}


			//Make it happen
			var linkPosXString = linkPosX + "px";
			var linkPosYString = linkPosY + "px";
			changeStyle('tooltipBox', 'left', linkPosXString);
			changeStyle('tooltipBox', 'top', linkPosYString);
		
			//THICK DOUBLE UNDERLINE
			changeStyle(linkRefString, 'borderBottomWidth', '3px');
		
			//REVEAL TIP
			changeStyle('tooltipBox', 'visibility', 'visible');			
					
					
			
			

			}
		

		
		
 	}		
	xmlHttp.send(null)

}