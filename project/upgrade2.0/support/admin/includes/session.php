<?php
ini_set('session.gc_maxlifetime',14400);
session_set_cookie_params(0);
session_start();
if(!isset($_SESSION['sess_staffid'])){
        $_SESSION['sess_staffid']="";
}
if(!isset($_SESSION['sess_staffname'])){
        $_SESSION['sess_staffname']="";
}
if(!isset($_SESSION['sess_staffemail'])){
        $_SESSION['sess_staffemail']="";
}
if(!isset($_SESSION['sess_isadmin'])){
        $_SESSION['sess_isadmin']= 0 ;
}
if(!isset($_SESSION['sess_backurl'])){
        $_SESSION['sess_backurl']="";
}
if(!isset($_SESSION["sess_langchoice"])){
        $_SESSION["sess_langchoice"]="";
}
if(!isset($_SESSION["sess_logourl"])){
        $_SESSION["sess_logourl"] = "./../images/logoo.gif";
}
if(!isset($_SESSION['sess_cssurl'])){
        $_SESSION['sess_cssurl']="styles/AquaBlue/style.css";
}
if(!isset($_SESSION["sess_language"]) ){
    $_SESSION["sess_language"] = "en";
}
if(!isset($_SESSION["sess_licensetype"])){
        $_SESSION["sess_licensetype"]="";
}
if(!isset($_SESSION["sess_domainname"])){
        $_SESSION["sess_domainname"]="";
}
if(!isset($_SESSION["sess_logactivity"])){
        $_SESSION["sess_logactivity"]="1";
}
if(!isset($_SESSION["sess_maxpostperpage"])){
        $_SESSION["sess_maxpostperpage"]="30";
}
if(!isset($_SESSION["sess_messageorder"])){
        $_SESSION["sess_messageorder"]="1";
}
?>