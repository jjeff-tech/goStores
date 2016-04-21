/* Global variables for timer1 */
var tmrStr_g;
var tmrIdO_g = 0
var tmrPauseO_g = 0
var tmrCntO_g= 0
//var tmrStrO_g //not using now.
/* Global variables for timer2 */
var tmrIdU_g = 0
var tmrPauseU_g = 0
var tmrCntU_g= 0
//var tmrStrU_g //not using now
/* Global variables for timer2 */
var tmrIdD_g = 0
var tmrPauseD_g = 0
var tmrCntD_g= 0
//var tmrStrD_g //not using now
var cid_g ; //chat id 
var dpt_g ; //department id 
var comp_g ; //companyid
var uid_g ; //userid
function getTimeString( ms ) {
   tmrStrO_g = "00:00:00";
   var secCnt = 0;
   var minCnt = 0;
   var hrCnt = 0; 
   secCnt = ms/1000 ;
   if ( secCnt >= 60) {
      minCnt = parseInt(secCnt / 60);
      secCnt -= (minCnt * 60);
      if (minCnt >= 60)
      {
        hrCnt = parseInt(minCnt / 60);
        minCnt -= (hrCnt * 60);
      }
   }
   tmrStr_g = ((hrCnt < 10 ) ? ('0' + hrCnt) : hrCnt )  + ':' +  ( (minCnt < 10 ) ? ('0' + minCnt) : minCnt)  + ':' + ((secCnt < 10 ) ? ('0' + parseInt(secCnt)) : parseInt(secCnt)) ;
   return tmrStr_g;
}

/* Common Function to trigger timer functions */
function triggerTimer( flg ) {
   switch( flg ) {
	 case 'O' :
	   if(tmrIdO_g) {
         clearTimeout(tmrIdO_g);
       }
	   getStaffOnlineStatus();
       tmrIdO_g = setTimeout("triggerTimer('O')", 4000);
	   break;
	 case 'U' :
	   if(tmrIdU_g) {
         clearTimeout(tmrIdU_g);
       }
	   tmrCntU_g+=  1 ;
       getChatUpdates();
       tmrIdU_g = setTimeout("triggerTimer('U')", 3000);
	   break;
	 case 'D' :
	   if(tmrIdD_g) {
         clearTimeout(tmrIdD_g);
       }
       getDesktopShareStatus();
       tmrIdD_g = setTimeout("triggerTimer('D')", 8000);
	   break;
   }
}
/* Function to reset the timer global variables (initialization)*/
function timerReset( flg ) {
  switch ( flg ){
     case 'O' : 
	   tmrCntO_g= 0 ;
       tmrPauseO_g = 0 ;
       tmrStrO_g = "00:00:00";
       break ;
	 case 'C' :
	   tmrCntU_g= 0 ;
       tmrPauseU_g = 0 ;
       tmrStrU_g = "00:00:00";
	   break ;
	  case 'D' :
	   tmrCntD_g= 0 ;
       tmrPauseD_g = 0 ;
       tmrStrD_g = "00:00:00";
	   break ;
  }
}
/*Common Function to call the trigger function for timers*/
function timerStart( flg ) {
   timerReset( flg )
   switch ( flg ){
     case 'O' : 
	   tmrIdO_g  = setTimeout("triggerTimer('O')", 4000);
	  break ;
	 case 'U' :
	   tmrIdU_g  = setTimeout("triggerTimer('U')", 3000);
	   break ;
	 case 'D' :
	   tmrIdD_g  = setTimeout("triggerTimer('D')", 8000);
	   break ;
   }
}
/* Onload function for the chat window.(chat id and department id are the arguments)*/
function init_chatpage( cid, did, comp, uid ) {
  cid_g = cid ;
  dpt_g = did ;
  comp_g = comp ;
  uid_g = uid ;
  chatscroll();
  timerStart('O'); // start timer to check staff online status 
  timerStart('U'); // start timer to get recent chat from database and update the chat display window.
 // timerStart('D'); // start timer to get the desktop share invite status.
}
/*Function to get the staff online status from database*/
function getStaffOnlineStatus() {
   send_data_one( '',"getConnectionSts.php?mod=O&chatid="+cid_g+"&dptid="+dpt_g+"&comp="+comp_g );
   var str =  xmlHttp1.responseText ;
   if(str.substring(0,3) == '##X' ) window.close();
   else if (str.substring(0,3) == '##S') {
     var divInfo = getChildById('divInfo') ;
     var spanSts = getChildById('spanSts',divInfo) ;
     var divStfOnline =  getChildById('divCallConnect') ;
     var spnStaffImg = getChildById('spnStaffImg', divStfOnline) ;
	 var imgStaff = getChildById('imgStaff', spnStaffImg) ;
     if ( str.substring(0,6) == '##SA::') {
	   var arStr = str.split('::');
	   var divChat = getChildById('divChat') ;
	   if ( divChat.style.display == 'none' ) divChat.style.display ="" ;
       spanSts.innerHTML='';
	   spanSts.innerHTML = arStr[1];
	   var spanInfo = getChildById('spanInfo',divInfo) ;
	   spanInfo.innerHTML = arStr[2];
	   /* Time elapsed since start time*/
	   var re = new RegExp('-', 'g'); 
	   var dts_str = arStr[3];
	   var dtc_str = arStr[5];
	   dts_str = dts_str.replace(re,'/');
	   dtc_str = dtc_str.replace(re,'/');
	   var dts = new Date(dts_str);
	   var dtc = new Date(dtc_str);
	   var str_tme = getTimeString(dtc-dts);
	   var divStatus = getChildById('divStatus') ;
	   //var spanTime = getChildById('spanTime',divStatus) ;
	   var spanTime = getChildById('spanTime') ;
	   spanTime.innerHTML = str_tme ;
	   if ( arStr[4] != 'N' ) {
		  imgStaff.src = arStr[4] ;
		  spnStaffImg.style.display = "";
	   } else {
		 imgStaff.src=""; 
		 spnStaffImg.style.display = "none";
	   }
     } else if ( str.substring(0,6) == '##SF::') {
	   var arStr = str.split('::');
	   spanSts.innerHTML='';
	   spanSts.innerHTML = arStr[1];
	   var spanInfo = getChildById('spanInfo',divInfo) ;
	   spanInfo.innerHTML = arStr[2];
	   /* Time elapsed between start time and end time */
	   var re = new RegExp('-', 'g'); 
	   var dts_str = arStr[3];
	   var dte_str = arStr[4];
	   dts_str = dts_str.replace(re,'/');
	   dte_str = dte_str.replace(re,'/');
	   var dts = new Date(dts_str);
	   var dte = new Date(dte_str);
	   var str_tme = getTimeString(dte-dts);
	   var divStatus = getChildById('divStatus') ;
	   var spanTime = getChildById('spanTime',divStatus) ;
	   spanTime.innerHTML = str_tme ;
	   if ( arStr[5] != 'N' ) {
		  imgStaff.src = arStr[5] ;
		  spnStaffImg.style.display = "";
	   } else {
		 imgStaff.src=""; 
		 spnStaffImg.style.display = "none";
	   }
	 } else {
	   //spanImg.innerHTML='';
	   spanSts.innerHTML='';
	   spanSts.innerHTML = xmlHttp1.responseText.substring(3);
	   spnStaffImg.style.display = "none";
     }
   } else {
   }
}
/* Function to get recent chat from database and update the chat display window. */
function getChatUpdates() {
   send_data_one( '',"getChatUpdates.php?mod=U&chatid="+cid_g);
   var str = xmlHttp1.responseText;
   if(str.substring(0,3) == '##X' ) window.close();
   else if (str.substring(0,3) == '##D') {
     var divChatUpd =  getChildById('divChatDisplay') ;
     var str_upd = str.substring(3) ;
	/* convert the flex specific code to pure html
	 var re = new RegExp("SIZE=\"([0-9]{1,2})\"","g");
     while(result=re.exec(str_upd)){
	   str_upd=str_upd.replace(re, "STYLE=\"font-size: $1px\"");
     }
	end*/
	 divChatUpd.innerHTML = str_upd ;
     chatscroll();
   } else{
   }
}

function getDesktopShareStatus() {
   send_data_one( '',"getDesktopShareStatus.php?uid="+uid_g);
   var str = xmlHttp1.responseText;
   if (str == '##HY') {
      var divShareAlert = getChildById('divShareAlert') ;
      divShareAlert.style.display ="";
   }
}
/* Function to send entered chat text to database */
function sendChat( unm ) {
   var divChat = getChildById('divChat') ;
   var spanChat =  getChildById('spanChat', divChat) ;
   var widChatEnter =  getChildById('txtMsg', spanChat) ;
   if ( widChatEnter.value =='' ) {
	   widChatEnter.focus();
	   return false;
   }
   var divChatUpd =  getChildById('divChatDisplay') ;
   var txtUpd = divChatUpd.innerHTML + '<span><FONT color="#0000FF" style="font-size:14px;" FACE="Verdana">'+ unm + ' : ' + '</FONT></span><span><FONT style="font-size:14px;" FACE="Verdana">' + convertHtmlChars(widChatEnter.value) + '</FONT></span><br>';
   var txtSend = '<span><FONT color="#0000FF" style="font-size:14px;" FACE="Verdana">'+ unm + ' : ' + '</FONT></span><span><FONT style="font-size:14px;" FACE="Verdana">' + convertHtmlChars(widChatEnter.value) + '</FONT></span><br>';
   send_data_one( txtSend,"updateChatText.php?mod=C&chatid="+cid_g);
   clearChatField(widChatEnter);
   divChatUpd.innerHTML = txtUpd ;
   chatscroll();
   var spanSend = getChildById('spanSend', divChat) ;
   var btnSend = getChildById('btnSnd', spanSend) ;
   btnSnd.disabled = true;
}
/* function to clear the chat entering textfield*/
function clearChatField( wid ) {
   wid.value = '';
   wid.focus();
}
/*Function to scroll down the chat display window.*/
function chatscroll() {
  var divChatUpd =  getChildById('divChatDisplay') ;
  divChatUpd.scrollTop = divChatUpd.scrollHeight;
}
/*Function to call the chat sending function if Enter key is pressed*/
function onChatEnterPress( e, unm ) {
   if (e.keyCode == 13) {
	  sendChat( unm ); 
   } else {
	   var divChat = getChildById('divChat') ;
	   var spanSend = getChildById('spanSend', divChat) ;
       var btnSend = getChildById('btnSnd', spanSend) ;
       btnSnd.disabled = false;
   }
}
/*Function to enable the chatlogemail window */
function emailChat() {
   var divMail = getChildById('divEmail') ;
   divMail.style.display ="";
}
/*Function to call the windowfor Rate support submit*/
function rateSupport() {
   window.open('rate_support.php?chatid='+cid_g,'RateSupport','width=400,height=275,left=300,top=250,resizable=no,location=no,toolbar=0');
}
/*Function to exit the chat session*/
function exitChat( comp ) {
   send_data_two( '',"endChatSession.php?mod=X&chatid="+cid_g);
   window.location.href="client_prechat.php?comp="+comp;
}
/*Function to close the echatlogmail window*/
function closeMailDiv() {
   var divMail = getChildById('divEmail') ;
   if(getChildById('spanAlert',divEmail)) getChildById('spanAlert',divEmail).innerHTML = '';
   divMail.style.display ="none";
}
function callShareWarn() {
   var divShareWarn = getChildById('divShareWarn') ;
   divShareWarn.style.display ="";
}
function closeShareWarn( ) {
   var divShareWarn = getChildById('divShareWarn') ;
   divShareWarn.style.display ="none";
}
function closeShareAlertDiv( uid ) {
   send_data_two( '',"setDesktopShareStatus.php?uid="+uid);
   var divShareAlert = getChildById('divShareAlert') ;
   divShareAlert.style.display ="none";
}
function callDesktopShareWindow( cid ) {
   var divShareWarn = getChildById('divShareWarn') ;
   divShareWarn.style.display ="none";
  // var rdWin = window.open('desktop_share_client_invoke.php?cid='+cid,'_blank','width=400,height=220, left=400, top=400');
  var ifrmRDS = getChildById('ifrmRDS') ;
  ifrmRDS.src="desktop_share_client_invoke.php?cid="+cid;
}
function convertHtmlChars(str)
{
  str = str.replace(/&/g, "&amp;");
  str = str.replace(/>/g, "&gt;");
  str = str.replace(/</g, "&lt;");
  str = str.replace(/"/g, "&quot;");
  str = str.replace(/'/g, "&#039;");
  return str;
}
 function calldesktop(cid)
 {
     
     
     send_data_one( '',"RDP/sharestatus.php?cid="+cid );
     var str =  xmlHttp1.responseText ;
   //  window.alert(str);
    if(str==0)
        {
            callShareWarn();
        }
        else
            callSessionWarn();
 }
 function callSessionWarn() {
   var divShareWarn = getChildById('divSessionWarn') ;
   divShareWarn.style.display ="";
}
function closeSessionWarn( ) {
   var divShareWarn = getChildById('divSessionWarn') ;
   divShareWarn.style.display ="none";
}