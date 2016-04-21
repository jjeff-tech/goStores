
<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | This page is for generating coupons in the system.                    |
// | File name : coupon.php                                                |
// | PHP version >= 5.2                                                    |
// | Created On	: 	Aug 30 2012//                                      |
// | Author : Vijay C <vijay.c@armiasystems.com>                           |

// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +------------------------------------------------------
class ControllerCoupon extends BaseController {
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
        PageContext::addScript("admin.js");
        PageContext::$response->activeLeftMenu='Coupons';
        /************* Left Menu Area Ends *******/
		PageContext::addStyle("admin_style.css");
          
    }

    /*
    function to load the index template
    */
    public function index($page = NULL) {
        //  $this->view->message      =   LibSession::get('message');        
        PageContext::addScript("jquery.addplaceholder.min.js");
        LibSession::delete('message');
         $session = new LibSession();

       
        if($session->get('coupon_add_success') == 'success'){
            
             PageContext::$response->success_message ='Coupon created successfully';
             PageContext::addPostAction('successmessage', 'index');
             $session->delete('coupon_add_success');
        }else if($session->get('coupon_edit_success') == 'success'){
             PageContext::$response->success_message ='Coupon changes updated successfully';
             PageContext::addPostAction('successmessage', 'index');
             $session->delete('coupon_edit_success');
        }else if($session->get('coupon_delete_success') == 'success'){
             PageContext::$response->success_message ='Coupon deleted successfully';
             PageContext::addPostAction('successmessage', 'index');
             $session->delete('coupon_delete_success');
        }

        if($this->isPost()) {
            if($_POST['action']=='search') {
                $txtSearch = $_POST['search'];
                 $this->view->txtSearch = $txtSearch;
                $searchArr = array(array('field' => 'vCouponCode', 'value' => $txtSearch));
            }
        }

        $pageContentArr = Admincomponents::getCoupon($txtSearch, $orderArr, $limit);
        $pageInfoArr = Utils::pageInfo($page, count($pageContentArr), PAGE_LIST_COUNT);

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        
        $this->view->showCoupons  = Admincomponents::getCoupon($txtSearch, $orderArr, $limit);
        $this->view->setLayout("home");
        
    }


    /*
    function to create new coupon
    */
    public function createcoupon($couponId=0) {
        PageContext::addScript('admin.js');
        $this->view->setLayout("home");
        PageContext::addJsVar('CURRENCY', CURRENCY_SYMBOL);
        //
        $session = new LibSession();
        if($couponId){
            $this->view->pageTitle = 'Edit Coupon';
        }else{
            $this->view->pageTitle = 'Add Coupon';
        }
        PageContext::addStyle("jquery-ui-1.8.23.custom.css");
        PageContext::addScript("jquery-1.8.0.min.js");
        PageContext::addScript("jquery-ui-1.8.23.custom.min.js");
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript('adcoupon.js');
        $this->view->coupon->vPricingMode = 'rate';
        if($couponId>0)
        {
            $this->view->coupon     = Admincomponents::getCouponDetails($couponId);
            $this->view->coupon     = $this->view->coupon[0];
            $this->view->couponId   = $couponId;
        }
        else {
            if($this->isPost()) {
                if($this->post('Update_Coupon'))
                {
                     
                     $couponName      = addslashes(($this->post('txtCouponName')!='') ? $this->post('txtCouponName') :$this->get('txtCouponName'));
                   
                     $couponId      = addslashes(($this->post('txtCouponId')!='') ? $this->post('txtCouponId') :$this->get('txtCouponId'));
                     $noOfCoupons   = addslashes(($this->post('txtNoOfCoupons')!='') ? $this->post('txtNoOfCoupons') :$this->get('txtNoOfCoupons'));
                     $expiryDate    = addslashes(($this->post('txtExpiryDate')!='') ? $this->post('txtExpiryDate') :$this->get('txtExpiryDate'));
                     $discountRate  = addslashes(($this->post('txtDiscountRate')!='') ? $this->post('txtDiscountRate') :$this->get('txtDiscountRate'));
                     $description   = addslashes(($this->post('txtDescription')!='') ? $this->post('txtDescription') :$this->get('txtDescription'));                   
                     $pricingMode = addslashes(($this->post('pricingMode')!='') ? $this->post('pricingMode') :$this->get('pricingMode'));
                     
                        $status        = Admincomponents::updateCoupon($noOfCoupons,$expiryDate,$discountRate,$description,$couponId,$couponName ,$pricingMode);
                        $this->view->message = 'Coupon updated successfully';
                        $session->set('coupon_edit_success','success');
                        LibSession::set('message',$this->view->message);
                        $this->redirect('coupon/index');
                         die;
                        $this->view->coupon     = Admincomponents::getCouponDetails($couponId);
                        $this->view->coupon     = $this->view->coupon[0];
                        $this->view->couponId   = $couponId;
                    
                    
                }
                else
                {
                     $couponName    = addslashes(($this->post('txtCouponName')!='') ? $this->post('txtCouponName') :$this->get('txtCouponName'));
                     $noOfCoupons   = addslashes(($this->post('txtNoOfCoupons')!='') ? $this->post('txtNoOfCoupons') :$this->get('txtNoOfCoupons'));
                     $expiryDate    = addslashes(($this->post('txtExpiryDate')!='') ? $this->post('txtExpiryDate') :$this->get('txtExpiryDate'));
                     $discountRate  = addslashes(($this->post('txtDiscountRate')!='') ? $this->post('txtDiscountRate') :$this->get('txtDiscountRate'));
                     $description   = addslashes(($this->post('txtDescription')!='') ? $this->post('txtDescription') :$this->get('txtDescription'));
                     $pricingMode = addslashes(($this->post('pricingMode')!='') ? $this->post('pricingMode') :$this->get('pricingMode'));
                     $status        = Admincomponents::createCoupon($noOfCoupons,$expiryDate,$discountRate,$description, $couponName, $pricingMode);

                     if($status)
                     {
                         $session = new LibSession();
                         $session->set('coupon_add_success','success');
                         $this->view->message = 'Coupon created successfully';
                         LibSession::set('message',$this->view->message);
                         $this->redirect('coupon/index');
                         die;
                     }
                     else{
                         PageContext::$response->error_message = 'Coupon Creation failed.Please try after sometime.';
                         PageContext::addPostAction('errormessage', 'index');
                         $this->view->message = 'Coupon Creation failed.Please try after sometime.';
                     }
                }
            }
        }
    }

    public function coupondelete($couponId)
    {
        $status        = Admincomponents::deleteCoupon("nCouponId='$couponId'");
        $this->view->message = 'Coupon deleted successfully';
        LibSession::set('message',$this->view->message);
        $this->disableLayout();
        $session = new LibSession();
        $session->set('coupon_delete_success','success');
        $this->redirect('coupon/index');
        exit;
    }

    public function generateCouponCode(){
        echo Admincomponents::generateCouponCode();
    }

}

?>