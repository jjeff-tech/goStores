<?php
//route configuration
Router::alias(":alias", "(.*)");
Router::alias(":static", "(aboutus|support|features|help|sitecontent|support|disclaimernotice|faq|policy|customization|howitworks)");
Router::alias(":thankspage", "(thankyousignup)");

Router::connect("cms","cms/cms/");
Router::connect("cms/","cms/cms/");
Router::connect("cms/developer/", "cms/cms/");
Router::connect("cms/developer", "cms/cms/");
Router::connect("cms/:alias", "cms/$1");

Router::connect("paynow","index/paynow");
Router::connect("help","index/help");
Router::connect("plan","index/plan");
Router::connect("screenshots","index/screenshots");
Router::connect("forgotpwd","index/forgotpwd");
Router::connect("freetrial","index/freetrial");

Router::connect(":static", "index/staticpages/$1");

Router::connect("twocheckout","payments/twocheckout");
Router::connect("contactus","index/contactus");

Router::connect("signup", "index/signup");
Router::connect("signin", "index/signin");
Router::connect(":thankspage", "index/thankyou/$1");
Router::connect("templates", "index/templates");
Router::connect("templates/:alias", "index/templatedetails/$1");
Router::connect("buytemplate/:alias", "index/buytemplates/$1");
Router::connect("storedemo", "index/storedemo");

Router::connect("dashboard/","user/dashboard");

Router::connect("fw", "framework");
Router::alias(":error", "error");
Router::connect(":error", "index/error");
?>
