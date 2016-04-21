var xmlHttp
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
//how to use ?
function getdata() {
   send_to_server( '',"getdata.php?chatid=1");
   alert(xmlHttp1.responseText);
   //var divChatUpd =  getChildById('divChatDisplay') ;
  // divChatUpd.innerHTML = xmlHttp1.responseText ;
}