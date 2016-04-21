<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.|
// | File name : plan.php                                                                               |
// | PHP version >= 5.2                                                                                 |
// | Created On	: 29 Nov 2012                                                                           |
// | Author : Febin James <febin.j@armiasystems.com>                                                    |
// | Modified / New additions : Meena Susan Joseph <meena.s@armiasystems.com>                           |
// +----------------------------------------------------------------------------------------------------+
// +----------------------------------------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                                                    |
// | All rights reserved                                                                                |
// +----------------------------------------------------------------------------------------------------+

class ControllerService extends BaseController {
    /*
		construction function. we can initialize the models here
    */
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
        PageContext::addScript("admin.js");
    }

    /*
    function to load the index template
    */
    public function index($action = NULL,$id = NULL,$page = NULL) //
    {
    	$currentURL ="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    	pageContext::$response->currentURL          =   $currentURL;
    	PageContext::addJsVar("currentURL", $currentURL);
    	$searchURL = BASE_URL."cms?section=".$request['section'];
    	pageContext::$response->searchURL          =   $searchURL;
    	PageContext::addJsVar("searchURL", $searchURL);
        PageContext::addStyle("invoice.css");
        $orderField = $_REQUEST['orderField'];
        $orderType  = ($_REQUEST['orderType']=='ASC')?'DESC':'ASC';
        $searchString= "WHERE 1=1 ";
        $searchVal=  ($_REQUEST['txtSearch'])?$_REQUEST['txtSearch']:NULL;
        $searchType = ($_REQUEST['cmbSearchType'])?$_REQUEST['cmbSearchType']:NULL;

        if($this->isPost()||($searchVal!='' && $searchType!='' )) {
            if($searchType<>'' && $searchVal<>''){
                if($searchType==1){
                    $searchString .=" AND u.vFirstName LIKE '%$searchVal%' OR  u.vLastName LIKE '%$searchVal%'" ;
                    $searchString .= " OR CONCAT_WS(' ',u.vFirstName,u.vLastName) LIKE '%".$searchVal."%'";
                } elseif($searchType==2) {
                    $searchString .=" AND ps.vServiceName LIKE '%$searchVal%'" ;
                } elseif($searchType==3) {
                    $searchString .=" AND p.vDomain LIKE '%$searchVal%' OR  p.vSubDomain LIKE '%$searchVal%'" ;
                    $searchString .= " OR CONCAT_WS('.',p.vSubDomain,s.vserver_name) LIKE '%".$searchVal."%'";
                }
            }
        }
        //echo $searchString;
        $pageUrl = BASE_URL."cms?section=orders&cmbSearchType=$searchType&txtSearch=$searchVal&";
        $perPageSize = 10;
        $page = (PageContext::$request['page'])?PageContext::$request['page']:1;
        $serviceHistoryCount = Admincomponents::getPlanDetails($searchString,$orderField,$orderType);
        $serviceHistoryCount = count($serviceHistoryCount);
        PageContext::$response->resultPageCount = ceil($serviceHistoryCount/$perPageSize);
        PageContext::$response->pagination      = Admin::pagination($serviceHistoryCount,$perPageSize,$pageUrl,PageContext::$request['page']);
        $serviceHistory = Admincomponents::getPlanDetails($searchString,$orderField,$orderType,$perPageSize,$page);        
        PageContext::$response->pageContents = $serviceHistory;
        PageContext::$response->txtSearch = $searchVal;
        PageContext::$response->searchType = $searchType;
        PageContext::$response->orderType = $orderType;
       //service history
        

    } // End Function

    public function servicedetails($parentId=''){  
        $this->disableLayout();
        
        //$idPLookup = $_REQUEST['parent_id'];
        $idPLookup = $parentId;
        PageContext::$response->dataDomainArr = Admincomponents::getStoreDomains($idPLookup);
        PageContext::$response->dataArr = Admincomponents::getInvoiceDetails(NULL,array(array('field' => 'i.nPLId', 'value'=>$idPLookup)));
        PageContext::$response->dataDomArr = Admincomponents::getInvoiceDomainDetails($idInvoice);
        Logger::info($this->view->dataArr);
    } // End Function

    
     public function invoicedetails($idInvoice = NULL){
        $this->disableLayout(); 
        PageContext::$response->invoice = $idInvoice;
        PageContext::$response->dataArr = Admincomponents::getInvoiceDetails($idInvoice);
        PageContext::$response->dataDomArr = Admincomponents::getInvoiceDomainDetails($idInvoice);
        Logger::info(PageContext::$response->dataArr);
        Logger::info(PageContext::$response->dataDomArr);

    } // End Function

    public function userplandetails($userId=''){
        $this->disableLayout();
        $idPLookup = $userId;
        PageContext::$response->dataArr = Admincomponents::getInvoiceDetails(NULL,array(array('field' => 'u.nUId', 'value'=>$userId)),'i.nPLId');
        Logger::info($this->view->dataArr);
    } // End Function

    public function ajaxBannerStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateBannerStatus($id,$value);
        }
        exit();
    }

    public function ajaxServiceFeatureStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateServiceFeatureStatus($id,$value);
        }
        exit();
    }

    public function ajaxThemeStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateThemeStatus($id,$value);
        }
        exit();
    }

    public function ajaxContentStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateContentStatus($id,$value);
        }
        exit();
    }

     public function ajaxNewsletterStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateNewsletterStatus($id,$value);
        }
        exit();
    }

     public function ajaxEmailSchedulerStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateEmailSchedulerStatus($id,$value);
        }
        exit();
    }

    public function resendinvoice(){
        
        $this->disableLayout();      

        $msg = 0;

        if($this->isPost()) {
            $invoiceId = addslashes(($this->post('invoice')!='') ? $this->post('invoice') :$this->get('invoice'));
            
            if(!empty($invoiceId)) {
                $msg = Admincomponents::sendInvoiceMail($invoiceId);
            }
        } // End Post

        echo $msg;
        exit();

    } // End Function

   public function ajaxScreenStatusChange(){
        $id    = PageContext::$request['id'];
        $value = PageContext::$request['value'];
        if($id>0){
            $updateVal = Admin::updateScreenStatus($id,$value);
        }
        exit();
    } // End Function

    public function changeAccountSetting($lookupID,$operationType=0) { // 0 => disable, 1 => enable
       
        $operationMode = $invOperationMode = $storeOperationMode = $msg = NULL;
       
       if (trim($lookupID) <> '') {
            $storeServerInfoArr = Admincomponents::getStoreServerInfo($lookupID);           
            if (!empty($storeServerInfoArr)) {
                PageContext::includePath('cpanel');
                $cpanelObj = new cpanel();
                $operationMode = ($operationType==1) ? 'enable' : 'disable';
                $res = $cpanelObj->enableDisableCpanelAccount($storeServerInfoArr, $operationMode);
                if ($res) {
                    $msg = "Successfully ";
                    $msg .= ($operationType==1) ? 'activated ' : 'suspended ';
                    $msg .= 'account';
                    $invOperationMode = ($operationType==1) ? 0 : 1; // 0 => enabling the invoice back, changing the delete status in billing Main to 0,
                    // 1 => disabling the invoice, changing the delete status in billing Main to 1,
                    $storeOperationMode = ($operationType==1) ? 1 : 0;
                    Admincomponents::updateExpiredDomain($lookupID, $storeOperationMode);
                    Admincomponents::updateInvoice($lookupID,$invOperationMode);
                } else {
                    $msg = "Unable to ";
                    $msg .= ($operationType==1) ? 'activate ' : 'suspend ';
                    $msg .= 'account';
                }
            }
        }
        header("location:".BASE_URL."cms?section=domains&message=".$msg);
        die();
    } // End Function

    public function doAccountCancellation($lookupID) { 
       $msg = NULL;
       if (trim($lookupID) <> '') {            
                $res = Cronhelper::doStoreTermination($lookupID);
                $msg = ($res==true) ? "Account canceled Successfully " : "Unable to cancel account";
        }
        header("location:".BASE_URL."cms?section=domains&message=".$msg);
        die();
    } // End Function

    public function doDomainRenewal($logID){
        //... do domain renewal
        $productLookUpID = $itemUpdateQry = $userID = $msg = NULL;
        $itemArr = Admincomponents::getListItem("DomainRenewalLog", array("nPLId","nUId","status","comments","cronAttempt"), array(array("field" => "id", "value" => $logID)));
         $msg = "Unable to renew domain due to some technical issues.";
         if(isset($itemArr[0]->status)){
             if($itemArr[0]->status==0){
                 
                $productLookUpID = $itemArr[0]->nPLId;                
                $userID = $itemArr[0]->nUId;
                $domainRenewalStatus = Admincomponents::doDomainRenewal($productLookUpID);

                if($domainRenewalStatus == true){
                    //... domain renewal successfully completed
                    //... log domain renewal as Domain Renewed
                    Admincomponents::logDomainRenewal($productLookUpID, 1, $logID);
                    $msg = "Domain Renewed successfully";
                } else {

                    Admincomponents::updateDomainRenewalAttempt($logID);
                    //... Notify admin if it is 5 th attempt
                    if($itemArr[0]->cronAttempt==5){
                        $errorMsg = 'Sorry, This is the fifth attempt! This domain may be lost!';
                        Cronhelper::generateDomainRenewalFailureNotification($productLookUpID, $userID, $errorMsg);
                    } // End notification
                    
                }

             }
         }
         header("location:".BASE_URL."cms?section=domain_renewal_log&message=".$msg);

    } // End Function

}

?>