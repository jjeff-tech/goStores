<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | Common database model class. It use for common functions             |
// | File name : adminhelper.php                                               |
// | PHP version >= 5.2                                                   |
// | Created On 03 August 2012                                                   |
// +----------------------------------------------------------------------+
// | Author: Meena Susan Joseph <meena.s@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +------------------------------------------------------

class ModelAdminhelper extends BaseModel
{
    
    public $dbObj;

    public function __construct() {
        parent::__construct();
        $this->dbObj = new Db();
    }

    public function getData($data) {
        // Sample Method
    } // End Function

} // End Class
?>