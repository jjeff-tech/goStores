/* xmlHttp Object Creation starts*/
var xmlHttp1
var xmlHttp2
if (!xmlHttp1) {
    try {
       xmlHttp1 = new ActiveXObject("Msxml2.XMLHTTP")
    } catch (e) {
       try {
          xmlHttp1 = new ActiveXObject("Microsoft.XMLHTTP")
       } catch (e) {
          try {
             xmlHttp1 = new XMLHttpRequest();
          } catch (e) {
             alert ('Your browser does not support XMLHttpRequest');
             xmlHttp1 = false ;
          }
       }
    }
}
if (!xmlHttp2) {
    try {
       xmlHttp2 = new ActiveXObject("Msxml2.XMLHTTP")
    } catch (e) {
       try {
          xmlHttp2 = new ActiveXObject("Microsoft.XMLHTTP")
       } catch (e) {
          try {
             xmlHttp2 = new XMLHttpRequest();
          } catch (e) {
             alert ('Your browser does not support XMLHttpRequest');
             xmlHttp2 = false ;
          }
       }
    }
}
/* xmlHttp Object Creation ends*/
/* function to connect and send data to server using first instance (xmlHttp1)*/
function send_data_one( data,urlTo) {
   xmlHttp1.open("POST", urlTo, false)
   xmlHttp1.setRequestHeader('Content-Type', data.length );
   xmlHttp1.send(data)
   if ( xmlHttp1.status!=200) {
         //Akhil commet due to un necessory msg
         // alert("Url: "+urlTo+" not found")
         return null
   }
}
/* function to connect and send data to server using second instance (xmlHttp2)*/
function send_data_two( data,urlTo) {
   xmlHttp2.open("POST", urlTo, false)
   xmlHttp2.setRequestHeader('Content-Type', data.length );
   xmlHttp2.send(data)
   if ( xmlHttp2.status!=200) {
       //Akhil commet due to un necessory msg
         // alert("Url: "+urlTo+" not found")
         return null
   }
}
/*common function to get a child of widget*/
function getChildById( id, tag  ) {
   if ( tag == null ) {
	  tag = document.getElementById('divAll');
   }
   for (var i = 0; i < tag.childNodes.length; i++) {
      if ( tag.childNodes[i].id == id) {
        return tag.childNodes[i] ;
      }
   }
   return null ;
}
/*email validation function*/
function validateEmail(email) {
   var str1=email;
   var arr=str1.split('@');
   var eFlag=true;
   if(arr.length != 2){
	  eFlag = false;
   } else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1){
	  eFlag = false;
   } else {
     var dot=arr[1].split('.');
     if(dot.length < 2){
        eFlag = false;
     } else {
       if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1){
          eFlag = false;
       }
	   for(i=1;i < dot.length;i++){
          if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1){
            eFlag = false;
          }
       }
  	   if(dot[i-1].length > 4) eFlag = false;
     }
   }
   return eFlag;
}
/*function to return zero flled value if the value is less than 10*/
function zeroFilledValue( val )  {
  if (val < 10) return "0" + val;
  else return val; 
}
