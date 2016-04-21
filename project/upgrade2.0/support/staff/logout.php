<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer1<programmer1@armia.com>                          |
// |          programmer1<programmer2@armia.com>                          |
// +----------------------------------------------------------------------+
    require_once("./includes/applicationheader.php");
	include("./includes/functions/miscfunctions.php");
        $conn = getConnection();
        clearStaffSession();
//        print_r($_SESSION);
//        exit;
		session_unset();
		session_destroy();
        header("Location: index.php");
        exit;


?>