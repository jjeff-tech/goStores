<?php
include_once(BASE_PATH . 'project/config/settings.php');

$conapi = mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD) or die(mysql_error());
mysql_select_db(MYSQL_DB, $conapi) or die(mysql_error());

function userAdd($loginnameapi, $passwordapi, $emailapi) {

    global $conapi;

    $sqlapi = " INSERT INTO sptbl_users(`nUserId`,`vUserName`,`nCompId`,`vEmail`,`vLogin`,`vPassword`,`dDate`, `vBanned`, `vDelStatus`) ";
    $sqlapi .= " VALUES('','" . addslashes($loginnameapi) . "','1', '" . addslashes($emailapi) . "','" . addslashes($loginnameapi) . "','" . addslashes(md5($passwordapi)) . "',now(),'0','0')";
    $resultapi = mysql_query($sqlapi);

}

?>
