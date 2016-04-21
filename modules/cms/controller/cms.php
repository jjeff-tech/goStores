<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | Framework Main Controller			                                          |
// | File name :Index.php                                                 |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems Ã¯Â¿Â½ 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class ControllerCms extends BaseController {
    /*
		construction function. we can initialize the models here
    */
    public function init() {
        parent::init();
        PageContext::$isCMS = true;

        $this->view->setLayout("home");
        PageContext::$body_class 	 = 'cms';
        $base_dir   =   'modules/cms/';
        $dir_path   =  "logics";
        $directory = $base_dir.$dir_path;
        if(is_dir($directory)) {
            if ($handle = opendir($directory)) {
                while (false !== ($file = readdir($handle))) {

                    if ($file == "." && $file == "..") continue;
                    if(is_dir($directory.'/'.$file))continue;
                    $path_parts = pathinfo($directory.'/'.$file);
                    if(!$path_parts || $path_parts['extension']	!=	'php')continue;
                    include_once($directory.'/'.$file);
                }
                closedir($handle);
            }
        }


        //PageContext::$response->menu =  Cms::loadMenu();
        PageContext::$response->cmsSettings    =  Cms::getCmsSettings();
        if(PageContext::$response->cmsSettings['site_logo'])
            $siteLogo                =   call_user_func(PageContext::$response->cmsSettings['site_logo']);
        PageContext::$response->siteLogo =  $siteLogo;
        PageContext::$enableBootStrap=true;
        PageContext::$metaTitle = META_TITLE." : Admin ";
        PageContext::addJsVar("formError", 0);
        $cmsLayoutSectionData= Cms::getlayoutSectionData();
        $cmsLayoutSectionConfig =   $cmsLayoutSectionData->section_config;

        $cmsLayoutSectionConfig= json_decode($cmsLayoutSectionConfig);

        foreach($cmsLayoutSectionConfig->headerLinks as $key=>$links) {
            $cmsLayoutSectionConfig->headerLinks->$key->link = call_user_func($links->linkSource);

        }
        PageContext::$response->headerLinks =  $cmsLayoutSectionConfig->headerLinks;
        $session    =   new LibSession();
        $cmsUsername = $session->get("cms_username");
        PageContext::$response->cmsUsername =  $cmsUsername;
        $date_separator =  GLOBAL_DATE_FORMAT_SEPERATOR;
        if($date_separator!="GLOBAL_DATE_FORMAT_SEPERATOR") {

        	$jsDateFormat = "mm".$date_separator."dd".$date_separator."yy";
        }
        else {
        	$date_separator = "-";
        	$jsDateFormat = "mm".$date_separator."dd".$date_separator."yy";
        }
        PageContext::addJsVar("date_separator", $jsDateFormat);
    }


    /*
     * License Key Check
    */
    function isValidLicense() {
    	$var_domain	= strtoupper(trim($_SERVER['HTTP_HOST']));
    	if($var_domain == '192.168.0.11' || $var_domain == 'LOCALHOST' || $var_domain == '127.0.0.1' ||  $var_domain == 'DEV.ISCRIPTS.COM' ||  $var_domain == 'MAKEREADYARMSS.COM' ||  $var_domain == 'MAKEREADYARMS.COM'){
    		return true;
    	}
    	else {
    		$is_valid = License::FCE74825B5A01C99B06AF231DE0BD667D($var_domain);
    		return $is_valid;
    	}
    }

    public function invalidlicense() {

    	PageContext::$response->errorMsg = "<b>Invalid License Key!<br/> Please Enter A Valid License Key.</b>";
    	if($_REQUEST['submit']) {
    		$license    =   $_REQUEST['inputlicense'];
    		$password   =   md5($_REQUEST['password']);
    		$res    =   License::FB65FDD43B9A0C83B8499D74B1A31890A($password);
    		if(count($res)>0) {
    			License::F03FD063C610FFF78F127C6DCC52A6524($license);
    		}
    		header("location: ".BASE_URL."cms");
    		exit;
    	}
    }


    /*
    function to load the index template
    */
    public function index() {

    	if( defined('PRODUCT_INSTALLER')) {
    		// License check
    		if(!$this->isValidLicense()) {
    			PageContext::AddPostAction("invalidlicense","cms","cms");
    			PageContext::$response->invalidLicense  =   1;
    			return;
    		}
    	}
        /* Autologin Starts */

        $url="https://iscripts.com/gostores/demo.php";
        //$url="http://demo.iscripts.com/demo.php";

        if(strcmp(str_replace("www.","",$_SERVER['HTTP_REFERER']),$url)==0)

        {

           $session    =   new LibSession();

           $session->set("admin_logged_in","1");

           $session->set("admin_type","admin");

           $session->set("cms_username","admin");

           $session->set("role_id",10);

        }

        /*Autologin ends*/
        if(CMS_ROLES_ENABLED)
            $roleEnabled    =   1;
//  /PageContext::addScript("bootstrap-modal.js");

        if(!$this->checkLogin()) {
            PageContext::AddPostAction("login","cms","cms");
            return;
        }

        if($roleEnabled) {

            $session    =   new LibSession();
            $roleId = $session->get("role_id");




            if( $session->get('admin_type')!="developer") {
                //PageContext::$response->menu =  Cms::loadMenu();
                $parentRoleIDArray=  Cms::getParentRoleList($roleId);

                $parentRoleIDString  =  "" ;
                for($loop=0;$loop<count($parentRoleIDArray);$loop++) {
                    $parentRoleIDString  .=  $parentRoleIDArray[$loop].",";
                }
                $parentRoleIDString = substr($parentRoleIDString, 0, -1);

                $privilegedSections = Cms::getPrivilegedSections($parentRoleIDString);
                //print_r($privilegedSections);
                $privilegedSections = $privilegedSections.",";

                $privilegedGroups = Cms::getPrivilegedGroups($parentRoleIDString);

                $privilegedGroups = $privilegedGroups.",";
                $menuList=  Cms::getprivilegedMenuList($roleId,$parentRoleIDArray);

                $sectionId=  Cms::getSectionId(PageContext::$request['section']);
                if(PageContext::$request['section']=="") {
                    $defaultMenu    =   Cms::loadDefaultMenu($roleId,$parentRoleIDArray);
                    $sectionId=  Cms::getSectionId($defaultMenu->section_alias);



                }
                $groupId=  Cms::getGroupId($sectionId);

                if(substr_count( $privilegedSections,$sectionId.",") || substr_count( $privilegedGroups,$groupId.",")) {
                    PageContext::$response->illegal =  1;
                    PageContext::addPostAction("permission","cms","cms");
                }
                else if(!count($menuList)) {
                    PageContext::addPostAction("permission","cms","cms");
                    PageContext::$response->illegal =  1;
                }
            }
            else {
                $menuList =  Cms::loadMenu();

            }
            //echopre($menuList);
            $menuListCount   = count($menuList);
            PageContext::$response->menu =  $menuList;
            PageContext::$response->menuCount =  $menuListCount;
            //getprivilegedMenuList
            PageContext::$response->cmsSettings    =  Cms::getCmsSettings();
            if(PageContext::$request['section']=="") {
                $defaultMenu    =   Cms::loadDefaultMenu($roleId,$parentRoleIDArray);

                PageContext::$request['section']=$defaultMenu->section_alias;
                PageContext::$response->defaultSection=$defaultMenu->section_alias;


            }
        }
        else {
            $menuList =  Cms::loadMenu();
            if(PageContext::$request['section']=="") {
                $defaultMenu    =   Cms::loadDefaultMenu();

                PageContext::$request['section']=$defaultMenu->section_alias;
                PageContext::$response->defaultSection=$defaultMenu->section_alias;

            }
        }

        PageContext::$response->menu =  $menuList;
        PageContext::$response->menuCount =  $menuListCount;

        //
        // to find whether custom post action or not
        $sectionData    =   Cms::getSectionData(PageContext::$request);
        PageContext::addJsVar("requestHeader", PageContext::$request['section']);
        $sectionConfig  =   json_decode($sectionData->section_config);

        //including js files
        foreach($sectionConfig->includeJsFiles as $files){

            PageContext::addScript($files);
        }
        $customCmsAction    =   0;
        if($sectionConfig->customCmsAction) {
            $customCmsAction =   1;

        }
        if($sectionConfig->customAction) {
            $isCustomAction =   1;

        }
        else
            $isCustomAction =   0;
        // if json is invalid

        if ($sectionConfig === null ) {
            $isCustomAction =   2;
            PageContext::$response->errorMessage  = "Invalid section alias or config ...";

        }
        //checking privileges
        $hasPrivileges  =   Cms::hasSectionPrivileges(PageContext::$request['section']);
        if($hasPrivileges   ==  0) {
            $isCustomAction =   2;
            PageContext::$response->errorMessage  = "Illegal access ...";
        }
        PageContext::$response->isCustomAction=$isCustomAction;
        //if no section default to first item in the section list.
        if(($isCustomAction==0 && PageContext::$request['section']) || $customCmsAction==1) {


            if($sectionConfig->siteSettings) {
                PageContext::$response->settingsTab    =   1;
                PageContext::addPostAction("settings","cms","cms");

            }
            else if($sectionConfig->dashboardPanel) {
                PageContext::$response->dashboardPanel    =   1;
                PageContext::addPostAction("dashboard","cms","cms");
            }
            else if($customCmsAction==1) {
                PageContext::$response->customCmsAction    =   1;
                $customActionModule         =   $sectionConfig->module;
                $customActionController     =   $sectionConfig->controller;
                $customActionMethod         =   $sectionConfig->method;
                PageContext::$response->customActionModule      =   $customActionModule;
                PageContext::$response->customActionController  =   $customActionController;
                PageContext::$response->customActionMethod      =   $customActionMethod;
                PageContext::addPostAction($customActionMethod,$customActionController,$customActionModule);
            }
            else {
                $postAction =   'sectionlisting';
                PageContext::addPostAction($postAction,"cms","cms");
                PageContext::$response->postAction=$postAction;
                // for bread crumb
                Cms::getBreadCrumb(PageContext::$request);
                $currentURL=Cms::formUrl(PageContext::$request,$sectionConfig);
                PageContext::$response->currentURL     =   $currentURL;
                PageContext::addJsVar("currentURL", $currentURL);
            }

        }
        else if(($isCustomAction    ==  1 )) {

            //PageContext::$response->customAction     =   $customActionArray->custom_action;
            $customActionModule         =   $sectionConfig->module;
            $customActionController     =   $sectionConfig->controller;
            $customActionMethod         =   $sectionConfig->method;
            PageContext::$response->customActionModule      =   $customActionModule;
            PageContext::$response->customActionController  =   $customActionController;
            PageContext::$response->customActionMethod      =   $customActionMethod;
            PageContext::addPostAction($customActionMethod,$customActionController,$customActionModule);
        }
        else if($isCustomAction ==  2 ) {
            $postAction =   'errordisplay';
            PageContext::addPostAction($postAction);

            PageContext::$response->postAction=$postAction;
        }




    }

    //TODO: temperory login logic need to refine this to a complete admin login functionality after first milestone
    public function login() {
       // echo "login functn";exit;
        $session    =   new LibSession();

        if(CMS_ROLES_ENABLED)
            $roleEnabled    =   1;
        if($_REQUEST['submit']) {
            $username   =   $_REQUEST['username'];
            $password   =   md5($_REQUEST['password']);
            if($roleEnabled) {
                $res    =   Cms::checkLogin($username,  $password,$roleEnabled);

                if(count($res)>0) {

                    if($res->type   ==   "admin")
                        $session->set("admin_type","admin");

                    if($res->type   ==   "sadmin")
                        $session->set("admin_type","sadmin");

                    $session->set("admin_logged_in","1");
                    $session->set("cms_username",$username);
                    $session->set("role_id",$res->role_id);

                    header("location: ".BASE_URL."cms");
                    exit;
                }
                else {
                    PageContext::$response->errorMsg = "Invalid Login!";
                }
            }
            else {
                $res    =   Cms::checkLogin($username,  $password);
                if(count($res)>0) {

                    if($res->type   ==   "admin")
                        $session->set("admin_type","admin");
                    if($res->type   ==   "sadmin")
                        $session->set("admin_type","sadmin");
                    $session->set("admin_logged_in","1");
                    $session->set("cms_username",$username);
                    header("location: ".BASE_URL."cms");
                    exit;
                }
                else {
                    PageContext::$response->errorMsg = "Invalid Login!";
                }
            }
        }

    }
    public function developer() {
        $session    =   new LibSession();
        if($_REQUEST['submit']) {
            $username   =   $_REQUEST['username'];
            $password   =   $_REQUEST['password'];

            if($username==CMS_DEVELOPER_USERNAME && $password==CMS_DEVELOPER_PASSWORD) {
                $session->set("admin_type","developer");
                $session->set("admin_logged_in","1");
                $session->set("cms_username",$username);
                header("location: ".BASE_URL."cms");
                exit;
            }
            else {
                PageContext::$response->errorMsg = "Invalid Login!";
            }
        }

    }
    public function settings() {

        //to get section config values
        $sectionData= Cms::getSectionData(PageContext::$request);
        PageContext::$response->message =   PageContext::$request['message'];
        $sectionConfig=$sectionData->section_config;
        $sectionConfig=json_decode($sectionConfig);
        $currentURL=Cms::formUrl(PageContext::$request,$sectionConfig);
        PageContext::$response->settingStyle = $sectionConfig->settingStyle;
        if($sectionConfig->settingStyle=="tab") {
            // $settingsValueArray  =   Cms::getSettingsTableData();

            $settingstabsArray    = Cms::getSettingsTabs($sectionData->table_name);
            $settingsTabs   =   array ();
            $loop   =   0;
            foreach ($settingstabsArray as $tab) {
                if($tab->groupLabel) {
                    $settingsTabs[$loop]->label             =    $tab->groupLabel;
                    $tabId                                  =   Cms::getSettingsTabId($tab->groupLabel,$sectionData->table_name);
                    $tabContent                             =   $tabId."List";
                    $tabContent                             =   Cms::getSettingsTableData($tab->groupLabel,$sectionData->table_name);
                    $cstLoop =  0;
                    foreach($tabContent as $column) {

                        foreach($sectionConfig->customColumns as $custKey=>$custVal) {

                            if($custKey==$column->settingfield) {
                                $tabContent[$cstLoop]->customColumn = $custVal;

                            }
                        }
                        $cstLoop++;
                    }

                    $settingsTabs[$loop]->tabContent        =   $tabContent;
                    $settingsTabs[$loop]->id                =   $tabId;
                    $loop++;
                }


            }

            if($sectionConfig->customColumns) {

            }
            PageContext::$response->sectionName = $sectionData->section_name;
            PageContext::$response->settingsTabs = $settingsTabs;

            if(isset($_POST['submit'])) {
                $postArray   =    PageContext::$request;
                foreach ($settingstabsArray as $tab) {

                    foreach($tabContent as $column) {
                        $tabId                                  =   Cms::getSettingsTabId($tab->groupLabel,$sectionData->table_name);
                        $tabContent                             =   $tabId."List";
                        $tabContent                             =   Cms::getSettingsTableData($tab->groupLabel,$sectionData->table_name);
                        $cstLoop =  0;
                        foreach($sectionConfig->customColumns as $custKey=>$custVal) {

                            if($custKey==$column->settingfield) {
                                if($column->type=="checkbox") {
                                    if(key_exists($custKey, PageContext::$request)) {
                                        $postArray[$custKey]=$custVal->checked;
                                    }
                                    else
                                        $postArray[$custKey]=$custVal->unchecked;

                                }


                            }
                        }
                        $cstLoop++;
                    }

                }
                Cms::saveSettings($postArray,$sectionData->table_name);


                $sucessMessage    =   "Settings edited successfully";

                header("Location:$currentURL&message=$sucessMessage");
                exit;

            }


        }
    }

    public function dashboard() {
        //to get section config values
        $sectionData= Cms::getSectionData(PageContext::$request);
        PageContext::$response->message =   PageContext::$request['message'];
        $sectionConfig=$sectionData->section_config;
        $sectionConfig=json_decode($sectionConfig);
        //echopre($sectionConfig);

        //if listing panel enabled

        if($sectionConfig->listingPanel) {
            $listPanelRowCount   =   $sectionConfig->listinPanelRow;
            PageContext::$response->listinPanelRow   =   $listPanelRowCount;
            for($rowLoop=1;$rowLoop<=$listPanelRowCount;++$rowLoop) {

                $listingId =   "listingPanel".$rowLoop;
                $panelConfig =   $sectionConfig->$listingId;


                $columnCount =   $panelConfig->columns;
                $listingPanel->columnCount    =   $columnCount;

                $listingPanelArray[$rowLoop]->columnCount  =  $columnCount;

                //echopre( $graphPanelArray[$rowLoop]);
                for($columnLoop=1;$columnLoop<=$columnCount;$columnLoop++) {
                    $panelId                =   "column".$columnLoop;
                    $listingPanelConfig     =   $panelConfig->$panelId;

                    $listData[$rowLoop][$columnLoop]                =   call_user_func($listingPanelConfig->fetchValue);
                    $listTitleLink[$rowLoop][$columnLoop]                =   call_user_func($listingPanelConfig->titlelinkSection);
                    $listingDataColumns[$rowLoop][$columnLoop]      =   $listingPanelConfig;

                }
            }

            PageContext::$response->listDataArray =     $listData;
            PageContext::$response->listTitleLink =     $listTitleLink;
            PageContext::$response->panelConfig =     $listingDataColumns;
            PageContext::$response->listingPanels =       $listingPanelArray;
        }
        //if graph panel enabled
        PageContext::$response->sectionName = $sectionData->section_name;
        PageContext::$metaTitle .= " | ".$sectionData->section_name;

  /*  if($sectionConfig->graphPanel) {

            PageContext::$enableFusionchart=true;
            include('project/lib/fusioncharts/Class/FusionCharts_Gen.php');

            $graphRowCount   =   $sectionConfig->graphpanelRow;
            PageContext::$response->graphRowCount   =   $graphRowCount;
            for($rowLoop=1;$rowLoop<=$graphRowCount;++$rowLoop) {

                $panelId =   "graphPanel".$rowLoop;
                $panelConfig =   $sectionConfig->$panelId;
                //echopre($panelConfig);
                $columnCount =   $panelConfig->columns;
                $graphPanel->columnCount    =   $columnCount;

                $graphPanelArray[$rowLoop]->columnCount  =  $columnCount;
                //echopre( $graphPanelArray[$rowLoop]);
                for($columnLoop=1;$columnLoop<=$columnCount;$columnLoop++) {
                    $graphConfig = new stdClass();
                    $grpahId        =   "graph".$columnLoop;
                    $graphConfig    =   $panelConfig->$grpahId;

                    $startDate   = date('Y-m-d',strtotime("-1 week"));
                    $endDate     = date('Y-m-d');
                   // echopre($graphConfig);
                    $graphObj  = Graph::plotGraph($startDate,$endDate,$graphConfig);
                    $graphObjArray[$rowLoop][$columnLoop]=$graphObj;
                    $graphPanelConfigArray[$rowLoop][$columnLoop]  = $graphConfig;

                }


            }


            PageContext::$response->graphPanelsConfig =     $graphPanelConfigArray;
            PageContext::$response->graphObjArray =     $graphObjArray;
            PageContext::$response->graphPanels =       $graphPanelArray;
            //echopre($graphPanelConfigArray);
            //echopre($graphPanelArray);

        }*/

         // if($sectionConfig->graphPanel) {

         //    $graphOb  =  Graph::getDomains($startDate,$endDate,$graphConfig);

         //     }
         // echopre($graphOb);
        if($sectionConfig->graphPanel) {

            PageContext::$enableFusionchart=true;
            include('project/lib/fusioncharts/Class/FusionCharts_Gen.php');

            $graphRowCount   =   $sectionConfig->graphpanelRow;
            PageContext::$response->graphRowCount   =   $graphRowCount;

                    $startDate   = date('Y-m-d',strtotime("-1 week"));
                    $endDate     = date('Y-m-d');

                    $from_date = new DateTime($startDate);
                    $to_date = new DateTime($endDate);
                    $catName = array();
                    for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
                              $catName[] = $date->format('D');
                        }


                         //$catNames  = '"'.implode('","',$catName).'"';
                         $catNames =   '"' . implode ( '", "', $catName ) . '"';
                        // echo $catNames;

        $graphObj1  = Graph::getDomains($startDate,$endDate,"Admin::getRegistredDomainCount");
        $graphObj1 =  implode(",",$graphObj1[0]);
        $graphObj2  = Graph::getUsers($startDate,$endDate,"Admin::getUsersCount");
        $graphObj2  = implode(",",$graphObj2[0]);
        $graphObj3  = Graph::getStores($startDate,$endDate,"Admin::getStoresCount");
        $graphObj3  = implode(",",$graphObj3[0]);
        $graphObj4  = Graph::getFreetrials($startDate,$endDate,"Admin::getFreeTrialsCount");
        $graphObj4  =  implode(",",$graphObj4[0]);

        PageContext::$response->categoryNames   = $catNames;
        PageContext::$response->graphDomains    = $graphObj1;
        PageContext::$response->graphUsers      = $graphObj2;
        PageContext::$response->graphStores     =$graphObj3;
        PageContext::$response->graphFreetrials =$graphObj4;


        }
        $currentURL=Cms::formUrl(PageContext::$request,$sectionConfig);

    }
    public function logout() {
        $session    =   new LibSession();
        $session->set("admin_logged_in","");
        $session->set("admin_type","");
        $session->set("cms_username","");

        if(CMS_ROLES_ENABLED)
            $roleEnabled    =   1;
        if($roleEnabled)
            $session->set("role_id","");
        header("location: ".BASE_URL."cms");
    }

    //check wether the admin is logged in or not
    public function checkLogin() {
        $session    =   new LibSession();
        if(!$session->get("admin_logged_in"))
            PageContext::$response->logged_in = 0;
        else
            PageContext::$response->logged_in = true;

        return PageContext::$response->logged_in;
    }
    // function for displaying invalid json format
    public function errordisplay() {

    }
    /*
      function to display section data
    */
    public function sectionlisting() {

        if(CMS_ROLES_ENABLED)
            $roleEnabled    =   1;
        $perPageSize  =   PageContext::$response->cmsSettings['admin_page_count'];

        $date_separator =    GLOBAL_DATE_FORMAT_SEPERATOR;
        if($date_separator!="GLOBAL_DATE_FORMAT_SEPERATOR")
        	$date_separator =  GLOBAL_DATE_FORMAT_SEPERATOR;
        else {
        	$date_separator = "-";
        }
        $currentDate    =   date("m".$date_separator."d".$date_separator."Y");
        $monthStartDate    =   date("m".$date_separator."d".$date_separator."Y",mktime(0,0,0,date("m"),1,date("Y")));

//         $currentDate    =   date("m-d-Y");
//         $monthStartDate    =   date("m-d-Y",mktime(0,0,0,date("m"),1,date("Y")));
        PageContext::$response->currentDate =   $currentDate;
        PageContext::$response->monthStartDate =   $monthStartDate;
        PageContext::addJsVar("formError", 0);
        PageContext::$enableFCkEditor=true;

        $errormessage = PageContext::$request['errormessage'];
        pageContext::$response->errorMessage          =   $errormessage;
        //to get section config values
        $sectionData= Cms::getSectionData(PageContext::$request);
        if(isset(PageContext::$request['parent_id'])) {
            $parentSectionData  = Cms::getParentSectionData(PageContext::$request);
            PageContext::$response->parentSectionName = $parentSectionData->section_name;
            PageContext::$metaTitle .= " | ".$parentSectionData->section_name;
            $parentSectionItem  =Cms::listParentItem($parentSectionData,PageContext::$request);
            PageContext::$response->parentBreadCrumbName = $parentSectionItem;
        }
        PageContext::$response->sectionName = $sectionData->section_name;
        PageContext::$metaTitle .= " | ".$sectionData->section_name;
        $sectionConfig=$sectionData->section_config;
        $sectionConfig=json_decode($sectionConfig);
        if ($sectionConfig === null ) {
            echo "<br>Error: Incorrect JSON Format<br><br>";

        }


        //to get list data for a particular section
        //external data source
        if($sectionConfig->dataSource) {
            $requestParams = PageContext::$request;
            $requestParams['perPageSize'] = $perPageSize;
            $listDataResults= call_user_func($sectionConfig->dataSourceFunction,$requestParams);

        }
        else
            $listDataResults= Cms::listData($sectionData,PageContext::$request,$perPageSize);


        //process listData for displaying in tpl
        //   echopre($listDataResults);
        $loopVar=0;
        $listData= array();
        foreach($listDataResults  as $record) {

            foreach($sectionConfig->detailColumns as $col) {
                foreach($sectionConfig->columns as $key =>  $val) {

                    if($col==$key) {

                        if($val->editoptions->type   ==   "file") {
                            $record->$col   =   Cms::getThumbImage($record->$col,60,60);
                        }

                        // if it is date, then convert it into a standard format
                        if($val->editoptions->type   ==   "datepicker") {
                            $record->$col   =   Cms::getTimeFormat($record->$col,$val->editoptions->dbFormat,$val->editoptions->displayFormat);
                        }
                        else  if($val->dbFormat) {

                            $record->$col   =   Cms::getTimeFormat($record->$col,$val->dbFormat,$val->displayFormat);
                        }
                        else if($val->editoptions->type   ==   "htmlEditor") {

                            $record->$col   =   htmlspecialchars_decode($record->$col);
                            $record->$col = str_replace("&#160;", "", $record->$col);
                        }

                        else if($val->customColumn) {

                            $columnName     =   $sectionConfig->keyColumn;
                            $primaryKeyValue         =   $record->$columnName;
                            $record->$col= call_user_func($val->customaction,$primaryKeyValue);
                            if($val->popupoptions) {
                                $functionName   =   $val->popupoptions->customaction;
                                $columnName     =   $sectionConfig->keyColumn;
                                $params['id']         =   $record->$columnName;
                                $params['value']         =   $record->$col;
                                $externalLink   =   call_user_func($functionName,$params);
                                $colValue=  substr($record->$col,0,30);


                                $record->$col =  "<button value='".$externalLink."' class='jqPopupLink btn btn-link' id='link_".$params['id']."' >".$colValue."</button>";


                            }


                        }
                        else {
                            if(trim($record->$col)!=   "" ) {

                                if($val->externalNavigation) {
                                    $functionName   =   $val->externalNavigationOptions->source;
                                    if($val->external)
                                        $columnName     =   "external_".$key;
                                    else
                                        $columnName     =   $sectionConfig->keyColumn;
                                    $params         =   $record->$columnName;
                                    $externalLink   =   call_user_func($functionName,$params);
                                    $colValue=  substr($record->$col,0,30);

                                    $record->$col =   "<a href='".$externalLink."' target='_blank'>".$colValue."</a>";
                                }
                                else if($val->listoptions) {
                                    $functionName   =   $val->listoptions->customaction;
                                    $columnName     =   $sectionConfig->keyColumn;
                                    $params['id']         =   $record->$columnName;
                                    $params['value']         =   $record->$col;
                                    $externalLink   =   call_user_func($functionName,$params);
                                    $colValue=  substr($record->$col,0,30);
                                    foreach($val->listoptions->enumvalues as $enumKey  => $enumValue) {
                                        $buttonColor  =   $val->listoptions->buttonColors->$enumKey;
                                        if($buttonColor=="green")
                                            $buttonClass  =   "btn-success";
                                        else if($buttonColor=="red")
                                            $buttonClass  =   "btn-danger";

                                        if($enumKey==$record->$col) {

                                            $record->$col =  $enumKey.'{cms_separator}<button value="'.$externalLink.'" id="button_'.$key.':'.$enumKey.':'.$params.'" class=" jqCustom btn btn-mini '.$buttonClass.'" type="button" >'.$enumValue.'</button>';
                                        }
                                    }
                                }
                                else if($val->popupoptions) {
                                    $functionName   =   $val->popupoptions->customaction;
                                    $columnName     =   $sectionConfig->keyColumn;
                                    $params['id']         =   $record->$columnName;
                                    $params['value']         =   $record->$col;
                                    $externalLink   =   call_user_func($functionName,$params);
                                    $colValue=  substr($record->$col,0,30);


                                    $record->$col =  "<button value='".$externalLink."' class='jqPopupLink btn btn-link' id='link_".$params['id']."' >".$colValue."</button>";


                                }
                                else if($val->decimalPoint) {
                                    $record->$col =  $val->prefix.number_format($record->$col,$val->decimalPoint).$val->postfix;
                                }
                                else
                                    $record->$col =  $val->prefix.html_entity_decode($record->$col).$val->postfix;
                            }
                            else
                                $record->$col = '<small class="muted">-</small>';
                        }
                        break;

                    }

                }
                foreach($sectionConfig->combineColumns as $key) {
                    if($col==$key) {
                        if(trim($record->$col)!=   "" )
                            $record->$col =   $record->$col;
                        else
                            $record->$col = '<small class="muted">-</small>';
                    }

                }


            }

            $listData[]=$record;

            foreach($sectionConfig->relations  as $key => $val) {

                $joinCount  =   Cms::getJoinResult($sectionData,$val,$record);
                $listData[$loopVar]->$key   =   $joinCount;

            }


            $loopVar++;
        }
        $session    =   new LibSession();
        if($roleEnabled && $session->get('admin_type')!="developer") {
            //opertaions allowed

            $session    =   new LibSession();
            $roleId = $session->get("role_id");


            $sectionId=  Cms::getSectionId(PageContext::$request['section']);
            $sectionRoles   =   Cms::getSectionRoles($sectionId);

            $parentRoleIDArray  =  Cms::getParentRoleList($roleId);

            if(!in_array($sectionRoles->view_role_id, $parentRoleIDArray)) {
                $viewPermission = 1;

            }
            if(!in_array($sectionRoles->add_role_id, $parentRoleIDArray)) {
                $addPermission = 1;

            }
            if(!in_array($sectionRoles->edit_role_id, $parentRoleIDArray)) {
                $editPermission = 1;

            }
            if(!in_array($sectionRoles->delete_role_id, $parentRoleIDArray)) {
                $deletePermission = 1;

            }
            if(!in_array($sectionRoles->publish_role_id, $parentRoleIDArray)) {
                $publishPermission = 1;

            }
        }
        else {
            $viewPermission = 1;
            $addPermission = 1;
            $editPermission = 1;
            $deletePermission = 1;
            $publishPermission = 1;

        }
        foreach($sectionConfig->opertations  as $operations) {
            if($operations=="add" && $addPermission)
                PageContext::$response->addAction   =   true;
            if($operations=="edit" && $editPermission)
                PageContext::$response->editAction   =   true;
            if($operations=="delete" && $deletePermission)
                PageContext::$response->deleteAction   =   true;
            if($operations=="customdelete" && $deletePermission) {
                PageContext::$response->deleteAction   =   true;
                $customDeleteOperation  =   1;
            }
            if($operations=="view" && $viewPermission)
                PageContext::$response->viewAction   =   true;
            if($operations=="publish" && $publishPermission)
                PageContext::$response->publishAction   =   true;


        }
//        //custom operations
        //$customOperationsList= new stdClass();
        //$customOpLoop=0;

        $customOperationsList = array();

        foreach($listDataResults  as $dt) {

            $customOperations = array();

            foreach( $sectionConfig->customOperations as $key=>$val) {
                $opt = new stdClass();
                $columnName     =   $sectionConfig->keyColumn;
                $params         =   $dt->$columnName;
                $link           =   call_user_func($val->options->linkSource,$params);
                $opt->name      =   $val->options->name;
                if($val->options->target=="newtab")
                    $target =   "target='_blank'";
                else
                    $target = "";
                $opt->link      =   "<a href='".$link."' $target class='cms_list_operation'>".$opt->name."</a>";


                $customOperations[$key]     =  $opt;

            }

            //echopre($customOperations);


            $customOperationsList[]         =   $customOperations;





        }//echopre($customOperationsList);

        // echo '<br><br><br><br><br>before: ';echopre($customOperationsList);
        //echopre1($customOperationsList);
        PageContext::$response->customOperationsList  =   $customOperationsList;

///echopre/($sectionConfig->customOperations);
        //to get total number of results in each section
        if($sectionConfig->dataSource) {
            $numResListData= call_user_func($sectionConfig->dataSourceCountFunction,PageContext::$request);

        }
        else
            $numResListData=Cms::listDataNumRows($sectionData,PageContext::$request);


        $numRowsListData=$numResListData;
        //to get total number of result pages
        $totalResulPages=ceil($numRowsListData/$perPageSize);
        PageContext::addJsVar("totalResulPages", $totalResulPages);
        //to form url using GET params
        $currentURL=Cms::formUrl(PageContext::$request,$sectionConfig);
        $searchURL=Cms::formSearchUrl(PageContext::$request);
        $pageUrl=Cms::formPagingUrl(PageContext::$request);
        PageContext::addJsVar("searchURL", $searchURL);
        if(PageContext::$request['page']!="") {
            $pageUrl=$pageUrl;
            $pageUrl    =   str_replace("page=".PageContext::$request['page'], "", $pageUrl);
            $pageUrl=$pageUrl."&";
        }
        else
            $pageUrl=$pageUrl."&";
        PageContext::$response->pagination      =   Cms::pagination($numRowsListData,$perPageSize,$pageUrl,PageContext::$request['page']);
        PageContext::$response->columnNum       =   count($sectionConfig->detailColumns)+4;
        PageContext::$response->currentURL      =   $currentURL;
        PageContext::$response->totalResultsNum =   $numRowsListData;
        PageContext::$response->resultsPerPage  =   $perPageSize;
        PageContext::$response->resultPageCount =   $totalResulPages;
        PageContext::$response->request         =   PageContext::$request;
        PageContext::$response->section_config  =   $sectionConfig;
        PageContext::$response->listColumns     =   $sectionConfig->listColumns;
        PageContext::$response->columns         =   $sectionConfig->columns;
        PageContext::$response->combineTables  =    $sectionConfig->combineTables;
        PageContext::$response->relations       =   $sectionConfig->relations;
        PageContext::$response->listData        =   $listData;
        PageContext::$response->sectionData     =   $sectionData;
        PageContext::$response->section_config->keyColumn   =   $sectionConfig->keyColumn;


        $searchableCoumnsList    =   Cms::getSearchableColumns($sectionData);
        PageContext::$response->searchableCoumnsList   =   $searchableCoumnsList;
        // if action is add
        if( PageContext::$request['action']=="add")
            PageContext::$response->showForm=TRUE;
        // if action is edit
        if(PageContext::$request['action']=="edit" ) {
            PageContext::$response->showForm=TRUE;
            //to get list data for a particular section

            $listItem= Cms::listItem($sectionData,PageContext::$request);
        }
        // if action is delete
        if(PageContext::$request['action']=="delete") {
            if($customDeleteOperation) {
                $statusArray=call_user_func($sectionConfig->customDeleteOperation,PageContext::$request[$sectionConfig->keyColumn]);

                if($statusArray['status']=="error") {
                    $message = $statusArray['message'];
                    $currentURL =   $currentURL."&errormessage=$message";
                    header("Location:$currentURL");
                    exit;
                }
                else {
                    if($statusArray['status']=="success") {
                        $message = $statusArray['message'];
                        if($message=="") {
                            $currentURL =   $currentURL."&msgFlag=1";
                            header("Location:$currentURL");
                            exit;
                        }
                        else {
                            $currentURL =   $currentURL."&errormessage=$message";
                            header("Location:$currentURL");
                            exit;
                        }
                    }
                }



            }
            else {


                Cms::deleteEntry($sectionData,PageContext::$request);
                $currentURL =   $currentURL."&msgFlag=1";
                header("Location:$currentURL");
                exit;
            }
        }

        // if action is publish
        if((PageContext::$request['action']=="publish") || (PageContext::$request['action']=="unpublish")) {
            Cms::changePublishStatus($sectionData,PageContext::$request);
            if(PageContext::$request['action']=="publish")
                $currentURL =   $currentURL."&msgFlag=2";
            if(PageContext::$request['action']=="unpublish")
                $currentURL =   $currentURL."&msgFlag=3";
            header("Location:$currentURL");
            exit;
        }

        // logic for creating form
        $objForm = new Htmlform();
        $errorFlag = 0;
        $objForm->method	= "POST";
        $objForm->action  	= $currentURL;
        if($sectionConfig->handleFile) {
            $objForm->handleFile    =   true;
        }
        $objForm->name    	= "form_".PageContext::$response->section_alias;
        if(PageContext::$request['action']=="edit")
            $objForm->form_title  = "Edit ".PageContext::$response->sectionName ;
        else
            $objForm->form_title  = "Add ".PageContext::$response->sectionName ;

        $objForm->primaryKey            =   PageContext::$request[$sectionConfig->keyColumn];
        $objForm->formActionType            =  PageContext::$request['action'];

        foreach($sectionConfig->detailColumns as  $col) {
            $val = $sectionConfig->columns->$col;
            if($val->editoptions) {

                $objFormElement                     =   new Formelement();
                $objFormElement->type               =   $val->editoptions->type;
                $objFormElement->name               =   $col;
                $objFormElement->id                 =   $col;
                $objFormElement->label              =   $val->editoptions->label;
                if(PageContext::$request['action']  ==    "edit") {
                    if( $objFormElement->type=="hidden") {
                        if($listItem[0]->$col)
                            $objFormElement->value          =   $listItem[0]->$col;
                        else
                            $objFormElement->value          =   $val->editoptions->value;
                    } else
                        $objFormElement->value          =   $listItem[0]->$col;
                    ////$val->editoptions->value;
                }
                else if(PageContext::$request['action']  ==    "add") {
                    if( $objFormElement->type=="hidden")
                        $objFormElement->value          =   $val->editoptions->value;
                    else
                        $objFormElement->value          =   $_POST[$col];//$val->editoptions->value;
                }else {
                    $objFormElement->value          =   $val->editoptions->value;
                }
                $objFormElement->default            =   $val->editoptions->default;
                $objFormElement->noEncryption            =   $val->editoptions->noEncryption;

                $objFormElement->class              =   $val->editoptions->class;
                $objFormElement->prehtml            =   $val->editoptions->prehtml;
                $objFormElement->posthtml           =   $val->editoptions->posthtml;
                $objFormElement->source             =   $val->editoptions->source;
                $objFormElement->sourceType         =   $val->editoptions->source_type;
                $objFormElement->validations        =   $val->editoptions->validations;
                $objFormElement->fileValidations        =   $val->editoptions->fileValidations;

                $objFormElement->hint               =   $val->editoptions->hint;
                $objFormElement->dbFormat           =   $val->editoptions->dbFormat;
                $objFormElement->displayFormat      =   $val->editoptions->displayFormat;
                $objFormElement->enumvalues         =   $val->editoptions->enumvalues;


                $objForm->addElement($objFormElement);
                if(isset($_POST['submit'])) {

                    $objFormValidation                      =   new Formvalidation();
                    if($val->editoptions->type  ==   "file") {
                        // if($val->editoptions->value=="")
                        if(PageContext::$request['action']  ==    "add") {
                            $response   =   $objFormValidation->validateForm($_FILES[$col]['name'],$objFormElement->label,$objFormElement);
                        }
                        else if(PageContext::$request['action']  ==    "edit" && $_FILES[$col]['name']) {
                            $response   =   $objFormValidation->validateForm($_FILES[$col]['name'],$objFormElement->label,$objFormElement);
                        }
                    }

                    else
                        $response   =   $objFormValidation->validateForm($_POST[$objFormElement->name],$objFormElement->label,$objFormElement);
                    if($response!=  "" ) {

                        if($errorFlag==0)
                            $objForm->form_error_message        =   $response;
                        PageContext::$response->showForm    =   TRUE;
                        PageContext::addJsVar("formError", 1);
                        $errorFlag  =   1;
                    }



                }

            }
        }
        PageContext::$response->addform = $objForm;
        // Form submit
        if(isset($_POST['submit']) && $errorFlag==0) {
            //echopre(PageContext::$request);exit;
            if(PageContext::$request['action']  ==   "edit") {
                $sucessMessage  =   "Record Updated Successfully";
                PageContext::$response->showForm    =   FALSE;
            }
            else
                $sucessMessage  =   "Record Added Successfully";

            Cms::saveForm($sectionData,$_POST,PageContext::$request);
            header("Location:$currentURL&message=$sucessMessage");
            exit;
        }
        if(isset(PageContext::$request['message'])) {
            PageContext::$response->message =   PageContext::$request['message'];
            PageContext::$response->showForm=FALSE;
        }
        if(PageContext::$request['msgFlag']  ==   "1") {
            $message  =   "Record Deleted Successfully";
            PageContext::$response->message =   $message;
        }
        if(PageContext::$request['msgFlag']  ==   "2") {
            $message  =   "Record Published Successfully";
            PageContext::$response->message =   $message;
        }
        if(PageContext::$request['msgFlag']  ==   "3") {
            $message  =   "Record Unpublished Successfully";
            PageContext::$response->message =   $message;
        }


    }

    public static function getreport() {

        $section                =   $_GET['requestHeader'];
        $request['section']     =   $section;
        if((GLOBAL_DATE_FORMAT_SEPERATOR))
        	$date_separator =  GLOBAL_DATE_FORMAT_SEPERATOR;
        else {
        	$date_separator = "-";
        }

        $reportStartDate        =    $_GET['reportStartDate'];
        $reportStartDateArray   =    explode($date_separator,$reportStartDate);
        $reportStartDate1        =   $reportStartDateArray[2]."-". $reportStartDateArray[0]."-". $reportStartDateArray[1];

        $reportEndDate          =    $_GET['reportEndDate'];
        $reportEndDateArray   =    explode($date_separator,$reportEndDate);
        $reportEndDate1        =   $reportEndDateArray[2]."-". $reportEndDateArray[0]."-". $reportEndDateArray[1];

        $sectionData            =   Cms::getSectionData($request);

        $sectionConfig          =   json_decode($sectionData->section_config);
        $reportColumnCount      =    count($sectionConfig->report->columns);

        if($sectionConfig->report) {
            // excel header
            $excelData  .=  "<table border='1'><tr>";

            $excelData  .=  "<td colspan=$reportColumnCount>Report: ".$sectionConfig->report->reportTitle." From $reportStartDate To $reportEndDate</td>";
            $excelData  .=  "</tr></table>";
            $excelData  .=  "<table border='1'><tr>";
            foreach($sectionConfig->report->columns as $col) {
                foreach($sectionConfig->columns as $key =>  $val) {
                    if($col==$key) {

                        $excelData  .=   "<td>".$val->name."</td>";
                    }

                }
            }
            $excelData  .=  "</tr></table>";
            $excelData  .=   "<table border='1'><tr>";
            //to get list data for a particular section
            if($sectionConfig->dataSource) {

                $listDataResults= call_user_func($sectionConfig->reportSourceFunction,PageContext::$request);

            }
            else
                $listDataResults= Cms::getReport($sectionData,$request,$reportStartDate1,$reportEndDate1);
            foreach($listDataResults  as $record) {
                $excelData  .=   "<tr>";
                foreach($sectionConfig->report->columns as $col) {
                    foreach($sectionConfig->columns as $key =>  $val) {
                        if($col==$key) {
                            if($val->dbFormat) {
                                $record->$col   =   Cms::getTimeFormat($record->$col,$val->dbFormat,$val->displayFormat);
                            }
                            $excelData  .=   "<td width='100px'>".$record->$col."</td>";
                        }

                    }
                }
                $excelData  .=   "</tr>";
            }
            header("Content-type: application/ms-excel");
            header("Content-Transfer-Encoding: binary");
            header("Content-Disposition: attachment; filename=\"".$sectionConfig->report->reportTitle.".xls\"");
            echo $excelData  .=   "</tr></table>";
            exit;
            exit;
        }

    }
    public function permission() {


    }
    public function manageroles() {

        $roleId        =   PageContext::$request['role_id'];
        if($roleId)
            $rolesDetails   =   Cms::getRoleDetails($roleId);
        pageContext::$response->rolesDetails          =   $rolesDetails;
        if( PageContext::$request['action']=="add")
            PageContext::$response->showForm=TRUE;
        PageContext::$response->form_title="Add Role";
        // if action is edit
        if(PageContext::$request['action']=="edit" ) {
            PageContext::$response->form_title="Edit Role";
            PageContext::$response->showForm=TRUE;
            //to get list data for a particular section

        }
        $message = PageContext::$request['message'];
        pageContext::$response->message          =   $message;
        $errormessage = PageContext::$request['errormessage'];
        pageContext::$response->errorMessage          =   $errormessage;
        $perPageSize  =   PageContext::$response->cmsSettings['admin_page_count'];
        pageContext::$response->previlegeDetails          =   $previlegeDetails;
        $currentURL=Cms::formUrl(PageContext::$request,$sectionConfig);
        pageContext::$response->currentURL          =   $currentURL;
        PageContext::addJsVar("currentURL", $currentURL);
        $rolesArray                                  =   Cms::getAllRolesArray(PageContext::$request['page'],$perPageSize);
        foreach($rolesArray as $role) {
            $roles = new stdClass();
            $roles->role_id  =   $role->role_id;
            $roles->role_name =   $role->role_name;
            $roles->parent_role_id  =   $role->parent_role_id;
            $roles->parent_role_name  =   Cms::getRoleName($role->parent_role_id);
            $rolesList[] = $roles;
        }
        $roles                                  =   Cms::getAllRoles();
        $totalresult    =   count($roles);
        $totalResulPages=ceil($totalresult/$perPageSize);
        PageContext::addJsVar("totalResulPages", $totalResulPages);
        PageContext::$response->totalResultsNum =   $totalresult;
        PageContext::$response->resultsPerPage  =   $perPageSize;
        $pageUrl=Cms::formPagingUrl(PageContext::$request);
        if(PageContext::$request['page']!="") {
            $pageUrl=$pageUrl;
            $pageUrl    =   str_replace("page=".PageContext::$request['page'], "", $pageUrl);
            $pageUrl=$pageUrl."&";
        }
        else
            $pageUrl=$pageUrl."&";
        PageContext::$response->pagination      =   Cms::pagination($totalresult,$perPageSize,$pageUrl,PageContext::$request['page']);
        if(PageContext::$request['action']=="delete") {
            $error      =   Cms::deleteRole($roleId);
            if($error) {
                if($error=="roleExist")
                    $errorMessage  =   "You cannot delete this role, it contains leaf roles ";
                else
                    $errorMessage  =   "You cannot delete this role, it contains users ";
                $currentURL =   $currentURL."&errormessage=$errorMessage";
                header("Location:$currentURL");
                exit;
            }
            $sucessMessage  =   "Record Deleted Successfully";

            $currentURL =   $currentURL."&message=$sucessMessage";
            header("Location:$currentURL");
            exit;
        }
        if(isset($_POST['submit'])) {

            $postArray['role_id'] = PageContext::$request['role_id'];
            $postArray['role_name'] = PageContext::$request['role_name'];

            $postArray['parent_role_id'] = PageContext::$request['parent_role_id'];
            $privilegeId = Cms::saveRoles($postArray['role_id'] ,$postArray);
            if($postArray['role_id'])
                $sucessMessage  =   "Record Edited Successfully";

            else
                $sucessMessage  =   "Record Added Successfully";



            header("Location:$currentURL&message=$sucessMessage");
            exit;

        }


        pageContext::$response->roles           =   $rolesList;
    }
    public function manageusers() {

        $userId        =   PageContext::$request['id'];
        if($userId)
            $userDetails   =   Cms::getUserDetails($userId);
        pageContext::$response->userDetails          =   $userDetails;
        if( PageContext::$request['action']=="add")
            PageContext::$response->showForm=TRUE;
        PageContext::$response->form_title="Add User";
        // if action is edit
        if(PageContext::$request['action']=="edit" ) {
            PageContext::$response->showForm=TRUE;
            PageContext::$response->form_title="Edit User";
            //to get list data for a particular section

        }
        if(PageContext::$request['action']=="changepw" ) {
            PageContext::$response->showPasswordForm=TRUE;

        }
        $message = PageContext::$request['message'];
        pageContext::$response->message          =   $message;
        $errormessage = PageContext::$request['errormessage'];
        pageContext::$response->errorMessage          =   $errormessage;
        $perPageSize  =   PageContext::$response->cmsSettings['admin_page_count'];
        pageContext::$response->previlegeDetails          =   $previlegeDetails;
        $currentURL=Cms::formUrl(PageContext::$request,$sectionConfig);
        pageContext::$response->currentURL          =   $currentURL;
        PageContext::addJsVar("currentURL", $currentURL);
        $usersArray                                  =   Cms::getAllUserArray(PageContext::$request['page'],$perPageSize);
        foreach($usersArray as $user) {
            $users = new stdClass();
            $users->id  =   $user->id;
            $users->username =   $user->username;
            $users->email =   $user->email;

            $users->role_name  =   Cms::getRoleName($user->role_id);
            $usersList[] = $users;
        }
        $roles                                  =   Cms::getAllRoles();

        PageContext::$response->roles =   $roles;
        $allUsers                                  =   Cms::getAllUsers();
        $totalresult    =   count($allUsers);
        $totalResulPages=ceil($totalresult/$perPageSize);
        PageContext::addJsVar("totalResulPages", $totalResulPages);
        PageContext::$response->totalResultsNum =   $totalresult;
        PageContext::$response->resultsPerPage  =   $perPageSize;
        $pageUrl=Cms::formPagingUrl(PageContext::$request);
        if(PageContext::$request['page']!="") {
            $pageUrl=$pageUrl;
            $pageUrl    =   str_replace("page=".PageContext::$request['page'], "", $pageUrl);
            $pageUrl=$pageUrl."&";
        }
        else
            $pageUrl=$pageUrl."&";
        PageContext::$response->pagination      =   Cms::pagination($totalresult,$perPageSize,$pageUrl,PageContext::$request['page']);
        if(PageContext::$request['action']=="delete") {
            $error      =   Cms::deleteUser($userId);

            $sucessMessage  =   "Record Deleted Successfully";

            $currentURL =   $currentURL."&message=$sucessMessage";
            header("Location:$currentURL");
            exit;
        }

        if((PageContext::$request['submit']=="Save")) {
// adding new user
            $postArray['id'] = PageContext::$request['id'];
            $postArray['username'] = PageContext::$request['username'];
            $postArray['password'] = PageContext::$request['password'];
            $postArray['email'] = PageContext::$request['email'];

            $postArray['role_id'] = PageContext::$request['role_id'];
            $postArray['type'] = 'admin';
            $userExist         =   Cms::checkUserExist(PageContext::$request['username'],$postArray['id']);
            if(!$userExist) {
                $privilegeId = Cms::saveUser($postArray['id'] ,$postArray);
                if($postArray['id'])
                    $sucessMessage  =   "Record Edited Successfully";

                else
                    $sucessMessage  =   "Record Added Successfully";
                $pageUrl=Cms::formPagingUrl(PageContext::$request);
                header("Location:$pageUrl&message=$sucessMessage");
                exit;
            }
            else {
                $errorMessage  =   "Username already exist ";
                header("Location:$currentURL&errormessage=$errorMessage");
                exit;

            }
        }
        if((PageContext::$request['submit']=="Update")) {
            //updating password
            $postArray['id'] = PageContext::$request['id'];
            $postArray['cpassword'] = PageContext::$request['cpassword'];
            $currentuserId = PageContext::$request['id'];
            $postArray['newpassword'] = PageContext::$request['newpassword'];
            $postArray['cnewpassword'] = PageContext::$request['cnewpassword'];
            $checkOldpassword   =   Cms::checkPassword($postArray['cpassword'],$currentuserId);
            if($checkOldpassword!=md5($postArray['cpassword'])) {
                $errorMessage  =   "Current password is wrong ";
                header("Location:$currentURL&errormessage=$errorMessage");
                exit;
            }
            else {
                $privilegeId = Cms::changeUserPassword($postArray['id'] ,$postArray);

                $sucessMessage  =   "Password updated successfully";
                $pageUrl=Cms::formPagingUrl(PageContext::$request);
                header("Location:$pageUrl&message=$sucessMessage");
                exit;
            }

        }
        pageContext::$response->errorMessage          =   $errormessage;
        pageContext::$response->users           =   $usersList;
    }
    public function manageprivilege() {

        $privilegeId        =   PageContext::$request['privilege_id'];

        pageContext::$response->privilegeId          =   $privilegeId;
        if($privilegeId)
            $previlegeDetails = Cms::getPrivilegeDetails($privilegeId);
        if( PageContext::$request['action']=="add") {
            PageContext::$response->form_title="Add Privilege" ;
            PageContext::$response->showForm=TRUE;
        }
        // if action is edit
        if(PageContext::$request['action']=="edit" ) {
            PageContext::$response->showForm=TRUE;
            PageContext::$response->form_title="Edit Privilege";

        }

        $message = PageContext::$request['message'];
        pageContext::$response->message          =   $message;
        $perPageSize  =   PageContext::$response->cmsSettings['admin_page_count'];
        pageContext::$response->previlegeDetails          =   $previlegeDetails;
        $currentURL=Cms::formUrl(PageContext::$request,$sectionConfig);
        pageContext::$response->currentURL          =   $currentURL;
        PageContext::addJsVar("currentURL", $currentURL);
        $privilegesList =  Cms::getprivilege();

        $privileges =  Cms::getprivilegeList(PageContext::$request['page'],$perPageSize);

        $privilegeList    =   array();
        $loop =0;
        $addedSections  =    "";
        $addedGroups = "";
        foreach($privileges as $privilege ) {
            $privilegeArray = new stdClass();
            $privilegeArray->privilege_id       =   $privilege->privilege_id;
            $privilegeArray->entity_type        =   $privilege->entity_type;
            if($privilege->entity_type=="section")
                $addedSections .= $privilege->entity_id.",";
            if($privilege->entity_type=="group")
                $addedGroups .= $privilege->entity_id.",";
            $privilegeArray->entity_id          =   $privilege->entity_id;
            $privilegeArray->enity_name         =   Cms::getEntityName($privilege->entity_id,$privilege->entity_type);
            $privilegeArray->view_role_id       =   Cms::getRoleName($privilege->view_role_id);
            $privilegeArray->add_role_id        =   Cms::getRoleName($privilege->add_role_id);
            $privilegeArray->edit_role_id       =   Cms::getRoleName($privilege->edit_role_id);
            $privilegeArray->delete_role_id     =   Cms::getRoleName($privilege->delete_role_id);
            $privilegeArray->publish_role_id    =   Cms::getRoleName($privilege->publish_role_id);
            $privilegeList[] = $privilegeArray;
            $loop++;


        }
        if(PageContext::$request['action']=="add") {
            $addedGroups = substr($addedGroups, 0,-1);
            $addedSections = substr($addedSections, 0,-1);
        }
        else {
            $addedGroups = "";
            $addedSections = "";
        }
        $roles                                  =   Cms::getAllPrivileges();
        $totalresult    =   count($roles);

        $totalResulPages=ceil($totalresult/$perPageSize);
        PageContext::addJsVar("totalResulPages", $totalResulPages);
        PageContext::$response->totalResultsNum =   $totalresult;
        PageContext::$response->resultsPerPage  =   $perPageSize;
        $pageUrl=Cms::formPagingUrl(PageContext::$request);
        if(PageContext::$request['page']!="") {
            $pageUrl=$pageUrl;
            $pageUrl    =   str_replace("page=".PageContext::$request['page'], "", $pageUrl);
            $pageUrl=$pageUrl."&";
        }
        else
            $pageUrl=$pageUrl."&";
        PageContext::$response->pagination      =   Cms::pagination($totalresult,$perPageSize,$pageUrl,PageContext::$request['page']);
        pageContext::$response->privilegList    =   $privilegeList;

        $sections                               =   Cms::getNewSections($addedSections);
        $groups                                 =   Cms::getNewGroups($addedGroups);
        pageContext::$response->sections        =   $sections;
        pageContext::$response->groups          =   $groups;
        $roles                                  =   Cms::getAllRoles();
        pageContext::$response->roles          =   $roles;
        if(PageContext::$request['action']=="delete") {
            Cms::deleteprivilege($privilegeId);
            $sucessMessage  =   "Record Deleted Successfully";

            $currentURL =   $currentURL."&message=$sucessMessage";
            header("Location:$currentURL");
            exit;
        }
        if(isset($_POST['submit'])) {

            $postArray['privilege_id'] = PageContext::$request['privilege_id'];
            $postArray['entity_type'] = PageContext::$request['entity_type'];
            if(PageContext::$request['section_entity_id'])
                $postArray['entity_id'] = PageContext::$request['section_entity_id'];
            if(PageContext::$request['group_entity_id'])
                $postArray['entity_id'] = PageContext::$request['group_entity_id'];
            $postArray['view_role_id'] = PageContext::$request['view_role_id'];
            $postArray['add_role_id'] = PageContext::$request['add_role_id'];
            $postArray['edit_role_id'] = PageContext::$request['edit_role_id'];
            $postArray['delete_role_id'] = PageContext::$request['delete_role_id'];
            $postArray['publish_role_id'] = PageContext::$request['publish_role_id'];
            $privilegeId = Cms::savePrivileges($postArray['privilege_id'] ,$postArray);
            if($postArray['privilege_id'])
                $sucessMessage  =   "Record Edited Successfully";

            else
                $sucessMessage  =   "Record Added Successfully";
            $pageUrl=Cms::formPagingUrl(PageContext::$request);
            PageContext::$response->showForm    =   FALSE;
            header("Location:$pageUrl&message=$sucessMessage");
            exit;
        }

    }



}

?>
