<?php
include("./includes/functions/impfunctions.php");
///*set_magic_quotes_runtime(0);*/
if (get_magic_quotes_gpc()) {
  $_GET = array_map('stripslashes_deep', $_GET);
}
include("./config/settings.php");
header("Content-type: text/xml");
$host = $glb_dbhost;
$user = $glb_dbuser;
$pass = $glb_dbpass;
$database = $glb_dbname;

$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");

$querystring = $_GET['data'];
$query = $querystring;
$resultID = mysql_query($query, $linkID) or die("Data not found.");

  
   
// Display number of fields
   
            $i = 0;
            $nameArray = array();


            while ($i < mysql_num_fields($resultID)) {
                $meta = mysql_fetch_field($resultID, $i);
                $nameArray[$i] = $meta->name;
				 $i++;
            }
            $i = 0;

			echo '<?xml version="1.0" encoding="UTF-8"?>';
			
			echo "<result>\n";			
			echo "<fieldNames>\n";
            while ($i < count($nameArray)) {
                echo "<field".$i.">".$nameArray[$i]."</field".$i.">\n";
                $i++;
            }
            echo "</fieldNames>\n";


			echo "<data>\n";
            $i = 1;			
			while($row = mysql_fetch_array($resultID)){
			
				

					echo "<record>\n";


							for($j = 0; $j < count($nameArray) ; $j++){
				
								
								echo "<".$nameArray[$j].">".htmlentities($row[$nameArray[$j]])."</".$nameArray[$j].">\n";

							}	

					echo "</record>\n"; 

			$i ++;	
			}


   			echo "</data>\n";	
   			echo "</result>\n";

	   
   
   


?> 