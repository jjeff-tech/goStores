var xmlHttp
var win;
if (!xmlHttp) {
    try {
       xmlHttp = new ActiveXObject("Msxml2.XMLHTTP")
    } catch (e) {
       try {
          xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")
       } catch (e) {
          try {
             xmlHttp = new XMLHttpRequest();
          } catch (e) {
             alert ('Your browser does not support XMLHttpRequest');
             xmlHttp = false ;
          }
       }
    }
}
function send_to_server(data, urlTo) {
   xmlHttp.open("POST", urlTo, false);
   xmlHttp.setRequestHeader('Content-Type', data.length );
   xmlHttp.send(data);
   if ( xmlHttp.status!=200) {
         //Akhil commet due to un necessory msg
     // alert("Url: "+urlTo+" not found");
      return null;
   }
}
function chatInvoke( cmp, site, pg ) {
  if ( !cmp && !site ) return false;
   if ( site ) {
	  var invoke_path = site+"getInvokeStatus.php?comp="+cmp+"&pg="+pg;
	  var chat_path = site+'index_client_chat.php?comp='+cmp+'&ref=invokeChat';
   } else {
	  var invoke_path = "getInvokeStatus.php?comp="+cmp+"&pg="+pg;
	  var chat_path = 'index_client_chat.php?comp='+cmp+'&ref=invokeChat';
   }
  send_to_server( '', invoke_path );
   if ( xmlHttp.responseText == 'Y' ){
	 if ( !win ) win = window.open(chat_path,'NeedHelp','width=475,height=620,resizable=yes,location=no');
   } else {
	 return false;
   }
} 	   