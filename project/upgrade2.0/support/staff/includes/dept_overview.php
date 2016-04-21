          <!--^^^^^^^^^^^^^^^^^^^^^^^^DEPT OVERVIEW ^^^^^^^^^^^^^^^^^^^^^^^^^^^  -->
         
		 <div class="left_item_block">
		 <div class="left_item_title"><?php echo TEXT_FIELDS_DEPARTMENT_OVERVIEW ?></div>
		  
              <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
                      

<?php
								$lst_dept = "'',";
                                $sql = "Select nDeptId from sptbl_staffdept WHERE nStaffId='".$_SESSION["sess_staffid"]."'";
                                $result = executeSelect($sql,$conn);
                                $depts="";
                                $xxx=0;
                                while ($row =mysql_fetch_array($result)){
                                       ($xxx=="1")?$depts.=",":$depts.="";
                                       $depts.=$row["nDeptId"];
                                       $xxx=1;
									   $lst_dept .= $row["nDeptId"] . ",";
                                }
                                ($depts=="")?$depts="0":$depts=$depts;
								$lst_dept = substr($lst_dept,0,-1);
								$_SESSION['department_ids'] = $lst_dept;

$tot_tickets=0;
$tot_kbs=0;
$tot_corespondence=0;
//$sql = "select count(t.nTicketId) as ticketcount,d.vDeptDesc as deptname,c.vCompName as compnayname from sptbl_tickets t,sptbl_depts d,sptbl_companies c where t.nDeptId=d.nDeptId and c.nCompId=d.nCompId and d.nDeptId In($depts) and t.vDelStatus='0' group by t.nDeptId";
$sql = "select d.nDeptId,d.vDeptDesc as deptname,count(t.nTicketId) as ticketcount,c.vCompName as companyname
                 from sptbl_depts d left outer join sptbl_tickets t
                on d.nDeptId = t.nDeptId inner join sptbl_companies c on d.nCompId = c.nCompId
                Where d.nDeptId IN($depts) AND t.vDelStatus='0' group by t.nDeptId ";
$rs = executeSelect($sql,$conn);

if (mysql_numrows($rs) > 0){
         while($row = mysql_fetch_array($rs)) {
         echo "<tr>
         <td style=\"word-break:break-all;\" width='75%'>".htmlentities($row["deptname"])."<br>(".htmlentities($row["companyname"]).")</td><td ><b>".$row["ticketcount"]."</b></td></tr>";
         $tot_tickets = $tot_tickets + $row["ticketcount"];
         }
}

        if(isset($_SESSION["sess_totaltickets"]) && $_SESSION["sess_totaltickets"] == 0 ){
	        $_SESSION["sess_totaltickets"]=$tot_tickets;
        }

         $sql = "select count(nKBId) as kbcount from sptbl_kb where vStatus='A'";
         $result = executeSelect($sql,$conn);
         if(mysql_numrows($result) > 0){

            $row =mysql_fetch_array($result);
            $tot_kbs=$row["kbcount"];
         }

         //$sql = "select count(nReplyId) as replycount from sptbl_replies where vDelstatus='0'";
		 $sql = "select count(r.nReplyId) as replycount from sptbl_tickets t inner join sptbl_replies r
		  on t.nTicketId = r.nTicketId where r.vDelstatus='0' AND t.nDeptId IN($depts)";
         $result = executeSelect($sql,$conn);
         if(mysql_numrows($result) > 0){

            $row =mysql_fetch_array($result);
            $tot_corespondence=$row["replycount"] + $tot_tickets;
         }
?>
                      
              </table>
         

       <!--^^^^^^^^^^^^^^^^^^^^^^^^/DEPT OVERVIEW ^^^^^^^^^^^^^^^^^^^^^^^^^^^  -->
</div>