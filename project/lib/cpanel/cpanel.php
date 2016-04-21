<?php
class cpanel {

    public function installproduct($dbArray,$installPath,$productArray) {
        set_time_limit(0);
        $cpaneluser = WHM_USER_LOGIN;
        $cpanelpass = WHM_USER_PASSWORD;
        $db_host    = WHM_USER_HOST;


        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(CPANEL_PORT);

        $directories    = explode(',',$productArray['permissionlist'] );
        foreach($directories as $directory) {

            $fileName      = $installPath.trim($directory);
            $args = array(
                    'sourcefiles'      => $fileName,
                    'destfiles' => $fileName,
                    'op' => 'chmod',
                    'metadata'=> '0777',
            );
            $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );
        }
        $fileName      = $installPath;
        $args = array(
                'sourcefiles'      => $fileName,
                'destfiles' => $fileName,
                'op' => 'chmod',
                'metadata'=> '0755',
        );
        $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );
        sleep(3);
        //echo "Install Product<br>";
        //echo $installPath."install/index.php";
        
        include $installPath."install/index.php";

    }
    public function createsubdomain($subdom,$userArray,$productArray) {

        $db_host    = WHM_USER_HOST;
        $cpaneluser = WHM_USER_LOGIN;
        $cpanelpass = WHM_USER_PASSWORD;
        $subdom     = str_replace(" ", '', $subdom);


        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(CPANEL_PORT);

        $addsub = $xmlapi->api2_query($cpaneluser, "SubDomain", "addsubdomain", array("rootdomain"=>$db_host,"domain"=>$subdom));

        $result = $addsub['data']['result'];
        $pos = strpos($result,'has been added.');
        if((integer)$result != 1 && $result != "1") {
            $res ="Subdomain can not crete";
        }else {
            $res = "Subdomain created successfully";
            $type='file';
            $file=$productArray['packname'];
            $local_path=PRODUCT_LOCATION.$file;
            $remote_path=SUBDOMAIN_IN_SAME_SERVER.$subdom.'';
            $fileLocation=SUBDOMAIN_IN_SAME_SERVER.$subdom.'/';

            $args = array(
                    'sourcefiles'      => $local_path,
                    'destfiles' => $remote_path,
                    'op' => 'extract',
            );

            $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );

            /*
             * Remove the zip file
            */

//            $args = array(
//                    'sourcefiles'      => $local_path,
//                    'destfiles' => $remote_path,
//                    'op' => 'unlink',
//            );
//
//            $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );


            $argsFTP = array(
                    'ftp_user'      => $ftpUser,
                    'ftp_pass' => $ftpPassword,
            );

            $dbArray = $this->create_mysql_db_user($db_host,$cpaneluser,$cpanelpass,$port=CPANEL_PORT,$subdom);

            $dbArray = array_merge($dbArray, $argsFTP);
            $dbArray = array_merge($dbArray, $userArray);
            $this->installproduct($dbArray,$fileLocation,$productArray);
            
            
            
            return $dbArray;
        }
    }

    public function cratedbusers($subdom) {
        $db_host    = WHM_USER_HOST;
        $cpaneluser = WHM_USER_LOGIN;
        $cpanelpass = WHM_USER_PASSWORD;



        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(CPANEL_PORT);
        $dbArray = $this->create_mysql_db_user($db_host,$cpaneluser,$cpanelpass,$port=CPANEL_PORT,$subdom);
    }


//function to check if ftp entered values are correct
    public function check_ftp_info($domain,$user,$pass) {

        global $conn_id;
        $hostip = gethostbyname($domain);

        //check if ftp domain is correct
        if(ftp_connect($hostip)) {


            $conn_id = ftp_connect($hostip);
            // login with username and password
            if($login_result=ftp_login($conn_id, $user, $pass)) {

                //turn passive mode on
                ftp_pasv ( $conn_id, true );

                if ((!$conn_id) || (!$login_result)) {//see if ftp info is incorrect


                    $message.= "<font class=redtext>FTP connection has failed!";
                    $message.= "Attempted to connect to $host for user $ftp_user_name</font>";
                    $log.= $message."<br>";

                } else {

                    //ftp info is fine
                    $message="ok";
                    $log.= $message."<br>";
                }

            }else {

                $message= "<font class=redtext>Cannot Connect to ftp server.Invalid login info! </font>";
                $log.= $message."<br>";
            }


        }else {

            $message= "<font class=redtext>Cannot Connect to ftp server.Invalid domain provided!</font> ";
            $log.= $message."<br>";
        }

        return $message;

    }
############### Function to create a database and user on cpanel using ftp username and password......
    public function create_mysql_db_user($server,$cpanelusername,$cpanelpassword,$port=2083,$dbname='mycart') {
        $db_host = $server;
        $cpanelpass = $cpanelpassword;
        $cpaneluser = $cpanelusername;
        $dbx = substr(str_replace(" ", '', $dbname),0,6);
        ;//$dbname;
        $ln  = strlen($cpaneluser);
        $tln = 14 - $ln;

        $usrx =  substr(str_replace(" ", '', $dbname),0,6);
        $passx =  substr(str_replace(" ", '', $dbname),0,10);//$dbname;//random_password();
        $databasename = $dbx;/////Get database name
        $databaseuser = $usrx;/////Get database user
        //$databasepass = $passx;/////Get database password

        $databasepass = $this->generateStrongPassword();

        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_port($port);
        $xmlapi->set_output('json');//set this for browser output
        //create database
        $createdb = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($databasename));

        if (empty($createdb["data"]["result"])) {
            //create user
            $usr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduser", array($databaseuser, $databasepass));

            if(empty($usr["data"]["result"])) {
                $addusr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduserdb", array("".$cpaneluser."_".$databasename."", "".$cpaneluser."_".$databaseuser."", 'all'));

                $rtarr  =   array(
                        'db_name' => ACCOUNT_PREFIX . '_'.$databasename,
                        'db_user' => ACCOUNT_PREFIX . '_'.$databaseuser,
                        'db_password' => $databasepass,
                        'subdomain'   => $dbname,
                );
//                        print_r($rtarr);
                return $rtarr;
            }else {
                $rtarr["msg"]   =   $usr["data"]["result"];
            }
        }else {
            $rtarr["msg"]   =   $createdb["data"]["result"];
        }
        return $rtarr;
    }

//function to upload files/dirs
    public function do_ftp($local_path,$remote_path,$file,$type) {

        global $conn_id;
        if($type=="dir") {

            @ftp_mkdir($conn_id, $remote_path);
            $stat =  stat ($local_path);
            $mode = substr(decoct ($stat[mode]), -3);
            ftp_site($conn_id, 'CHMOD ' .$mode.' '.$remote_path);
            if (is_dir($local_path)) {

                if ($dh = opendir($local_path)) {

                    while (($files = readdir($dh)) !== false) {

                        if (($files != ".") && ($files != "..") && ($files != "thumbimages") && ($files != "Thumbs.db") &&  ($files != "resource.txt") ) {

                            $local_file=$local_path."/".$files;
                            $remote_file=$remote_path."/".$files;

                            if(is_dir($local_file)=="1") {

                                $log.=do_ftp($local_file,$remote_file,$files,'dir');

                            }

                            if(is_file($local_file)=="1") {

                                if (@ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
                                    $stat =  stat ($local_file);
                                    $mode = substr(decoct ($stat[mode]), -3);
                                    ftp_site($conn_id, 'CHMOD ' .$mode.' '.$remote_file);
                                    $log.= "<font class=greentext>successfully uploaded ".$file."/".$files."</font><br>";

                                } else {

                                    $log.= "<font class=redtext>There was a problem while uploading ".$file."/".$files."</font><br>";

                                }

                            }


                        }


                    }

                    closedir($dh);

                }
                return $log;

            }

        }else {
//                echo "$conn_id, $remote_path, $local_path, FTP_BINARY";
            ftp_chdir($conn_id, $local_path);

            if (ftp_put($conn_id, $remote_path, $local_path, FTP_BINARY)) {

                $log.= "<font class=greentext>successfully uploaded ".$file."</font><br>";
                $stat =  stat ($local_path);
                $mode = substr(decoct ($stat[mode]), -3);
                ftp_site($conn_id, 'CHMOD ' .$mode.' '.$remote_path);

            } else {

                $log.= "<font class=redtext>There was a problem while uploading ".$file."</font><br>";

            }

            // upload a file
            if (ftp_put($conn_id, $remote_path, $local_path, FTP_ASCII)) {
//                 echo "successfully uploaded $file\n";
            } else {
//                 echo "There was a problem while uploading $file\n";
            }

        }

        return $log;

    }

    public function extractzip($fileLocation) {
        $zip = new ZipArchive;
        $zipPath = $fileLocation.'/test.zip';
        $res = $zip->open($zipPath);
        if ($res === TRUE) {
            $zip->extractTo($fileLocation);
            $zip->close();
            echo 'Unzip was successful';
        }
        else {
            echo 'Unzip was not successful';
        }
    }



    public function createcpanelaccount_tmp() {
        // $db_host,$cpanelpass,$cpaneluser,$accontdet
        $cpaneluser     = WHM_USER_LOGIN;
        $cpanelpass     = WHM_USER_PASSWORD;
        $db_host    = WHM_USER_HOST;

        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        //create account
        $createac = $xmlapi->createacct($acctconf);
        if(isset($accdet) && !empty($accdet)) {
            if(isset($accdet["result"]["status"]) && $accdet["result"]["status"] == 1) {
                //drgf

            }
        }
        return $createac;
    }

    public function createcpanelaccount($username,$password,$domainName,$email,$productArray) {
        $error_msg = '';

        $cpaneluser     = WHM_USER_LOGIN;
        $cpanelpass     = WHM_USER_PASSWORD;
        $db_host        = WHM_USER_HOST;
        $databasepass   = $this->generateStrongPassword();

        $acctconf['username']       =   $username;
        $acctconf['password']       =   $databasepass;
        $acctconf['domain']         =   $domainName;
        $acctconf['plan']           =   SERVER_PACKAGE_NAME;
        $acctconf['contactemail']   =   $email;
        $acctconf['maxpark']        =       2;
        $acctconf['maxaddon']       =       2;
        //$acctconf['ip']           =       WHM_USER_IP;

        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(WHM_PORT);
//echopre($acctconf);
        try {
            $createac = $xmlapi->createacct($acctconf);
        } catch (Exception $e) {
            $acctconf['status'] = 0;
            $error_msg = $e->getMessage();
        }
        //echo '$error_msg='.$error_msg;
    //echo '$createac='.$createac;    
        //create account
        if(isset($createac) && !empty($createac)) {
            if(isset($createac["result"]["status"]) && $createac["result"]["status"] == 1) {
                //$acctconf['returnurl']        = "http://www.".$domainName."/";
                $acctconf['returnurl']              = "http://www.".$domainName."/";

                // if parked domain is enabled
                $siteOperationParkDomain = OPERATION_MODE_PARK_DOMAIN;

                if($siteOperationParkDomain == 'Y'){
                    /*
                     * Park domain
                    */
                    $xmlapi1 = new xmlapi($db_host);
                    $xmlapi1->password_auth("" . $cpaneluser . "", "" . $cpanelpass . "");
                    
                    $xmlapi1->set_debug(1); //output actions in the error log 1 for true and 0 false
                    $xmlapi1->set_output('json'); //set this for browser output

                    $xmlapi1->set_port(WHM_PORT);
                    //echo "<br/>username = ".$cpaneluser;
                    //echo "<br/>cpanel port = ".WHM_PORT;

                    $subDomainNamePreference = $this->splitDomainName($domainName);
                    
                    $infoArr = array();
                    if(!empty($domainName)){
                        $infoArr = explode(".",$domainName);
                        $topdomain = $infoArr[1];
                    }
                    //$subdomainName = $username.'.'.DOMAIN_NAME;

                    $subdomainName = $subDomainNamePreference.'.'.DOMAIN_NAME;
                    //echo "<br/>domain/subdomainName = ".$subdomainName;
                    //$topdomain = $domainName;
                    
                    //echo "<br/>topdomain = ".$subdomainName;
                    $args = array('domain' => $subdomainName,
                    ); //'topdomain' => $topdomain,

                    $r = $xmlapi1->api2_query($username, "Park", "park", $args);
                    //echo "<br/>Return value of park domain ==== <pre>"; print_r($r); echo "</pre>";
                    //$cc = $xmlapi1->api2_query($cpaneluser, "Park", "listparkeddomains", $args);
                    //echo "<br/>Return value of listed parked domains ==== <pre>"; print_r($cc); echo "</pre>";
                    //die();

                    $acctconf['tempdispurl'] = "http://" .$subdomainName.'/';

                    /*************** End Park Domain *******************/
                }

                

                
                $resCpanel = $this->createDomain($acctconf['username'],$acctconf,$productArray);
                
                
                if($resCpanel) {
                    $acctconf['status']     = 1;
                    $acctconf['statusmsg'] = 'Domain creation successful';
                } else {
                    $acctconf['status'] = 0;
                    $acctconf['statusmsg'] = 'Store setup failed';
                    $acctconf['tech_statusmsg'] = 'Store setup failed';
                }
            } else {
                $acctconf['status'] = 0;
                $acctconf['statusmsg'] = 'Cpanel account creation failed';
                $acctconf['tech_statusmsg'] = $createac["result"]["statusmsg"];
            }
        } else {
            $acctconf['status'] = 0;
            $acctconf['statusmsg'] = 'Cpanel account creation failed';
            if($error_msg <> '') {
                $acctconf['tech_statusmsg'] = $error_msg;
            } else {
                $acctconf['tech_statusmsg'] = 'Cpanel account creation failed';
            }
        }
        $acctconf['c_pass'] = $databasepass;
        $acctconf['db_name'] = $resCpanel['db_name'];
        $acctconf['db_user'] = $resCpanel['db_user'];
        $acctconf['db_password'] = $resCpanel['db_password'];
        
        
        
        return $acctconf;
        die;
    }

    function createDomain($cpanelacccountuser,$userArray,$productArray,$upgradeFlag=0) {

        
        
        
        
        set_time_limit(0);
        if($upgradeFlag==0) {
            //$db_host    = $userArray['ip'];
            $db_host    = WHM_USER_IP;
        }
        else {
            $db_host    = $userArray['domain'];
        }
        $cpaneluser = $userArray['username'];
        $cpanelpass = $userArray['password'];
        $subdom     = str_replace(".com", '', $userArray['domain']);
        $subdom     = str_replace("www.", '', $subdom);
        $userArray['store_name'] = $productArray['store_name'];


        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(CPANEL_PORT);

        //echo "<pre>"; print_r($userArray); echo "</pre>";
        //echo "<pre>"; print_r($productArray); echo "</pre>";

        $res = "Domain created successfully";
        $type='file';
        $file=$productArray['packname'];
        $local_path=PRODUCT_LOCATION.$file;
        // echo $remote_path='/home/'.$cpanelacccountuser.'/public_html/';
        $remote_path='/public_html/';
        $fileLocation='/home/'.$cpanelacccountuser.'/public_html/';//echo $db_host."****".$cpaneluser."****".$cpanelpass."****".$local_path."****".'/public_html/'.$file;//exit;
        $this->doftp($db_host,$cpaneluser,$cpanelpass,$local_path,'/public_html/'.$file);
        //$local_file = '/newdrive/home/perlscripts/public_html/gostores/project/products/vistacart.zip';
        $local_path = '/public_html/'.$file;
       // echo $local_path."&&&&".$remote_path;
        $args = array(
                'sourcefiles'      => $local_path,
                'destfiles' => $remote_path,
                'op' => 'extract',
        );
        $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );

        /*
             * Remove the zip file
        */

        $args = array(
                'sourcefiles'      => $local_path,
                'op' => 'unlink',
        );

        $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );

       /************************* SSL INSTALLATION ***********************/
        $ssl_domain = $userArray['domain'];
        $xmlapi->api2_query($cpaneluser, 'SSL', 'installssl',
            array(
                'domain'         => $ssl_domain,
                'cabundle'       => SSL_CA_BUNDLE,
                'crt'            => SSL_CERTIFICATE,
                'key'            => SSL_PRIVATE_KEY
            ));
        /************************* SSL INSTALLATION ***********************/

        $argsFTP = array(
                'ftp_user'      => $ftpUser,
                'ftp_pass' => $ftpPassword,
        );

        $dbArray = $this->create_mysql_db_user_domain($db_host,$cpaneluser,$cpanelpass,$port=CPANEL_PORT,$subdom);

        $dbArray = array_merge($dbArray, $argsFTP);
        $dbArray = array_merge($dbArray, $userArray);


        
         /********* Cron Creation ********************/
//            $xmlapi = new xmlapi($db_host);
//        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
//        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
//        $xmlapi->set_output('json');//set this for browser output
//        $xmlapi->set_port(CPANEL_PORT);
//        
//        $cronDomain = $userArray['domain'];
//        
//        
//            $command = "wget -q -O /dev/null 'http://$cronDomain/admin/crongeneration/' > /dev/null 2>&1";
//   
//     $set = $xmlapi->api2_query($cpaneluser, "Cron", "add_line", array(
//         "command"       => $command,
//    "day"           => '*',
//    "hour"          => '*',
//    "minute"        => '*/5',
//    "month"         => '*',
//    "weekday"       => '*'
//     ));
//            
//            
     
            
            /************* Cron Creation End******************/
        



        //$status=$this->installproductindomain($dbArray,$fileLocation,$productArray,$upgradeFlag);
        $status=$this->installScript($dbArray,$fileLocation,$productArray,$upgradeFlag);

        if($status) {
//                echo "store setup completed";

        }
        else {
//                echo "Store setup failed";
        }
        
        
        if($status){
            $status = array();
            $status['db_name'] = $dbArray['db_name'];
            $status['db_user'] = $dbArray['db_user'];
            $status['db_password'] = $dbArray['db_password'];
        }
        
        
       // echo "Create Domain";
        //echopre($status);
        
        return $status;
        die;
    }

    public function doftp($host,$ftp_user_name,$ftp_user_pass,$local_file,$ftp_path) {
     //$conn_id = ftp_connect('172.31.29.117');
     // set up basic connection

         // echo "<br/>host = ".$host;
         // echo "<br/>ftp_user_name = ".$ftp_user_name;
         // echo "<br/>ftp_user_pass = ".$ftp_user_pass;
         // echo "<br/>local_file = ".$local_file;
         // echo "<br/>ftp_path = ".$ftp_path;  

        sleep(12);
        $conn_id = ftp_connect($host);

//$local_file = '/home/perlscripts/public_html/gostores/project/products/vistacart.zip';
// login with username and password


        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);


// check connection
        if ((!$conn_id) || (!$login_result)) {
             echo "<br/>FTP connection has failed!";
//    echo "Attempted to connect to $ftp_server for user $ftp_user_name";
//            exit;
        } else {
            //echo "Connected to $ftp_server, for user $ftp_user_name";
            // upload the file
            ftp_pasv($conn_id, true);

            @ftp_site($conn_id, 'CHMOD 777 ' . $ftp_path);
            @ftp_site($conn_id, 'CHMOD 777 ' . $local_file);

            //echo '$ftp_path='.$ftp_path;
           // echo "<br>";
           // echo '$local_file='.$local_file;
            
            $upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);
            //echopre($upload);
        }



// check upload status
        if (!$upload) {
            //echo "FTP upload has failed!";
            $status = 0;
        } else {
            //echo "Uploaded $source_file to $ftp_server as $destination_file";
            $status = 1;
        }

// close the FTP stream
        ftp_close($conn_id);
        //echo "FTP Status = ".$status;

        return $status;
    }

############### Function to create a database and user on cpanel using ftp username and password......
    public function create_mysql_db_user_domain($server,$cpanelusername,$cpanelpassword,$port=2083,$dbname='mycart') {
        $db_host = $server;
        $cpanelpass = $cpanelpassword;
        $cpaneluser = $cpanelusername;
        $dbx = substr(str_replace(" ", '', $dbname),0,6);
        $dbx = str_replace(".", '', $dbx);
        ;//$dbname;
        $ln  = strlen($cpaneluser);
        $tln = 14 - $ln;

        $usrx =  substr(str_replace(" ", '', $dbname),0,6);
        $passx =  substr(str_replace(" ", '', $dbname),0,10);//$dbname;//random_password();

        /*
         * Dummy pass string
        */
        $passln  = strlen($passx);
        $dummyln = 10 - $passln;
        $dummypassString    = "5h%iH8kO";
        if($passln<10) {
            $passx = $passx.substr($dummypassString,0,$dummyln);
        }

        $databasename = $dbx;/////Get database name
        $databaseuser = $usrx;/////Get database user
        //$databasepass = $passx;/////Get database password

        $databasepass = $this->generateStrongPassword();

        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_port($port);
        $xmlapi->set_output('json');//set this for browser output
        //create database
        $createdb = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($databasename));
//        print_r($createdb);

        if (empty($createdb["data"]["result"])) {
            //create user
            $usr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduser", array($databaseuser, $databasepass));

            if(empty($usr["data"]["result"])) {
                $addusr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduserdb", array("".$cpaneluser."_".$databasename."", "".$cpaneluser."_".$databaseuser."", 'all'));

                $rtarr  =   array(
                        'db_name' => $cpanelusername.'_'.$databasename,
                        'db_user' => $cpanelusername.'_'.$databaseuser,
                        'db_password' => $databasepass,
                        'subdomain'   => $dbname,
                );
//                        print_r($rtarr);
                return $rtarr;
            }else {
                $rtarr["msg"]   =   $usr["data"]["result"];
            }
        }else {
            $rtarr["msg"]   =   $createdb["data"]["result"];
        }
        return $rtarr;
    }

    public function installproductindomain($dbArray,$installPath,$productArray,$upgradeFlag) {
        set_time_limit(0);

        $db_host    = $dbArray['ip'];
        $cpaneluser = $dbArray['username'];
        $cpanelpass = $dbArray['password'];

        if($upgradeFlag==0) {

            $xmlapi = new xmlapi($db_host);
            $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
            $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
            $xmlapi->set_output('json');//set this for browser output
            $xmlapi->set_port(CPANEL_PORT);


            // Set file permissions to 0777 to the required folders
            $directories    = explode(',',$productArray[permissionlist] );
            foreach($directories as $directory) {

                $fileName      = $installPath.trim($directory);
                $args = array(
                        'sourcefiles'      => $fileName,
                        'destfiles' => $fileName,
                        'op' => 'chmod',
                        'metadata'=> '0777',
                );
                $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );
            }

            // Set back file permissions to 0755 to the base folder
            $fileName      = $installPath;
            $args = array(
                    'sourcefiles'      => $fileName,
                    'destfiles' => $fileName,
                    'op' => 'chmod',
                    'metadata'=> '0755',
            );
            $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );


            $dbArray['user_email']   =   $dbArray['contactemail'];
            $dbArray['store_name']     = "My Account";
            sleep(5);
            //Installation based on server setting

            $siteOperationMode = OPERATION_MODE_SERVER;

            // if parked domain enabled status
            $siteOperationParkDomain = OPERATION_MODE_PARK_DOMAIN;

            if($siteOperationMode=='S' && $siteOperationParkDomain == 'N') {
                //if website and cart installation is in single server
                //Scenario : single server with no temporary URL
                include $installPath."app/webroot/install/index.php";
            } else {
                //if website and cart installation is in two different server
                // or Parked Domain is enabled

                // Scenario: 1) single server with temporary URL
                // Scenario: 2) multiple server with temporary URL

                //Curl Post

                if(isset($dbArray["tempdispurl"]) && !empty($dbArray["tempdispurl"])) {
                    // only if parked domain is enabled, and account creation is for domain
                    $url = $dbArray["tempdispurl"].'app/webroot/install/index-curl.php';
                } else {
                    $url = $dbArray["returnurl"].'app/webroot/install/index-curl.php';
                }

                $fieldArr = array("db_name" => $dbArray["db_name"],
                        "db_user" => $dbArray["db_user"],
                        "db_password" => $dbArray["db_password"],
                        "subdomain" => $dbArray["subdomain"],
                        "ftp_user" => $dbArray["ftp_user"],
                        "ftp_pass" => $dbArray["ftp_pass"],
                        "username" => $dbArray["username"],
                        "password" => $dbArray["password"],
                        "domain" => $dbArray["domain"],
                        "plan" => $dbArray["plan"],
                        "contactemail" => $dbArray["contactemail"],
                        //"ip" => $dbArray["ip"],
                        "ip" => WHM_USER_IP,
                        "returnurl" => $dbArray["returnurl"],
                        "store_name" => $dbArray["store_name"],
                        "installPath" => $installPath);

                $resultArr = $this->docURLPost($url, $fieldArr);

            }


        } else {

            //... physically there is no upgrade instead product restriction is overrided

        }
        // Write up the configuration for cart, this is common for both normal installation and upgrade
        $configurationFileContent = $productArray['xmlproductdata'];
        $operationArgArr = array(
                'dir'      => '/public_html/app/webroot/',
                'filename' => CONFIG_FILE_NAME,
                'content' => $configurationFileContent,
        );

        $status = $this->doCpanelOperation($db_host, $dbArray['username'], $dbArray['password'], $operationArgArr, $module='Fileman', $function='savefile');

        return TRUE;
        die;

    }


    public function installScript($dbArray,$installPath,$productArray,$upgradeFlag) {
        
        //echopre($dbArray);
       // echopre($installPath);
        //echopre($productArray);
        //echopre($upgradeFlag);
        
        
//        echo "InstallScrip";
//        echopre($upgradeFlag);
        
        if($upgradeFlag==0) {
            set_time_limit(0);
            /*@set_magic_quotes_runtime(0);

            if (get_magic_quotes_gpc()) {
                $_POST = array_map('stripslashes_deep', $_POST);
                $_GET = array_map('stripslashes_deep', $_GET);
                $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
            }*/


            //$db_host    = $dbArray['ip'];
            $db_host    = WHM_USER_IP;

            $cpaneluser = $dbArray['username'];
            $cpanelpass = $dbArray['password'];

            $xmlapi = new xmlapi($db_host);
            $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
            $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
            $xmlapi->set_output('array');//set this for browser output
            $xmlapi->set_port(CPANEL_PORT);

            // Set file permissions to 0777 to the required folders
           
            $directories    = explode(',',$productArray[permissionlist] );
             //echopre($productArray);
            foreach($directories as $directory) {

                $fileName      = $installPath.trim($directory);
                //echo $fileName.'<br>';
                $args = array(
                        'sourcefiles'      => $fileName,
                        'destfiles' => $fileName,
                        'op' => 'chmod',
                        'metadata'=> '0777',
                );
                $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );
            }


            // Set back file permissions to 0755 to the base folder
            $fileName      = $installPath;
                $args = array(
                        'sourcefiles'      => $fileName,
                        'destfiles' => $fileName,
                        'op' => 'chmod',
                        'metadata'=> '0755',
                );
                $r = $xmlapi->api2_query($cpaneluser, "Fileman", "fileop", $args );


            $dbArray['user_email']   =   $dbArray['contactemail'];
            $dbArray['store_name']     = "My Account";
            sleep(10);
            //Installation based on server setting



            $schemafile = PRODUCT_LOCATION."install/schema.sql";
            $datafile =  PRODUCT_LOCATION."install/data.sql";
            $wpconfigfile = PRODUCT_LOCATION."install/wp-config.php";

            //echo $schemafile.'---'.$datafile.'<br>';
            

            $txtDBServerName = "localhost";
            $txtTablePrefix = "VistaCart_";


            $txtSiteURL = $dbArray["returnurl"];
            $txtSiteURL = rtrim($txtSiteURL, "/");
            $txtSecureSiteURL = str_replace("http://", "https://", $txtSiteURL);

            /* * ********************************check server configuration **************************************************** */

            $server_flag = true;

            $val1 = ini_get("safe_mode");
            $val3 = ini_get("file_uploads");

            $gd = function_exists('gd_info');
            $curl = function_exists('curl_init');
            $mysql = function_exists('mysqli_connect');

            if (!empty($val1) || $val1 == 1) {
                $server_flag = false;
            } elseif (empty($val3) || $val3 != 1) {
                $server_flag = false;
            } elseif (!$gd) {
                $server_flag = false;
            } elseif (!$curl) {
                $server_flag = false;
            } elseif (!$mysql) {
                $server_flag = false;
            }

            $mysqlsupport = true;
            if (!function_exists('mysqli_connect')) {
                $mysqlsupport = false;
            }
            if (!$server_flag) {
                $serverconfiguration = "FAILURE";
            }
            else {
                $serverconfiguration = "OK";
            }
            
//echo $serverconfiguration;
            /* --------------------Check server PHP configuration--------------------------- */


            $error = false;
            $error_message = '';


            /* ---------------------------------------------------------------------------------------------------------------- */
            $post_flag = false;
            $dbconfigfile =$installPath."app/config/database.php";
            if(WHM_USER_IP=='3.218.115.137')
            {
            $txtDBServerName = "localhost"; //WHM_USER_IP;  //
            }else{
            $txtDBServerName = WHM_USER_IP;
            }
            $txtDBName = $dbArray['db_name'];
            $txtDBUserName = $dbArray['db_user'];
            $txtDBPassword = $dbArray['db_password'];
            $txtSiteName = $dbArray['store_name'];
            $txtAdminName = 'admin';
            $txtAdminPassword = 'admin';
            $txtConfirmAdminPassword = 'admin';
            $txtLicenseKey = '140CSI270101000011399221112STP';
            $txtAdminEmail = $dbArray['user_email'];
            $txtTablePrefix     = 'vista_';
//echopre($dbArray);
//echopre($_SESSION);

            $post_flag = true;
            $message = "";

            // Connect New Account's DB
            /*echo "<br/>txtDBServerName = ".$txtDBServerName;
            echo "<br/>txtDBUserName = ".$txtDBUserName;
            echo "<br/>txtDBPassword = ".$txtDBPassword;
            echo "<br/>txtDBName = ".$txtDBName; */

            @mysqli_close();
            $connectionForScript = @mysqli_connect($txtDBServerName, $txtDBUserName, $txtDBPassword,$txtDBName);
            //echo "<br/>mysqli error number = ".mysqli_connect_errno();
             if (mysqli_connect_errno())
        {
            $error = true;
        }




            if ($error) {
                //$message = "<u><b>Please correct the following errors to continue:</b></u>" . "<br><br>" . $message;
                //echo $message;
                return false;

            } else {
                //-------------------------UPDATE CONFIG FILE---------------------------//
                $uniqueid = time() . mt_rand() . session_id();
                if (strlen($uniqueid) > 15) {
                    $uniqueid = substr($uniqueid, 0, 15);
                    $uniqueid = md5($uniqueid);
                }

                $configcontent = "<?php\n";
                $configcontent .= "define('INSTALLED', true); \n\n";
                $configcontent .= "define('VERSION', '2.0'); \n\n";
                $configcontent .= "\n?>";

                // Write up the configuration for cart [/app/webroot/config.php]

                $operationArgArr = array(
                        'dir'      => '/public_html/app/webroot/',
                        'filename' => 'config.php',
                        'content' => $configcontent,
                );

                $status = $this->doCpanelOperation($db_host, $dbArray['username'], $dbArray['password'], $operationArgArr, $module='Fileman', $function='savefile');

                //---------------------------UPDATE THE DB CONNECTOR--------------------//
               /* $default = '$default';

                $dbconfigcontent = "<?php\n";
                $dbconfigcontent .= "class DATABASE_CONFIG { \n\n";
                $dbconfigcontent .= "var $default = array( \n\n";
                $dbconfigcontent .= "'driver' => 'mysqli', \n\n";
                $dbconfigcontent .= "'persistent' => false,\n\n";
                $dbconfigcontent .= "'host' => '" . $txtDBServerName . "',\n\n";
                $dbconfigcontent .= "'login' => '" . $txtDBUserName . "',\n\n";
                $dbconfigcontent .= "'password' => '" . $txtDBPassword . "',\n\n";
                $dbconfigcontent .= "'database' => '" . $txtDBName . "',\n\n";
                $dbconfigcontent .= "'prefix' => '" . $txtTablePrefix . "', \n\n";
                $dbconfigcontent .= "); \n\n";
                $dbconfigcontent .= "}\n\n";
                $dbconfigcontent .= "\n?>";*/

/* Old Cakephp databse Config
        $default = '$default';
        $thisDefault = '$this->default';
        $dbconfigcontent = "<?php\n";
        $dbconfigcontent .= "class DATABASE_CONFIG { \n\n"; 
        $dbconfigcontent .= "var $default = array( \n\n";
        $dbconfigcontent .= "'driver' => 'mysqli', \n\n";
        $dbconfigcontent .= "'persistent' => false,\n\n";
        $dbconfigcontent .= "'host' => '" . $txtDBServerName . "',\n\n";
        $dbconfigcontent .= "'login' => '" . $txtDBUserName . "',\n\n";
        $dbconfigcontent .= "'password' => '" . $txtDBPassword . "',\n\n";
        $dbconfigcontent .= "'database' => '" . $txtDBName . "',\n\n";
        $dbconfigcontent .= "'prefix' => '" . $txtTablePrefix . "' ";
        $dbconfigcontent .= "); \n\n";
        $dbconfigcontent .= "}\n\n";
        $dbconfigcontent .= "\n?>";
        
    */    
        
                $thisDefault = '$this->default';
                
                $defaultVar = '$default';
                
		$dbconfigcontent = "<?php\n";
		$dbconfigcontent .= "class DATABASE_CONFIG { \n\n";

		$dbconfigcontent .= "public $defaultVar = array();\n\n";
  
  		$dbconfigcontent .= "function __construct() {\n\n";
		$dbconfigcontent .= "$thisDefault = array( \n\n";
		$dbconfigcontent .= "'datasource' => 'Database/Mysql', \n\n";
		$dbconfigcontent .= "'persistent' => false,\n\n";
		$dbconfigcontent .= "'host' => '". $txtDBServerName ."',\n\n";
		$dbconfigcontent .= "'login' => '". $txtDBUserName ."',\n\n";
		$dbconfigcontent .= "'password' => '". $txtDBPassword ."',\n\n";
		$dbconfigcontent .= "'database' => '". $txtDBName ."',\n\n";
		$dbconfigcontent .= "'prefix' => '". $txtTablePrefix ."' \n\n";
		$dbconfigcontent .= "); \n\n";
		$dbconfigcontent .= "}\n\n";
		$dbconfigcontent .= "}\n\n";
		$dbconfigcontent .= "\n?>";
        


                // Write up the DB configuration for cart [/app/config/database.php]
                $operationArgArr = array(
                        'dir'      => '/public_html/app/Config/',
                        'filename' => 'database.php',
                        'content' => $dbconfigcontent,
                );

                $status = $this->doCpanelOperation($db_host, $dbArray['username'], $dbArray['password'], $operationArgArr, $module='Fileman', $function='savefile');

                //-------------------------UPDATE WP DB CONNECTOR-----------------------//

                $contents = file_get_contents($wpconfigfile);

                $contents = str_replace('CON_DB_NAME', $txtDBName, $contents);
                $contents = str_replace('CON_DB_USER', $txtDBUserName, $contents);
                $contents = str_replace('CON_DB_PASS', $txtDBPassword, $contents);
                $contents = str_replace('CON_DB_HOST', $txtDBServerName, $contents);
                $contents = str_replace('CON_DB_PREFIX', $txtTablePrefix . 'wp_', $contents);




                // Write up the WP configuration for cart [app/webroot/blog/wp-config.php]
                $operationArgArr = array(
                        'dir'      => '/public_html/app/webroot/blog/',
                        'filename' => 'wp-config.php',
                        'content' => $contents,
                );

                $status = $this->doCpanelOperation($db_host, $dbArray['username'], $dbArray['password'], $operationArgArr, $module='Fileman', $function='savefile');

                //------------------------UPDATE THE DB---------------------------------//
                $sqlquery = @fread(@fopen($schemafile, 'r'), @filesize($schemafile));
                $sqlquery = preg_replace('/Vista_/', $txtTablePrefix, $sqlquery);
                $sqlquery = $this->splitsqlfile($sqlquery, ";");

                for ($i = 0; $i < sizeof($sqlquery); $i++) {
                    mysqli_query($connectionForScript,$sqlquery[$i]);
                }

               // echo "Tables Created <br>";
                
                $dataquery = @fread(@fopen($datafile, 'r'), @filesize($datafile));
                $dataquery = preg_replace('/Vista_/', $txtTablePrefix, $dataquery);
                $dataquery = $this->splitsqlfile($dataquery, ";");

                for ($i = 0; $i < sizeof($dataquery); $i++) {
                    
                    //echo $dataquery[$i].'<br>';
                    
                    mysqli_query($connectionForScript,$dataquery[$i]);
                }


                //-------------------UPDATE INITIAL CONFIG VALUES-----------------------//
                $adminusername = addslashes($txtAdminName);
                $adminpassword = md5($txtAdminPassword);
                $adminmailpword = $txtAdminPassword;
                $adminblogpword = 'admin';

                
                $plan_id = $_SESSION['default_plan_id'];
                
                include_once(PRODUCT_LOCATION."install/wp-includes/class-phpass.php" );
                $wp_hasher = new PasswordHash( 8, TRUE );
                $hashed_wp_password = $wp_hasher->HashPassword( $adminblogpword );

                $sqladminsettings = "UPDATE " . $txtTablePrefix . "admins SET admin_name = '" . $adminusername . "',admin_pword ='" . $adminpassword . "', blog_pword = '" . $adminblogpword . "', email = '" . addslashes($txtAdminEmail) . "' WHERE admin_name = 'admin'";
                mysqli_query($connectionForScript,$sqladminsettings) or die(mysqli_error($connectionForScript));

                $sqlsettings = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($txtSiteName) . "' WHERE fieldname ='site_name'";
                mysqli_query($connectionForScript,$sqlsettings) or die(mysqli_error($connectionForScript));

                $sqlsettings = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($txtSiteBaseFolder) . "' WHERE fieldname ='sitebasefolder'";
                mysqli_query($connectionForScript,$sqlsettings) or die(mysqli_error($connectionForScript));

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($txtAdminEmail) . "' WHERE fieldname ='admin_email'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($txtLicenseKey) . "' WHERE fieldname ='vLicenceKey'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($plan_id) . "' WHERE fieldname ='subscribed_planid'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                 $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes(ADMIN_EMAILS) . "' WHERE fieldname ='super_admin_email'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                
                
                $plan_id = $_SESSION['default_plan_id'];

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($plan_id) . "' WHERE fieldname ='subscribed_planid'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                $template_id = ($_SESSION['default_template_id'])?$_SESSION['default_template_id']:1;

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($template_id) . "' WHERE fieldname ='subscribed_templateid'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                $main_site = SECURE_URL;
                
                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($main_site) . "' WHERE fieldname ='main_site'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($productArray['planProductRestriction']) . "' WHERE fieldname ='product_count'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($productArray['productAccessKey']) . "' WHERE fieldname ='store_access_key'";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                
                
                
                
                //WP configuration section 
                
                //Commented for disbaling blog in vistacart 30.05.2019 by Anoop
/*
                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_users SET user_pass = '" . addslashes($hashed_wp_password) . "', user_email = '" . addslashes($txtAdminEmail) . "' WHERE ID = 1";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $admin_link = $txtSiteURL . '/admins';
                $blog_link = $txtSiteURL . '/app/webroot/blog';

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_options SET option_value = '" . addslashes($blog_link) . "' WHERE option_id IN(1, 36)";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $wp_admin_menu_links = array('enabled' => 1, 'title' => 'Back To Main Store', 'title_link' => $admin_link, 'links' => array(), 'disabled_menus' => '');
                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_options SET option_value = '" . serialize($wp_admin_menu_links) . "' WHERE option_id IN(159)";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $sqlsettings2 = mysqli_query($connectionForScript,"SELECT ID, guid FROM " . $txtTablePrefix . "wp_posts");
                if($sqlsettings2) {
                    while($sqlres = mysqli_fetch_array($sqlsettings2)) {
                        $text_val = $sqlres['guid'];
                        $text_val = str_replace('http://localhost/vistacart', $txtSiteURL, $text_val);

                        mysqli_query($connectionForScript,"UPDATE " . $txtTablePrefix . "wp_posts SET guid = '" . addslashes($text_val) . "' WHERE ID = " . $sqlres['ID']);
                    }
                }

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_posts SET post_content = REPLACE(post_content, 'http://localhost/vistacart', '".$txtSiteURL."')";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_usermeta SET meta_key = '" . $txtTablePrefix . "wp_capabilities' WHERE umeta_id IN(10)";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_usermeta SET meta_key = '" . $txtTablePrefix . "wp_user_level' WHERE umeta_id IN(11)";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_usermeta SET meta_key = '" . $txtTablePrefix . "wp_dashboard_quick_press_last_post_id' WHERE umeta_id IN(14)";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "wp_options SET option_name = '" . $txtTablePrefix . "wp_user_roles' WHERE option_id IN(92)";
                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));
*/
                @mysqli_close($connectionForScript);

                Utils::reconnect();

            }
        }

         if($upgradeFlag==1)
        {
//            echopre($dbArray);
//            echopre($productArray);
//            echopre($installPath);
//            echopre($upgradeFlag);
            
//           $txtDBServerName = WHM_USER_IP;  //"localhost";
//            $txtDBName = $dbArray['db_name'];
//           $txtDBUserName = $dbArray['db_user'];
//            $txtDBPassword = $dbArray['db_password'];
//            $txtTablePrefix     = 'goStores_';
//           @mysqli_close();
//           $connectionForScript = @mysqli_connect($txtDBServerName, $txtDBUserName, $txtDBPassword,$txtDBName);
//            
             //$username = $dbArray['username'];
             //$plan_id = $productArray['productServices'][0];
//
//                $sqlsettings1 = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($plan_id) . "' WHERE fieldname ='subscribed_planid'";
//              // echo $sqlsettings1;
//                mysqli_query($connectionForScript,$sqlsettings1) or die(mysqli_error($connectionForScript));

            
          //  exit;
             //$storeArray = array();
             //$storeArray['subscribed_planid'] = $plan_id;
             //$storeArray['super_admin_email'] = ADMIN_EMAILS;
             
             //$url = 'http://' . WHM_USER_IP . '/~' . $username . '/Settings/updatesystemsettings';


//            $post = [
//                'settings_data' => json_encode($storeArray)
//            ];


            //$ch = curl_init($url);
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // execute!
            //$response = curl_exec($ch);

            // close the connection, release resources used
            //curl_close($ch);
            
            
            
        }  
        
        
        
        
        
        // Write up the configuration for cart, this is common for both normal installation and upgrade
        $configurationFileContent = $productArray['xmlproductdata'];
        $operationArgArr = array(
                'dir'      => '/public_html/app/webroot/',
                'filename' => CONFIG_FILE_NAME,
                'content' => $configurationFileContent,
        );
        $db_host    = WHM_USER_IP;
        $status = $this->doCpanelOperation($db_host, $dbArray['username'], $dbArray['password'], $operationArgArr, $module='Fileman', $function='savefile');
        return true;
    }


    public function createcpanelaccountforsubdomain($username,$password,$domainName,$email,$productArray) {

        set_time_limit(0);

        $error_msg = '';

        $cpaneluser     = WHM_USER_LOGIN;
        $cpanelpass     = WHM_USER_PASSWORD;
        $db_host        = WHM_USER_HOST;

        $databasepass = $this->generateStrongPassword();

        $acctconf['username']       =   $username;
        $acctconf['password']       =   $databasepass;
        $acctconf['domain']     =   $domainName;

        $acctconf['plan']       =   SERVER_PACKAGE_NAME;
        $acctconf['contactemail']   =   $email;
        //$acctconf['ip']                 =       WHM_USER_IP;

        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(WHM_PORT);

        try {
            $createac = $xmlapi->createacct($acctconf);
        } catch (Exception $e) {
            $acctconf['status'] = 0;
            $error_msg = $e->getMessage();
        }
      // echo "<pre>"; print_r($createac); echo "</pre>"; //die();

        //create account
        if(isset($createac) && !empty($createac)) {
            if(isset($createac["result"]["status"]) && $createac["result"]["status"] == 1) {
                $acctconf['returnurl']      = "http://www.".$domainName."/";

                $resCpanel = $this->createDomain($acctconf['username'],$acctconf,$productArray);
               //// echo "Cpanel lib";
             //  echopre($resCpanel);
                if($resCpanel) {
                    $acctconf['status']     = 1;
                    $acctconf['statusmsg'] = 'Domain creation successful';
                } else {
                    $acctconf['status']     = 0;
                    $acctconf['statusmsg'] = 'Domain creation failed';
                    $acctconf['tech_statusmsg'] = 'Domain creation failed';
                }
            } else {
                $acctconf['status']     = 0;
                $acctconf['statusmsg']          = 'Cpanel account creation failed';
                $acctconf['tech_statusmsg']     = $createac["result"]["statusmsg"];
            }
        } else {
            $acctconf['status'] = 0;
            $acctconf['statusmsg'] = 'Cpanel account creation failed';
            if($error_msg <> '') {
                $acctconf['tech_statusmsg'] = $error_msg;
            } else {
                $acctconf['tech_statusmsg'] = 'Cpanel account creation failed';
            }
        }
        $acctconf['c_pass'] = $databasepass;
        $acctconf['db_name'] = $resCpanel['db_name'];
        $acctconf['db_user'] = $resCpanel['db_user'];
        $acctconf['db_password'] = $resCpanel['db_password'];
        
        return $acctconf;
        die;
    }

    public function upgradeaccount($username,$password,$domainName,$email,$productArray) {
        $cpaneluser     = WHM_USER_LOGIN;
        $cpanelpass     = WHM_USER_PASSWORD;
        $db_host        = WHM_USER_HOST;

        $databasepass = $this->generateStrongPassword();

        $acctconf['username']       =   $username;
        $acctconf['password']       =   $databasepass;
        $acctconf['domain']         =   $productArray['domain'];


//        $acctconf['plan']     =   ACCOUNT_PREFIX . "_cloud";
        //SERVER_PACKAGE_NAME
        $acctconf['plan']       =   SERVER_PACKAGE_NAME;
        $acctconf['contactemail']   =   $email;
        $acctconf['MAXPARK'] = 2;
        $acctconf['MAXADDON'] = 2;

        $domainName                     =       $productArray['domain'];

//        modifyacct
        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(WHM_PORT);
        $args = array(
                'user '       => $username,
                'domain'      => $domainName,
                'MAXPARK'     => $acctconf['MAXPARK'],
                'MAXADDON'    => $acctconf['MAXADDON'],
        );

        try{
            $createac = $xmlapi->modifyacct($username,$args );
        } catch (Exception $e) {
            $acctconf['status'] = 0;
            $error_msg = $e->getMessage();
        }
        //echo "<br/>Before----><pre>"; print_r($createac); echo "<pre>";
        $createac = json_decode($createac,1);
        //echo "<br/>After---><pre>"; print_r($createac); echo "<pre>";
        //echo "<br/>Status = ".$createac["result"][0]["status"];
        //die();

        //$acctconf['ip']                 =       WHM_USER_IP;//"174.123.32.42";
        //create account
        if(isset($createac) && !empty($createac)) {
            if(isset($createac["result"][0]["status"]) && $createac["result"][0]["status"] == 1) {
                //return url
                $acctconf['returnurl']      = "http://www.".$domainName."/";
                // if parked domain is enabled
                $siteOperationParkDomain = OPERATION_MODE_PARK_DOMAIN;
                if($siteOperationParkDomain == 'Y'){
                    /** Park domain **/
                    $xmlapi1 = new xmlapi($db_host);
                    $xmlapi1->password_auth("" . $cpaneluser . "", "" . $cpanelpass . "");

                    $xmlapi1->set_debug(1); //output actions in the error log 1 for true and 0 false
                    $xmlapi1->set_output('json'); //set this for browser output

                    $xmlapi1->set_port(WHM_PORT);
                    //echo "<br/>username = ".$cpaneluser;
                    //echo "<br/>cpanel port = ".WHM_PORT;
                    $subDomainNamePreference = $this->splitDomainName($domainName);
                    //$subdomainName = $username.'.'.DOMAIN_NAME;
                    $subdomainName = $subDomainNamePreference.'.'.DOMAIN_NAME;

                    $args = array('domain' => $subdomainName, );
                    
                    $topdomain = $domainName;
                    $args = array('domain' => $subdomainName,'topdomain' => $topdomain
                    ); //'topdomain' => $topdomain,
                    
                    //$list = $xmlapi1->listparkeddomains($cpaneluser);
                    //echo "<br/>List of all parked domains ==== <pre>"; print_r($list); echo "</pre>";

                    $r = $xmlapi1->api2_query($cpaneluser, "Park", "park", $args);
                    //echo "<br/>Return value of park domain ==== <pre>"; print_r($r); echo "</pre>";
                    //$cc = $xmlapi1->api2_query($cpaneluser, "Park", "listparkeddomains", $args);
                    //echo "<br/>Return value of listed parked domains ==== <pre>"; print_r($cc); echo "</pre>";
                    //die();


                    $acctconf['tempdispurl'] = "http://" .$subdomainName.'/';

                    /*************** End Park Domain *******************/

                }


                 /********* Cron Creation ********************/
//            $xmlapi = new xmlapi($db_host);
//        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
//        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
//        $xmlapi->set_output('json');//set this for browser output
//        $xmlapi->set_port(CPANEL_PORT);
//        
//        $cronDomain = $domainName;
//        
//        
//            $command = "wget -q -O /dev/null 'http://$cronDomain/admin/crongeneration/' > /dev/null 2>&1";
//   
//     $set = $xmlapi->api2_query($cpaneluser, "Cron", "add_line", array(
//         "command"       => $command,
//    "day"           => '*',
//    "hour"          => '*',
//    "minute"        => '*/5',
//    "month"         => '*',
//    "weekday"       => '*'
//     ));
//            
//            
//     
            
            /************* Cron Creation End******************/
                
                
                
                
                
                if($this->updateInstalledProduct($acctconf['username'],$acctconf,$productArray,1)) {
                    $acctconf['status']     = 1;
                }
                else {
                    $acctconf['status']     = 0;
                }

                return $acctconf;
                die;
            } else {
                $acctconf['status']     = 0;
                return $acctconf;
                die;
            }


        }
    }

    function updateInstalledProduct($cpanelacccountuser,$userArray,$productArray,$upgradeFlag=0) {
//echo "updateInstalledProduct-$cpanelacccountuser,$userArray,$productArray,$upgradeFlag<br>";
        set_time_limit(0);
        if($upgradeFlag==0) {
            $db_host    = $userArray['ip'];
        }
        else {
            $db_host    = $userArray['domain'];
        }
        $cpaneluser = $userArray['username'];
        $cpanelpass = $userArray['password'];
        $subdom     = str_replace(".com", '', $userArray['domain']);
        $subdom     = str_replace("www.", '', $subdom);

        $fileLocation='/home/'.$cpanelacccountuser.'/public_html/';

        /********************* DB Entry ********************/

        $databasename = $databaseuser = $databasepass = NULL;

        $dbArray = array("db_name" => $databasename,
                "db_user" => $databaseuser,
                "db_password" => $databasepass,
                "subdomain" => $userArray['domain'],
                "ftp_user" => "",
                "ftp_pass" => "");
        /********************* DB Entry ********************/

        $dbArray = array_merge($dbArray, $userArray);

        //$status=$this->installproductindomain($dbArray,$fileLocation,$productArray,$upgradeFlag);
        $status=$this->installScript($dbArray,$fileLocation,$productArray,$upgradeFlag);

//        print_r($userArray);
        if($status) {
//                echo "store setup completed";

        }
        else {
//                echo "Store setup failed";
        }
        
        //$status['db_user'] = $databasename;
       // $status['db_user'] = $databaseuser;
        //$status['db_password'] = $databasepass;        
        
        return $status;
    }

    public function terminateaccount($username,$password,$domainName){
        $cpaneluser     = WHM_USER_LOGIN;
        $cpanelpass     = WHM_USER_PASSWORD;
        $db_host    = WHM_USER_HOST;
        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(WHM_PORT);
        $args = array(
                'user '       => $username,

        );
        $deleteac = $xmlapi->removeacct ($username,$args );
        $deleteac = json_decode($deleteac,1);
        if(isset($deleteac) && !empty($deleteac)) {
            if(isset($deleteac["result"][0]["status"]) && $deleteac["result"][0]["status"] == 1){
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
        die;
    }

    public function doFtpUploadAndCpanelOperations($serverInfoArr = array(), $ftpPathArr = array(), $operationArgArr = array()) {

        $status = array('ftp'=>'',
                'operations'=>'');

        if(!empty($serverInfoArr)) {

            // Todo : place file from the locations provided source path => destination path if paths are provided
            if(!empty($ftpPathArr)) {

                // Place file with ftp connection
                $status['ftp'] = $this->doftp($serverInfoArr['c_host'],$serverInfoArr['c_user'],$serverInfoArr['c_pass'],$ftpPathArr['source_path'],$ftpPathArr['destination_path']);
            }

            //Todo : cpanel file operations like extract, allow file permission etc.
            if(!empty($operationArgArr)) {

                // do file operations with cpanel
                $status['operations'] = $this->doCpanelOperation($serverInfoArr['c_host'], $serverInfoArr['c_user'], $serverInfoArr['c_pass'], $operationArgArr);
            }

        }


        return $status;

    } // End Function

    public function doCpanelOperation($c_host, $c_user, $c_pass, $operationArgArr=array(), $module='Fileman', $function='fileop') {
        // Todo: perform cpanel operations like extract file, allow folder permission
        $xmlapi = new xmlapi($c_host);
        $xmlapi->password_auth($c_user,$c_pass);
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(CPANEL_PORT);

        $r = $xmlapi->api2_query($c_user, $module, $function, $operationArgArr );

        return $r;

    } // End Function

    
    public function updateStoreDatabse($lookUpData,$storeArray)
    {
        $vAccountDetails = unserialize($lookUpData->vAccountDetails);
        if($vAccountDetails['db_name']){
            
            $txtTablePrefix = 'goStores_';
            
        if(WHM_USER_IP=='3.218.115.137')
            {
            $txtDBServerName = "localhost"; //WHM_USER_IP;  //
            }else{
            $txtDBServerName = WHM_USER_IP;
            }    
            
        $connectionForScript = @mysqli_connect($txtDBServerName, $vAccountDetails['db_user'], $vAccountDetails['db_password'],$vAccountDetails['db_name']);
        foreach($storeArray as $k=>$v){
                $sqlsettings = "UPDATE " . $txtTablePrefix . "settings SET value = '" . addslashes($v) . "' WHERE fieldname ='$k'";
                //echo $sqlsettings.'<br>';
                mysqli_query($connectionForScript,$sqlsettings);
                
        }
         @mysqli_close($connectionForScript);

                Utils::reconnect();
        }
        
    }
    
    
    public function createCpanelCronjob($res)
    {
        
        //echopre($res);
        if($res->bluedogdetails)
        {
            $vAccountDetails = unserialize($res->vAccountDetails);
            
            
           $host = WHM_USER_IP;
        
            
            
            
             /********* Cron Creation ********************/
            $xmlapi = new xmlapi($host);
        $xmlapi->password_auth("".$vAccountDetails['c_user']."","".$vAccountDetails['c_pass']."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(CPANEL_PORT);
        
        if($res->vSubDomain){
        $cronDomain = $vAccountDetails['c_host'];
        }else{
        $cronDomain = $vAccountDetails['c_host'];
        }
        
  $command = "wget -q -O /dev/null 'http://$cronDomain/products/cron_synchronize_inventory_source_products' > /dev/null 2>&1";
  // echo $command;
   $set = $xmlapi->api2_query($vAccountDetails['c_user'], "Cron", "add_line", array(
         "command"       => $command,
    "day"           => '*',
    "hour"          => '*',
    "minute"        => '*/5',
    "month"         => '*',
    "weekday"       => '*'
     ));
  // echopre($set);         
            
  $command1 = "wget -q -O /dev/null 'http://$cronDomain/products/cron_dump_inventory_source_products' > /dev/null 2>&1";
   //echo $command1;
   $set = $xmlapi->api2_query($vAccountDetails['c_user'], "Cron", "add_line", array(
         "command"       => $command1,
    "day"           => '*',
    "hour"          => '00',
    "minute"        => '5',
    "month"         => '*',
    "weekday"       => '*'
     ));   
   
   $command2 = "wget -q -O /dev/null 'http://$cronDomain/orders/update_inventory_source_orders' > /dev/null 2>&1";
   //echo $command1;
   $set = $xmlapi->api2_query($vAccountDetails['c_user'], "Cron", "add_line", array(
         "command"       => $command2,
    "day"           => '*',
    "hour"          => '*/3',
    "minute"        => '15',
    "month"         => '*',
    "weekday"       => '*'
     ));   
   
  $command2 = "wget -q -O /dev/null 'http://$cronDomain/products/cron_update_invsrc_product_inventory' > /dev/null 2>&1";
   //echo $command1;
   $set = $xmlapi->api2_query($vAccountDetails['c_user'], "Cron", "add_line", array(
         "command"       => $command2,
    "day"           => '*',
    "hour"          => '*/3',
    "minute"        => '30',
    "month"         => '*',
    "weekday"       => '*'
     ));  
   
  //echopre($set);          
            /************* Cron Creation End******************/
            
            
            
            
        }
        
        
        
        
    }
    
    

    public function upgradesubdomainaccount($username,$password,$domainName,$email,$productArray) {
        $cpaneluser     = WHM_USER_LOGIN;
        $cpanelpass     = WHM_USER_PASSWORD;
        $db_host    = WHM_USER_HOST;

        $acctconf['username']       =   $username;
        $acctconf['password']       =   $password;
        $acctconf['domain']     =   $productArray['domain'];

//        $acctconf['plan']     =   ACCOUNT_PREFIX . "_cloud";
        //SERVER_PACKAGE_NAME
        $acctconf['plan']       =   SERVER_PACKAGE_NAME;
        $acctconf['contactemail']   =   $email;

        $domainName                     =       $productArray['domain'];
//        modifyacct
        $xmlapi = new xmlapi($db_host);
        $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");
        $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false
        $xmlapi->set_output('json');//set this for browser output
        $xmlapi->set_port(WHM_PORT);
        //$acctconf['ip']                 =       WHM_USER_IP;//"174.123.32.42";

        /*
             * Setting configcontents to file
        */
        if($productArray['xmlproductdata']!="") {
            $dir      = $installPath.'app/webroot/';
            $args = array(
                    'dir'      => $dir,
                    'filename' => CONFIG_FILE_NAME,
                    'content' => $productArray['xmlproductdata'],
            );
            $r = $xmlapi->api2_query($username, "Fileman", "savefile", $args );
        }
    } // End Function

    public function enableDisableCpanelAccount($userAccountArr, $option = 'disable') {

        if(isset($userAccountArr['whm_user_host']) && isset($userAccountArr['whm_user_password']) && isset($userAccountArr['whm_user_login']) && isset($userAccountArr['c_user'])) {
            $cpaneluser = $userAccountArr['whm_user_login'];
            //$cpanelpass = $userAccountArr['whm_user_password'];
            $cpanelpass     = WHM_USER_PASSWORD;
            $db_host = $userAccountArr['whm_user_host'];

            $xmlapi = new xmlapi($db_host);
            $xmlapi->password_auth("" . $cpaneluser . "", "" . $cpanelpass . "");
            $xmlapi->set_debug(1); //output actions in the error log 1 for true and 0 false
            $xmlapi->set_output('json'); //set this for browser output
            $xmlapi->set_port(WHM_PORT);


            if($option == 'disable') { // Suspend account
                $cpanelActionResponse = $xmlapi->suspendacct($userAccountArr['c_user']);
            } else { // Activate back
                $cpanelActionResponse = $xmlapi->unsuspendacct($userAccountArr['c_user']);
            }
            $cpanelActionResponse = json_decode($cpanelActionResponse,1);

            //need to confirm the return array
            if (isset($cpanelActionResponse) && !empty($cpanelActionResponse)) {
                if (isset($cpanelActionResponse["result"][0]["status"]) && $cpanelActionResponse["result"][0]["status"] == 1) {
                    return true;
                } else { // if the response is failure from the Cpanel API return back without performing any operation
                    return false;
                }
            } else { // if there is no response from the Cpanel API return back without performing any operation
                return false;
            }
        } else { // if there is insufficient account info return back without performing any operation
            return false;
        }
    } // End Function

    public function docURLPost($url, $fieldArr) {
        $resultArr = array();

        $fields_string = NULL;
        $error = NULL;
        //url-ify the data for the POST
        foreach($fieldArr as $key=>$value) {
            $fields_string .=(!empty($fields_string)) ? '&' : '';
            $fields_string .= $key.'='.$value;
        }
        //
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        $result = curl_exec($ch);
        if(!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            // echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
        } else {
            $error = curl_error($ch);
        }
        //close connection
        curl_close($ch);
        //Curl Post end

        $resultArr = array('result' => $result, 'error' => $error);

        return $resultArr;
    } // End Function

    public function splitDomainName($domainName) {

        $infoArr = array();
        $subDomainPreference = NULL;

        if(!empty($domainName)) {

            $infoArr = explode(".",$domainName);
            $subDomainPreference = $infoArr[0];
        }

        return $subDomainPreference;

    } //End Function

    public function splitsqlfile($sql, $delimiter) {
        // Split up our string into "possible" SQL statements.
        $tokens = explode($delimiter, $sql);
        // try to save mem.
        $sql = "";
        $output = array();
        // we don't actually care about the matches preg gives us.
        $matches = array();
        // this is faster than calling count($oktens) every time thru the loop.
        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; $i++) {
            // Don't wanna add an empty string as the last thing in the array.
            if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
                // This is the total number of single quotes in the token.
                $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
                // Counts single quotes that are preceded by an odd number of backslashes,
                // which means they're escaped quotes.
                $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

                $unescaped_quotes = $total_quotes - $escaped_quotes;
                // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
                if (($unescaped_quotes % 2) == 0) {
                    // It's a complete sql statement.
                    $output[] = $tokens[$i];
                    // save memory.
                    $tokens[$i] = "";
                } else {
                    // incomplete sql statement. keep adding tokens until we have a complete one.
                    // $temp will hold what we have so far.
                    $temp = $tokens[$i] . $delimiter;
                    // save memory..
                    $tokens[$i] = "";
                    // Do we have a complete statement yet?
                    $complete_stmt = false;

                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
                        // This is the total number of single quotes in the token.
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        // Counts single quotes that are preceded by an odd number of backslashes,
                        // which means they're escaped quotes.
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                        $unescaped_quotes = $total_quotes - $escaped_quotes;

                        if (($unescaped_quotes % 2) == 1) {
                            // odd number of unescaped quotes. In combination with the previous incomplete
                            // statement(s), we now have a complete statement. (2 odds always make an even)
                            $output[] = $temp . $tokens[$j];
                            // save memory.
                            $tokens[$j] = "";
                            $temp = "";
                            // exit the loop.
                            $complete_stmt = true;
                            // make sure the outer loop continues at the right point.
                            $i = $j;
                        } else {
                            // even number of unescaped quotes. We still don't have a complete statement.
                            // (1 odd and 1 even always make an odd)
                            $temp .= $tokens[$j] . $delimiter;
                            // save memory.
                            $tokens[$j] = "";
                        }
                    } // for..
                } // else
            }
        }
        return $output;
    }

private function generateStrongPassword($length = 12, $add_dashes = false, $available_sets = 'luds')
{
    $sets = array();
    if(strpos($available_sets, 'l') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if(strpos($available_sets, 'u') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if(strpos($available_sets, 'd') !== false)
        $sets[] = '23456789';
    if(strpos($available_sets, 's') !== false)
        $sets[] = '!@#$%&*?';
    $all = '';
    $password = '';
    foreach($sets as $set)
    {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
    }
    $all = str_split($all);
    for($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[array_rand($all)];
    $password = str_shuffle($password);
    if(!$add_dashes)
        return $password;
    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while(strlen($password) > $dash_len)
    {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
}

}
?>