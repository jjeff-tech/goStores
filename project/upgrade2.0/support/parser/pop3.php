<?php
///*set_magic_quotes_runtime(0);*/
// Check if magic_quotes_runtime is active
if(get_magic_quotes_runtime())
{
    // Deactivate
    /*set_magic_quotes_runtime(false);*/
}
$path=$_SERVER['argv'][0];
$dotreal=dirname(realpath($path));
$dotdotreal=dirname($dotreal);
if($dotreal=="") $dotreal=".";
if($dotdotreal=="") $dotdotreal="..";
require_once("$dotdotreal/includes/decode.php");
if(!isValidForParser(1,'P',$dotdotreal)) {
	exit();
}

include_once("$dotdotreal/config/settings.php");
include_once("$dotreal/includes/functions/miscfunctions.php");
include_once("$dotreal/includes/functions/impfunctions.php");
include_once("$dotreal/includes/functions/dbfunctions.php");
include_once("$dotreal/includes/mimedecode.inc.php");
include_once("$dotreal/includes/RFC822.php");
include_once("$dotreal/languages/en/pop3.php");
require_once("$dotreal/functions.php");
$conn = getConnection();

		$sql = "select * from sptbl_pop3settings";

		$var_result = executeSelect($sql,$conn);
		if (mysql_num_rows($var_result) > 0) {
			while($var_row = mysql_fetch_array($var_result)){
				$var_department = $var_row["nDeptId"];
				$var_deptemail  = $var_row["vDeptEmail"];
				$var_pop3_servername = $var_row["vServerName"];
				$var_pop3_username = $var_row["vUserName"];
				$var_pop3_password = $var_row["vPassword"];
				$var_port = $var_row["nPortNo"];


				$pop3 = pop3_open($var_pop3_servername, $var_port);
				if (!$pop3) {					
				 	//printf("[ERROR] Failed to connect to server<BR>\n");
					return 0;
				}
				 
				if (!pop3_user($pop3, $var_pop3_username)) {					
				 	//printf("[ERROR] Username failed!<BR>\n");
					return 0;
				}
				 
				 if (!pop3_pass($pop3, $var_pop3_password)) {					
				 	//printf("[ERROR] PASS failed!<BR>\n");
					return 0;
				 }
				
				 $articles = pop3_list($pop3);
				 if (!$articles) {					
				 	//printf("[ERROR] LIST failed!<BR>\n");
					return 0;
				 }
 				 
				 for ($i_count = 1; $i_count <= $articles ["count"]; $i_count++)
				 {
					$emailcontent="";
					$data = pop3_retr($pop3,$i_count);
					if (!$data) {
						//printf("data goes wrong on '$i_count'<BR>\n");
						return 0;
					}
					for ($j = 0; $j < $data["count"]; $j++)
					{
						$emailcontent .= $data[$j];
					}
					$createticket = 0;

					require("pop3parser.php");
			
			        if($createticket==1){
						fputs($pop3, "DELE $i_count\r\n");
					}
//					pop3_dele($pop3,$i_count);
				 }
			        fputs($pop3, "QUIT\r\n");
 			}
  }
//	pop3_quit($pop3);


  function pop3_open($server, $port)  
  {
	global $POP3_GLOBAL_STATUS;

	$pop3 = fsockopen($server, $port);

	// Maybe some people need something like:
	// $pop3 = fsockopen($server, $port, &$errno, &$errstr, 30);
	
	if ($pop3 <= 0) return 0;

	$line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);

	if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

	return $pop3;
  }

  function pop3_user($pop3, $user)
  {
	global $POP3_GLOBAL_STATUS;

	fputs($pop3, "USER $user\r\n");
	$line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);
	
	if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

	return 1;
  }

  function pop3_pass($pop3, $pass)
  {
	global $POP3_GLOBAL_STATUS;

	fputs($pop3, "PASS $pass\r\n");
	$line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);
	
	if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

	return 1;
  }
  
  function pop3_stat($pop3)
  {
	global $POP3_GLOBAL_STATUS;

	fputs($pop3, "STAT\r\n");
	$line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);

        if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

	if (!preg_match("~+OK (.*) (.*)~i", $line, $regs))
		return 0;

	return $regs[1];
  }

  function pop3_list($pop3)
  {	
        global $POP3_GLOBAL_STATUS;
  
        fputs($pop3, "LIST\r\n");
        $line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);

        if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

	$i = 0;
	while  (substr($line  =  fgets($pop3, 1024),  0,  1)  <>  ".")
	{
		$articles[$i] = $line;
		$i++;
	}
	$articles["count"] = $i;

	return $articles;
  }

  function pop3_retr($pop3, $nr)
  {
        global $POP3_GLOBAL_STATUS;
  
        fputs($pop3, "RETR $nr\r\n");
        $line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);

        if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

/* [Bug-fix!]  Reported by: Rain Pihelpuu 
before - while (substr($line  =  fgets($pop3, 1024),  0, 1) <>  ".") -
after - while (substr($line  =  fgets($pop3, 1024),  0) != ".\r\n") -
*/
	$i = 0;
        while (substr($line  =  fgets($pop3, 1024),  0)  !=  ".\r\n")
        {
                $data[$i] = $line;
                $i++;
        }
        $data["count"] = $i;

        return $data;
  }

  function pop3_header($pop3, $msg_num , $num_lines)
  {
	global $POP3_GLOBAL_STATUS;

	$data_count=0;
 	fputs($pop3, "TOP $msg_num $num_lines\r\n");
 	$line = fgets($pop3,1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);
        if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;
 
        while (substr($line  =  fgets($pop3, 1024),  0)  !=  ".\r\n")
        {
  	  if(substr($line,0,3)=="To:")
	  {
		$data["TO"] = substr($line, 4);
		/*If there are < and > then we just want the stuff in there*/
	  	if (@preg_match("<([^>]*)>", $line, $parts))
		{
		  $data["TO"] = $parts[0];
		}
	        $data_count++;
           }elseif(substr($line,0,5)=="From:"){
		$data["FROM"]=substr($line,6);
		$data_count++;
	   }elseif(substr($line,0,8)=="Subject:"){
		$data["SUBJECT"]=substr($line,9);
		$data_count++;
	   }
	   if ($data_count==3)
   		break;   // We've got them all.
			 // Do not loop longer then necessary
        }
	return $data;
  }

  function pop3_dele($pop3, $nr)
  {  
        global $POP3_GLOBAL_STATUS;

        fputs($pop3, "DELE $nr\r\n");
        $line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);

        if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

        return 1;
  }

  function pop3_quit($pop3)
  {
        global $POP3_GLOBAL_STATUS;

        fputs($pop3, "QUIT\r\n");
        $line = fgets($pop3, 1024);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] = substr($line, 0, 1);
        $POP3_GLOBAL_STATUS[$pop3]["LASTRESULTTXT"] = substr($line, 0, 1024);

        if ($POP3_GLOBAL_STATUS[$pop3]["LASTRESULT"] <> "+") return 0;

        return 1;
  }
?>
