<?php
class Db extends BaseModel{
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/*
	Common function to check the existance of an item
	*/
	function checkExists($table,$field,$where)
    {
		if($where!='')
			$where= ' WHERE '.$where;
    		$query = "SELECT count(".$field.") as cnt
    	         	 FROM ".$this->tablePrefix .$table.$where ;
				  				  
        $res = $this->execute($query);
    	return $this->fetchOne($res);
    }
    
	/*
	Function to insert the values to table
	*/
	function addFields($table,$postedArray)
	{
		foreach($postedArray as $key=>$val){
			$postedArray[$key] = addslashes($postedArray[$key]);  
		}
		return $this->insert($this->tablePrefix.$table, $postedArray);
	}
	
	/*
	Function to update the table details
	*/
	function updateFields($table,$postedArray,$condition)
	{
		
		foreach($postedArray as $key=>$val){
			$postedArray[$key] = addslashes($postedArray[$key]);  
		}
		
		return $this->update($this->tablePrefix.$table, $postedArray,$condition);
	}
	

	
	/*
	Common function to return the row fields
	*/
	function selectRow($table,$field,$where)
    {
		if($where!='')
			$where= ' WHERE '.$where;
    	 $query = "SELECT ".$field."
    	          FROM ".$this->tablePrefix .$table.$where ;
        $res = $this->execute($query);
    	return $this->fetchOne($res);
    }

	/*
	Common function to return the row fields
	*/
	function selectRecord($table,$field,$where)
    {
		if($where!='')
			$where= ' WHERE '.$where;
    	$query = "SELECT ".$field."
    	          FROM ".$this->tablePrefix .$table.$where ;
	
        $res = $this->execute($query);
    	return $this->fetchRow($res);
    }

	/*
 	Common function to return the resultset details
	*/
	function selectResult($table,$field,$where=NULL)
    {
		if($where!='')
			$where= ' WHERE '.$where;
    	$query = "SELECT ".$field."
    	          FROM ".$this->tablePrefix .$table.$where ;
        //echo $query.'<br>';
        $res = $this->execute($query);
    	return $this->fetchAll($res);
    }

	/*
	Function to delete the  keywords of a Brands
	*/
	function deleteRecord($table,$where)
	{
		$query = "DELETE FROM ".$this->tablePrefix  .$table.'  WHERE '.$where ;
        $res = $this->execute($query);

	}
	
	/*
	Function to execute a query to delete a records
	*/
	function customQuery($query)
	{
         $res = $this->execute($query);

	}
	
	
	/**
	 * Method to prepare custom Sql query ,Dont Use this for HTML TAGS BASED INSERTION
	 * @param  Query String with data field replaced with ?
	 * @param Array of data to be replaced in order 
	 * @param Status of PDO 0 if disabled 1 if enabled default 0 
	 * @param Type of database default mysql 
	 * @return Sql Query
	 * @example
	 * For Sql query <br>
	 * Select * from data where user ='username' AND ID=1<br>
	 * <br>
	 * $query="Select * from data where user='?' AND ID=?";<br>
	 * $dataArray=array("username");<br>
	 * $result= prepareQuery($query,$dataArray);<br>
	 * 
	 * 
	 **/
	
	function prepareQuery($query,$dataArray,$pdo=0,$databaseType="mysql")
	{
		$databaseType=strtolower($databaseType);
		
		switch ($databaseType)
		{
			case "mysql":
					{
						$count=preg_match_all('/\?/',$query,$matches);	
						if($count==count($dataArray))
						{
							if($pdo==0)
							{	
							for($i=0;$i<$count;$i++)
							{	
									$dataArray[$i]=strip_tags($dataArray[$i]);
									$dataArray[$i]=addslashes($dataArray[$i]);
									$query=preg_replace("/\?/",$dataArray[$i],$query,1);
									
								}
							}
							
						}
						else
						{
							return "Data Insufficient";
						}
						break;
					}
			default:
				{
					return "Unknown Database";
					break;
				}
					
		}
		
		return  $query;
	
	}
	
	
	

	/*
	Function to delete the  keywords of a Brands
	*/
	function selectQuery($query)
	{
		if($query != '')
		{
	        $res = $this->execute($query);
			return $this->fetchAll($res);
			 
		}
	}
	
	/*
	Function to fetch single row from result set after executing a query
	*/
	function fetchSingleRow($query)
	{
		if($query != '')
		{
	        $res = $this->execute($query);
	        $data = $this->fetchAll($res);
	        if($data) return $data[0]; 
		}
	}


	/*
	function to get the count of records 
	*/


	function getDataCount($table='',$selFields = '*',$where='')
	{
		$query 	= 'SELECT COUNT('.$selFields.') AS cnt FROM ' . $this->tablePrefix . $table .' ' .$where;				 
  		 // echo $query;
 		return $this->fetchOne($this->execute($query));
	}

	/*
	function to get the data for the page
	*/
	function getPageData($table='',$groupby = '',$sort_filed='',$sort_order='DESC',$selFields = '*',$where='',$join)
	{
 	
  		$query 	= 'SELECT '.$selFields.' FROM '.$this->tablePrefix. $table .'  ' . $join.'  ' .$where;				 
 
		if($groupby		!='')
			$query.=' GROUP BY '.$groupby;
		if($sort_filed		!='')
			$query.=' ORDER BY '.$sort_filed.' '.$sort_order.' ';
 			
			 //echo $query;
			 
  		$paging_qr		= 	$this->dopaging($query,'',PAGE_LIST_COUNT);
		$res			=	$this->execute($paging_qr);
		return $this->fetchAll($res);
	}

	
	
		/*
	function to get the data for the page
	*/
	function getPagingData($selFields = '*',$table='',$join ,$where='', $groupby = '',$sort_order='DESC',$sort_filed='',$limit='')
	{
	
	
  		$query 	= 'SELECT '.$selFields.' FROM '.$this->tablePrefix. $table .'  ' . $join .' '.$where;				 
 
 

		if($groupby		!='')
                    $query.=' GROUP BY '.$groupby;
		if($sort_filed		!='')
                    $query.=' ORDER BY '.$sort_filed.' '.$sort_order.' ';
					
		 //echo '<br>'.$query;
                if($limit!="")
                {
                   $query.=' LIMIT '.$limit;
                    $res    =	$this->execute($query);
                }
                else
                {
                    $paging_qr	= 	$this->dopaging($query,'',PAGE_LIST_COUNT);
                    $res	=	$this->execute($paging_qr);
                }
                return $this->fetchAll($res);

	}


		/*
	function to get the data for the page
	*/
	function getPagingCount($selFields = '*',$table='',$join ,$where='', $groupby = '',$sort_order='DESC',$sort_filed='')
	{
	
	
  		$query 	= 'SELECT COUNT('.$selFields.') AS cnt FROM '.$this->tablePrefix. $table .'  ' . $join .' '.$where;				 
 
 

		if($groupby		!='')
			$query.=' GROUP BY '.$groupby;
		if($sort_filed		!='')
			$query.=' ORDER BY '.$sort_filed.' '.$sort_order.' ';
		//   echo $query.'<br>';	
			
  		$paging_qr		= 	$this->dopaging($query,'',PAGE_LIST_COUNT);
		$res			=	$this->execute($paging_qr);
		return $this->fetchAll($res);
	}





		/*
	function to get all the datas without pagination
	*/
	function getAllData($selFields = '*',$table='',$join ,$where='', $groupby = '',$sort_order='DESC',$sort_filed='')
	{
	
   		$query 	= 'SELECT '.$selFields.' FROM '.$this->tablePrefix. $table .'  ' . $join .' '.$where;				 
 		if($groupby		!='')
                    $query.=' GROUP BY '.$groupby;
		if($sort_filed		!='')
                    $query.=' ORDER BY '.$sort_filed.' '.$sort_order.' ';
					
					
		 //echo  $query;		
         $res    =	$this->execute($query);
         return $this->fetchAll($res);
 	}
	
}
?>