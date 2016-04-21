<?php
session_set_cookie_params(0);
session_start(); 
if(!isset($_SESSION['sess_userid'])){
        $_SESSION['sess_userid']="";
}
if(!isset($_SESSION['sess_username'])){
        $_SESSION['sess_username']="";
}
if(!isset($_SESSION['sess_useremail'])){
        $_SESSION['sess_useremail']="";
}
if(!isset($_SESSION['sess_usercompid'])){
        $_SESSION['sess_usercompid']="";
}
if(!isset($_SESSION['sess_backurl'])){
        $_SESSION['sess_backurl']="";
}
if(!isset($_SESSION["sess_cssurl"])){
        $_SESSION["sess_cssurl"]="styles/AquaBlue/style.css";
}

if(!isset($_SESSION["sess_language"])){
        
        $_SESSION["sess_language"]= "en";
}

if(!isset($_SESSION["sess_logourl"])){
        $_SESSION["sess_logourl"] = "images/logoo.gif";
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