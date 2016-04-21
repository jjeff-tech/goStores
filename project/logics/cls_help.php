<?php
/*
 * All User Entity Logics should come here.
*/
class Help {

    public static $dbObj = null;
/*
 * function to fetch user help
 */
    public static function getUserHelpDetails($userId='') {
        Help::$dbObj     = new Db();
        $helpData        = array();
        $helpData        = Help::$dbObj->selectResult('Help',"vTitle,tDescription","eType='".HELP_USER_CAT."' AND eStatus='".HELP_ACTIVE_STATUS."'");
        return $helpData;
    }
} //End Class


?>