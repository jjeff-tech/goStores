<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			*/
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>             		              |
// |          										                      |
// +----------------------------------------------------------------------+

$filename = "./staff/games links.txt";
$file = '00964.jpg';    
header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header('Content-Length: ' . filesize($filename));
header('Content-Disposition: attachment; filename=' . basename($filename));
readfile($filename);
?>