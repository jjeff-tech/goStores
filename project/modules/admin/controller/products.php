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

class ControllerProducts extends BaseController {
    /*
		construction function. we can initialize the models here
    */
    public $sCatArr;

    public function init() {
        parent::init();

        /************* Admin Access Check ****************/
        $adminAccess = User::adminAccessCheck();
        /*
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
        PageContext::addJsVar("BASE_URL",BASE_URL);


        //Service Category
        $sCatArr = Admincomponents::getListItem("ServiceCategories", array('nSCatId','vCategory'), array(array('field' => 'nStatus', 'value' => 1), array('field' => 'nSCatId', 'value' => 1, 'condition' => '!=')), array('sort' => ASC, 'fields' => array('vCategory')));
        $sCatOptions = array();
        //Category Options
        foreach($sCatArr as $sCatItem) {
            $sCatOptions[$sCatItem->nSCatId] = $sCatItem->vCategory;
        }
        $this->sCatArr = $sCatOptions;


    }

    /*
    function to load the index template
    */
    public function index($action = NULL,$page = NULL) //
    {
        $this->view->setLayout("home");
        PageContext::addScript("formValidations.js");
        PageContext::addScript("addproduct.js");
        PageContext::addScript("admin.js");
        PageContext::addScript("jquery.addplaceholder.min.js");

        $sessionObj = new LibSession();

        $orderArr = $searchArr = array();
        $limit = NULL;
        if($this->isPost()) {
            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
                $searchArr = array(array('field' => 'vPName' , 'value' => addslashes($txtSearch)));
            }
        }

        //$pageFullContent = Admincomponents::getListItem($table, $fieldArr, $filterArr, $orderArr, $limit, $searchArr);
        $pageFullContent = Admincomponents::getListItem("Products", array('nPId','vPName','dLastUpdated'), NULL, NULL, NULL, $searchArr);
        $pageFullCount = count($pageFullContent);

        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT);

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        $this->view->pageContents = Admincomponents::getListItem("Products", array('nPId','vPName','dLastUpdated'), NULL, $orderArr, $limit, $searchArr);
        // Add Product Success Message
        if($sessionObj->get('add_prd_success')=='success'){
            PageContext::$response->success_message = 'Product added successfully!';
            PageContext::addPostAction('successmessage','index');
            $sessionObj->delete('add_prd_success');
        }
        // Edit Product Success Message
        if($sessionObj->get('edit_prd_success')=='success'){
            PageContext::$response->success_message = 'Product modified successfully!';
            PageContext::addPostAction('successmessage','index');
            $sessionObj->delete('edit_prd_success');
        }

    } // End Function

    /****************************** CURL Functionality  **************************/
    private function cURLpost($url, $fields){
        $post_field_string = http_build_query($fields, '', '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);
        curl_setopt($ch, CURLOPT_REFERER, trim($_SERVER["HTTP_HOST"]));
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        curl_close ($ch);

        return $response;
    }
    /****************************** CURL Functionality  **************************/

    public function listproducts(){
        $storeId    = PageContext::$request['storeid'];
        $page       = PageContext::$request['page'];

        $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    	pageContext::$response->currentURL          =   $currentURL;
    	PageContext::addJsVar("currentURL", $currentURL);
    	$searchURL = BASE_URL."cms?section=".$request['section'];
    	pageContext::$response->searchURL          =   $searchURL;
    	PageContext::addJsVar("searchURL", $searchURL);
        $showHide = false;
        $message = '';
        $successError = 'success';
        $searchArr  = '';
        $planId     = '';
        $postData   = array();
        //PageContext::addScript("addplan.js");

        if(isset($_REQUEST['section_action'])){
            $showHide = true;

            if(isset($_REQUEST['plan_id'])){
                $planId = $_REQUEST['plan_id'];
            }
        }


        /******************** CURL section for the API call for the products ******************/
        $cURLurl = "";
        $store_name = "";
        $itemArr = Admincomponents::getListItem("ProductLookup", array("vSubDomain","nSubDomainStatus","vDomain","nDomainStatus","vAccountDetails"), array(array("field" => "nPLId", "value" => $storeId)));
        //echo "<pre>"; print_r($itemArr); echo "</pre>";

        $acct_details = unserialize($itemArr[0]->vAccountDetails);
        $store_name = trim($acct_details["store_name"]);
        //echo "<pre>"; print_r($acct_details); echo "</pre>";
        $hostname = "";
        if($itemArr[0]->nSubDomainStatus == 1){
            if(trim($acct_details["c_host"]) <> ""){
                $hostname = trim($acct_details["c_host"]);
            }else{
                $hostname = trim($itemArr[0]->vSubDomain);
            }
            $cURLurl          = $hostname."/products/getproducts";
            $cURLCurrUrl      = $hostname."/products/getdefaultcurrency";
            $cURLStatusUrl    = $hostname."/products/suspendproduct";

            //$cURLurl        = BASE_URL."project/Demo/products/getproducts";
            //$cURLCurrUrl    = BASE_URL."project/Demo/products/getdefaultcurrency";
            //$cURLStatusUrl  = BASE_URL."project/Demo/products/suspendproduct";

            $storeDomainName = trim($itemArr[0]->vSubDomain);
        }else if($itemArr[0]->nDomainStatus==1){
            if(trim($acct_details["c_host"]) <> ""){
                $hostname = trim($acct_details["c_host"]);
            }else{
                $hostname = trim($itemArr[0]->vDomain);
            }
            $cURLurl          = $hostname."/products/getproducts";
            $cURLCurrUrl      = $hostname."/products/getdefaultcurrency";
            $cURLStatusUrl    = $hostname."/products/suspendproduct";

            //$cURLurl        = BASE_URL."project/Demo/products/getproducts";
            //$cURLCurrUrl    = BASE_URL."project/Demo/products/getdefaultcurrency";
            //$cURLStatusUrl  = BASE_URL."project/Demo/products/suspendproduct";

            $storeDomainName = trim($itemArr[0]->vSubDomain);
        }else{
            $cURLurl            = "";
            $cURLCurrUrl        = "";
            $storeDomainName    = "";
            $cURLStatusUrl      = "";
        }

        $products       = "";
        $arrParams      = array();
        $arrProducts    = array();

        $sort_item = "";
        $sort_order = "";
        if(trim($_GET['sort_item']) <> ''){
            $sort_item = trim($_GET['sort_item']);
        }
        if(trim($_GET['sort_order']) <> ''){
            $sort_order = trim($_GET['sort_order']);
        }
        $arrParams = array("sort_item" => trim($sort_item),"sort_order" => trim($sort_order),"store_id" => trim($storeId));
        if($this->isPost()){
            if(trim($_POST['action']) == 'search'){
                $txtSearch = $_POST['search'];
                $arrParams["search_term"] = $txtSearch;
            }

            if(trim($_POST['action']) == 'changestatus'){
                $product_id     = $_POST['product_id'];
                $st_id = $_POST['store_id'];
                $itemArray = Admincomponents::getListItem("ProductLookup", array("vAccountDetails"), array(array("field" => "nPLId", "value" => $st_id)));
                $account_details = unserialize($itemArray[0]->vAccountDetails);

                $status_value   = self::cURLpost($cURLStatusUrl,array("product_id" => trim($product_id),"user_name" => trim($account_details["user_name"]),"user_email" => trim($account_details["user_email"])));
                if($status_value){
                    $message = "The product status has been updated";
                }else{
                    $message = "An error occurred in updating the product status";
                }
            }
        }
        //echo "<pre>"; print_r($arrParams); echo "</pre>";
        if(trim($cURLurl) <> ""){
            $products = self::cURLpost($cURLurl,$arrParams);
        }
        if(trim($products) <> ""){
            $arrProducts = json_decode($products,1);
        }
        //echo "<pre>"; print_r($products); echo "</pre>";
        //echo "<pre>"; print_r($arrProducts); echo "</pre>"; die();

        /**************************** PAGINATION AREA  *******************************/
        $pageInfoArr    = Utils::pageInfo($page, count($arrProducts), PAGE_LIST_COUNT);
        $limit          = $pageInfoArr['limit'];
        PageContext::$response->pageInfo = $pageInfoArr;

        $per_page = PAGE_LIST_COUNT;
        if(!$page){
            $page = 1;
        }
        if($page){
            $lowlimit = ($page - 1) * $per_page;
            $highlimit = ($page * $per_page) - 1;
        }
        if(is_array($arrProducts) && count($arrProducts)>0){
            $counter = $lowlimit;
            foreach($arrProducts as $key => $val){
                if($key == $counter && $key <= $highlimit){
                    $array_products[$key] = $val;$counter++;
                }
            }
            //echo "<pre>"; print_r($array_products); echo "</pre>";
            PageContext::$response->pageContents = $array_products;
        }
        /**************************** PAGINATION AREA  *******************************/

        $currency = "";
        if(trim($cURLCurrUrl) <> ""){
            $currency_value = self::cURLpost($cURLCurrUrl,array());
            $currency = json_decode($currency_value);
        }
        if(trim($currency) == "" || trim($currency) == "USD"){
            $currency = "$";
        }
        /******************** CURL section for the API call for the products ******************/

        $storeDomainName = BASE_URL."project/Demo/";

        PageContext::$response->showAddForm     = $showHide;
        PageContext::$response->txtSearch       = $txtSearch;
        PageContext::$response->message         = $message;
        PageContext::$response->successError    = $successError;
        PageContext::$response->storeId         = $storeId;
        PageContext::$response->store_name      = $store_name;
        PageContext::$response->storeDomainName = $hostname;
        PageContext::$response->currency        = $currency;
        PageContext::$response->postedData      = $postData;

    }

    function listplans(){
    	$currentURL ="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    	pageContext::$response->currentURL          =   $currentURL;
    	PageContext::addJsVar("currentURL", $currentURL);
    	$searchURL = BASE_URL."cms?section=".$request['section'];
    	pageContext::$response->searchURL          =   $searchURL;
    	PageContext::addJsVar("searchURL", $searchURL);
        $showHide = false;
        $message = '';
        $successError = 'success';
        $searchArr = '';
        $planId = '';
        $postData = array();
        PageContext::addScript("addplan.js");

        if(isset($_REQUEST['section_action'])){
            $showHide = true;

            if(isset($_REQUEST['plan_id'])){
                $planId = $_REQUEST['plan_id'];
            }
        }

         if(isset($_REQUEST['section_action']) && $_REQUEST['section_action']=='delete_plan'){
             if($planId>0)
             $deletePlan = Admincomponents::deletePlanGostores($planId);
         }

        if($this->isPost()) {
            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
                $searchArr = array(array('field' => 'vServiceName' , 'value' => addslashes($txtSearch)));
            }

            if(isset($_POST['catId'])){
                $dataArr['vServiceName']            = addslashes($_POST['serviceName']);
                $dataArr['nSCatId']                 = addslashes($_POST['catId']);
                $dataArr['vServiceDescription']     = addslashes($_POST['serviceDescription']);
                $dataArr['nQty']                    = addslashes($_POST['nQty']);;
                $dataArr['price']                   = addslashes($_POST['servicePrice']);
//                $dataArr['trasaction_fee']                   = addslashes($_POST['trasaction_fee']);
//                $dataArr['savings']                   = addslashes($_POST['savings']);
//                $dataArr['permonth_price']                   = addslashes($_POST['permonth_price']);
//                $dataArr['third_party_transaction']                   = addslashes($_POST['third_party_transaction']);
//                $dataArr['makeready_payments']                   = addslashes($_POST['makeready_payments']);
                $dataArr['vBillingInterval']        = addslashes($_POST['billingInterval']);
                $dataArr['nBillingDuration']        = addslashes($_POST['billingDuration']);
//                $dataArr['nStatus']                 = 0;
                $dataArr['nServiceId']              = $_POST['planId'];
                $dataArr['vType']                   = addslashes($_POST['planType']);
                $dataArr['dLastUpdated']            = date('Y-m-d H:i:s');
                $dataArr['nPId']                    = 1; //default for vistacart

                $postData = $dataArr;
            
                $misingFields = false;
                foreach($dataArr as $key => $val){
                    if(trim($val) == '' && $key <> 'nServiceId'){
                 
                        $misingFields = true;
                    
                       // echo "Here";
                    }
                }
                
                   // echopre1($dataArr);
                $duplicateFilter = array();

                $duplicateFilter[] = array("field" => "LOWER(vServiceName)","value" => strtolower($dataArr['vServiceName']));

                if(!empty($dataArr['nServiceId'])){
                    $duplicateFilter[] = array("field" => "nServiceId","value" => $dataArr['nServiceId'], 'condition' => '!=');
                }

                $duplicateArr = Admincomponents::getListItem("ProductServices", array("vServiceName"), $duplicateFilter);
                $planNameCountFlag = 0;
                if(strlen(strtolower($dataArr['vServiceName']))>15){
                    $planNameCountFlag =1;
                }

                if($misingFields){
                    $message = "Please fill all the mandatory fields";
                    $successError = 'error';

                    $showHide = true;
                } else if(count($duplicateArr) > 0){
                    $message = "Plan Name already exists";
                    $successError = 'error';

                    $showHide = true;
                } else if($planNameCountFlag==1){
                    $message = "Plan Name length exceeds maximum character limit 15";
                    $successError = 'error';

                    $showHide = true;
                } else{
                    $addPlan = Admincomponents::savePlanGostores($dataArr);
                    if($addPlan['success']){
                        $message = "Plan added successfully";
                    }
                    else{
                        $message = "Sorry an error occurred. Please try again";
                        $successError = 'error';
                        $planId = $_POST['planId'];

                        $showHide = true;
                    }
                }

            }
        }

        //$filterArr = array(array('field' => 'nSCatId' , 'value' => 1));


        PageContext::$response->pageContents = Admincomponents::getListItem("ProductServices", array('*'), $filterArr, NULL, NULL, $searchArr);
        //echo "<pre>"; print_r(PageContext::$response->pageContents); echo "</pre>";
        PageContext::$response->serviceFeatures = Admincomponents::getListItem("ServiceFeatures", array('*'), array(array('field' => 'eStatus' , 'value' => 'Active')));
        PageContext::$response->showAddForm = $showHide;
        PageContext::$response->txtSearch = $txtSearch;
        PageContext::$response->message = $message;
        PageContext::$response->successError = $successError;
        PageContext::$response->planId = $planId;
        PageContext::$response->postedData = $postData;

    }

 public function listuserplans()
    {
//     	$currentURL ="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//     	pageContext::$response->currentURL          =   $currentURL;
//     	PageContext::addJsVar("currentURL", $currentURL);
//     	$searchURL = BASE_URL."cms?section=".$request['section'];
//     	pageContext::$response->searchURL          =   $searchURL;
//     	PageContext::addJsVar("searchURL", $searchURL);
        PageContext::addScript("specialcost.js");
        PageContext::addStyle("common.css");
        $showHide = false;
        $message = '';
        $successError = 'success';
        $searchArr = array();
        $filterArr = array();

        // $filter = "b.nUId ='".$userId."'";
        $planId = '';
        $postData = array();

        //getUserBillingPlans
        $userId = PageContext::$request['parent_id'];
        if(!empty($userId)){
            // ... exclude domain renewal from service plan
            $filterArr=array(array('field' => 'b.nUId', 'value' => $userId), array('field' => 'pl.vDomain', 'value' => '1', 'condition' => '!='));
        }


        if(isset($_REQUEST['section_action'])){
            $showHide = true;
            if(isset($_REQUEST['node_id'])){
                $itemId = $_REQUEST['node_id'];

            }
        }

        if($this->isPost()) {
            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
                $searchArr = array(array('field' => 'ps.vServiceName' , 'value' => addslashes($txtSearch)));
            }

            if(isset($_POST['addSpecials'])){
                $dataArr['note']            = isset($_POST['note']) ? $_POST['note'] : '';
                $dataArr['cost']            = isset($_POST['cost']) ? $_POST['cost'] : '';
                $dataArr['capture']         = isset($_POST['capture']) ? $_POST['capture'] : '';
                $itemId = $_REQUEST['node_id'];
                $dataArr['nBmId']           = $itemId;
                $specials = array();

                $c=0;
                if(!empty($dataArr['note'])) {
                    foreach($dataArr['note'] as $item){
                        $specials[] = array('note' => addslashes($item), 'cost' => $dataArr['cost'][$c], 'capture' => $dataArr['capture'][$c]);
                        $c++;
                    }
                }

                $postData = $dataArr;

                $misingFields = false;

                if(!empty($dataArr['note'])) {
                    foreach($dataArr['note'] as $key => $val){
                        if(trim($val) == '' && $key <> 'nBmId'){
                            $misingFields = true;
                        }
                    }
                }

                if(!empty($dataArr['cost'])) {
                    foreach($dataArr['cost'] as $key => $val){
                        if(trim($val) == '' && $key <> 'nBmId'){
                            $misingFields = true;
                        }
                    }
                }

                if(!empty($dataArr['capture'])) {
                    foreach($dataArr['capture'] as $key => $val){
                        if(trim($val) == '' && $key <> 'nBmId'){
                            $misingFields = true;
                        }
                    }
                }

                if($misingFields){
                    $message = "Please fill all the mandatory fields";
                    $successError = 'error';

                    $showHide = true;
                }
                else{
                    $addSpecials = Admincomponents::updateBilling($dataArr['nBmId'],$specials);
                    if($addSpecials['success']){
                        $message = "Special cost added successfully";
                    }
                    else{
                        $message = "Sorry an error occurred. Please try again";
                        $successError = 'error';
                        $itemId = $_POST['node_id'];

                        $showHide = true;
                    }
                }

            }
        }

        //$filterArr = array(array('field' => 'nSCatId' , 'value' => 1));

        //PageContext::$response->pageContents = Admincomponents::getListItem("ProductServices", array('*'), $filterArr, NULL, NULL, $searchArr);
        PageContext::$response->userId = $userId;
        $pageArr = Admincomponents::getUserBillingPlans($filterArr, $searchArr);
        PageContext::$response->pageContents = $pageArr;
        PageContext::$response->serviceFeatures = Admincomponents::getListItem("ServiceFeatures", array('*'), array(array('field' => 'eStatus' , 'value' => 'Active')));
        PageContext::$response->showAddForm = $showHide;
        PageContext::$response->txtSearch = $txtSearch;
        PageContext::$response->message = $message;
        PageContext::$response->successError = $successError;
        PageContext::$response->itemId = $itemId;
        PageContext::$response->postedData = $postData;

    }

    public function template($file){
        Admincomponents::downloadFile($file);
    }

    public function addproduct() { //
        $this->view->setLayout("home");
        PageContext::addScript("addproduct.js");
        PageContext::includePath("resize");
        PageContext::addStyle("thickbox.css");
        PageContext::addScript("thickbox.js");
        $sessionObj = new LibSession();
        ini_set('upload_max_size','200M');

        $errMsg = NULL;
        $dataArr = array(); // Product Data

        //Default Product Services
        $dataSerArr = Admincomponents::defaultProductServices();

        $max_upload = (int)(ini_get('upload_max_filesize'));
        $this->view->maxUpload = $max_upload;

        if($this->isPost()) {

            // Service Item Count
            $serItem = (count($_POST['serviceName']) > 0) ? (count($_POST['serviceName']) - 1) : 0 ;
            $productName = addslashes(($this->post('productName')!='') ? $this->post('productName') :$this->get('productName'));
            $pdName = addslashes(($this->post('productName')!='') ? $this->post('productName') :$this->get('productName'));
            $productCaption = addslashes(($this->post('productCaption')!='') ? $this->post('productCaption') :$this->get('productCaption'));
            $productDescription = addslashes(($this->post('productDescription')!='') ? $this->post('productDescription') :$this->get('productDescription'));
            $productRelease = addslashes(($this->post('productRelease')!='') ? $this->post('productRelease') :$this->get('productRelease'));
            $productPermission = addslashes(($this->post('productPermission')!='') ? $this->post('productPermission') :$this->get('productPermission'));

            $duplicatePrdCheck = Admincomponents::getListItem("Products", array("nPId","vPName"), array(array('field' => 'LOWER(vPName)', 'value' => strtolower($productName))));

            if(!empty($duplicatePrdCheck)) {
                $errMsg .= "Product name already exists! Try another name.";
            } else {
                $dataArr['nPId'] = NULL;
                $dataArr['nPRId'] = NULL;
                $dataArr['vPName'] = $productName;
                $dataArr['vProductCaption'] = $productCaption;
                $dataArr['vProductDescription'] = $productDescription;
                $dataArr['vProductCaption'] = $productCaption;

                // Product Release
                $dataArr['vVersion'] = $productRelease;

                // Product Permission
                $dataArr['vPermissions'] = $productPermission;

                // Product Name for Files
                $pdName = Utils::stripChar(array("'"), $pdName);
                $pdName = strtolower(Utils::stripWhitespaces($pdName));

                // Product Pack Name for Files
                $pRelease = Utils::stripChar(array("'"), $productRelease);
                $pRelease = strtolower(Utils::stripWhitespaces($pRelease));

                //************************* PRODUCT PACK **********************/
                if(Utils::formatBytes($_FILES['productPack']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Pack';
                } else if(is_uploaded_file($_FILES['productPack']['tmp_name'])) {
                    $productPackParts = pathinfo($_FILES['productPack']['name']);
                    $productPackOriginal = BASE_PATH.'project/products/'.$pdName.'_'.$pRelease.'_.'.$productPackParts['extension'];

                    if(move_uploaded_file($_FILES['productPack']['tmp_name'], $productPackOriginal)) {

                        $dataArr['vProductPack'] = $pdName.'_'.$pRelease.'_.'.$productPackParts['extension'];
                    } // Pack

                } // End If
                //************************* PRODUCT PACK ********************/
                //************************* PRODUCT LOGO **********************/
                if(Utils::formatBytes($_FILES['productLogo']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Logo';
                } else if(is_uploaded_file($_FILES['productLogo']['tmp_name'])) {
                    $logoParts = pathinfo($_FILES['productLogo']['name']);
                    $logoOriginal = BASE_PATH.'project/styles/images/'.$pdName.'_main.'.$logoParts['extension'];

                    if(move_uploaded_file($_FILES['productLogo']['tmp_name'], $logoOriginal)) {

                        $resizeLogoObj = new resize($logoOriginal);
                        $resizeLogoObj->resizeImage(240, 320, 'exact');
                        $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$logoOriginal);
                        $resizeLogoObj->saveImage($rz, 100);

                        $dataArr['vProductlogo'] = $pdName.'_main_disp.gif';
                    }
                }
                //************************* PRODUCT LOGO ENDS **********************/
                //************************* PRODUCT LOGO SMALL **********************/
                if(Utils::formatBytes($_FILES['productLogoSmall']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Logo Small';
                } else if(is_uploaded_file($_FILES['productLogoSmall']['tmp_name'])) {
                    $logoSmallParts = pathinfo($_FILES['productLogoSmall']['name']);
                    $logoSmallOriginal = BASE_PATH.'project/styles/images/'.$pdName.'_small.'.$logoSmallParts['extension'];

                    if(move_uploaded_file($_FILES['productLogoSmall']['tmp_name'], $logoSmallOriginal)) {

                        $resizeLogoSmallObj = new resize($logoSmallOriginal);
                        $resizeLogoSmallObj->resizeImage(122, 77, 'exact');
                        $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$logoSmallOriginal);
                        $resizeLogoSmallObj->saveImage($rz, 100);

                        $dataArr['vProductlogoSmall'] = $pdName.'_small_disp.gif';
                    }
                }
                //************************* PRODUCT LOGO SMALL ENDS ******************/
                //************************* PRODUCT SCREENS **********************/
                if(Utils::formatBytes($_FILES['productScreens']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Screens';
                } else if(is_uploaded_file($_FILES['productScreens']['tmp_name'])) {
                    $productScreensParts = pathinfo($_FILES['productScreens']['name']);
                    $productScreensOriginal = BASE_PATH.'project/styles/images/'.$pdName.'_scr.'.$productScreensParts['extension'];

                    if(move_uploaded_file($_FILES['productScreens']['tmp_name'], $productScreensOriginal)) {

                        $resizeScreenObj = new resize($productScreensOriginal);
                        $resizeScreenObj->resizeImage(487, 305, 'exact');
                        $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$productScreensOriginal);
                        $resizeScreenObj->saveImage($rz, 100);

                        $dataArr['vProductScreens'] = $pdName.'_scr_disp.gif';
                    }
                } // End If
                //************************* PRODUCT SCREENS ******************/

                //************************* PRODUCT SERVICES ******************/
                $serviceArr = array();
                //echo '<pre>'; print_r($_POST['billingType']); echo '</pre>';
                if(!empty($serItem)) {
                    $i=0;
                    foreach($_POST['nServiceId'] as $snItem) {
                        $serviceArr[$i]['nServiceId']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['serviceName'] as $snItem) {
                        $serviceArr[$i]['vServiceName']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['serviceDescription'] as $snItem) {
                        $serviceArr[$i]['vServiceDescription']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['serviceCategory'] as $snItem) {
                        $serviceArr[$i]['nSCatId']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['servicePrice'] as $snItem) {
                        $serviceArr[$i]['price']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['billingType'] as $snItem) {
                        $serviceArr[$i]['vBillingInterval']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['billingInterval'] as $snItem) {
                        $serviceArr[$i]['nBillingDuration']  = $snItem;
                        $i++;
                    }

                }
                //************************* PRODUCT SERVICES END ******************/
                $dataArr['productServices'] = $serviceArr;
                $dataSerArr =$serviceArr;

            } // End Else

            //echo '<pre>'; print_r($serviceArr); echo '</pre>';

            if(empty($errMsg)) {
                //saveProduct
                Admincomponents::saveProduct($dataArr);
                $sessionObj->set('add_prd_success', 'success');
                $this->redirect("products/index/");
            } else {
                PageContext::$response->error_message =  $errMsg;
                PageContext::addPostAction('errormessage','index');
            }

        } // End Post

        //Product Services
        $this->view->dataArr = $dataArr;
        $this->view->dataSerArr = $dataSerArr;
    } // End Function

    public function editproduct($idProduct = NULL) { //
        $this->view->setLayout("home");
        PageContext::addScript("addproduct.js");
        PageContext::includePath("resize");
        PageContext::addStyle("thickbox.css");
        PageContext::addScript("thickbox.js");
        $sessionObj = new LibSession();
        $dbObj = new Db();
        $errMsg = NULL;
        ini_set('upload_max_size','200M');
        $dataArr = Admincomponents::getProductDetails($idProduct);
        // Old Files
        $productPackOld = $dataArr->vProductPack; // Product Pack Old
        $productLogoOld = $dataArr->vProductlogo; // Product Logo Old
        $productLogoSmOld = $dataArr->vProductlogoSmall; // Product Logo Small Old
        $productScreensOld = $dataArr->vProductScreens; // Product Screens Old
        //
        $imagePath = BASE_PATH.'project/styles/images/';
        $productPath = BASE_PATH.'project/products/';

        $max_upload = (int)(ini_get('upload_max_filesize'));
        $this->view->maxUpload = $max_upload;

        if($this->isPost()) {

            // Service Item Count
            $serItem = (count($_POST['serviceName']) > 0) ? (count($_POST['serviceName']) - 1) : 0 ;
            $idProduct = addslashes(($this->post('nPId')!='') ? $this->post('nPId') :$this->get('nPId')); //
            $PRId = addslashes(($this->post('nPRId')!='') ? $this->post('nPRId') :$this->get('nPRId')); //
            $productName = addslashes(($this->post('productName')!='') ? $this->post('productName') :$this->get('productName'));
            $pdName = addslashes(($this->post('productName')!='') ? $this->post('productName') :$this->get('productName'));
            $productCaption = addslashes(($this->post('productCaption')!='') ? $this->post('productCaption') :$this->get('productCaption'));
            $productDescription = addslashes(($this->post('productDescription')!='') ? $this->post('productDescription') :$this->get('productDescription'));
            $productRelease = addslashes(($this->post('productRelease')!='') ? $this->post('productRelease') :$this->get('productRelease'));
            $productPermission = addslashes(($this->post('productPermission')!='') ? $this->post('productPermission') :$this->get('productPermission'));

            $duplicatePrdCheck = Admincomponents::getListItem("Products", array("nPId","vPName"), array(array('field' => 'LOWER(vPName)', 'value' => strtolower($productName)), array('field' => 'nPId', 'value' => $idProduct, 'condition' => '!=' )));


            if(!empty($duplicatePrdCheck)) {
                $errMsg .= "Product name already exists! Try another name.";
            } else {
                $dataArr['nPId'] = $idProduct;
                $dataArr['nPRId'] = $PRId;
                $dataArr['vPName'] = $productName;
                $dataArr['vProductCaption'] = $productCaption;
                $dataArr['vProductDescription'] = $productDescription;
                $dataArr['vProductCaption'] = $productCaption;

                // Product Release
                $dataArr['vVersion'] = $productRelease;

                // Product Permission
                $dataArr['vPermissions'] = $productPermission;

                // Product Name for Files
                $pdName = Utils::stripChar(array("'"), $pdName);
                $pdName = strtolower(Utils::stripWhitespaces($pdName));

                // Product Pack Name for Files
                $pRelease = Utils::stripChar(array("'"), $productRelease);
                $pRelease = strtolower(Utils::stripWhitespaces($pRelease));

                //************************* PRODUCT PACK **********************/
                if(Utils::formatBytes($_FILES['productPack']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Pack';
                } else if(is_uploaded_file($_FILES['productPack']['tmp_name'])) {
                    $productPackParts = pathinfo($_FILES['productPack']['name']);
                    $productPackOriginal = $productPath.$pdName.'_'.$pRelease.'_.'.$productPackParts['extension'];

                    if(move_uploaded_file($_FILES['productPack']['tmp_name'], $productPackOriginal)) {

                        $dataArr['vProductPack'] = $pdName.'_'.$pRelease.'_.'.$productPackParts['extension'];

                        if(is_file($productPath.$productPackOld)) {
                            @unlink($productPath.$productPackOld);
                        }

                    } // Pack

                } // End If
                //************************* PRODUCT PACK ********************/
                //************************* PRODUCT LOGO **********************/
                if(Utils::formatBytes($_FILES['productLogo']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Logo';
                } else if(is_uploaded_file($_FILES['productLogo']['tmp_name'])) {
                    $logoParts = pathinfo($_FILES['productLogo']['name']);
                    $logoOriginal = $imagePath.$pdName.'_main.'.$logoParts['extension'];

                    if(move_uploaded_file($_FILES['productLogo']['tmp_name'], $logoOriginal)) {

                        $resizeLogoObj = new resize($logoOriginal);
                        $resizeLogoObj->resizeImage(240, 320, 'exact');
                        $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$logoOriginal);
                        $resizeLogoObj->saveImage($rz, 100);

                        $dataArr['vProductlogo'] = $pdName.'_main_disp.gif';
                        if(is_file($imagePath.$productLogoOld)) {
                            @unlink($imagePath.$productLogoOld);
                        }

                    }
                }
                //************************* PRODUCT LOGO ENDS **********************/
                //************************* PRODUCT LOGO SMALL **********************/
                if(Utils::formatBytes($_FILES['productLogoSmall']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Logo Small';
                } else if(is_uploaded_file($_FILES['productLogoSmall']['tmp_name'])) {
                    $logoSmallParts = pathinfo($_FILES['productLogoSmall']['name']);
                    $logoSmallOriginal = $imagePath.$pdName.'_small.'.$logoSmallParts['extension'];

                    if(move_uploaded_file($_FILES['productLogoSmall']['tmp_name'], $logoSmallOriginal)) {

                        $resizeLogoSmallObj = new resize($logoSmallOriginal);
                        $resizeLogoSmallObj->resizeImage(122, 77, 'exact');
                        $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$logoSmallOriginal);
                        $resizeLogoSmallObj->saveImage($rz, 100);

                        $dataArr['vProductlogoSmall'] = $pdName.'_small_disp.gif';
                        if(is_file($imagePath.$productLogoSmOld)) {
                            @unlink($imagePath.$productLogoSmOld);
                        }

                    }
                }
                //************************* PRODUCT LOGO SMALL ENDS ******************/
                //************************* PRODUCT SCREENS **********************/
                if(Utils::formatBytes($_FILES['productScreens']['size']) > $max_upload) {
                    $errMsg .= '<br>'.'Maximum upload size exceeds the limit for Product Screens';
                } else if(is_uploaded_file($_FILES['productScreens']['tmp_name'])) {
                    $productScreensParts = pathinfo($_FILES['productScreens']['name']);
                    $productScreensOriginal = $imagePath.$pdName.'_scr.'.$productScreensParts['extension'];

                    if(move_uploaded_file($_FILES['productScreens']['tmp_name'], $productScreensOriginal)) {

                        $resizeScreenObj = new resize($productScreensOriginal);
                        $resizeScreenObj->resizeImage(487, 305, 'exact');
                        $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$productScreensOriginal);
                        $resizeScreenObj->saveImage($rz, 100);

                        $dataArr['vProductScreens'] = $pdName.'_scr_disp.gif';
                        if(is_file($imagePath.$productScreensOld)) {
                            @unlink($imagePath.$productScreensOld);
                        }
                    }
                } // End If
                //************************* PRODUCT SCREENS ******************/

                //************************* PRODUCT SERVICES ******************/
                $serviceArr = array();
                //echo '<pre>'; print_r($_POST['billingType']); echo '</pre>';
                if(!empty($serItem)) {
                    $i=0;
                    foreach($_POST['nServiceId'] as $snItem) {

                        $serviceArr[$i]['nServiceId']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['serviceName'] as $snItem) {
                        $serviceArr[$i]['vServiceName']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['serviceDescription'] as $snItem) {
                        $serviceArr[$i]['vServiceDescription']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['serviceCategory'] as $snItem) {
                        $serviceArr[$i]['nSCatId']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['servicePrice'] as $snItem) {
                        $serviceArr[$i]['price']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['billingType'] as $snItem) {
                        $serviceArr[$i]['vBillingInterval']  = $snItem;
                        $i++;
                    }
                    $i=0;
                    foreach($_POST['billingInterval'] as $snItem) {
                        $serviceArr[$i]['nBillingDuration']  = $snItem;
                        $i++;
                    }


                }
                //************************* PRODUCT SERVICES END ******************/
                $dataArr['productServices'] = $serviceArr;

            } // End Else

            //echo '<pre>'; print_r($dataArr); echo '</pre>'; exit('Control comes here');

            if(empty($errMsg)) {
                //saveProduct
                Admincomponents::saveProduct($dataArr);
                $sessionObj->set('edit_prd_success', 'success');
                $this->redirect("products/index/");
            } else {
                PageContext::$response->error_message =  $errMsg;
                PageContext::addPostAction('errormessage','index');
            }

        } // End Post

        //Product Details
        $this->view->dataArr = $dataArr;

    } // End Function


   public function addServiceItem() {
        // New Service Item
        $this->view->disableLayout();
        $this->view->disableView();
        $content = NULL;

        if($this->isPost()) {
            //
            $cntN = addslashes(($this->post('cntN')!='') ? $this->post('cntN') :$this->get('cntN'));
            $cntL = addslashes(($this->post('cntL')!='') ? $this->post('cntL') :$this->get('cntL'));
            if(!empty($cntN)) {
                //
                $k=0;
                for($i=$cntL+1; $i<=($cntL+$cntN); $i++) {
                    $k = $i-1;
                    $content .='<li id="serItem_'.$i.'">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tbl">
                           <input type="hidden" name="nServiceId[]" id="nServiceId_'.$i.'" value="" />
                            <tr>
                                <td align="left" valign="top" width="2%">'.$this->view->checkbox('chkService[]', '', 'x', NULL, 'chkService'.$i).'</td>
                                <td align="left" valign="top" width="20%">
                                    <input type=text name="serviceName[]" id="serviceName_'.$i.'" value="" class="textbox width2">
                                        <div class="error" id="service_name_error_'. $i.'"></div>
                                </td>
                                <td align="left" valign="top" width="20%">
                                    <textarea name="serviceDescription[]" id="serviceDescription_'.$i.'" class="textarea width2">'.$dataSerItem['serviceDescription'].'</textarea>
                                    <div class="error" id="service_description_error_'.$i.'"></div>
                                </td>
                                <td align="left" valign="top" width=10%">
                                    '.CURRENCY_SYMBOL.'<input type=text name="servicePrice[]" id="servicePrice_'.$i.'" value="" class="textbox width3">
                                     <div class="error" id="service_price_error_'.$i.'"></div>
                                </td>
                                <td align="left" valign="top" width="20%">
                                        '.$this->view->select("serviceCategory[]",$this->sCatArr,NULL,'textbox width2', NULL, 'serviceCategory'.$i).'
                                </td>
                                <td align="left" valign="top" width="18%">
                                        '.$this->view->radio('billingType['.$k.']', 'M', 'M', NULL, '&nbsp;', NULL).' month(days)<br>
                                        '.$this->view->radio('billingType['.$k.']', 'Y', NULL, NULL, '&nbsp;', NULL).' year<br>
                                        '.$this->view->radio('billingType['.$k.']', 'L', NULL, NULL, '&nbsp;', NULL).' lifetime<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="billingInterval[]" id="billingInterval_'.$i.'" value="" class="textbox width3" maxlength="6">
                                        <div class="error" id="service_billing_duration_error_'.$i.'"></div>
                                </td>

                            </tr>
                        </table>
                    </li>';
                } // End For

            } // End If

        } // End If

        echo $content;


    } // End Function

    public function dropservice() {
        $this->view->disableLayout();
        $this->view->disableView();
        $dbObj = new Db();
        $msg = NULL;

        if($this->isPost()) {
            $id = addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));
            if(!empty($id)) {
                $dbObj->deleteRecord("ProductServices", "nServiceId = '".$id."'");
                $msg = 'dropped';
            }
        } // End Post

        echo $msg;

    } // End Function

    public function dropproduct() {
        $this->view->disableLayout();
        $this->view->disableView();

        $dbObj = new Db();
        $msg = NULL;

        if($this->isPost()) {
            $id = addslashes(($this->post('id')!='') ? $this->post('id') :$this->get('id'));

            if(!empty($id)) {
                $prdArr = Admincomponents::getListItem("Products", array('nPId','vPName','vProductCaption','vProductPack','vProductlogoSmall','vProductlogo','vProductDescription','vProductScreens','nPRId'), array(array('field' => 'nPId', 'value' => $id)));

                //InvoicePlan
                $selQry ="SELECT COUNT(inv.nIPId) as cntSerSubscription FROM ".$dbObj->tablePrefix."InvoicePlan inv LEFT JOIN ".$dbObj->tablePrefix."ProductServices ps ON inv.nServiceId = ps.nServiceId LEFT JOIN ".$dbObj->tablePrefix."Products p ON ps.nPId = p.nPId WHERE p.nPId = '".$id."'";
                $cntArr = $dbObj->selectQuery($selQry);
                $cntServiceSubscription = (empty($cntArr)) ? 0 : $cntArr[0]->cntSerSubscription;
                if($cntServiceSubscription > 0) {
                   $msg = 'serviceexists';
                } else {
                // Old Files
                $productPackOld = $prdArr[0]->vProductPack; // Product Pack Old
                $productLogoOld = $prdArr[0]->vProductlogo; // Product Logo Old
                $productLogoSmOld = $prdArr[0]->vProductlogoSmall; // Product Logo Small Old
                $productScreensOld = $prdArr[0]->vProductScreens; // Product Screens Old

                $imagePath = BASE_PATH.'project/styles/images/';
                $productPath = BASE_PATH.'project/products/';

                if(is_file($productPath.$productPackOld)) {
                    @unlink($productPath.$productPackOld);
                }

                if(is_file($imagePath.$productLogoOld)) {
                    @unlink($imagePath.$productLogoOld);
                }

                if(is_file($imagePath.$productLogoSmOld)) {
                    @unlink($imagePath.$productLogoSmOld);
                }

                if(is_file($imagePath.$productScreensOld)) {
                    @unlink($imagePath.$productScreensOld);
                }

                //delete Services
                $dbObj->deleteRecord("ProductServices", "nPId = '".$id."'");
                //delete Releases
                $dbObj->deleteRecord("ProductReleases", "nPId = '".$id."'");
                //delete Permission
                $dbObj->deleteRecord("ProductPermission", "nPId = '".$id."'");
                //delete Product
                $dbObj->deleteRecord("Products", "nPId = '".$id."'");

                $msg = 'Product deleted successfully';
                }
            }
        } // End Post

        echo $msg;
    }

    // Function to show plan feature popup from cms
    public function planFeatures(){
        $this->disableLayout();
        PageContext::$response->serviceFeatures = Admincomponents::getListItem("ServiceFeatures", array('*'), array(array('field' => 'eStatus' , 'value' => 'Active')));
        Logger::info(PageContext::$response->serviceFeatures);

    } // End Function

    // Function to show banner image popup from cms
    public function viewbannerimage($banner_id=''){
        $this->disableLayout();
        $bannerId = $banner_id;
        PageContext::$response->bannerDetails = Admincomponents::getBannerDetails($bannerId);
        Logger::info(PageContext::$response->bannerDetails);
    } // End Function

    // Function to change plan status
    public function ajaxPlanStatusChange(){
        $this->disableLayout();
        $status_id = PageContext::$request['status_id'];
        $plan_id   = PageContext::$request['plan_id'];
        $planType  = PageContext::$request['planType'];
        $planCount = Admincomponents::getPlansCount();
        if($status_id==0 && $planCount >= 10 && $planType!='free') $statusChange = 2;
        else $statusChange = Admincomponents::changePlanStatus($status_id,$plan_id);
        echo $statusChange.'**'.$planCount;
        exit();
    } // End Function

    // Function to show screen image popup from cms
    public function viewscreenimage($screen_id=''){
        $this->disableLayout();
        $screenId = $screen_id;
        PageContext::$response->screenDetails = Admincomponents::getScreenDetails($screenId);
        Logger::info(PageContext::$response->screenDetails);
    } // End Function

} // End Class

?>
