
<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.                                      |
// | File name : index.php                                                  |
// | PHP version >= 5.2                                                   |
// | Created On	: 	Aug 23 2012
// | Author : Meena Susan Joseph <meena.s@armiasystems.com>                                               |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +------------------------------------------------------


class ControllerRole extends BaseController {
    /*
		construction function. we can initialize the models here
    */
    public function init() {
        parent::init();
        
        /************* Admin Access Check ****************/
        $adminAccess = User::adminAccessCheck();

        if($adminAccess==0) {
            $this->redirect('login/index');
        }
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
        PageContext::addScript("admin.js");

    }

    /*
    function to load the index template
    */
    public function index($action = NULL,$id = NULL, $txtSearch = NULL, $page = NULL) // dashboard
    {
         if($txtSearch=='x')
            $txtSearch = NULL;
        
        $this->view->pageCount = $page;        
        PageContext::$response->activeLeftMenu='Roles';
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("jquery.metadata.js");
        //Logger::info("hello world");
        $this->view->setLayout("home");
        PageContext::addScript("formValidations.js");
        PageContext::addScript("jquery.addplaceholder.min.js");
       

        $session = new LibSession();
        switch ($action){
            case 'add' :
                $this->view->pageTitle = 'Add Role';
                $this->view->action = 'add';
                $this->view->addEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            case 'edit' :
                $this->view->pageTitle = 'Edit Role';
                $this->view->action = 'edit';
                $this->view->editEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            default :
                $this->view->pageTitle = 'Roles';
                if($session->get('add_role_success')=='success'){
                     PageContext::$response->success_message =  "Role created successfully" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('add_role_success');
                }

                if($session->get('edit_role_success')=='success'){
                   
                     PageContext::$response->success_message =  "Role changes saved successfully" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('edit_role_success');
                }
                if($session->get('activate_role_success')=='success'){
                     PageContext::$response->success_message =  "Successfully activated the role" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('activate_role_success');
                }
                if($session->get('deactivate_role_success')=='success'){
                     PageContext::$response->success_message =  "Successfully deactivated the role" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('deactivate_role_success');
                }

            break;
        }
        // Module List        
        $modules = Admincomponents::getActiveModules();
        $this->view->moduleArr = $modules;
        //Roles        
        $role = Admincomponents::getRoleData($id);
        $this->view->role = $role;

        //Button Label
        $this->view->buttonLabel = (empty($id)) ? 'Add' : 'Update'; // Button Label
        
        
        //Page Head Action
        $pageHeadLabel =NULL;
        if($action=='add') {
         $pageHeadLabel = 'Add ';
        } else if($action=='edit') {
         $pageHeadLabel = 'Edit ';
        }

        $this->view->pageHeadLabel = $pageHeadLabel;

        $this->view->action	= $action  ;
        $this->view->id 	= $id    ;
       
        /*
         * Activate / Deactivate
         */
        if(($action == 'deactivate' || $action == 'activate') && $id != '') {

            switch ($action) {
                case "deactivate":
                    $statusArr = array("nStatus" => 0);
                    $session->set('deactivate_role_success', 'success');
                    break;
                case "activate":
                    $statusArr = array("nStatus" => 1);
                    $session->set('activate_role_success', 'success');
                    break;
            }
                       
            AdminComponents::updateListItem($statusArr, "Role", "nRid", $id);
            $this->redirect('role');
            $this->view->message = 'Changes saved successfully';

        }
        /*
         * Add / Edit / Search
         */
        if($this->isPost()) {
            
            if($_POST['action']=='search'){
                $txtSearch = $_POST['search'];
            } else if($_POST['action']=='edit' || $_POST['action']=='add') {
                $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
                $role = addslashes(($this->post('role')!='') ? $this->post('role') :$this->get('role'));
                $moduleAccess = $_POST['moduleAccess'];
                // Roles
                $roleData = array();
                $roleData['nRid'] = $id ;
                $roleData['vRoleName'] = $role;
                $roleData['moduleAccess'] = $moduleAccess;
                
                $savedRoleData = Admincomponents::saveRole($roleData);
                if(isset($savedRoleData['errMsg']) && !empty($savedRoleData['errMsg'])) {
                  
                     PageContext::$response->error_message =  $savedRoleData['errMsg'];
                     PageContext::addPostAction('errormessage','index');
                    $this->view->message = $savedRoleData['errMsg'];
                } else { // End Error Message
                    if(empty($roleData['nRid'])){
                        $session->set('add_role_success', 'success');
                    }else{
                        $session->set('edit_role_success', 'success');
                    }
                    $this->redirect('role');
                    $this->view->message = "Changes Saved successfully";
                }
            }

        } // End isPost


        switch ($action){
            case 'sortbyrolenameasc':
                $sort_order= 'ASC';
                $orderArr['fields']  = array('vRoleName');
                $this->view->rolenameSortStyle = 'sort_column_down';
                $this->view->rolenameSortAction = 'sortbyrolenamedesc';

                $this->view->searchParam = $txtSearch;
            break;
            case 'sortbyrolenamedesc':
               $orderArr['sort'] = 'DESC';
               $orderArr['fields']  = array('vRoleName');
               $this->view->rolenameSortStyle = 'sort_column_up';
               $this->view->rolenameSortAction = 'sortbyrolenameasc';
               $this->view->searchParam = $txtSearch;

            break;
            default:
                $orderArr['sort'] = 'ASC';
                $orderArr['fields']  = array('vRoleName');
                $this->view->rolenameSortStyle = 'sort_column_down';
                $this->view->rolenameSortAction = 'sortbyrolenamedesc';
                $this->view->searchParam = $txtSearch;
            break;

        }
        $pageFullContent = Admincomponents::getRoles($txtSearch,$limit,null,$orderArr);
        $pageFullCount = count($pageFullContent);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT);

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        
        $this->view->txtSearch = $txtSearch;        
        $this->view->pageContents = Admincomponents::getRoles($txtSearch,$limit,null,$orderArr);

    } // End Function




}

?>