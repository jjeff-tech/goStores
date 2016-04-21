<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ControllerReports extends BaseController {
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

    public function index($startDate = NULL, $endDate= NULL, $product=NULL , $subscriptionType= NULL, $page=NULL){
     
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("jquery.metadata.js");
        PageContext::addStyle("jquery-ui-1.8.23.custom.css");
        PageContext::addScript("admin.js");
        $this->view->pageTitle = 'Reports';

       if($this->isPost()){
            $this->view->startDate = $this->post('reportStartDate');
            $this->view->endDate = $this->post('reportEndDate');
            $this->view->product = $this->post('product');
            if($this->post('free_subscription')=='FREE' && $this->post('paid_subscription')=='PAID')
                $this->view->subscriptionType =  'all';
            else if($this->post('free_subscription')=='FREE' || $this->post('paid_subscription')=='PAID')
                $this->view->subscriptionType = $this->post('free_subscription')?$this->post('free_subscription'):$this->post('paid_subscription');
            else
                 $this->view->subscriptionType =  'all';

            if(strtotime($this->view->startDate)>strtotime($this->view->endDate)){
                PageContext::$response->error_message = "End date should be greater than start date";
                PageContext::addPostAction('errormessage', 'index');
                $error = TRUE;
            }else{
                $error = FALSE;
            }

            
       }else{
            $this->view->startDate = str_replace('-','/',$startDate);
            $this->view->endDate = str_replace('-','/',$endDate);
            $this->view->product = $product;
            $this->view->subscriptionType =  $subscriptionType;
       }
       if(!$error){
            $reports = Admincomponents::generateReports($this->view->startDate, $this->view->endDate, $this->view->product, $this->view->subscriptionType);
            $pageInfoArr = Utils::pageInfo($page, count($reports), PAGE_LIST_COUNT);
            $limit = $pageInfoArr['limit'];
            $this->view->reports = Admincomponents::generateReports($this->view->startDate, $this->view->endDate, $this->view->product, $this->view->subscriptionType,$limit);
            $this->view->pageInfo = $pageInfoArr;
       }
       $this->view->productsList = Admincomponents::fetchProductsList();
       Logger::info($this->view->productsList);
       PageContext::$response->activeLeftMenu = 'Reports';
       $this->view->setLayout("home");
    }

    public function exportReport($startDate = NULL, $endDate= NULL, $product=NULL , $subscriptionType= NULL, $page=NULL){

        $startDate = str_replace('-','/',$startDate);
        $endDate = str_replace('-','/',$endDate);
        $fieldHeaders = array ("Sl No","Product", "Subscription Type", "Subscriptions" , "Total Amount");
        $reports = Admincomponents::generateReports($startDate,  $endDate, $product, $subscriptionType);
        $i = 0;
        foreach($reports as $pageItem) {
            ++$i;
            $fieldValues[] = array($i,
                                $pageItem->vPName,
                                $pageItem->vSubscriptionType,
                                $pageItem->invoice_count,
                                CURRENCY_SYMBOL.Utils::formatPrice($pageItem->toal_amount) );
        }
        Utils::doExcelExport($fieldHeaders, $fieldValues);
    }
    public function  __destruct() {
       
    }
}
?>
