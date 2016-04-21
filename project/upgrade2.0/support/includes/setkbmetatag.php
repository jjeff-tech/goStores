<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if(isNotNull($_GET["id"])){
	$kbid = $_GET["id"];

	settype($kbid,integer);
	 $sql = " SELECT vMetaTage_keyword,vMetaTage_desc  ";
	$sql .=" FROM  sptbl_kb  ";
	$sql .=" WHERE nKBID = '". mysql_real_escape_string($kbid) . "' ";

	$rs = executeSelect($sql,$conn);
	if(mysql_num_rows($rs) > 0){
		$row = mysql_fetch_array($rs);
		$metaTageKeyword = $row["vMetaTage_keyword"];
                $metaTageDesc = $row["vMetaTage_desc"];
        }
}
$metaTageKeyword = $metaTageKeyword == ""?"Knowledgebase ":$metaTageKeyword;
$metaTageDesc = $metaTageDesc == ""?"Knowledgebase ":$metaTageDesc;

?>

<meta content="<?php echo $metaTageDesc;?>" name="description">
<meta content="<?php echo $metaTageKeyword;?>" name="keywords">