<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$sql = "Select vLookUpValue from sptbl_lookup WHERE  vLookUpName = 'SiteURL'";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {
                    $var_row = mysql_fetch_array($var_result);
                     $var_siteURL =$var_row["vLookUpValue"];
                     define("SITE_URL", $var_siteURL);
                }
?>
