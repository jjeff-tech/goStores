<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.                                      |
// | File name : index.php                                                  |
// | PHP version >= 5.2                                                   |
// | Created On	: 	Aug 21 2012
// | Author : Meena Susan Joseph <meena.s@armiasystems.com>                                        |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +------------------------------------------------------
class ControllerLogin extends BaseController {
    /*
		construction function. we can initialize the models here
    */
    public function init() {
        parent::init();
		PageContext::addStyle("admin_style.css");
    }

    /*
    function to load the index template
    */
    public function index1() {
        
        
        
        /************* Admin Access Check ****************/
        $adminAccess = User::adminAccessCheck();

        if($adminAccess==1) {
            $this->redirect('index/index');
        }
        /************* Admin Access Check End ************/
        Logger::info("hello world");
        
        $this->view->setLayout("login");
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("adminlogin.js");
        

        $status = NULL;
        $userData = array();

        if($this->isPost()) {

            $username 	= addslashes(($this->post('txtUsername')!='') ? $this->post('txtUsername') :$this->get('txtUsername'));
            $password 	= addslashes(($this->post('txtPassword')!='') ? $this->post('txtPassword') :$this->get('txtPassword'));

            $checkLogin = User::adminLoginCheck($username, $password);

            if($checkLogin['status']==1){
                $roleLogin = User::loggedUserRoleCheck();
              
                if(!$roleLogin){
                    $checkLogin['status'] = 0;
                    $checkLogin['errMsg'] = "Your user role is no longer active!";
                    session_unset($_SESSION['adminUser']['username']);
                    session_unset();
                }else{
                    $checkLogin['status'] = 1;
                }
            }
            if($checkLogin['status']==1) {
                User::updateLastLogin();
                $this->redirect('index/index');
            }

        } // End IsPost

        if(isset($checkLogin['errMsg']) && !empty($checkLogin['errMsg'])) { // Error on Page
            $this->view->errMsg = $checkLogin['errMsg'];

        }
    }

    /*
    Function to logout the user
    */
    public function logout() {
        session_destroy();
        session_unset($_SESSION['adminUser']);

        //header("location:".ConfigUrl::base());
        $this->redirect('index/index');
        $this->view->disableView();
        exit();
    }

    /*
     Function for forgotpassword
    */
    public function forgotpassword() {
        /************* Admin Access Check ****************/
        $adminAccess = User::adminAccessCheck();

        if($adminAccess==1) {
            $this->redirect('index/index');
        }
        /************* Admin Access Check End ************/
        
        $this->view->setLayout("login");
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("forgotpassword.js");
        PageContext::includePath("email");
        $errMsg = $msgSuccess = NULL;

       
        
        if($this->isPost()) {
            $userEmail 	= addslashes(($this->post('txtuseremail')!='') ? $this->post('txtuseremail') :$this->get('txtuseremail'));
           
            if(!empty($userEmail)) {
                $validateEmail = verify_email($userEmail);
                if($validateEmail) {
                    // Send Credentials
                    $userArr = Admincomponents::getListItem('Admin', array('nAId','vEmail','vUsername','vFirstName','vLastName'), array(array('field' => 'vEmail', 'value' => addslashes($userEmail))));
                    if(!empty($userArr)) {
                        Admincomponents::sendPassword($userEmail);
                        $msgSuccess = "Login credentials has been sent to your email";
                        PageContext::$response->success_message = "Login credentials has been sent to your email";
                        PageContext::addPostAction('successmessage','settings');
                        $this->view->messageFunction = 'successmessage';
                    } else {
                      
                       PageContext::$response->error_message = "Email-ID doesnot exists";
                       PageContext::addPostAction('errormessage','settings');
                       $this->view->messageFunction = 'errormessage';
                    }
                } else {
                  
                    PageContext::$response->error_message = "Please enter valid Email-ID";
                    PageContext::addPostAction('errormessage','settings');
                    $this->view->messageFunction = 'errormessage';
                }
            }

            $this->view->msgSuccess = $msgSuccess;
            $this->view->errMsg = $errMsg;

        } // End IsPost

    } // End Function

    //Function to load user deatils for cms
    public function loaduserdetails($userId){
        $this->disableLayout();
        $this->view->userDetails = Admincomponents::getUserdetails($userId);
    }

}

?>