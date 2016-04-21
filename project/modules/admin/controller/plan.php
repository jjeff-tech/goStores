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

class ControllerPlan extends BaseController {
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
        PageContext::addJsVar("BASE_URL",BASE_URL);
        PageContext::addScript("admin.js");
    }

    /*
    function to load the index template
    */
    public function index($action = NULL,$id = NULL,$page = NULL) //
    {
        //Logger::info("hello world");
        $this->view->setLayout("home");
        PageContext::addScript("formValidations.js");

        //Plans
        $plan = Admincomponents::getPlanById($id);
        Logger::info($plan);
        $this->view->plan = $plan;
        $pageFullContent = Admincomponents::getPlans();
        $pageFullCount = count($pageFullContent);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT);

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;


        //Button Label
        $this->view->buttonLabel = (empty($id)) ? 'Add' : 'Save'; // Button Label

        $this->view->action = $action ;
        $this->view->id = $id ;
        $txtSearch = NULL;

        /*
         * Activate / Deactivate
        */
        if(($action == 'deactivate' || $action == 'activate') && $id != '') {

            switch ($action) {
                case "deactivate":
                    $statusArr = array("nStatus" => 0);
                    break;
                case "activate":
                    $statusArr = array("nStatus" => 1);
                    break;
            }
            AdminComponents::updateListItem($statusArr, "Plans", "nPlanId", $id);
            $this->view->message = 'Changes saved successfully';

        }
        /*
         * Add / Edit / Search
        */
        if($this->isPost()) {

            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
            } else if($_POST['action']=='edit' || $_POST['action']=='add') {
                $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
                $plan = addslashes(($this->post('plan')!='') ? $this->post('plan') :$this->get('plan'));
                $description = addslashes(($this->post('description')!='') ? $this->post('description') :$this->get('description'));
                // Plan Details
                $planData = array();
                $planData['nPlanId'] = $id;
                $planData['vPlanName'] = $plan;
                $planData['vDescription'] = $description;

                $savedPlanData = Admincomponents::savePlan($planData);
                if(isset($savedPlanData['errMsg']) && !empty($savedPlanData['errMsg'])) {
                    $this->view->message = $savedPlanData['errMsg'];
                } else { // End Error Message
                    $this->view->message = "Changes Saved successfully";
                }
            }

        } // End isPost
        $this->view->txtSearch = $txtSearch;
        $this->view->pageContents = Admincomponents::getPlans($txtSearch, $limit);

    } // End Function

    public function drop() // Delete
    {
        $this->view->disableLayout();
        $this->view->disableView();
        $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
        $itemArr = array("nStatus" => 0, "nDeleteStatus" => 1);
        Admincomponents::updatePlan($itemArr, $id);
        Admincomponents::updatePlanPackages($itemArr, "nPlanId", $id);
        echo $id;
    } // End Function

    public function packages($action = NULL,$id = NULL,$page = NULL) //
    {
        //Logger::info("hello world");
        $this->view->setLayout("home");
        PageContext::addScript("formValidations.js");

        //Plans Purchase Category Detail
        $dataArr = Admincomponents::getListItemById($id, "nPPId", array('nPPId','nPlanId','vDescription','nPlanAmount','nStatus'), "PlanPackages");

        Logger::info($dataArr);
        $this->view->dataArr = $dataArr;

        //Plan Purchase Category Array
        $planArr = Admincomponents::getPlans(NULL, NULL, array(array('field' => 'nStatus', 'value'=>'1')));

        //Category Array
        $catArr = array();
        if(!empty($planArr)) {
            foreach($planArr as $catItem) {
                $catArr[$catItem->nPlanId] = $catItem->vPlanName;
            }
        }

        $this->view->categoryArr = $catArr;
        //Category Array End

        $pageFullContent = Admincomponents::getPlanPackages();

        $pageFullCount = count($pageFullContent);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;

        //Button Label
        $this->view->buttonLabel = (empty($id)) ? 'Add' : 'Save'; // Button Label

        $this->view->action = $action ;
        $this->view->id = $id ;
        $txtSearch = NULL;

        /*
         * Activate / Deactivate
        */
        if(($action == 'deactivate' || $action == 'activate') && $id != '') {

            switch ($action) {
                case "deactivate":
                    $statusArr = array("nStatus" => 0);
                    break;
                case "activate":
                    $statusArr = array("nStatus" => 1);
                    break;
            }
            Admincomponents::updateListItem($statusArr, "PlanPackages", "nPPId", $id);
            $this->view->message = 'Changes saved successfully';

        }
        /*
         * Add / Edit / Search
        */
        if($this->isPost()) {

            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
            } else if($_POST['action']=='edit' || $_POST['action']=='add') {
                $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
                $plan = addslashes(($this->post('plan')!='') ? $this->post('plan') :$this->get('plan'));
                $description = addslashes(($this->post('description')!='') ? $this->post('description') :$this->get('description'));
                $amount = addslashes(($this->post('amount')!='') ? $this->post('amount') :$this->get('amount'));

                // Plan Details
                $planPData = array();
                $planPData['nPPId'] = $id;
                $planPData['nPlanId'] = $plan;
                $planPData['vDescription'] = $description;
                $planPData['nPlanAmount'] = $amount;

                $savedData = Admincomponents::savePlanPackages($planPData);
                if(isset($savedData['errMsg']) && !empty($savedData['errMsg'])) {
                    $this->view->message = $savedData['errMsg'];
                } else { // End Error Message
                    $this->view->message = "Changes Saved successfully";
                }
            }

        } // End isPost
        $this->view->txtSearch = $txtSearch;
        $this->view->pageContents = Admincomponents::getPlanPackages($txtSearch, $limit);


    } // End Function

    public function droppackages() // Delete
    {
        $this->view->disableLayout();
        $this->view->disableView();
        $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
        //dropListItem
        Admincomponents::dropListItem("PlanPackages", array(array('field' => 'nPPId', 'value' => $id)));
        echo $id;
    } // End Function


    public function purchasecategory($action = NULL, $id = NULL, $txtSearch = NULL, $page = NULL) //
    {
        //Logger::info("hello world");
        if($txtSearch=='x')
            $txtSearch = NULL;
       
        PageContext::$response->activeLeftMenu = 'Service Category';
        $this->view->pageCount = $page;
        $this->view->setLayout("home");
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("formValidations.js");
        PageContext::addScript("jquery.addplaceholder.min.js");
        

        //Plans Purchase Category
        $dataArr = Admincomponents::getPlanPurchaseCategoryById($id);


        Logger::info($dataArr);
        
       

        //Button Label
        $this->view->buttonLabel = (empty($id)) ? 'Add' : 'Save'; // Button Label

        $this->view->action = $action ;
        $this->view->id = $id ;
       
        $session = new LibSession();
        switch ($action){
            case 'add' :
                $this->view->action = 'add';
                $this->view->pageTitle = 'Add Service Category';
                $this->view->addEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
                $dataArr = new Db();
                $dataArr->vInputType = 'C';
                
            break;
            case 'edit' :
                $this->view->action = 'edit';
                $this->view->pageTitle = 'Edit Service Category';
                $this->view->editEnabled = TRUE;
                $this->view->buttonValue = 'Save Changes';
            break;
            default :
                $this->view->pageTitle = 'Service Categories';
                if($session->get('add_service_category_success')=='success'){
                     PageContext::$response->success_message =  "Service Category created successfully" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('add_service_category_success');
                }

                if($session->get('edit_service_category_success')=='success'){
                    PageContext::$response->success_message =  "Service Category changes saved successfully" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('edit_service_category_success');
                }
                if($session->get('delete_service_category_success')=='success'){
                     PageContext::$response->success_message =  "Service Category  deleted successfully" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('delete_service_category_success');
                }
                if($session->get('activate_service_category_success')=='success'){
                     PageContext::$response->success_message =  "Service Category activated successfully" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('activate_service_category_success');
                }

                if($session->get('deactivate_service_category_success')=='success'){
                     PageContext::$response->success_message =  "Service Category deactivated successfully" ;
                     PageContext::addPostAction('successmessage','index');
                     $this->view->messageFunction = 'successmessage';
                     $session->delete('deactivate_service_category_success');
                }

            break;
        }
       
        /*
         * Activate / Deactivate
        */
        if(($action == 'deactivate' || $action == 'activate') && $id != '') {

            switch ($action) {
                case "deactivate":
                    $statusArr = array("nStatus" => 0);
                    $session->set('deactivate_service_category_success','success');
                    break;
                case "activate":
                    $statusArr = array("nStatus" => 1);
                    $session->set('activate_service_category_success','success');
                    break;
            }
            Admincomponents::updateListItem($statusArr, "ServiceCategories", "nSCatId", $id);
            $this->view->message = 'Changes saved successfully';
            $this->redirect("plan/purchasecategory");

        }

        if($action=='delete' && $id != ''){
           
            $this->droppurchasecategory($id);
            $session->set('delete_service_category_success','success');
            $this->redirect("plan/purchasecategory");
        }
        /*
         * Add / Edit / Search
        */
        if($this->isPost()) {

            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
            } else if($_POST['action']=='edit' || $_POST['action']=='add') {
                $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
                $name = addslashes(($this->post('category')!='') ? $this->post('category') :$this->get('category'));
                $description = addslashes(($this->post('description')!='') ? $this->post('description') :$this->get('description'));
                $inputType  = ($this->post('inputType')!='') ? $this->post('inputType') :$this->get('inputType');

                // Plan Details
                $planCData = array();
                $planCData['nId'] = $id;
                $planCData['vCategory'] = $name;
                $planCData['vDescription'] = $description;
                $planCData['vInputType'] = $inputType;
                
                $savedData = Admincomponents::savePlanPurchaseCategory($planCData);
                if(isset($savedData['errMsg']) && !empty($savedData['errMsg'])) {
                    PageContext::$response->error_message =  $savedData['errMsg'];
                    PageContext::addPostAction('errormessage','index');
                
                } else { // End Error Message
                    if($id){
                         $session->set('edit_service_category_success','success');
                    }else{
                         $session->set('add_service_category_success','success');
                    }

                    $this->redirect("plan/purchasecategory");
                    //$this->view->message = "Changes Saved successfully";
                }
            }

        } // End isPost

        switch ($action){
            case 'sortbycategorynameasc':
                $sort_order= 'ASC';
                $orderArr['fields']  = array('vCategory');
                $this->view->serviceCategorySortStyle = 'sort_column_down';
                $this->view->serviceCategorySortAction = 'sortbycategorynamedesc';

                $this->view->searchParam = $txtSearch;
            break;
            case 'sortbycategorynamedesc':
               $orderArr['sort'] = 'DESC';
               $orderArr['fields']  = array('vCategory');
               $this->view->serviceCategorySortStyle = 'sort_column_up';
               $this->view->serviceCategorySortAction = 'sortbycategorynameasc';
               $this->view->searchParam = $txtSearch;

            break;
            default:
                $orderArr['sort'] = 'ASC';
                $orderArr['fields']  = array('vCategory');
                $this->view->serviceCategorySortStyle = 'sort_column_down';
                $this->view->serviceCategorySortAction = 'sortbycategorynamedesc';
                $this->view->searchParam = $txtSearch;
            break;

        }
        
        $this->view->dataArr = $dataArr;
        $pageFullContent = Admincomponents::getPlanPurchaseCategory($txtSearch, $limit,NULL,$orderArr);

        $pageFullCount = count($pageFullContent);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        $this->view->txtSearch = $txtSearch;
        $this->view->pageContents = Admincomponents::getPlanPurchaseCategory($txtSearch, $limit,NULL,$orderArr);


    } // End Function

    public function purchasecategorydetails($action = NULL,$id = NULL,$page = NULL) //
    {
        //Logger::info("hello world");
        $this->view->setLayout("home");
        PageContext::addScript("formValidations.js");

        //Plans Purchase Category Detail
        $dataArr = Admincomponents::getListItemById($id, "nId", array('nId','nPlanPurchaseCategoryId','vDescription','nAmount','nIsMandatory','nStatus'), "PlanPurchaseCategoryDetails");

        Logger::info($dataArr);
        $this->view->dataArr = $dataArr;

        //Plan Purchase Category Array
        $planPurchaseCategoryArr = Admincomponents::getPlanPurchaseCategory(NULL, NULL, array(array('field' => 'nStatus', 'value'=>'1')));

        //Category Array
        $catArr = array();
        if(!empty($planPurchaseCategoryArr)) {
            foreach($planPurchaseCategoryArr as $catItem) {
                $catArr[$catItem->nId] = $catItem->vCategory;
            }
        }
        $this->view->categoryArr = $catArr;
        //Category Array End

        $pageFullContent = Admincomponents::getPlanPurchaseCategoryDetails();

        $pageFullCount = count($pageFullContent);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;

        //Button Label
        $this->view->buttonLabel = (empty($id)) ? 'Add' : 'Save'; // Button Label

        $this->view->action = $action ;
        $this->view->id = $id ;
        $txtSearch = NULL;

        /*
         * Activate / Deactivate
        */
        if(($action == 'deactivate' || $action == 'activate') && $id != '') {

            switch ($action) {
                case "deactivate":
                    $statusArr = array("nStatus" => 0);
                    break;
                case "activate":
                    $statusArr = array("nStatus" => 1);
                    break;
            }
            Admincomponents::updateListItem($statusArr, "PlanPurchaseCategoryDetails", "nId", $id);
            $this->view->message = 'Changes saved successfully';

        }

        
        /*
         * Add / Edit / Search
        */
        if($this->isPost()) {

            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
            } else if($_POST['action']=='edit' || $_POST['action']=='add') {
                $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
                $category = addslashes(($this->post('category')!='') ? $this->post('category') :$this->get('category'));
                $description = addslashes(($this->post('description')!='') ? $this->post('description') :$this->get('description'));
                $amount = addslashes(($this->post('amount')!='') ? $this->post('amount') :$this->get('amount'));
                $isMandatory = (isset($_POST['isMandatory'])) ? 1 : 0;

                // Plan Details
                $planCData = array();
                $planCData['nId'] = $id;
                $planCData['nPlanPurchaseCategoryId'] = $category;
                $planCData['vDescription'] = $description;
                $planCData['nAmount'] = $amount;
                $planCData['nIsMandatory'] = $isMandatory;

                $savedData = Admincomponents::savePlanPurchaseCategoryDetail($planCData);
                if(isset($savedData['errMsg']) && !empty($savedData['errMsg'])) {
                    $this->view->message = $savedData['errMsg'];
                } else { // End Error Message
                    $this->view->message = "Changes Saved successfully";
                }
            }

        } // End isPost
        $this->view->txtSearch = $txtSearch;
        $this->view->pageContents = Admincomponents::getPlanPurchaseCategoryDetails($txtSearch, $limit);


    } // End Function

    public function droppurchasecategory($id) // Delete Plan Pruchase Category
    {
     //   $this->view->disableLayout();
     //   $this->view->disableView();
        //$id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));

        Logger::info($id);
       
        Admincomponents::deletePlanPurchaseItem($id);

      //  echo $id;
    } // End Function

    public function droppurchasecategorydetail() // Delete Plan Pruchase Category
    {
        $this->view->disableLayout();
        $this->view->disableView();
        $id 	= addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));

        //dropListItem
        Admincomponents::dropListItem("PlanPurchaseCategoryDetails", array(array('field' => 'nId', 'value' => $id)));

        echo $id;
    } // End Function

}

?>