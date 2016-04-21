<?php
/*DB connection*/
 include("../config/settings.php");
 include("../includes/functions/dbfunctions.php");
 $conn = getConnection();
 $chat_id=$_GET[cid];
 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 $sql_w = "SELECT * FROM sptbl_desktop_share WHERE nChatId=".$chat_id." AND Status='1'";
 $rs_w = executeSelect($sql_w,$conn);
    if ( mysql_num_rows($rs_w)> 0 ) {
        echo 1;
    }
    else 
        echo 0;
?>
