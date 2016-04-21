/* xmlHttp Object Creation starts*/
var xmlHttp
 var tmr_g = 0
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
tmr_g  = setTimeout("triggerTimer()", 3000);
function triggerTimer() {
  if(tmr_g) clearTimeout(tmr_g);
  checkSessionExist();
  tmr_g = setTimeout("triggerTimer()", 3000);
}
/* function to connect and send data to server using first instance (xmlHttp1)*/
function send_data( data,urlTo) {
   xmlHttp.open("POST", urlTo, false)
   xmlHttp.setRequestHeader('Content-Type', data.length );
   xmlHttp.send(data)
   if ( xmlHttp.status!=200) {
          alert("Url: "+urlTo+" not found")
         return null
   }
}
function checkSessionExist() {
  send_data( '',"../checkSessionExist.php" );
  var str =  xmlHttp.responseText ;
  if ( str == '#X') window.close();
}