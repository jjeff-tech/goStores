<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.                                      |
// | File name : plan.php                                                  |
// | PHP version >= 5.2                                                   |
// | Created On	: 23 Aug 2012
// | Author : Meena Susan Joseph <meena.s@armiasystems.com>                                               |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+

class ControllerSettings extends BaseController {
    /*
		construction function. we can initialize the models here
    */
    public $dbObj = null;

    public function init() {
        parent::init();

        /************* Admin Access Check ****************/
        //$adminAccess = User::adminAccessCheck();

        /*
        if($adminAccess==0) {
            $this->redirect('login/index');
        }
         */
        /************* Admin Access Check End ************/
        /************* Left Menu Area ************/
        $leftMenuArr = NULL;
        if(isset($_SESSION['adminUser']['userModules']) && !empty($_SESSION['adminUser']['userModules'])) {
            $leftMenuArr = $_SESSION['adminUser']['userModules'];
        }
        $this->view->leftMenu='left_main';
        $this->view->leftMenuArr = $leftMenuArr;
        /************* Left Menu Area Ends *******/
        PageContext::addStyle("admin_style.css");
        PageContext::addJsVar("BASE_URL",BASE_URL);
        $this->dbObj = new Db();
        PageContext::addScript("admin.js");
    }

    /*
    function to load the index template
    */
    public function index() //
    {
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("jquery.validate.js");
                
        //Logger::info("hello world");
        PageContext::$response->activeLeftMenu = 'Settings';
        $this->view->setLayout("home");
        PageContext::includePath("resize");
        PageContext::addStyle("thickbox.css");
        PageContext::addScript("thickbox.js");

        $error=''; // Error;

        // Form Post
        if($this->isPost()) {
            $dataSettings = $_POST;
            $dataSettings["enom_testmode"] = $dataSettings["enom_testmode"]=="YES"?$dataSettings["enom_testmode"]:"NO";
            //************************* SITE BANNER **********************/
            if(is_uploaded_file($_FILES['siteBanner']['tmp_name'])) {
                $bannerParts = pathinfo($_FILES['siteBanner']['name']);               
                $bannerOriginal = BASE_PATH.'project/styles/images/'.'mainbanner.'.$bannerParts['extension'];

                if(move_uploaded_file($_FILES['siteBanner']['tmp_name'], $bannerOriginal)) {

                    $resizeObj = new resize($bannerOriginal);
                    //$resizeObj2 = new LibResize($bannerOriginal);
                    $resizeObj->resizeImage(775, 100, 'exact');
                    $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$bannerOriginal);
                    $resizeObj->saveImage($rz, 100);
                    $bannerUpdate['value']='mainbanner_disp.gif';
                    $this->dbObj->updateFields("Settings",$bannerUpdate,"settingfield='siteBanner'");
                }
            }
            //************************* SITE BANNER ENDS **********************/
            foreach($dataSettings as $field=>$value) {
                //Update Other Fields
                $arrUpdate = array();
                $arrUpdate['value']=addslashes($value);
                $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='".$field."'");
            } // Foreach

            PageContext::$response->success_message = "Changes Saved Successfully" ;
            PageContext::addPostAction('successmessage');
            //$this->view->message   = (empty($error)) ?    "Changes Saved Successfully" : $error;

        } // End isPost

        //Settings
        $settings = Admincomponents::getSiteSettings();
        $this->view->setting = $settings;
        //echo '<pre>'; print_r($settings); echo '</pre>';


    } // End Function

    /*
     * Function to load the payment setting
    */
    public function payments() {
        
        PageContext::$response->activeLeftMenu = 'Settings';
        $this->view->setLayout("home");

        $epaypal=($this->post('p_paypal')=='on') ? 'Y' : 'N';
        $sandbox=($this->post('p_sandbox')=='on') ? 'Y' : 'N';

        $e_auth=($this->post('e_auth')=='on') ? 'Y' : 'N';
        $a_test=($this->post('a_test')=='on') ? 'Y' : 'N';

        $error = NULL;

        // Paypal Settings
        if($this->isPost()) {

            $arrUpdate       =   array("value"=>addslashes($epaypal));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='enablepaypal'");

            $arrUpdate       =   array("value"=>addslashes($sandbox));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='enablepaypalsandbox'");

            $arrUpdate       =   array("value"=>addslashes($this->post('p_tocken')));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='paypalidentitytoken'");

            $arrUpdate       =   array("value"=>addslashes($this->post('p_email')));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='paypalemail'");
                       
            $arrUpdate       =   array("value"=>addslashes($e_auth));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='authorize_enable'");

            $arrUpdate       =   array("value"=>addslashes($this->post('a_logid')));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='authorize_loginid'");

            $arrUpdate       =   array("value"=>addslashes($this->post('a_tkey')));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='authorize_transkey'");

            $arrUpdate       =   array("value"=>addslashes($this->post('a_email')));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='authorize_email'");

            $arrUpdate       =   array("value"=>addslashes($a_test));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='authorize_test_mode'");


            $arrUpdate       =   array("value"=>addslashes($this->post('currency')));

            $this->dbObj->updateFields("Settings",$arrUpdate,"settingfield='admin_currency'");

            // Success Message
           // $this->view->message   = (empty($error)) ?    "Changes Saved Successfully" : $error;
            PageContext::$response->success_message = "Changes Saved Successfully" ;
            PageContext::addPostAction('successmessage');


        } // End isPost

        $this->view->authEnable     =   $this->dbObj->selectRow("Settings","value","settingfield='authorize_enable'");

        $this->view->authLoginId     =   $this->dbObj->selectRow("Settings","value","settingfield='authorize_loginid'");

        $this->view->authtransKey     =   $this->dbObj->selectRow("Settings","value","settingfield='authorize_transkey'");

        $this->view->authEmail     =   $this->dbObj->selectRow("Settings","value","settingfield='authorize_email'");

        $this->view->authTestMode   =   $this->dbObj->selectRow("Settings","value","settingfield='authorize_test_mode'");


        //**************** PAYPAL


        $this->view->enablePaypal     =   $this->dbObj->selectRow("Settings","value","settingfield='enablepaypal'");

        $this->view->enableSandBox     =   $this->dbObj->selectRow("Settings","value","settingfield='enablepaypalsandbox'");

        $this->view->paypalTocken     =   $this->dbObj->selectRow("Settings","value","settingfield='paypalidentitytoken'");

        $this->view->paypalEmail     =   $this->dbObj->selectRow("Settings","value","settingfield='paypalemail'");

        $this->view->currency     =   $this->dbObj->selectRow("Settings","value","settingfield='admin_currency'");

    } // End Functions

    public function successmessage(){

    }

    public function errormessage(){

    }

    public function ajaxMakeServerDefault(){ 
       
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateDefaultServer($id,$value);
        }
        exit();
    }

} // End Class

?>