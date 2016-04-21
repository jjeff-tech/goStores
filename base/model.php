<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | Singleton Database Class                                             |
// | File name : database.php                                             |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: BINU CHANDRAN.E<binu.chandran@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Modified : ARUN SADASIVAN (01/07/2012)								  |
// |----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                    |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+
class BaseModel
{
    /**
    * db instance 
    * @var instance of AbstractDatabase
    **/
	protected $_db;
	/**
	* Table prefix
	* @var string 
	**/
	public $tablePrefix;
   /**
    protected variable for connection
    **/
  protected static $_connection_made=null; 

    /**
    * Constructor
    **/
	public function __construct($host='',$uname='',$pwd='',$database='',$prefix='')
    {
        $this->_db = BaseDatabase::getInstance();
         /* if($prefix)
          $this->tablePrefix = $prefix;
        else
          $this->tablePrefix = MYSQL_TABLE_PREFIX;

        $this->_db->_connect($host,$uname,$pwd,$database);

        $this->_MysqliConn = $this->_db->getMysqlInstance();*/
        $this->_db = BaseDatabase::getInstance();

        if($prefix)
          $this->tablePrefix = $prefix;
        else
        $this->tablePrefix = MYSQL_TABLE_PREFIX;
         if(!self::$_connection_made)
           {
            $this->_db->_connect($host,$uname,$pwd,$database);
            $this->_MysqliConn = $this->_db->getMysqlInstance();
            self::$_connection_made=$this->_MysqliConn;
           }
          else 
           {
           $this->_MysqliConn=self::$_connection_made;    
           }

          
    }
    /**
    * Method to execute mysql queries
    * @param string $query
    **/
	final public function execute($query)
    {
           // echo $query.'<br>';
          
       if(PageContext::$debug){
       	   $sqlObj = new stdClass();
       	   $sqlObj->query = $query;
       	   $sqlObj->start = microtime(true);
       	   PageContext::$debugObj->sqls[] = $sqlObj;
       }

       $res = $this->_MysqliConn->query($query); 

       if (PageContext::$debug) {
        $sqlObj->timetaken = microtime(true) - $sqlObj->start;
       }
       
       return $res;
    }
    /**
    * Method to return the id of last  executed query
    * @param string $query
    * return integer
    **/
	final public function lastInsertId()
    {

        $res= $this->_MysqliConn->insert_id; 
        return $res;
    }
    /**
    * Method to fetch all rows from a result set
    * @param resource $resultSet
    * @return array $resultArray
    **/
	final public function fetchAll($resultSet)
	{
		$resultArray = array();
     if(!empty($resultSet)){
      while($obj = $resultSet->fetch_object())
      {
        //echopre($obj);
        $resultArray[] = $obj;
      }
    }
  if(empty($resultArray)) {
            return array();
        }
        return $resultArray;
	}
     /**
    * Method to fetch result as pair from a result set
    * @param resource $resultSet
    * @return array $resultArray
    **/
	final public function fetchPair($resultSet)
	{
		$resultArray = array();
		while($obj = $resultSet->fetch_array(MYSQLI_NUM))
		{
			$resultArray[$obj[0]] = $obj[1];
		}
		return $resultArray;
	}
	/**
    * Method to fetch one item from a result set
    * @param resource $resultSet
    * @return array $resultArray
    **/
	final public function fetchOne($resultSet)
	{
		 $result =  $resultSet->fetch_array(MYSQLI_NUM);
        return isset($result[0]) ? $result[0] : false;
	}
    /**
     * Method to get result set as an enumerated array
     * @param <type> $resultSet
     * @return <type> Enumerated array containing the query result
     */
    final public function fetchNumeric($resultSet){
        $resultArray  = array();
        while($obj =  $resultSet->fetch_row()){
            $resultArray[] = $obj[0];
        }
        return $resultArray;
    }
    /**
    * Method to fetch single row from a result set
    * @param resource $resultSet
    * @return array $resultArray
    **/
	final public function fetchRow($resultSet)
	{
		$resultArray = array();
		while($obj = $resultSet->fetch_object())
		{
			$resultArray[] = $obj;
		}
        if(isset($resultArray[0])) {
            return $resultArray[0];
        }
		return $resultArray;
	}
	/**
	* Method to insert rows 
	* @param string $table
	* @param array $data
	**/
    final public function insert($table, array $data,$doAudit=false)
    {
        $query = 'INSERT INTO ' . $table . ' SET ';
        $query .= $this->_buildQueryString($data);

       if(PageContext::$debug){
       	   $sqlObj = new stdClass();
       	   $sqlObj->query = $query;
       	   $sqlObj->start = microtime(true);
       	   PageContext::$debugObj->sqls[] = $sqlObj;
       }
       //echo $query.'<br>';
       $this->execute($query);
        
       if (PageContext::$debug) {
        $sqlObj->timetaken = microtime(true) - $sqlObj->start;
       }

         $insert_id = $this->_MysqliConn->insert_id;
       
       //add to audit table
       if($doAudit) Dbaudit::auditRecord($table,$data,$insert_id,'insert');
       
	   return $insert_id;
    }
	/**
	* Method to update table
	* @param string $table
	* @param array $data
	* @param string $where
	**/
    final public function update($table, array $data, $where,$doAudit=false)
    {
       $query = 'UPDATE ' . $table . ' SET ';
       $query .= $this->_buildQueryString($data);
       $query .= ' WHERE '.$where;
        
       if(PageContext::$debug){
       	   $sqlObj = new stdClass();
       	   $sqlObj->query = $query;
       	   $sqlObj->start = microtime(true);
       	   PageContext::$debugObj->sqls[] = $sqlObj;
       }
       
       $this->execute($query);
        
       if (PageContext::$debug) {
        $sqlObj->timetaken = microtime(true) - $sqlObj->start;
       }
        
       if($doAudit)Dbaudit::auditRecord($table,$data,$where,'update');
        
       return $this->hasAffected();
    }
    /**
     * function to print the query
     * @param <type> $type
     * @param <type> $table
     * @param array $data
     * @param <type> $where
     * @param <type> $stop
     */
    final public function printQuery($type, $table, array $data = array(), $where = '', $stop = '' ){
        if(strtolower($type) == 'insert'){
            $query = 'INSERT INTO ' . $table . ' SET ';
            $query .= $this->_buildQueryString($data);
        }else if(strtolower($type) == 'update'){
            $query = 'UPDATE ' . $table . ' SET ';
            $query .= $this->_buildQueryString($data);
            $query .= ' WHERE ' . $where;
        }else if(strtolower($type) == 'delete'){
            $query = 'DELETE FROM ' . $table;
            $query .= ' WHERE ' . $where;
        }
         echo $query;
         if($stop != ''){
             exit;
         }
    }
	/**
	* Method to delete rows from table
	* @param string $table
	* @param array $data
	* @param string $where
	**/
    final public function delete($table, $where,$doAudit)
    {
        $query = 'DELETE FROM ' . $table;
        $query .= ' WHERE ' . $where;
        
       if(PageContext::$debug){
       	   $sqlObj = new stdClass();
       	   $sqlObj->query = $query;
       	   $sqlObj->start = microtime(true);
       	   PageContext::$debugObj->sqls[] = $sqlObj;
       } 
       
       $this->execute($query);
        
       if($doAudit)Dbaudit::auditRecord($table,"",$where,'delete');
        
       if (PageContext::$debug) {
        $sqlObj->timetaken = microtime(true) - $sqlObj->start;
       }
        return $this->hasAffected();
    }
	/**
	* Method to get status has affected 
	**/
    final public function hasAffected()
    {
       // return mysql_affected_rows() > 0;
      return $this->_MysqliConn->affected_rows > 0;
    }
	/**
	* Method to get number of rows affected
	**/
    final public function affectedRows()
    {
      return $this->_MysqliConn->affected_rows;
      //  return mysql_affected_rows();
    }
	/**
	* Method to build mysql query string from an array 
	* @param array $data
	**/
    private function _buildQueryString(array $data)
    {
        $columnCount    = count($data);
        $currentColumn  = 1;
		$query = '';
        foreach ($data as $column => $value) {
            $query .= $column . ' = "' . $value.'"';
            if ($currentColumn++ <  $columnCount) {
                $query .= ', ';
            }
        }
        return $query;
    }
    /**
     *
     * Method to escape the string input by the user
     * @param string $data
     * @return $data
     */
    final public function escapeString($data)
    {
       //return "'" . $this->_MysqliConn->real_escape_string($data) . "'" ;
      return  $this->_MysqliConn->real_escape_string($data);
    }

     
 
    
   	public  function dopaging($sql,$numrecords=0,$limit=15,$pagestart=1)
	{
 	 //$temp           = $this->_MysqliConn->query($sql) or die($this->_MysqliConn->error);
  $temp  = $this->execute($query);

  $numrows        = $temp->num_rows;
	
	/*
	 *  $start = LibPager::findStart($limit,$numrows); 
	 * new code added by jinson to pass the page number
	 */
	$start = LibPager::findStart($limit,$numrows,$pagestart); 
	if ($numrecords == 0)
	  LibPager::$pages = LibPager::findPages($numrows, $limit);
	else{
	if ($numrows > $numrecords)   
	  LibPager::$pages = LibPager::findPages($numrecords, $limit);
	else
	 LibPager:: $pages = LibPager::findPages($numrows, $limit);
	}
	if ($numrecords == 0)
	  $sql1 = $sql . " LIMIT " . $start. ", ". $limit ;
	else{
	 if (($start + $limit) > $numrecords) 
	   $sql1 = $sql . " LIMIT " . $start. ", ". ($numrecords - $start);
	 else
	   $sql1 = $sql . " LIMIT " . $start. ", ". $limit ;
	}
 		return $sql1;
		
	}

	function checkTableExist($table)
	{
		$query="SHOW TABLES LIKE '".$table."'" ;
		$result=$this->execute($query);
		if($this->fetchRow($result))
			return 1;
		else
			return 0;
	
	
	}
	function selectResultFrom($table,$field,$where)
	{
		if($where!='')
			$where= ' WHERE '.$where;
		$query = "SELECT ".$field."
    	          FROM ".$table.$where ;
	
		$res = $this->execute($query);
		return $this->fetchAll($res);
	}
	
}
?>