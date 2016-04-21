<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php

class ControllerPostaction extends BaseController {
    /*
      construction function. we can initialize the models here
     */

    public function init() {
        parent::init();
        PageContext::$body_class = "home";
        PageContext::addJsVar("createnewsletter", BASE_URL . "index/createnewsletter/");
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("general.js");
        PageContext::addJsVar('userLogin', BASE_URL . "index/userlogin/");
        PageContext::addJsVar('loginSuccess', BASE_URL . "user/dashboard/");
        PageContext::addScript("dropmenu.js");
        PageContext::addScript("dropmenu_ready.js");
        PageContext::addStyle("dropdown_style.css");
        //PageContext::addScript("jquery.min.js");
        PageContext::addScript('jquery.timer.js');
        PageContext::addScript("login.js");
        PageContext::addScript("hoverIntent.js");
        //PageContext::addScript("jquery-1.2.6.min.js");
        PageContext::addScript("superfish.js");
        User::googleAnalytics();
        PageContext::addStyle("custom_progress/progress.css");
        PageContext::$headerCodeSnippet = "<script>
  (function() {
    var cx = '001817314419920192210:y32ewf88ube';
    var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);
  })();
</script>";
    }

    /*
      function to load the index template
     */

     //functionality to load cloud top menu
    public function testpostaction() { //echo 'here';exit;
                $this->view->setLayout("home");
              PageContext::addPostAction('paypalpro','payments');
              PageContext::addPostAction('paypalflow','payments');
              

          
            
    }
}// End calss