<?php
		error_reporting(E_ALL ^ E_NOTICE);
		include("./includes/session.php");
        //include("./includes/settings.php");
        include("../config/settings.php");
        if( !isset($INSTALLED))
			header("location:../install/index.php") ;
		include("./includes/functions/dbfunctions.php");
		include("./includes/functions/impfunctions.php");


	    /*ini_set('magic_quotes_runtime',0);*/

        if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);

        }
		//PATCH FOR WINDOWS -- SINCE $_SERVER["REQUEST_URI"] NOT FUNCTIONING ON CERTAIN WINDOWS SERVERS - AUGUST 16, 2005
		if(!isset($_SERVER['REQUEST_URI'])) {
			if(isset($_SERVER['SCRIPT_NAME']))
				$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
			else
				$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];

			if($_SERVER['QUERY_STRING']){
				$_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING'];
			}
		}
		//PATCH FOR WINDOWS -- SINCE $_SERVER["REQUEST_URI"] NOT FUNCTIONING ON CERTAIN WINDOWS SERVERS - AUGUST 16, 2005

		if (basename($_SERVER["REQUEST_URI"]) != "index.php") {
			if(adminLoggedIn()){
				clearAdminSession();
			}
                     if (basename($_SERVER["REQUEST_URI"]) != "autocomplete.php") {
			if(!staffLoggedIn()) {
				header("location:index.php");
				exit;
			}
                     }
			//$rqUri = explode("?",basename($_SERVER["REQUEST_URI"]));
		    //$rqFl = $rqUri[0];
			
			if ($_SERVER['HTTP_REFERER'] == "" && ((basename($_SERVER["REQUEST_URI"]) != "staffmain.php") || (basename($_SERVER["REQUEST_URI"]) != "chatview.php")) ) {
				header("location:staffmain.php");
				exit;
			}
		}

		if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
                $_SP_language = "en";
        }else{
                $_SP_language = $_SESSION["sess_language"];
        }
         include("./languages/".$_SP_language."/main.php");
		include("./includes/main_smtp.php");

		include("./includes/pvtmessagealert.php");  // included for displaying private message alert below language selection combo
		include("./includes/newticketsalert.php");  // included for displaying new tickets alert

                include("../includes/constants.php");

                

		//echo "<br>".$_SESSION["sess_language"];
?>