<?php
/*
 * All User Entity Logics should come here.
*/
class Cmshelper {

    public static $dbObj = null;
/*
 * function to fetch user help
 */
    
    public static function fetchInvoicesListOLD() {
        /*
        Cmshelper::$dbObj     = new Db();
        $helpData        = array();
        $helpData        = Cmshelper::$dbObj->selectResult("Invoice ","'vInvNo','vSubscriptionType','nUId','dGeneratedDate','dDueDate','dPayment','pStatus','nTotal'");
        return $helpData;
        */

         Cmshelper::$dbObj     = new Db();
        /*         
        //$invoiceDetails = array('vInvNo'=>'Vipi','vSubscriptionType'=>'Vipi','nUId'=>'Vipi','dGeneratedDate'=>'Vipi','dDueDate'=>'Vipi','dPayment'=>'Vipi','pStatus'=>'Vipi','nTotal'=>'Vipi');
         $invoiceDetails = $dbh->selectRecord("Invoice ","'vInvNo','vSubscriptionType','nUId','dGeneratedDate','dDueDate','dPayment','pStatus','nTotal'");
         return $invoiceDetails;
         //return '<a href="javascript:void(0)" class="invoiceDetails" name="'.$invoiceDetails->nInvId.'" >'.$invoiceDetails->vInvNo.'</a>';
        */      
        /*
         $dbh = new Db();
         $invoiceDetails = $dbh->selectRecord("Invoice ","'vInvNo','vSubscriptionType','nUId','dGeneratedDate','dDueDate','dPayment','pStatus','nTotal'");
         return $invoiceDetails;
         //return '<a href="javascript:void(0)" class="invoiceDetails" name="'.$invoiceDetails->nInvId.'" >'.$invoiceDetails->vInvNo.'</a>';
        */
         //SELECT vInvNo,vSubscriptionType,nUId,dGeneratedDate,dDueDate,dPayment FROM tbl_Invoice
            //return  Cmshelper::$dbObj->selectRecord("Invoice "," * ","vInvNo=2");
            /*        
            Cmshelper::$dbObj     = new Db();
            $helpData        = array();
            $helpData        = Cmshelper::$dbObj->selectResult("Invoice ","vInvNo,vSubscriptionType,nUId,dGeneratedDate,dDueDate,dPayment,nTotal");
            return $helpData;                
            */        
            $query = "SELECT * FROM tbl_Invoice";
            $res = Cmshelper::$dbObj->execute($query);//print_r($dbh->fetchAll($res));exit;
            return  Cmshelper::$dbObj->fetchAll($res);
        
    }

    
    public static function fetchInvoicesCountOLD() {
        Cmshelper::$dbObj     = new Db();
        $query = "SELECT COUNT(*) AS cnt FROM tbl_Invoice";
        $res = Cmshelper::$dbObj->execute($query);//print_r($dbh->fetchAll($res));exit;
        $return_array = Cmshelper::$dbObj->fetchAll($res);
        return $return_array->cnt;
    }

    
    
    
    
    public static function fetchInvoicesList($request,$perPageSize='8'){
        $dbh                     = new db();
        $tablePrefix             = $dbh->tablePrefix;

        $perPageSize             = $request['perPageSize'];
        $page                    = $request['page'];

        if($page == '')  $page   = 1;

        $startPage               = ($page-1)*$perPageSize;
        $limit                   = " LIMIT $startPage,$perPageSize";
        $cond = 1;

        
        if(trim($request['orderField']) != '')
        {
           if(trim($request['orderField']) == "leave_type")
              $order = "ORDER BY lvs.title ".$request['orderType']; 
           else
               $order = "ORDER BY ".$request['orderField']." ".$request['orderType']; 
        }
        else 
            $order = "ORDER BY vInvNo DESC";        
        
        
        
        if(trim($request['searchText']) != '')
        {
           if($request['searchField']== "ALL")
              $where = " (lvs.title LIKE '%" . $request['searchText']."%' OR empl.from_date LIKE '%" . $request['searchText']."%' OR empl.to_date LIKE '%" . $request['searchText']."%') AND ".$cond;
           else if($request['searchField'] == "leave_type")
                   $where = "lvs.title LIKE '%" . $request['searchText']."%' AND ".$cond ;
           else
               $where = $request['searchField']." LIKE '%" . $request['searchText']."%' AND ".$cond ;
        } 
        else 
            $where = $cond;
         
        $query = "SELECT * FROM ".$tablePrefix."Invoice WHERE $where $order $limit";
        $res = $dbh->execute($query);//print_r($dbh->fetchAll($res));exit;
        return  $dbh->fetchAll($res);
    }    
    
    
    public static function fetchInvoicesCount($request,$perPageSize='8'){
        $dbh                     = new db();
        $tablePrefix             = $dbh->tablePrefix;
        if($request['perPageSize']>0){
            $perPageSize             = $request['perPageSize'];
        }
        $page                    = $request['page'];

        if($page == '')  $page   = 1;

        $startPage               = ($page-1)*$perPageSize;
        $limit                   = " LIMIT $startPage,$perPageSize";
        
        echo 'Count - ';
        echo $limit;
        echo '<br/>';
        
        $cond = 1;

        
        if(trim($request['orderField']) != '')
        {
           if(trim($request['orderField']) == "leave_type")
              $order = "ORDER BY lvs.title ".$request['orderType']; 
           else
               $order = "ORDER BY ".$request['orderField']." ".$request['orderType']; 
        }
        else 
            $order = "ORDER BY vInvNo DESC";        
        
        
        
        if(trim($request['searchText']) != '')
        {
           if($request['searchField']== "ALL")
              $where = " (lvs.title LIKE '%" . $request['searchText']."%' OR empl.from_date LIKE '%" . $request['searchText']."%' OR empl.to_date LIKE '%" . $request['searchText']."%') AND ".$cond;
           else if($request['searchField'] == "leave_type")
                   $where = "lvs.title LIKE '%" . $request['searchText']."%' AND ".$cond ;
           else
               $where = $request['searchField']." LIKE '%" . $request['searchText']."%' AND ".$cond ;
        } 
        else 
            $where = $cond;
         
        $query = "SELECT COUNT(*)AS cnt FROM ".$tablePrefix."Invoice WHERE $where $order $limit";
        echo $query;
        echo '<br/>';
        $res = $dbh->execute($query);
        $return_array =  $dbh->fetchAll($res);
        echo $return_array->cnt;
        return $return_array->cnt;
    }    
    

    public static function formatBeforeDisplay($request){
        print_r($request);
        exit;
    }
    
    
    
    
} //End Class


?>