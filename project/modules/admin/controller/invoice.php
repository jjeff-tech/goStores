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

class ControllerInvoice extends BaseController {
    public function init() {
        parent::init();

        $adminAccess = User::adminAccessCheck();
        if($adminAccess==0) {
            $this->redirect('login/index');
        }
        $leftMenuArr = NULL;
        if(isset($_SESSION['adminUser']['userModules']) && !empty($_SESSION['adminUser']['userModules'])) {
            $leftMenuArr = $_SESSION['adminUser']['userModules'];
        }
        $this->view->leftMenu='left_main';
        $this->view->leftMenuArr = $leftMenuArr;
        PageContext::addStyle("admin_style.css");        
              
    }

    public function index($startDate = NULL, $endDate= NULL, $product ='all' , $subscriptionType= 'PAID-FREE', $userEmail = 'all', $page=NULL){

        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("admin.js");
        $this->view->pageTitle = 'Invoice';
        
        $error = $limit = NULL;
        $dataArr = array();
        // Supply default values
        $dataArr['reportStartDate'] = NULL;
        $dataArr['reportEndDate'] = NULL;
        $dataArr['product'] = 'all';
        $dataArr['paid_subscription'] = 'PAID'; 
        $dataArr['free_subscription'] = 'FREE';
        $dataArr['invDue'] = ''; 
        $dataArr['userEmail'] = 'all';
        PageContext::addStyle("jquery-ui-1.8.23.custom.css");

       if($this->isPost()){            
            $dataArr['reportStartDate'] = $this->post('reportStartDate');
            $dataArr['reportEndDate'] = $this->post('reportEndDate');
            $dataArr['product'] = $this->post('product');            
            $dataArr['paid_subscription'] = (isset($_POST['paid_subscription'])) ? 'PAID' : NULL;
            $dataArr['free_subscription'] = (isset($_POST['free_subscription'])) ? 'FREE' : NULL;
            $dataArr['invDue'] = (isset($_POST['invDue'])) ? 'DUE' : NULL;
            $dataArr['userEmail']=$this->post('userEmail');

            if(strtotime($dataArr['reportStartDate']) > strtotime($dataArr['reportEndDate'])){
                PageContext::$response->error_message = "End date should be greater than start date";
                PageContext::addPostAction('errormessage', 'index');
                $error = TRUE;
                $dataArr = array();
            }else{
                $error = FALSE;
            }   
       }else{
           $dataArr['reportStartDate']  = $startDate!='x'?str_replace('-','/',$startDate):NULL;
           $dataArr['reportEndDate']    = $endDate!='x'?str_replace('-','/',$endDate):NULL;
           $dataArr['product']          = $product;
          // $subscription =  explode("-", $subscriptionType);
           Logger::info($subscriptionType);
           
            if(strstr($subscriptionType,'DUE'))
                $dataArr['invDue'] = 'DUE';
            else
               $dataArr['invDue'] = NULL;
            if(strstr($subscriptionType,'PAID'))
                $dataArr['paid_subscription'] =  'PAID';
            else
               $dataArr['paid_subscription'] = NULL;
            if(strstr($subscriptionType,'FREE'))
                $dataArr['free_subscription'] =  'FREE';
            else
               $dataArr['free_subscription'] = NULL;
           
           $dataArr['userEmail'] = $userEmail;
       }
           
       $invReports  = Admincomponents::getInvoices($dataArr, $limit);
       $pageInfoArr = Utils::pageInfo($page, count($invReports), PAGE_LIST_COUNT);
       //echopre($pageInfoArr);
       $limit       = $pageInfoArr['limit'];
       $invReports  = Admincomponents::getInvoices($dataArr, $limit);
       $this->view->pageInfo = $pageInfoArr;
       $this->view->productsList = Admincomponents::fetchProductsList();
       $this->view->pageContents = $invReports;
       $this->view->dataArr = $dataArr;
       Logger::info($this->view->productsList);
       $this->view->userEmailList = Admincomponents::getUserEmailList();
       Logger::info($this->view->userEmailList);
       PageContext::$response->activeLeftMenu = 'Invoice';
       $this->view->setLayout("home");
    }

    public function invoicedetails($idInvoice = NULL){
        PageContext::addStyle("invoice.css");
        $this->view->dataArr = Admincomponents::getInvoiceDetails($idInvoice);
        $this->view->dataDomArr = Admincomponents::getInvoiceDomainDetails($idInvoice);
        Logger::info($this->view->dataArr);
        $this->view->pageTitle = 'Invoice Details';
        PageContext::$response->activeLeftMenu = 'Invoice';
        $this->view->setLayout("home");
    } // End Function

    public function exportReport($data = NULL){
        $this->view->disableLayout();
        $this->view->disableView();
        $dataArr = Utils::unserializeNdecodeArr($data);
        $invReports = Admincomponents::getInvoices($dataArr);
        $fieldValues = array();
        $fieldHeaders = array ("Sl No.","Invoice No.", "Product", "Subscription Type", "User", "Generated On", "Due On", "Paid On", "Payment Status" , "Total Amount");
        $i =0;
        foreach($invReports as $pageItem) {
            ++$i;
            $fieldValues[] = array($i,
                                $pageItem->vInvNo,
                                $pageItem->vPName,
                                $pageItem->vSubscriptionType,
                                $pageItem->vEmail,
                                Utils::formatDate($pageItem->dGeneratedDate),
                                Utils::formatDate($pageItem->dDueDate),
                                Utils::formatDate($pageItem->dPayment),
                                Admincomponents::getInvoicePaymentStatus($pageItem->currentDate, $pageItem->dDueDate, $pageItem->dPayment),
                                CURRENCY_SYMBOL.Utils::formatPrice($pageItem->nTotal) );
        }
        
        Utils::doExcelExport($fieldHeaders, $fieldValues);
    }
    public function  __destruct() {
       
    }
}
?>
