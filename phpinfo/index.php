<?php
		session_start();
 		//===================================================================================================================
		//================================PRODUCTWISE REQUIREMENTS===========================================================
		//===================================================================================================================
		
			global $required_functions;
			global $required_extensions;
			global $required_write_permissions;
			global $required_ports;
			$product = "gostores";
		//provide required_functions in comma separated form for each product
		
		
	
		if($product == "multicart") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("includes/config.php","products","portfolios","products/bulk_images","products/zip","csv","pem","banners","digital_product","categories/avatar","categories","homepagebanners",".htaccess");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "eswap") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("includes/config.php","images","pics","pics/profile","lang_flags","help","banners","pem");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "gostores") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("project/Demo/app/webroot/img/products","project/Demo/app/webroot/img/csv","project/Demo/app/webroot/Fax","project/Demo/app/webroot/files","project/Demo/app/webroot/files/File","project/Demo/app/webroot/files/Flash","project/Demo/app/webroot/files/Image","project/Demo/app/webroot/files/Media","project/Demo/app/webroot/files/Graph","project/Demo/app/webroot/img/SiteLogo_disp.gif","project/Demo/app/webroot/img/SiteLogo.jpg","project/Demo/app/tmp/cache","project/Demo/app/tmp/cache/models","project/Demo/app/tmp/cache/views","project/Demo/app/tmp/cache/persistent","project/Demo/app/tmp","project/Demo/app/tmp/logs","project/Demo/app/tmp/sessions","project/Demo/app/webroot/img","project/Demo/app/webroot/css","project/Demo/app/webroot/Fedex/shipping_label","project/Demo/app/webroot/Fedex","project/Demo/app/webroot/blog/wp-content","project/Demo/app/webroot/blog/wp-config.php","project/Demo/app/controllers/components/pple.xml","project/Demo/app/config/database.php","project/Demo/app/webroot/config.php","project/support/custom","project/support/styles","project/support/attachments","project/support/backup","project/support/downloads","project/support/csvfiles","project/support/api/useradd.php","project/support/api/server_class.php","project/support/config/settings.php","project/support/admin/purgedtickets","project/support/admin/purgedtickets/attachments","project/support/staff/images","project/support/fckeditorimages","project/support/FCKeditor/editor/filemanager/connectors/php/config.php","project/files","project/config/config.php","project/config/settings.php");
			$required_ports 			= 	array(3306,443,80,25,2082,2083,2086,2087);
		
		}else if($product == "printlogic") {
		
			$required_functions			=	array("system");
			$required_extensions		=	array("mysql","curl","gd","imageMagick","imagick");
			$required_write_permissions	=	array("img/products","img/csv","Fax","files","files/File","files/Flash","files/Image","files/Media","files/Graph","img/SiteLogo_disp.gif","img/SiteLogo.jpg","../tmp/cache","../tmp/cache/models","../tmp/cache/views","../tmp/cache/persistent","../tmp","../tmp/logs","../tmp/sessions","img","css","Fedex/shipping_label","Fedex","img/customized_tshirts","img/customized_tshirts_foreground","img/editorimages","img/editorimages/default","img/editorimages/temp","img/editorimages/customized","img/editorimages/cropimages","img/TIFF","img/tshirts","img/categoryimages","img/fontpreview","../config/database.php","config.php");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "autohoster") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("logo","logo/files","images/Graph","includes/config.php","support/custom","support/attachments","support/backup","support/downloads","support/csvfiles","support/admin/purgedtickets","support/admin/purgedtickets/attachments","websitebuilder","admin/invoice","admin/domaininvoice","admin/autoupdatetransfer","websitebuilder/usergallery","websitebuilder/sites","websitebuilder/sitepages","websitebuilder/sitepages/sites","websitebuilder/sitepages/tempsites");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "easybiller") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("includes/config.php","images","caticons","pem","admin/invoice","qrylog/qrylog.txt","helpdesk/attachments","helpdesk/backup","helpdesk/custom","helpdesk/downloads","helpdesk/admin/purgedtickets","helpdesk/admin/purgedtickets/attachments","images/graphs","images/graphs/affgraph.png","images/graphs/salesgraph.png","images/graphs/usergraph.png","images/logo.jpg","images/banners","fckeditorimages","fckeditor/editor/filemanager/connectors/php/config.php");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "reservelogic") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("project/files","project/files/banners","project/files/destination","project/files/destinationThumb","project/files/location","project/files/locationThumb","project/styles/images","project/config/config.php","project/config/settings.php");			
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "vistacart") {
				
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("img/products","img/csv","Fax","files","files/File","files/Flash","files/Image","files/Media","files/Graph","img/SiteLogo_disp.gif","img/SiteLogo.jpg","../tmp/cache","../tmp/cache/models","../tmp/cache/views","../tmp/cache/persistent","../tmp","../tmp/logs","../tmp/sessions","../tmp/logs","img","css","Fedex/shipping_label","Fedex","blog/wp-content","blog/wp-config.php","../controllers/components/pple.xml","../config/database.php","config.php");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "socialware") {
		
			$required_functions			=	array("system");
			$required_extensions		=	array("mysql","curl","gd","ffmpeg");
			$required_write_permissions	=	array("images","tmpeditimages","images/album","images/classifieds","images/editor","images/gifts","images/profile","images/smilies","images/template","member_photos","gifts_images","event_category","myblog/template_image","logos","avatars","myblog/templates","album_photos","group_photos",".htaccess","videos","videos/thumb","videos/flv","videos/xml","music","music/files","music/xml","images/tickets","jobs","images/news","pem","flashslideshow","flashslideshow/xml","product_images","banners","classified_files","help","files","config.inc.php");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "dailydeals") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd","Gettext","pcre");
			$required_write_permissions	=	array("system/application/config/database_local.php","system/application/config/config_local.php","config.php","public/images/backend/","public/images/backend/icons/","public/images/frontend/","public/images/icons/","public/images/newsletter/","public/images/ui/","public/images/ui/colorbox/","public/images/ui/colorbox/internet_explorer/","public/images/upload/","public/images/upload/Image/","public/images/upload/profile/","system/application/cache");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "easycreate") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd","domxml");
			$required_write_permissions	=	array("workarea","workarea/sites","sites","uploads","uploads/siteimages","uploads/siteimages/banners","uploads/themes","templates","samplelogos","includes/install_config.php","includes/configsettings.php");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "cybermatch") {
		
			$required_functions			=	array("system");
			$required_extensions		=	array("mysql","curl","gd","ffmpeg");
			$required_write_permissions	=	array("images","images/profiles","images/success_stories","userfiles","help","images/spam","banners","pem","config.inc.php");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "easyindex") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","gd");
			$required_write_permissions	=	array("uploads/image","uploads/video","uploads/doc","public/images/default","public/images/default/mainlogo.png","public/images/default/mainlogo_disp.png","public/images/default/mainbanner.gif","public/images/default/mainbanner_disp.gif","config/settings.php","config/globals.php","easyindex_config.php");
			$required_ports 			= 	array(3306,443,80,25);
		
		}else if($product == "supportdesk") {
		
			$required_functions			=	array();
			$required_extensions		=	array("mysql","curl","domxml");
			$required_write_permissions	=	array("custom","styles","attachments","backup","downloads","csvfiles","api/useradd.php","api/server_class.php","config/settings.php","admin/purgedtickets","admin/purgedtickets/attachments","staff/images","fckeditorimages","FCKeditor/editor/filemanager/connectors/php/config.php","languages","languages","languages","languages");
			$required_ports 			= 	array(3306,443,80,25);
		
		}
		else{
		
			echo "<p style='color:blue;'><i><b> Please enter a valid product name.</b></i></p>";
			exit();
		
		}
	
		//===================================================================================================================
		//================================ACTUAL TEST========================================================================
		//===================================================================================================================
		
		
	//define style
	define('CHECK_PASS', '<div class="CheckResult Pass">Ok!</div>');
	define('CHECK_FAIL', '<div class="CheckResult Fail">Not OK</div>');
	define('CHECK_UNKNOWN', '<div class="CheckResult Unknown">Unknown</div>');


	//program option
	define('CHECK_SAFE_MODE', false); // !(bool)ini_get('safe_mode')
	define('CHECK_BASEDIR_OK', is_null(ini_get('open_basedir')) || ini_get('open_basedir') === false || ini_get('open_basedir') == '');
	define('CHECK_FILE_UPLOADS', (bool)ini_get('file_uploads'));
	define('CHECK_URL_FOPEN', (!CHECK_SAFE_MODE && ini_get('allow_url_fopen')));
	define('CHECK_EMAIL_SENDING',(mail("mahesh.s@armia.com","My subject","Ok","From: webmaster@freeboards.net.com")));
	define('CHECK_PHP_SAPI', php_sapi_name());

	
	//check mod rewrite available
	if(in_array(CHECK_PHP_SAPI, array('apache', 'apache2handler'))){
		define('CHECK_MOD_REWRITE_AVAILABLE',  in_array('mod_rewrite', apache_get_modules()));
	}
		
	//check access outside URL
	define('CHECK_CURL_AVAILABLE', function_exists('curl_init'));
	$ok = false;

	if (CHECK_CURL_AVAILABLE || CHECK_URL_FOPEN) {
		if (CHECK_CURL_AVAILABLE) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://www.iscripts.com/');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);

			$temp = curl_exec($ch);
			curl_close($ch);

			if (!empty($temp)) {
				$ok = true;
			}
		} else {
			$temp = file_get_contents('http://www.iscripts.com/');
			if (!empty($temp)) {
				$ok = true;
			}
		}
	}

	define('ACCESS_REMOTE_URL', $ok);

	//session working environment
	if (!isset($_SESSION['CHECK_SESSION_CHECK'])) {

		$_SESSION['CHECK_SESSION_CHECK'] = true;
	}

	
	define('CHECK_SESSION_AUTOSTART', (bool) ini_get('session.auto_start'));
	define('CHECK_SESSION_DIR_EXISTS', (bool) is_dir(ini_get('session.save_path')));
	define('CHECK_SESSION_REFERER_CHECK_CORRECT', ini_get('session.referer_check') == '' || strpos($_SERVER['HTTP_HOST'], ini_get('session.referer_check')));
	define('CHECK_SESSION_OK', isset($_SESSION['CHECK_SESSION_CHECK']) && $_SESSION['CHECK_SESSION_CHECK'] === true);
	define('CHECK_EMAIL_SENDING', (bool) mail("mahesh.s@armia.com","My subject","Ok","From: webmaster@freeboards.net.com"));
	
function Check_General() {

	$checks['SafeMode OFF'] = array(
		'result' => CHECK_SAFE_MODE,
		'feature' => 'File Uploads',
		'fix' => 'Please turn off safe mode in the server'
	);
	
		$checks['OpenBase Dir OFF'] = array(
		'result' => CHECK_SAFE_MODE,
		'feature' => 'File Uploads',
		'fix' => 'Please turn off open base dir in the server or add temp directry path to the restriction'
	);
	
		$checks['Allow Remote URL Access'] = array(
		'result' => ACCESS_REMOTE_URL,
		'feature' => 'Payment & Other APIs',
		'fix' => 'Please ask hosting company to fix the issue'
	);
	
		$checks['Allow Uploads'] = array(
		'result' => CHECK_FILE_UPLOADS,
		'feature' => 'Uploads',
		'fix' => 'Please ask hosting company to set file_uploads value in php.ini'
	);	
	
		$checks['Send Email'] = array(
		'result' => CHECK_EMAIL_SENDING,
		'feature' => 'Emails & Alerts',
		'fix' => 'Please ask hosting company to enable sendmail in the server'
	);
	
		$checks['RewriteRules'] = array(
		'result' => CHECK_MOD_REWRITE_AVAILABLE,
		'feature' => '.htaccess and rewrites',
		'fix' => 'Please ask hosting company to enable modrewite in server.'	
	);		

	DisplayResults($checks);
}
	
	
function Check_Sessions()
{
		$checks = array();
		$checks['Session Storing'] = array(
		'result' => CHECK_SESSION_OK,
		'feature' => 'Storing Session Value',
		'fix' => 'See if session path is set in php.ini and session folder has write permission');
		
		$checks['Session AutoStart OFF'] = array(
		'result' => !CHECK_SESSION_AUTOSTART,
		'feature' => 'Storing Session Value',
		'fix' => 'Disable session auto_start in php.ini');
		
		$checks['Session Referer Check'] = array(
		'result' => CHECK_SESSION_REFERER_CHECK_CORRECT,
		'feature' => 'Storing Session Value',
		'fix' => 'Contact Server Support');
		
	DisplayResults($checks);
}

function Check_Ports()
{
	global $required_ports;
	$checks = array();
	$host = $_SERVER['HTTP_HOST'];
	foreach ($required_ports as $port) {
		$connection = @fsockopen($host, $port);
		$checks[$port] = is_resource($connection);
		@fclose($connection);
	}

	DisplayResults($checks);
	
}

function Check_Writable()
{
	global $required_write_permissions;
	$checks = array();
	for ($i = 0; $i < count($required_write_permissions); $i++)
		{

		$checks[$required_write_permissions[$i]] = is_writable("../".$required_write_permissions[$i]);
		
		
		}
	
	DisplayResults($checks);
	
}

function Check_Required_Functions()
{			
	global $required_functions;
	$checks = array();

	foreach ($required_functions as $func) {
		$checks[$func.'()'] = function_exists($func);
	}

	DisplayResults($checks);
}




function Check_Required_extensions()
{			
	global $required_extensions;
	$checks = array();

	foreach ($required_extensions as $extensions) {
	
		$checks[$extensions] = in_array($extensions, get_loaded_extensions());
		
	
	}
		

	DisplayResults($checks);
}



function DisplayResults($checks, $section='')
{
	if (empty($checks)) {
		return;
	}

	echo '<fieldset>'."\n";
	if (!empty($section)) {
	 	echo '<legend>'.$section.'</legend>'."\n";
	}
	echo "<dl>\n";

	foreach ($checks as $label => $check) {
		echo "<dt>".$label.":</dt>\n";

		if (is_array($check)) {
			if ($check['result'] === true) {
				echo '<dd class="CheckResult Pass">Ok</dd>'."\n";
			} elseif ($check['result'] === false) {
				echo '<dd class="CheckResult Fail">Not Ok</dd>'."\n";
				echo '<dd class="CheckResultFeature">This feature is required for: <em>'.$check['feature'].'</em></dd>'."\n";
				echo '<dd class="CheckResultFix">To fix this: <em>'.$check['fix'].'</em></dd>'."\n";
			} else {
				echo '<dd class="CheckResult Unknown">Unknown</dd>'."\n";
				echo '<dd class="CheckResultFeature">This feature is required for: <em>'.$check['feature'].'</em></dd>'."\n";
				echo '<dd class="CheckResultFix">To fix this: <em>'.$check['fix'].'</em></dd>'."\n";
			}
		} else {
			if ($check === true) {
				echo '<dd class="CheckResult Pass">Ok</dd>'."\n";
			} elseif ($check === false) {
				echo '<dd class="CheckResult Fail">Not Ok</dd>'."\n";
			} else {
				echo '<dd class="CheckResult Unknown">Unknown</dd>'."\n";
			}
		}
	}

	echo '</dl>'."\n";
	echo '</fieldset>'."\n";
	echo '<br class="Clear" />'."\n";
}
?>		
		
		
		
		
		
		
		
		
		
		
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>iScripts PHP Environment Check</title>
	<meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
	<style type="text/css" media="screen">
		html
		{
			color: #32393D;
			font-family: Arial, Tahoma, Helvetica, sans-serif;
		}
		.Header {
			height: 70px;
		}
 		h1 {
			background: white url(http://iscripts.com/images/iscripts_logo.png) center left no-repeat;
			color:#FE4819;
			font-size: 1.7em;
			font-weight: normal;
			height: 70px;
			padding-left: 180px;
			padding-top: 30px;
			margin: 0;
		}
		h2 { color: #196297;}
		.CheckResult
		{
			color: white;
		}
		.Pass {
			background-color: green;
		}
		.Fail {
			background-color: red;
		}
		.Unknown {
			background-color: blue;
		}
		.Message {
			background-color: #cccccc;
		}		
		.Clear { clear:both;}

		dt {
			width: 300px;
			float: left;
			clear: left;
			border-top: 1px solid #CACACA;
			padding: 3px;
		}

		dd.CheckResult {
			float: left;
			padding: 3px;
			margin-bottom: 1px;
			border-top: 1px solid #CACACA;
			margin-left: 0;
		}

		dd.CheckResultFeature,
		dd.CheckResultFix
		{
			clear: left;
			#background: white url(http://iscripts.com/images/iscripts_logo.png) center left no-repeat;
			margin: 0;
			padding-left: 20px;
			font-size: 0.9em;
		}

		fieldset {
			width: 600px;
			border-color: #EEEEEE;
		}

		legend {
			font-weight: bold;
		}

		em { color: #FE4819;}

		a { color:#196297;}
		a:hover { color: #FE4819;}

	</style>


</head>
<body>
	<div class="Header">
		<h1><br>Server Environment Analysis</h1>
	</div>
	<br class="Clear" />
	<div class="Content">
		<h2>Server Information</h2>
		<ul>
		<?php
				echo "<li>Server Info :  ".  php_uname() ."</li>";		
				echo "<li>Operating System : ".  PHP_OS  ."</li>";
				echo '<li>PHP is running in ' . CHECK_PHP_SAPI . ' mode</li>';
				$message = (is_writable('test')) ;
				
				if((is_writable('test') && (substr(sprintf('%o', fileperms('/tmp')), -4)=='0777'))){
				
					$message = "Server needs chmod 777 as write permissions";
				
				}else if ((is_writable('test') && (substr(sprintf('%o', fileperms('/tmp')), -4)=='0755'))){
				
					$message = "Server needs chmod 755 as write permissions";
				
				}else if (!(is_writable('test'))){
				
					$message = "Server needs chmod 777 as write permissions";
				
				}
				echo "<li>".$message."</li>";
				?>
				</ul>
		<?php
	
				echo '<h2>General Settings</h2>';
				echo '<div><ul>';
				Check_General();
				echo '</ul></div>';		
				echo '<h2>Sessions</h2>';
				echo '<div><ul>';
				Check_Sessions();
				echo '</ul></div>';
				echo '<h2>Ports</h2>';
				echo '<span  class="Message">If Not OK please ask hosting company to open the respective port</span>';
				echo '<div><ul>';
				Check_Ports();
				echo '</ul></div>';
				echo '<h2>Write Permissions</h2>';
				echo '<span  class="Message">If Not OK please provide write permission to respective file/folder</span>';
				echo '<div><ul>';
				Check_Writable();
				echo '</ul></div>';
				echo '<h2>Required Functions</h2>';
				echo '<span  class="Message">If Not OK , see if the function is disabled or module missing.</span>';
				echo '<div><ul>';
				Check_Required_Functions();
				echo '</ul></div>';
				echo '<h2>Required Extensions</h2>';
				echo '<span  class="Message">If Not OK , ask hosting company to install the respective extentions.</span>';
				echo '<div><ul>';
				Check_Required_extensions();
				echo '</ul></div>';
				
				echo '<h2>PHP INFO</h2>';
				echo '<div><ul>';
				phpinfo();
				echo '</ul></div>';				

		
		
			
		
?>
	</div>
</body>
</html>
	