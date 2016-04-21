<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | Singleton Database Class                                             |
// | File name : database.php                                             |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: BINU CHANDRAN.E<binu.chandran@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class BaseDatabase
{
	/**
    * Instance of this
	* @var instance
    **/
    private static $_instance = null;
    /**
    * Database connection
	* @var object
    **/
    private static $_connection = null;
	/**
	* constructor
	**/
    public function __construct()
    {
        
    }
	/**
	* Method to get instance of AbstractDatabase
	* @return object $_instance
	**/
    public static function getInstance()
    {
        if (self::$_instance == null) 
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
	/**
	* Method to connect mysql server
	**/
    public function _connect($host='',$uname='',$pwd='',$database='')
    {
	
         if($host){
            if (!empty(self::$_connection)) 
                self::$_connection = null;//@mysql_close(self::$_connection);
         if (!is_resource(self::$_connection)) {
               
                    // echo $host.'*****'.$uname.'******'.$pwd.'<br/>';
                     self::$_connection = new MySQLi($host,$uname,$pwd,$database);

                     mysqli_query("SET SESSION sql_mode ='' ");
                      
                     if(self::$_connection->connect_errno)
                     {
                       die("ERROR : -> ".self::$_connection->connect_error);
                     }else{
                        self::$_connection->query("SET SESSION sql_mode ='' ");
                     }
            }
        }else{
            if (!is_resource(self::$_connection)) {
               
                if (self::$_connection != null) 
                  self::$_connection->close();                 
                 self::$_connection = new MySQLi(MYSQL_HOST,MYSQL_USERNAME,MYSQL_PASSWORD,MYSQL_DB);
                  if(self::$_connection->connect_errno)
                     {
                       die("ERROR : -> ".self::$_connection->connect_error);
                     }

               
            }
        }
          //self::$_instance = self::$_connection;

    }

    public function getMysqlInstance() {
       if (self::$_connection instanceof MySQLi) {
            return self::$_connection;
       }
    }  

	/**
	* Method to close mysql connection
	**/
    public function close()
    {
        if (is_resource(self::$_connection)) 
        {
              self::$_connection->close();
        }
    }
    /**
	* Destructor
	**/
    public function __destruct()
    {
    	//Close sql connection
       // $this->close();
    }
}
?>