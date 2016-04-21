***************************************************************
           iScripts SupportDeskv4.3  Readme File
                 February 17, 2016
***************************************************************
(c) Copyright Armia Systems,Inc 2005-16. All rights reserved.

This file contains information that describes installation and 
usage of iScripts SUPPORTDESK 
You can find more help on the product and the installation in the /docs folder.


***************************************************************
Contents
***************************************************************
1.0 Introduction
2.0 Installing iScripts SUPPORTDESK
3.0 Operational Instructions for iScripts SUPPORTDESK 
4.0 Setting email parser
5.0 Supportdesk API
6.0 Upgrading from version2.0
7.0 Upgrading from version3.0
8.0 Upgrading from version4.0
9.0 Upgrading from version4.2

***************************************************************
1.0 Introduction
***************************************************************

This file contains important information you should read
before installing iScripts SUPPORTDESK. You will require PHP
and MySQL installed on your server to Proceed with installation. 


***************************************************************
2.0 Installing iScripts SUPPORTDESK 

2.1 Upload the unzipped contents to a web accessible directory on your website.
    For eg: http://yourdomain.com/support/ where “support” is the directory.    
    Make sure you upload the licence.txt file to the license folder inside  
    support directory. You can download the license.txt file by following the  
    link in the welcome email.

2.2 Create a MySQL database and a database user with full permission on the  
    database which just created. If you have any hosting control panel like   
    cPanel, Plesk, DirectAdmin or Helm you can do the same by logging into it.

2.3 Open a browser and point it to http://yourdomain.com/support/install/

    First of all, enter the same license key you received at the time of purchase,
    in the "License Key" field. The script would function only for the domain it 
    is licensed. If you cannot recall the license its also included in the email 
    you received with subject: “iScripts.com software download link”. You can also 
    get the license key from your user panel at www.iscripts.com

    Now you need to provide write permission (chmod 777) for a set of  
    files and folders as listed below which are necessary to continue the   
    installation. You may set these manually or just enter the 
    FTP credentials & let the script do it for you.

	a)	config/settings.php
	b)	attachments
	c)	downloads
	d)	custom
	e)	styles
	f)	backup
	g)	csvfiles
	h)	api/useradd.php
	i)	api/server_class.php
	j)	admin/purgedtickets
	k)	admin/purgedtickets/attachments	

    Now enter the Database info including the hostname, database name, 
    database user and password. If your MySQL server resides in the same machine 
    you can just enter “localhost” as database hostname. 

    Enter a couple of display preferences and again a few more 
    admin settings. You can always change these admin settings 
    once you login to the admin panel later.
  		

Remove the 'Write' permission provided to the file ‘config/settings.php'.

Delete the 'install' folder and you are ready to go. 

Follow the steps mentioned under 3.0(Operational instructions)for configuration.


***************************************************************
3.0 Operational Instructions for iScripts SUPPORTDESK 
***************************************************************

After you have successfully installed the iScripts 
SUPPORTDESK, you can configure and start using it by following
the instructions below.

3.1) Login to Admin Section (http://yourdomain.com/support/admin/) using 
     the following information.
	username	:	admin
	password	:	admin
	 
3.2) Create companies you may want your users to register with
	 (Companies->Add)

3.3) Create departments in each company (Departments->Add)

3.4) Add staff members (Staff->Add)

3.5) Assign staff members to departments  
	 (Departments -> Assign Staffs)

3.6) Create Knowledge base Categories  
	 (Knowledge base -> Add Categories)

3.7) Add Knowledge base entries  
	 (Knowledge base -> Add KB Entries)  


Staff can login to Staff section through the URL ( http://yourdomain.com/support/staff/ )

***************************************************************
4.0 Setting email parser
***************************************************************

You can generate tickets from email using either an email forwarder
or pop3. The procedure to implement this is listed below

4.1) To use mail forwarder for your ticket system  add 
     path_to_php -q yourinstalldirectory/parser/parser.php  as
     the forwarder address for your support mail address.
     an example could be  
     |/usr/bin/php -q /home/iscripts/public_html/install-folder/parser/parser.php 


     NOTE: 1) The default 'Support' department added during the 
	      installation process will use 'dept@yoursite.com' as its 
	      contact email. 
     	      Hence you need to set the forwarder for this email address. 
	      If you are setting a forwarder for a different address 
              please modify the email address of the department 'Support' 
              after logging into Admin Panel.
	   	

4.2) To use pop3 to fetch mail for your ticket system add 
     path_to_php -q yourinstalldirectory/parser/pop3.php  as
     "Command to run" in the cron setting.Set the cron to run in every minute.
     an example could be  
	/usr/bin/php -q /home/iscripts/public_html/install-folder/parser/pop3.php 

     NOTE: 1) The default 'Support' department added during the 
	      installation process will use 'dept@yoursite.com' as its 
	      contact email. Hence you need to set the same value in add 
	      pop3 configuration in the admin Panel

           2) All mails from your inbox will be deleted by the parser 
   	      after reading.


4.3) To use use the automatic escalation feature for your ticket system add 
     path_to_php -q yourinstalldirectory/admin/escalations.php as
     "Command to run" in the cron setting.Set the cron to run in every hour.
     an example could be  
	/usr/bin/php -q /home/iscripts/public_html/install-folder/admin/escalations.php 


4.4) To use use the weekly staff performance report feature for your ticket system add 
     path_to_php -q yourinstalldirectory/admin/cron_staffreportmail.php as
     "Command to run" in the cron setting.Set the cron to run in every week.
     an example could be  
	/usr/bin/php -q /home/iscripts/public_html/install-folder/admin/cron_staffreportmail.php 



***************************************************************
5.0 SupportDesk API
***************************************************************

iScripts Supportdesk provides an API to add users to the supportdesk
from your program.This could be used in systems proposed to have common
user database.More information on API integration is provided on 
"API Documentation.txt" located in 'api' folder.


***************************************************************
6.0 Upgrading from version2.0
***************************************************************

6.1 Download the new version of iScripts SupportDesk and replace 
     all your current files except config folder and license folder with the new  
     one

   NOTE: 1) You will lose all customizations; language will be set as English,
		and Cool green will be set as the default style for staff/users.

6.2 Run the URL http://yourdomain.com/updations and follow the 
    instructions shown on screen. 


***************************************************************
7.0 Upgrading from version3.0
***************************************************************
7.1 Download the new version of iScripts SupportDesk and replace 
     all your current files except config folder and license folder with the new  
     one

   NOTE: 1) You will lose all customizations; language will be set as English,
		and Cool green will be set as the default style for staff/users.

7.2 Run the URL http://yourdomain.com/upgrade-3.0 and follow the 
    instructions shown on screen. 


***************************************************************
8.0 Upgrading from version4.0
***************************************************************
8.1 Download the new version of iScripts SupportDesk and replace 
     all your current files except config folder and license folder with the new  
     one

   NOTE: 1) You will lose all customizations; language will be set as English,
		and Cool green will be set as the default style for staff/users.

8.2 Run the URL http://yourdomain.com/upgrade-4.0 and follow the 
    instructions shown on screen. 


***************************************************************
9.0 Upgrading from version4.2
***************************************************************
9.1 Download the new version of iScripts SupportDesk and replace 
     all your current files except config/settings.php file 

   NOTE: 1) You will lose all customizations; language will be set as English,
		and Aqua Blue will be set as the default style for staff/users.

9.2 Run the URL http://yourdomain.com/upgrade-4.3/ and follow the 
    instructions shown on screen. 



