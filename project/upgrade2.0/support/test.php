<?php

include("./includes/functions/impfunctions.php");
///*set_magic_quotes_runtime(0);*/
if (get_magic_quotes_gpc()) {
  $_GET = array_map('stripslashes_deep', $_GET);
}
include("./config/settings.php");
$host = $glb_dbhost;
$user = $glb_dbuser;
$pass = $glb_dbpass;
$database = $glb_dbname;
   $querystring = $_GET['data'];  
   $querystring1 = $_GET['data1'];  
$query =  $querystring;
echo $query;
$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");
$resultID = mysql_query($query, $linkID) or die("Data not found.");  
$query_1 = "select * from test where name='".$querystring1."'";
$result = mysql_query($query_1, $linkID) or die("Data not found."); 
$row = mysql_fetch_array($result);
echo $row['name']; 
?>