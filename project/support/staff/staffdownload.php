<?php 

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                          |
// |                           |
// +----------------------------------------------------------------------+

  //include("./includes/settings.php");
  include("../config/settings.php");
  include("./includes/functions/dbfunctions.php");
  $conn = getConnection();
  $sql="SELECT vURL    FROM sptbl_downloads WHERE   nDLId ='" . addslashes($_GET["id"]) . "'";
  $rs_url = executeSelect($sql,$conn);
  $rowurl=mysql_fetch_array($rs_url);
  $filename="../".$rowurl['vURL'];
  header('Content-Description: File Transfer'); 
  header('Content-Type: application/force-download'); 
  header('Content-Length: ' . filesize($filename)); 
  header('Content-Disposition: attachment; filename=' . basename($filename)); 
  readfile($filename);
?> 
