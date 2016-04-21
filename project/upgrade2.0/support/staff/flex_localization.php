<?php 
 include("./includes/session.php");
 header("Content-type: text/xml");
 $myfile = "languages/".$_SESSION["sess_language"]."/flex.txt"; 
 $fp = fopen($myfile, "r");
 echo "<?xml version='1.0' encoding='UTF-8'?>";
 echo "<result>\n";
 while (!feof($fp)) {
  $lne = fgets($fp);
  $pos = strpos( $lne, '=' );
  $str1=substr($lne,0,$pos);
  $str2=substr($lne,$pos+1);
  echo "<".$str1.">".$str2."</".$str1.">\n";
 }
 echo "</result>\n";

?>