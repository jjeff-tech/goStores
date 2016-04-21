******************************************************************************************
                        iScripts GoStores 2.4 Readme File
                                 April 27, 2017
******************************************************************************************
(c) Copyright Armia Systems,Inc 2005-12. All rights reserved.

This file contains information that describes the installation of iScripts GoStores
You can find more help on the product and the installation in the /docs folder.

******************************************************************************************
Contents
******************************************************************************************
1.0 Introduction
2.0 Requirements
3.0 Installing iScripts GoStores
4.0 Setting cron job
5.0 Upgrading from version 2.0 iScripts GoStores Platform

******************************************************************************************
1.0 Introduction
******************************************************************************************
This file contains important information you should read before installing 
iScripts GoStores

The iScripts GoStores2.0 enables any web master to offer domain 
management and hosting service for novice and expert users. 

The iScripts GoStores relies on GD support and it requires GD
library to be enabled in your PHP.

The iScripts GoStores relies on Curl support and it requires Curl 
to be enabled in your PHP.

******************************************************************************************
2.0 Requirements
******************************************************************************************
The iScripts GoStores1.1 is developed in PHP and the database is
MySQL. Since the software uses a considerably large number of GD library
(A PHP add-on library used for image manipulation) functions, it requires your
version of PHP compiled with the GD library extension. 

The requirements can be summarized as given below:
	1. PHP > 4.x.x with GD support. (PHP 5 preferred)
		You can get the latest PHP version at http://www.php.net
	2. MySQL > 3.x.x
	3. Curl Support (For using Authorize.net, LinkPoint payment gateway)

    Other Requirements for Trouble free installation/working

	* SendMail - (Yes)
	* PHP safe mode - (OFF)
	* CURL extension - (Yes)
	* PHP -GD - (Yes) 

******************************************************************************************
3.0 Installing iScripts GoStores 
******************************************************************************************
3.1) Unzip the entire contents to a folder of your choice.

	a) Upload the contents to the server to the desired folder using an FTP client. If you do not have a FTP client we suggest  CoreFTP or FTPzilla

3.2) Run the following URL in your browser and follow the instructions.
		
	http://folder_to_which_you_have_extracted_files/
		
	If you have uploaded the files in the root (home directory), you can
	access the iScripts GoStores install wizard at http://www.yoursitename.
		
	You can also install the script in any directory under the root. For example if you 
	have uploaded the zip file in a directory like http://www.yoursitename/gostores then 
	you can access the site at http://www.yoursitename/gostores
		
	If you have no GD Library support in your PHP, recompile your PHP
	with GD Support to continue.
		
	If you have no Curl support in your PHP, recompile your PHP
	with Curl Support to continue.

	Make the changes requested by the installer if any and then refresh
	the installation url mentioned above to continue.
		
3.3) Provide the database details. (The database should be created and
       appropriate permissions must be set before you continue)

3.4) Make sure you enter the same license key you received at the time of purchase,in the 
	"License Key" field. The script would function only for the domain it is licensed.If you cannot recall the license its also included in the email you received with subject:			“iScripts.com software download link”. You can also get the license key from your user panel
	at www.iscripts.com    

3.5) Remove the 'Write' permission provided to the file 'project/config/settings.php' 
	and the folder to which you have extracted the files to. 

3.6) Delete the 'install' folder. 

3.7) Run the URL you have extracted the gostores files in your browser to access the
	site. If you have unzipped the files in the root (home directory), you can
	access the GoStores at http://www.yoursitename.

*******************************************************************************
4.0 Setting cron jobs
*******************************************************************************

4.1) To send automatic invoicing to the customers, automatic domain renewal
        and domain transfer etc set cron jobs/scheduled tasks to run the following files
        at 1 A.M daily.

	a)  wget -q -O /dev/null "http://www.yoursitename/admin/crongeneration/" > /dev/null 2>&1

	b)  wget -q -O /dev/null "http://www.yoursitename/admin/crongeneration/billattempt" > /dev/null 2>&1

	c)  wget -q -O /dev/null "http://www.yoursitename/admin/crongeneration/freetrialexpirynotification" > /dev/null 2>&1

	d)  wget -q -O /dev/null "http://www.yoursitename/admin/admin/crongeneration/billnotification" > /dev/null 2>&1

	e)  wget -q -O /dev/null "http://www.yoursitename/admin/admin/crongeneration/disableExpiredDomains" > /dev/null 2>&1

	f) /usr/bin/php -q /home/yourdomainusername/public_html/yourinstallationfolder/project/support/parser/pop3.php


*******************************************************************************
5.0 Upgrading from version 2.0 iScripts GoStores Platform
*******************************************************************************

 
Note: You will lose all customizations, that you have already done.



5.1) Download the new version of iScripts GoStores.


5.2) Take backup of the existing all files and database.


5.3) After replacing all your current files with the new ones, copy back the following files and folders from your backup


    - project/Demo/app/webroot/img/products/
    - project/Demo/app/webroot/img/csv/
    - project/Demo/app/webroot/Fax/
    - project/Demo/app/webroot/files/
    - project/Demo/app/webroot/files/File/
    - project/Demo/app/webroot/files/Flash/
    - project/Demo/app/webroot/files/Image/
    - project/Demo/app/webroot/files/Media/
    - project/Demo/app/webroot/files/Graph/
    - project/Demo/app/webroot/img/SiteLogo_disp.gif
    - project/Demo/app/webroot/img/SiteLogo.jpg
    - project/Demo/app/tmp/cache/
    - project/Demo/app/tmp/cache/models/
    - project/Demo/app/tmp/cache/views/
    - project/Demo/app/tmp/cache/persistent/
    - project/Demo/app/tmp/
    - project/Demo/app/tmp/logs/
    - project/Demo/app/tmp/sessions/
    - project/Demo/app/webroot/img/
    - project/Demo/app/webroot/css/
    - project/Demo/app/webroot/Fedex/shipping_label/
    - project/Demo/app/webroot/Fedex/
    - project/Demo/app/webroot/blog/wp-content/
    - project/Demo/app/webroot/blog/wp-config.php
    - project/Demo/app/controllers/components/pple.xml
    - project/Demo/app/config/database.php
    - project/Demo/app/webroot/config.php
    - project/support/custom/
    - project/support/styles/
    - project/support/attachments/
    - project/support/backup/
    - project/support/downloads/
    - project/support/csvfiles/
    - project/support/api/useradd.php
    - project/support/api/server_class.php
    - project/support/config/settings.php
    - project/support/admin/purgedtickets/
    - project/support/admin/purgedtickets/attachments/
    - project/support/staff/images/
    - project/support/fckeditorimages/
    - project/support/FCKeditor/editor/filemanager/connectors/php/config.php
    - project/files/
    - project/config/config.php
    - project/config/settings.php



5.4) Run the URL http://www.yoursitename/project/upgrade2.2/ and follow the instructions shown on screen. 
