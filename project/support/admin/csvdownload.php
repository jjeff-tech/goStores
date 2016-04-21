<?php 

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com> 		                          |
// |                    											      |
// +----------------------------------------------------------------------+

  $filename="../csvfiles/".addslashes($_GET["id"]).".txt";
  header('Content-Description: File Transfer'); 
  header('Content-Type: application/force-download'); 
  header('Content-Length: ' . filesize($filename)); 
  header('Content-Disposition: attachment; filename=' . basename($filename)); 
  readfile($filename);
?> 
