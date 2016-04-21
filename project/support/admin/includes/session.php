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

 $passwordLength="3";
 
 function handleError(){
	 	//TODO: need to implement a switch which will determine whether to display original error or send error mailt o admin based on environment
	 	$error = error_get_last();
	 	//echopre($error);
   if ($error['type'] ==1 || $error['type']==4 ) {
		        
				$errormsgblock =' <div><ul>
					                    <li><b>Line</b> '.$error['line'].'</li>
			                            <li><b>Message</b> '.$error['message'].'</li>
			                            <li><b>File</b> '.$error['file'].'</li>                             
			                          </ul></div>';
				return printErrorMessage("Error",$errormsgblock);		
    	}else{return 0;}
	 }
	 
	function printErrorMessage($title,$message_block){
	 		$message='<html><header><title>'.$title.'</title></header>
		                    <style>                 
		                    .error_content{                     
		                        background: ghostwhite;
		                        vertical-align: middle;
		                        margin:0 auto;
		                        padding:10px;
		                        width:50%;                              
		                     } 
		                     .error_content label{color: red;font-family: Georgia;font-size: 16pt;font-style: italic;}
		                     .error_content ul li{ background: none repeat scroll 0 0 FloralWhite;                   
		                                border: 1px solid AliceBlue;
		                                display: block;
		                                font-family: monospace;
		                                padding: 2%;
		                                text-align: left;
		                      }
		                    </style>
		                    <body style="text-align: center;">  
		                      <div class="error_content">
		                          <label >'.$title.'</label>'.
		                          $message_block
		                          .'<a href="javascript:history.back()"> Back </a>                          
		                      </div>
		                    </body></html>';
		
                        
                     return $message;
	 }
 
 
?>