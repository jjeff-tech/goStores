<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.                                      |
// | File name : index.php                                                  |
// | PHP version >= 5.2                                                   |
// | Created On	: 	Nov 17 2011                                               |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +------------------------------------------------------


class ControllerIndex extends BaseController {
    /*
		construction function. we can initialize the models here
    */
    public function init() {
        parent::init();
        $this->dbObj = new Db();
        /************* Admin Access Check ****************/
        /* $adminAccess = User::adminAccessCheck();

        if($adminAccess==0) {
            $this->redirect('login/index');
        } */
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
        PageContext::addJsVar('MAIN_URL', BASE_URL);
    }

    /*
    function to load the index template
    */
    public function index1() // dashboard
    {
        
        Logger::info("hello world");
        $this->view->setLayout("home");
        PageContext::addScript("FusionCharts.js");
        // PageContext::addScript("tab.js");
        PageContext::includePath("graph");

        
        /************* Graph  Area **************/
        
        //Site Analytics
        $siteAnalyticsArr = Admincomponents::getListItem("SiteAnalytics", array('nCount', 'dTrackingDate'), NULL, array('sort' => 'ASC', 'fields' => array('MONTH(dTrackingDate)')));

        $freeTiral = Admincomponents::getFreeTrialsAcrossLastYear();
        $subscription = Admincomponents::getSubscriptionAcrossLastYear();

        $graphObj = new graph(1,920);
        $graphObj->setChartParams("",1,1,0);
        $arrData[0][0] = "Free";
        $arrData[0][1] = "";
        $arrData[1][0] = "Subscription";
        $arrData[1][1] = "";
        for($i=1;$i<=12;$i++){
            $arrCatNames[$i] = date("M", mktime(0, 0, 0, $i, 10));
            $arrData[0][$i+1] = $freeTiral[$i]['trial_count']?$freeTiral[$i]['trial_count']:0;
            $arrData[1][$i+1] = $subscription[$i]['trial_count']?$subscription[$i]['trial_count']:0;
        }
       // $graphObj->setAxis();
        $graphObj->addChartData($arrData,$arrCatNames);
        $this->view->graph   = $graphObj ;
         Logger::info($arrData);
         Logger::info($arrCatNames);
      
        //Free Trial Data Graph
        $freeTiral = Admincomponents::getFreeTrialsAcrossLastYear();
        $graphObj2 = new graph(8);
        $graphObj2->setChartParams("");
        for($i=1;$i<=12;$i++){
            $arrData[$i-1][0] = date("M", mktime(0, 0, 0, $i, 10));
            $arrData[$i-1][1] = $freeTiral[$i]['trial_count']?$freeTiral[$i]['trial_count']:NULL;
        }
        $graphObj2->addChartData($arrData,"color=FF0000");
        $this->view->graph2   = $graphObj2 ;


        //Subscription  Data Graph
        $subscription = Admincomponents::getSubscriptionAcrossLastYear();
        for($i=1;$i<=12;$i++){
            $arrData[$i-1][0] = date("M", mktime(0, 0, 0, $i, 10));
            $arrData[$i-1][1] =$subscription[$i]['trial_count']?$subscription[$i]['trial_count']:NULL;
        }
        $graphObj4 = new graph(8);
        $graphObj4->addChartData($arrData);
        $graphObj4->setChartParams("");
        $this->view->graph4   = $graphObj4 ;

        
        //Upgradation Data Graph
        $upgradations = Admincomponents::getUpgradationsAcrossLastYear();
        $arrData = null;
        for($i=1;$i<=12;$i++){
            $arrData[$i-1][0] = date("M", mktime(0, 0, 0, $i, 10));
            $arrData[$i-1][1] = $upgradations[$i]['trial_count']?$upgradations[$i]['trial_count']:NULL;
        }
        $graphObj3 = new graph(8);
        $graphObj3->setChartParams("");
        $graphObj3->addChartData($arrData);
        $this->view->graph3   = $graphObj3 ;  

        $arrCatNames= $arrData = null;
        $freeTiral = Admincomponents::getTrialsOverRange();
        $subscription = Admincomponents::getSubscriptionsOverRange();
        $products = Admincomponents::fetchProductsList();
        foreach($products as $product){
            $productNames[] = $product->vPName;
        }
        
        //Free Trial Pie Graph
        for($i=0;$i<count($productNames);$i++){
           if($freeTiral[$productNames[$i]]['trial_count']){
                $arrData[$i][0] = $productNames[$i];
                $arrData[$i][1] = $freeTiral[$productNames[$i]]['trial_count']?$freeTiral[$productNames[$i]]['trial_count']:NULL;
                
            }
        }
        $graphObj5 = new graph(10,450,120);
        $graphObj5->addChartData($arrData);
        $graphObj5->setChartParams("Free Trial",1,1,1,5,1);
        $this->view->graph5 = $graphObj5 ;

         //Subscription  Pie Graph
        $arrData = null;
        for($i=0;$i<=count($productNames);$i++){
             if($subscription[$productNames[$i]]['trial_count']){
            $arrData[$i][0] = $productNames[$i];
            $arrData[$i][1] = $subscription[$productNames[$i]]['trial_count']?$subscription[$productNames[$i]]['trial_count']:NULL;
             }
        }
        $graphObj6 = new graph(10,450,120);
        $graphObj6->addChartData($arrData);
        $graphObj6->setChartParams("Subscription",1,1,1,5,1);
        $this->view->graph6 = $graphObj6 ;
        //
        /************* Graph  Area Ends *********/

        /************* Latest Invoices **********/
        //getLatestInvoice
        $latestInvArr = Admincomponents::getLatestInvoice();
        $this->view->latestInvArr = $latestInvArr;
        /************* Latest Invoices End ******/

        /************* Latest Payments **********/
        //getLatestPayments
        $latestPymtArr = Admincomponents::getLatestPayments();
        $this->view->latestPymtArr = $latestPymtArr;
        /************* Latest Payments End **********/
        PageContext::$response->activeLeftMenu = 'home';


    }

    /*
	function to list the admin users. Also super admin can create and change the module access
    */
    public function adminusers($action = NULL,$id = NULL,$txtSearch = NULL, $page = NULL) {

        
        if($txtSearch=='x')
            $txtSearch = NULL;
        PageContext::$response->activeLeftMenu='Site Admin';
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("jquery.addplaceholder.min.js");
       
        $session = new LibSession();
        $this->view->action			= $action;
        $this->view->id 			= $id;
         switch ($action){
            case 'add' :
                $this->view->action = 'add';
                $this->view->pageTitle = 'Add Admin User';
                $this->view->addEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            case 'edit' :
                $this->view->action = 'edit';
                $this->view->pageTitle = 'Edit Admin User';
                $this->view->editEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            default :
                $this->view->pageTitle = 'Site Admins';
                if($session->get('addsuccess')=='success'){
                     PageContext::$response->success_message =  "Site admin created successfully" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('addsuccess');
                }

                if($session->get('editsuccess')=='success'){
                     PageContext::$response->success_message =  "Site admin user changes saved successfully" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('editsuccess');
                }
                if($session->get('deletesuccess')=='success'){
                     PageContext::$response->success_message =  "Site admin user deleted successfully" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('deletesuccess');
                }
                    
            break;
        }
        
        $limit = NULL;
        $searchArr = array();

        // delete code starts here
        if($action != '' && $id != '') {
            switch ($action) {
                case "delete":
                    $this->dbObj->deleteRecord('Admin',"nAId='".$id."' AND nAId!='".$_SESSION['adminUser']['userID']."'");
                    $session->set('deletesuccess','success');
                    $this->redirect('index/adminusers');
                    break;
            }
        }
        // delete code ends here
 
        if($this->isPost()) {
             if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
                $this->view->searchParam = $txtSearch;
                $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
            
            } else if($_POST['action']=='edit' || $_POST['action']=='add') {
            $this->view->pageContent->vUsername 	= $ad_uname 		= addslashes($this->post('ad_uname'));
            $this->view->pageContent->vFirstName 	= $ad_fname  		= addslashes($this->post('ad_fname'));
            $this->view->pageContent->vLastName 	= $ad_lname  		= addslashes($this->post('ad_lname'));
            $this->view->pageContent->vEmail		= $ad_email 		= addslashes($this->post('ad_email'));
            $this->view->pageContent->nStatus	 	= $ad_status 		= addslashes($this->post('ad_status'));
            $this->view->pageContent->nRid			= $ad_role 		= addslashes($this->post('ad_role'));
            $ad_pwd 								= addslashes($this->post('ad_pwd'));
            $ad_cpwd 								= addslashes($this->post('ad_cpwd'));
            $id                                         = addslashes($this->post('id'));
            if($ad_uname != '' && $ad_pwd !='' && $ad_cpwd != '' && $ad_email !='' && $ad_fname !='' ) {
               
                if($ad_pwd == $ad_cpwd) {
                    if($id!="")
                        $where   =   "vUsername='".addslashes($ad_uname)."' AND nAId<>'".$id."' ";
                    else
                        $where   =   "vUsername='".addslashes($ad_uname)."'";

                    $userExist   =   $this->dbObj->selectRow("Admin","nAId",$where);
                    $postedArray    	= array("nRid"			=> $ad_role,
                            "vUsername"		=> $ad_uname,
                            "vPassword"		=> md5($ad_pwd),
                            "vFirstName"	=> $ad_fname,
                            "vLastName"		=> $ad_lname,
                            "vEmail"		=> $ad_email,
                            "nStatus"		=> $ad_status);
                    if($userExist!=""){
                        PageContext::$response->error_message =  "Username already exists.Try another Username!" ;
                        PageContext::addPostAction('errormessage');
                        $this->view->message  		=   "Admin user already exist";
                    }else {
                        if($id!="") {
                            $checkEmailExists = $this->dbObj->checkExists("Admin","nAId"," vEmail='".$ad_email."' AND nAId !=".$id);
                       
                            if(!$checkEmailExists){
                                $this->dbObj->updateFields("Admin",$postedArray,"nAId ='".$id."'");
                                $session->set('editsuccess','success');
                                $this->redirect('index/adminusers');
                            }else{
                                 PageContext::$response->error_message =  "User exists with same email id." ;
                                 PageContext::addPostAction('errormessage');
                             }
                            
                        }else {
                            $checkEmailExists = $this->dbObj->checkExists("Admin","nAId"," vEmail='".$ad_email."'");
                             if(!$checkEmailExists){
                                $this->dbObj->addFields("Admin",$postedArray);
                                $session->set('addsuccess','success');
                                $this->redirect('index/adminusers');
                             }else{
                                 PageContext::$response->error_message =  "User exists with same email id." ;
                                 PageContext::addPostAction('errormessage');
                             }
                          
                        }
                    }
                }else{
                    PageContext::$response->error_message =  "Please enter similar passwords" ;
                    PageContext::addPostAction('errormessage');
                    $this->view->message  	=   "Please enter similar passwords" ;
                }
            }else if( $ad_uname != '' && $ad_email !='' && $ad_fname !='' ) {
              
                    if($id!="")
                        $where   =   "vUsername='".addslashes($ad_uname)."' AND nAId<>'".$id."' ";
                    else
                        $where   =   "vUsername='".addslashes($ad_uname)."'";

                    $userExist   =   $this->dbObj->selectRow("Admin","nAId",$where);
                    $postedArray    	= array("nRid"			=> $ad_role,
                            "vUsername"		=> $ad_uname,
                            "vFirstName"	=> $ad_fname,
                            "vLastName"		=> $ad_lname,
                            "vEmail"		=> $ad_email,
                            "nStatus"		=> $ad_status);
                    if($userExist!="")
                        $this->view->message  		=   "Admin user already exist";
                    else {
                        if($id!="") {
                            
                            $checkEmailExists = $this->dbObj->checkExists("Admin","nAId"," vEmail='".$ad_email."' AND nAId !=".$id);
                             if(!$checkEmailExists){
                                Logger::info($postedArray);
                                $this->dbObj->updateFields("Admin",$postedArray,"nAId ='".$id."'");
                                $session->set('editsuccess','success');
                                $this->redirect('index/adminusers');
                            }else{
                                 PageContext::$response->error_message =  "User exists with same email id." ;
                                 PageContext::addPostAction('errormessage');
                             }
                            
                        }
                    }
                }
            else{
                 PageContext::$response->error_message =  "Please enter all details" ;
                 PageContext::addPostAction('errormessage');
                $this->view->message  	=  "Please enter all details";
            }
            } // End Add or Edit
        } // End Post

        if($id!="" && $id!=0)
            $this->view->pageContent   	=     $this->dbObj->selectRecord("Admin","*","nAId='".$id."'");

       
        if($action == 'add' || $action == 'edit')
            $this->view->roles 		= $this->dbObj->selectResult("Role","*","nRid!=1");
        //Block role edit for logged in user
        if($_SESSION['adminUser']['userID']==$id){
           $this->view->displayRole =TRUE;
           $this->view->role ='Super Admin';
           $this->view->displayUsername = TRUE;
            
        }
        if(empty($page))
            $this->view->pageCount = 1;
        else
            $this->view->pageCount = $page;


        switch ($action){
            case 'sortbyusernameasc':
                $sort_order= 'ASC';
                $orderArr['fields']  = array('vUsername');
                $this->view->usernameSortStyle = 'sort_column_down';
                $this->view->usernameSortAction = 'sortbyusernamedesc';
                $this->view->lastLoginSortStyle = 'sort_column_down';
                $this->view->lastLoginSortAction = 'sortbylastlogindesc';
                $this->view->searchParam = $txtSearch;


                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            case 'sortbyusernamedesc':
               $orderArr['sort'] = 'DESC';
               $orderArr['fields']  = array('vUsername');
               $this->view->usernameSortStyle = 'sort_column_up';
               $this->view->usernameSortAction = 'sortbyusernameasc';
               $this->view->lastLoginSortStyle = 'sort_column_down';
               $this->view->lastLoginSortAction = 'sortbyusernamedesc';
               $this->view->lastLoginSortStyle = 'sort_column_down';
               $this->view->lastLoginSortAction = 'sortbylastlogindesc';
               $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            case 'sortbylastloginasc':

                $orderArr['sort'] = 'ASC';
                $orderArr['fields']  = array('dLastLogin');
                $this->view->lastLoginSortStyle = 'sort_column_down';
                $this->view->lastLoginSortAction = 'sortbylastlogindesc';
                $this->view->usernameSortStyle = 'sort_column_down';
                $this->view->usernameSortAction = 'sortbyusernamedesc';
                $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            case 'sortbylastlogindesc':

                $orderArr['sort'] = 'DESC';
                $orderArr['fields']  = array('dLastLogin');
                $this ->view->lastLoginSortStyle = 'sort_column_up';
                $this->view->lastLoginSortAction = 'sortbylastloginasc';
                $this->view->usernameSortStyle = 'sort_column_down';
                $this->view->usernameSortAction = 'sortbyusernamedesc';
                $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            default:
               $orderArr['sort'] = 'ASC';
               $orderArr['fields']  = array('vUsername');
               $this->view->usernameSortStyle = 'sort_column_down';
               $this->view->usernameSortAction = 'sortbyusernamedesc';
               $this->view->lastLoginSortStyle = 'sort_column_down';
               $this->view->lastLoginSortAction = 'sortbylastlogindesc';
               $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
        }

        $pageContentArr = Admincomponents::getListItem("Admin", array('nAId', 'nRid', 'vUsername', 'vFirstName', 'vLastName', 'dLastLogin', 'vEmail', 'nSuperAdmin', 'nStatus'), NULL,$orderArr, $limit, $searchArr);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, count($pageContentArr), PAGE_LIST_COUNT);

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        //TODO : add pagination
                //PageContents
        $db = new Db();
        $pageContentArr = Admincomponents::getListItem("Admin A LEFT JOIN ".$db->tablePrefix."Role R ON(A.nRid = R.nRid)", array('A.nAId', 'A.nRid', 'A.vUsername', 'A.vFirstName', 'A.vLastName', 'A.dLastLogin', 'A.vEmail', 'A.nSuperAdmin', 'A.nStatus','R.vRoleName'), NULL,$orderArr, $limit, $searchArr);

        $this->view->adusers = $pageContentArr;
        $this->view->setLayout("home");
    }

    /*
	function to load the cms section
    */
    public function cms($action = NULL, $id = NULL) {
       
        PageContext::$response->activeLeftMenu = 'cms';
        PageContext::addScript(BASE_URL."project/lib/editors/ckeditor/ckeditor.js");
        PageContext::addScript(BASE_URL."project/lib/editors/ckeditor/common.js");
        PageContext::addScript("adminCms.js");
        $session = new LibSession();
        switch($action){
            case 'add':
                $this->view->pageTitle = "Add Page Content";
                break;
            case 'edit':
                $this->view->pageTitle = "Edit Page Content";
                break;
            default:
                $this->view->pageTitle = " Page Contents";
                if($session->get('edit_page_content_success')=='success'){
                    PageContext::$response->success_message = "Page Content changes saved successfully";
                    PageContext::addPostAction('successmessage');
                    $session->delete('edit_page_content_success');

                }else if($session->get('add_page_content_success')=='success'){
                    PageContext::$response->success_message = "Page Content  saved successfully";
                    PageContext::addPostAction('successmessage');
                    $session->delete('add_page_content_success');

                }
                break;
        }
        $this->view->action		= $action  ;
        $this->view->id 		= $id    ;

        if($action != '' && $id != '') {
            switch ($action) {
                case "deactivate":
                    $cmsArray 				= array("cms_status" 	=> 2);
                    $this->dbObj->updateFields("Cms",$cmsArray,"cms_id='".$id."'");
                    $this->view->message 	= 'Successfully deactivated the CMS';
                    break;
                case "activate":
                    $cmsArray 				= array("cms_status" 	=> 1);
                    $this->dbObj->updateFields("Cms",$cmsArray,"cms_id='".$id."'");
                    $this->view->message 	= 'Successfully activated the CMS';
                    break;
            }
        }
        if($this->isPost()) {

            $this->view->id 			= $id   		= trim($this->post('id'));
            $this->view->cms_name 		= $cms_name    	= trim($this->post('cms_name'));

            $this->view->txtRefTitle 	= $txtRefTitle  = trim($this->post('cms_ref_title'));
            $this->view->txtTitle    	= $txtTitle    	= trim($this->post('cms_title'));
            $this->view->txtDesc     	= $txtDesc    	= trim($this->post('cms_desc'));
            $this->view->txtShortDesc	= $txtShortDesc = trim($this->post('cms_shortdesc'));
            $this->view->status			= $txtStatus    = trim($this->post('cms_status'));

            $postedArray    = array(	"cms_type"		=> 'cms',
                    "cms_name"		=> addslashes($cms_name),
                    "cms_ref_title"	=> addslashes($txtRefTitle),
                    "cms_title"		=> addslashes($txtTitle),
                    "cms_desc"		=> addslashes($txtDesc),
                    "cms_shortdesc"	=> addslashes($txtShortDesc),
                    "cms_status"	=> 1);
            if($txtTitle!="" && $txtDesc!="") {
                if($id!="")
                    $where   =   "cms_title='".addslashes($txtTitle)."' AND cms_id<>'".$id."' ";
                else
                    $where   =   "cms_title='".addslashes($txtTitle)."'";

                $contentExist   =   $this->dbObj->selectRow("Cms","cms_id",$where);

                if($contentExist!="")
                    $this->view->message  =   msg_red("Already existing Content Title");
                else {
                    if($id!="") {
                        $this->dbObj->updateFields("Cms",$postedArray,"cms_id='".$id."'");
                        $session->set('edit_page_content_success','success');
                        header("Location:cms/success/edit");
                    }
                    else {
                        $this->dbObj->addFields("Cms",$postedArray);
                        $session->set('add_page_content_success','success');
                        header("Location:cms/success/add");
                    }
                }
            }
            else {

                $this->view->message		=  "Please Enter Required Fields";
            }
        }
        if($id!="" && $id!=0)
        {
            $this->view->btnval        = "Save Changes";
            $this->view->pageContent   	=  $pageContent   =    $this->dbObj->selectRecord("Cms","*","cms_type='cms' AND cms_id='".$id."'");
        }
        else
        {
            $this->view->btnval        = "Add Content";
        }
        //TODO : add pagination
        $this->view->pageContents 		= $this->dbObj->selectResult("Cms","*","cms_type='cms'");
        $this->view->setLayout("home");

    } // End Function

    /*
	function to load the module section
    */
    public function module($action=NULL, $id= NULL, $txtSearch = NULL, $page = NULL) {
        
       
        if($txtSearch=='x')
            $txtSearch = NULL;
        $this->view->pageCount = $page ;
        
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("formValidations.js");
        PageContext::addScript("jquery.addplaceholder.min.js");
        PageContext::$response->activeLeftMenu='Modules';
        $this->view->leftMenu 	='left_main';
        $this->view->action		= $action  ;
        $this->view->id 		= $id    ;
        $session = new LibSession();
        switch ($action){
            case 'add' :
                $this->view->action = 'add';
                $this->view->pageTitle = 'Add Module';
                $this->view->addEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            case 'edit' :
                $this->view->action = 'edit';
                $this->view->pageTitle = 'Module';
                $this->view->editEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            default :
                $this->view->pageTitle = 'Modules';
                if($session->get('add_module_success')=='success'){
                     PageContext::$response->success_message =  "Module created successfully" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('add_module_success');
                }

                if($session->get('edit_module_success')=='success'){
                     PageContext::$response->success_message =  "Module changes saved successfully" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('edit_module_success');
                }
                if($session->get('activate_module_success')=='success'){
                     PageContext::$response->success_message =  "Successfully activated the module" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('activate_module_success');
                }
                if($session->get('deactivate_module_success')=='success'){
                     PageContext::$response->success_message =  "Successfully deactivated the module" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('deactivate_module_success');
                }

            break;
        }
        
       
        //Button Label
        $this->view->buttonLabel = (empty($id)) ? 'Add' : 'Save'; // Button Label

        //Page Head Action
        $pageHeadLabel =NULL;
        if($action=='edit') {
         $pageHeadLabel = 'Edit ';
        }
        $this->view->pageHeadLabel = $pageHeadLabel;
        
        $this->view->action = $action ;
        $this->view->id = $id ;
        
        if($action != '' && $id != '') {
            switch ($action) {
                case "deactivate":
                    $modArray 				= array("nStatus" 	=> 2);
                    $this->dbObj->updateFields("Module",$modArray,"nMId='".$id."'");
                    $session->set('deactivate_module_success', 'success');
                    $this->redirect('index/module');
                  //  $this->view->message 	= 'Successfully deactivated the module';
                    break;
                case "activate":
                    $modArray 				= array("nStatus" 	=> 1);
                    $this->dbObj->updateFields("Module",$modArray,"nMId='".$id."'");
                    $session->set('activate_module_success', 'success');
                    $this->redirect('index/module');
                   // $this->view->message 	= 'Successfully activated the module';
                    break;
            }
        }
        if($this->isPost()) {
            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
            } else if($_POST['action']=='edit' || $_POST['action']=='add') {
                $this->view->pageContent->id			= $id   		= trim($this->post('id'));
                $this->view->pageContent->vModuleName	= $mod_name    	= trim($this->post('mod_name'));
                $this->view->pageContent->vDescription 	= $mod_desc  	= trim($this->post('mod_desc'));
                $this->view->pageContent->nStatus   	= $mod_status   = trim($this->post('mod_status'));



                $postedArray    = array("vModuleName"	=> addslashes($mod_name),
                        "vDescription"	=> addslashes($mod_desc),
                        "nStatus" => addslashes($mod_status));
                if($mod_name!="" && $mod_desc!="") {
                    if($id!="")
                        $where   =   "vModuleName='".addslashes($mod_name)."' AND nMId<>'".$id."' ";
                    else
                        $where = "vModuleName='".addslashes($mod_name)."'";

                    $contentExist   =   $this->dbObj->selectRow("Module","nMId",$where);

                    if($contentExist!=""){
                        PageContext::$response->error_message =  "Module name already exists! Try another module name" ;
                        PageContext::addPostAction('errormessage');
                        //$this->view->message  =    "Already existing Module";
                    }else {
                        if($id!="") {
                            $this->dbObj->updateFields("Module",$postedArray,"nMId='".$id."'");
                            $session->set('edit_module_success', 'success');
                            $this->redirect('index/module');
                           // header("Location:module/success/edit");
                        }
                        else {
                            $this->dbObj->addFields("Module",$postedArray);
                            $session->set('add_module_success', 'success');
                            $this->redirect('index/module');
                           // header("Location:module/success/add");
                        }
                    }
                }
                else {
                    $this->view->message		=  "Please Enter Required Fields";
                }

            } // End Add / Edit
        }
        if($id!="" && $id!=0) {
            $modules = Admincomponents::getModules(NULL, NULL, array(array('field' => 'nMId', 'value' => $id)));
            $this->view->pageContent = (!empty($modules)) ?  $modules[0] : array();
        }
        switch ($action){
            case 'sortbymodulenameasc':
                $sort_order= 'ASC';
                $orderArr['fields']  = array('vModuleName');
                $this->view->modulenameSortStyle = 'sort_column_down';
                $this->view->modulenameSortAction = 'sortbymodulenamedesc';
              
                $this->view->searchParam = $txtSearch;
            break;
            case 'sortbymodulenamedesc':
               $orderArr['sort'] = 'DESC';
               $orderArr['fields']  = array('vModuleName');
               $this->view->modulenameSortStyle = 'sort_column_up';
               $this->view->modulenameSortAction = 'sortbymodulenameasc';
               $this->view->searchParam = $txtSearch;

            break;
            default:
                $orderArr['sort'] = 'ASC';
                $orderArr['fields']  = array('vModuleName');
                $this->view->modulenameSortStyle = 'sort_column_down';
                $this->view->modulenameSortAction = 'sortbymodulenamedesc';
                $this->view->searchParam = $txtSearch;
            break;
        
        }

        $pageFullContent = Admincomponents::getModules($txtSearch,$limit,null,$orderArr);
        $pageFullCount = count($pageFullContent);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT);

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        
        $this->view->txtSearch = $txtSearch;
        $this->view->pageContents = Admincomponents::getModules($txtSearch,$limit,null,$orderArr);
        $this->view->setLayout("home");
    }

    /*
	function to list the site users
    */
    public function users($action = NULL,$id = NULL, $txtSearch=NULL , $page = NULL) {
       
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("formValidations.js");
        PageContext::addScript("jquery.addplaceholder.min.js");

         if($txtSearch=='x')
            $txtSearch = NULL;
         
        $this->view->pageCount = $page ;
        
        $searchArr = array();
        $limit = NULL;
        $session = new LibSession();
        $id= $id ?$id :$_POST['id'];
        $this->view->action = $action;
        $this->view->id = $id;
        
        switch ($action){
            case 'add' :
                $this->view->action = 'add';
                $this->view->pageTitle = 'Add User';
                $this->view->addEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            case 'edit' :
                $this->view->action = 'edit';
                $this->view->pageTitle = 'Edit  User';
                $this->view->editEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            default :
                $this->view->pageTitle = 'Users';
                if($session->get('add_user_success')=='success'){
                     PageContext::$response->success_message =  "User created successfully" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('add_user_success');
                }

                if($session->get('edit_user_success')=='success'){
                     PageContext::$response->success_message =  "User changes saved successfully" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('edit_user_success');
                }

                if($session->get('wallet_management_success')=='success'){
                     PageContext::$response->success_message =  "Amount credited to the user" ;
                     PageContext::addPostAction('successmessage');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('wallet_management_success');
                }
                

            break;
        }
        PageContext::$response->activeLeftMenu = 'Users';
        if($action != '' && $id != '') {
            switch ($action) {
                case "deactivate":
                    $userArray 				= array("nStatus" 	=> 2);
                    $this->dbObj->updateFields("User",$userArray,"nUId='".$id."'");
                    $this->view->message 	= 'Successfully deactivated the user';
                    break;
                case "activate":
                    $userArray 				= array("nStatus" 	=> 1);
                    $this->dbObj->updateFields("User",$userArray,"nUId='".$id."'");
                    $this->view->message 	= 'Successfully activated the user';
                    break;
            }
        }
        // Search Area
        if($this->isPost()) {
            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
                $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));                              
            }

            if($_POST['action']=='add'||$_POST['action']=='edit'){
                $userArray = array("vUsername" => addslashes($this->post('uname')),
                        "vFirstName"=>addslashes($this->post('fname')),
                        "vLastName"=>addslashes($this->post('lname')),
                        "vEmail"=>addslashes($this->post('email')),
                        "vInvoiceEmail"=>addslashes($this->post('invoice_email')),
                        "vAddress"=>addslashes($this->post('address')),
                        "vCountry"=>addslashes($this->post('country')),
                        "vState"=>addslashes($this->post('state')),
                        "vZipcode"=>addslashes($this->post('zip_code')),
                        "vPhoneNumber"=>addslashes($this->post('phone_number')),
                        "nStatus"=>addslashes($this->post('status')),
                        );
                        $password = $this->post('pwd')?addslashes($this->post('pwd')):'';
                $this->view->userArray[0] = $userArray;
                Logger::info($this->view->userArray);
                if(($action=='add' && $password == addslashes($this->post('cpwd')))||
                     ($action=='edit' && empty($password))||
                       ($action=='edit' && !empty($password) &&  $password == addslashes($this->post('cpwd'))) ){

                    if($action=='add')
                        $checkEmailExists = $this->dbObj->checkExists("User","nUId"," vEmail='".$userArray["vEmail"]."'");
                    else if($action=='edit')
                        $checkEmailExists = $this->dbObj->checkExists("User","nUId"," vEmail='".$userArray["vEmail"]."' AND nUId!='".$id."'");

                    if($checkEmailExists){
                        PageContext::$response->error_message = "Account exists with provided email id";
                        PageContext::addPostAction("errormessage");
                        
                    }else{
                         if($action=='add')
                            $checkUsernameExists = $this->dbObj->checkExists("User","nUId"," vUsername='".$userArray["vUsername"]."'");
                         else if($action=='edit')
                            $checkUsernameExists = $this->dbObj->checkExists("User","nUId"," vUsername='".$userArray["vUsername"]."' AND nUId!='".$id."'");

                        if($checkUsernameExists){
                            PageContext::$response->error_message = "Username already exists,Try another username!";
                            PageContext::addPostAction("errormessage");
                        }else if($action=='add'){
                            if($password)
                                $userArray["vPassword"] = md5($password);
                            $this->dbObj->addFields("User",$userArray);
                            $session->set('add_user_success','success');
                            $this->redirect("index/users");
                        }else if($action=='edit'){
                            if($password)
                                $userArray["vPassword"] = md5($password);
                            $this->dbObj->updateFields("User",$userArray,'nUId='.$id);
                            $session->set('edit_user_success','success');
                            $this->redirect("index/users");
                        }
                    }
                }else{
                    PageContext::$response->error_message = "Password fields does not match";
                    PageContext::addPostAction("errormessage");
                }
                
            }
        }else if($action=='edit'){
           
            $userArray = $this->dbObj->selectRecord("User","*"," nUId=".$id);
             
            $this->view->userArray = array($userArray);
            Logger::info($this->view->userArray);
        }
       switch ($action){
            case 'sortbyusernameasc':
                $sort_order= 'ASC';
                $orderArr['fields']  = array('vUsername');
                $this->view->usernameSortStyle = 'sort_column_down';
                $this->view->usernameSortAction = 'sortbyusernamedesc';
                $this->view->lastLoginSortStyle = 'sort_column_down';
                $this->view->lastLoginSortAction = 'sortbylastlogindesc';
                $this->view->searchParam = $txtSearch;


                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            case 'sortbyusernamedesc':
               $orderArr['sort'] = 'DESC';
               $orderArr['fields']  = array('vUsername');
               $this->view->usernameSortStyle = 'sort_column_up';
               $this->view->usernameSortAction = 'sortbyusernameasc';
               $this->view->lastLoginSortStyle = 'sort_column_down';
               $this->view->lastLoginSortAction = 'sortbyusernamedesc';
               $this->view->lastLoginSortStyle = 'sort_column_down';
               $this->view->lastLoginSortAction = 'sortbylastlogindesc';
               $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            case 'sortbylastloginasc':

                $orderArr['sort'] = 'ASC';
                $orderArr['fields']  = array('dLastLogin');
                $this->view->lastLoginSortStyle = 'sort_column_down';
                $this->view->lastLoginSortAction = 'sortbylastlogindesc';
                $this->view->usernameSortStyle = 'sort_column_down';
                $this->view->usernameSortAction = 'sortbyusernamedesc';
                $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            case 'sortbylastlogindesc':

                $orderArr['sort'] = 'DESC';
                $orderArr['fields']  = array('dLastLogin');
                $this ->view->lastLoginSortStyle = 'sort_column_up';
                $this->view->lastLoginSortAction = 'sortbylastloginasc';
                $this->view->usernameSortStyle = 'sort_column_down';
                $this->view->usernameSortAction = 'sortbyusernamedesc';
                $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
            default:
               $orderArr['sort'] = 'ASC';
               $orderArr['fields']  = array('vUsername');
               $this->view->usernameSortStyle = 'sort_column_down';
               $this->view->usernameSortAction = 'sortbyusernamedesc';
               $this->view->lastLoginSortStyle = 'sort_column_down';
               $this->view->lastLoginSortAction = 'sortbylastlogindesc';
               $this->view->searchParam = $txtSearch;

                if($txtSearch!=''){
                    $searchArr = array(array('field' => 'vUsername', 'value' => $txtSearch), array('field' => 'vFirstName', 'value' => $txtSearch), array('field' => 'vLastName', 'value' => $txtSearch), array('field' => 'vEmail', 'value' => $txtSearch));
                }
            break;
        }
        
        $userArr = Admincomponents::getListItem("User", array('nUId','vUsername','vPassword','vFirstName','vLastName','dLastLogin','vEmail'), NULL,$orderArr, $limit, $searchArr);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, count($userArr), PAGE_LIST_COUNT);

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        
        $userArr = Admincomponents::getListItem("User", array('nUId','vUsername','vPassword','vFirstName','vLastName','dLastLogin','vEmail'), NULL,$orderArr, $limit, $searchArr);

        $this->view->siteusers 		= $userArr;
        $this->view->txtSearch = $txtSearch;
        $this->view->setLayout("home");
    }

    // Function to credit amount to users wallet
    public function addtowallet($id=NULL){
        
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("jquery.metadata.js");
        $this->view->id 	= $id;
        $this->view->leftMenu 	='left_main';
        
        //Get userEmail using userId
        $userArr = Admincomponents::getListItem("User", array('vEmail'), array(array('field' => 'nUId', 'value' => "$id")));
        if(!empty($userArr[0]->vEmail))
            $this->view->userEmail = $userArr[0]->vEmail;
        
        //Get existing balance using userId
        $walletArr = Admincomponents::getListItem("Wallet", array('nBalanceAmount'), array(array('field' => 'nUId', 'value' => "$id")));
        if(empty($walletArr[0]->nBalanceAmount))
            $this->view->userBalanceAmt = '0';
        else
            $this->view->userBalanceAmt = $walletArr[0]->nBalanceAmount;
     
        if($this->isPost()) {
            $userId = $this->post('id');
            $userBalance = trim($this->post('mod_userbalance'));
            $amtToAdd = trim($this->post('mod_amttoadd'));
            $status = User::addToWallet($userId,$userBalance,$amtToAdd);
            if($status==1){
                $session = new LibSession();
                $session->set("wallet_management_success", 'success');
               $this->view->message  = "Successfully credited to user wallet";
                $this->redirect("index/users");
            } else {
                PageContext::$response->error_message = "Error occured. Please try again!";
                PageContext::addPostAction('errormessage');

             //  $this->view->message  = "Please try again!";
            }
        }
        //header("Location:index/addtowallet/");
        $this->view->setLayout("home");
    }

    public function successmessage(){
        
    }

    public function errormessage(){
        
    }

    public function emailcontent($action = NULL, $id = NULL) {
        
        PageContext::$response->activeLeftMenu = 'emailcontent';
        PageContext::addScript(BASE_URL."project/lib/editors/ckeditor/ckeditor.js");
        PageContext::addScript(BASE_URL."project/lib/editors/ckeditor/common.js");
        PageContext::addScript("adminCms.js");

        $session = new LibSession();
        $this->view->action		= $action  ;
        $this->view->id 		= $id    ;

        switch($action){
            case 'add':
                $this->view->pageTitle = "Add Email Content";
                break;
            case 'edit':
                $this->view->pageTitle = "Edit Email Content";
                break;
            default:
                $this->view->pageTitle = " Email Contents";
                if($session->get('edit_email_content_success')=='success'){
                    PageContext::$response->success_message = "Email Content changes saved successfully";
                    PageContext::addPostAction('successmessage');
                    $session->delete('edit_email_content_success');

                }else if($session->get('add_email_content_success')=='success'){
                    PageContext::$response->success_message = "Email Content  saved successfully";
                    PageContext::addPostAction('successmessage');
                    $session->delete('add_email_content_success');

                }
                break;
        }
        if($action != '' && $id != '') {
            switch ($action) {
                case "deactivate":
                    $cmsArray 				= array("cms_status" 	=> 2);
                    $this->dbObj->updateFields("Cms",$cmsArray,"cms_id='".$id."'");
                    $this->view->message 	= 'Successfully deactivated the CMS';
                    break;
                case "activate":
                    $cmsArray 				= array("cms_status" 	=> 1);
                    $this->dbObj->updateFields("Cms",$cmsArray,"cms_id='".$id."'");
                    $this->view->message 	= 'Successfully activated the CMS';
                    break;
            }
        }
        if($this->isPost()) {

            $this->view->id 			= $id   		= trim($this->post('id'));
            $this->view->cms_name 		= $cms_name    	= trim($this->post('cms_name'));

            $this->view->txtRefTitle 	= $txtRefTitle  = trim($this->post('cms_ref_title'));
            $this->view->txtTitle    	= $txtTitle    	= trim($this->post('cms_title'));
            $this->view->txtDesc     	= $txtDesc    	= trim($this->post('cms_desc'));
            $this->view->txtShortDesc	= $txtShortDesc = trim($this->post('cms_shortdesc'));
            $this->view->status			= $txtStatus    = trim($this->post('cms_status'));

            $postedArray    = array(	"cms_type"		=> 'email',
                    "cms_name"		=> addslashes($cms_name),
                    "cms_ref_title"	=> addslashes($txtRefTitle),
                    "cms_title"		=> addslashes($txtTitle),
                    "cms_desc"		=> addslashes($txtDesc),
                    "cms_shortdesc"	=> addslashes($txtShortDesc),
                    "cms_status"	=> 1);
            if($txtTitle!="" && $txtDesc!="") {
                if($id!="")
                    $where   =   "cms_title='".addslashes($txtTitle)."' AND cms_id<>'".$id."' ";
                else
                    $where   =   "cms_title='".addslashes($txtTitle)."'";

                $contentExist   =   $this->dbObj->selectRow("Cms","cms_id",$where);

                if($contentExist!="")
                    $this->view->message  =   msg_red("Already existing Content Title");
                else {
                    if($id!="") {
                        $this->dbObj->updateFields("Cms",$postedArray,"cms_id='".$id."'");
                        $session->set('edit_email_content_success','success');
                        header("Location:emailcontent/success/edit");
                    }
                    else {
                        $this->dbObj->addFields("Cms",$postedArray);
                        $session->set('add_email_content_success','success');
                        header("Location:emailcontent/success/add");
                    }
                }
            }
            else {

                $this->view->message		=  "Please Enter Required Fields";
            }
        }
        if($id!="" && $id!=0)
        {
            $this->view->btnval        = "Save Changes";
            $this->view->pageContent   	=  $pageContent   =    $this->dbObj->selectRecord("Cms","*","cms_type='email' AND cms_id='".$id."'");
        }
        else
        {
            $this->view->btnval        = "Add Content";
        }
        //TODO : add pagination
        $this->view->pageContents 		= $this->dbObj->selectResult("Cms","*","cms_type='email'");
        $this->view->setLayout("home");

    } // End Function

    public function emailSettings($action = NULL, $id = NULL){
        PageContext::$response->activeLeftMenu = 'emailcontentmgmt';
        PageContext::addScript(BASE_URL."project/lib/editors/ckeditor/ckeditor.js");
        PageContext::addScript(BASE_URL."project/lib/editors/ckeditor/common.js");
        PageContext::addScript("adminCms.js");

        $session = new LibSession();
        $this->view->action		= $action  ;
        $this->view->id 		= $id    ;
        switch($action){
            case 'add':
                $this->view->pageTitle = "Add Email Content";
                break;
            case 'edit':
                $this->view->pageTitle = "Edit Email Templates";
                break;
            default:
                $this->view->pageTitle = " Email Templates";
                if($session->get('edit_email_settings_success')=='success'){
                    PageContext::$response->success_message = "Email Settings changes saved successfully";
                    PageContext::addPostAction('successmessage');
                    $session->delete('edit_email_settings_success');

                }else if($session->get('add_email_settings__success')=='success'){
                    PageContext::$response->success_message = "Email Content  saved successfully";
                    PageContext::addPostAction('successmessage');
                    $session->delete('add_email_settings__success');

                }
                break;
        }

                if($this->isPost()) {

            $this->view->id 			= $id   		= trim($this->post('id'));
            $this->view->cms_name 		= $cms_name    	= trim($this->post('cms_name'));

            $this->view->txtRefTitle 	= $txtRefTitle  = trim($this->post('cms_ref_title'));
            $this->view->txtTitle    	= $txtTitle    	= trim($this->post('cms_title'));
            $this->view->txtDesc     	= $txtDesc    	= trim($this->post('cms_desc'));
            $this->view->txtShortDesc	= $txtShortDesc = trim($this->post('cms_shortdesc'));
            $this->view->status			= $txtStatus    = trim($this->post('cms_status'));

            $postedArray    = array(	"cms_type"		=> 'email_settings',
                    "cms_name"		=> addslashes($cms_name),
                    "cms_ref_title"	=> addslashes($txtRefTitle),
                    "cms_title"		=> addslashes($txtTitle),
                    "cms_desc"		=> addslashes($txtDesc),
                    "cms_shortdesc"	=> addslashes($txtShortDesc),
                    "cms_status"	=> 1);
            

             if($id!="") {
                $this->dbObj->updateFields("Cms",$postedArray,"cms_id='".$id."'");
                $session->set('edit_email_settings_success','success');
                header("Location:emailsettings/success/edit");
             }else {
                $this->dbObj->addFields("Cms",$postedArray);
                $session->set('add_email_settings_success','success');
                header("Location:emailsettings/success/add");
             }
           
        }
        
        if($id!="" && $id!=0)
        {
            $this->view->btnval        = "Save Changes";
            $this->view->pageContent   	=  $pageContent   =    $this->dbObj->selectRecord("Cms","*","cms_type='email_settings' AND cms_id='".$id."'");
        }
        $this->view->pageContents = $this->dbObj->selectResult("Cms","*","cms_type='email_settings'");
        $this->view->setLayout("home");
    }
    
    public function gotosupport()
    {
        
        header("Location:support/admin/index.php?forcedLogin=C5EDAC1B8C1D58BAD90A246D8F08F53B");
    }

    public function ajaxUserStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateUserStatus($id,$value);
        }
        exit();
    }

    
}
?>