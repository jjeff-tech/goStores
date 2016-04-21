<?php
 
 /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                  |
// |                                                                                                           |
// +----------------------------------------------------------------------+

		include("./includes/session.php");
        include("../config/settings.php");
		include("./includes/functions/dbfunctions.php");
		include("./includes/functions/impfunctions.php");		 
		if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
                $_SP_language = "en";
        }else{
                $_SP_language = $_SESSION["sess_language"];
        }		
		
		include("./languages/".$_SP_language."/ajax.php");
                $var_staffid = $_SESSION["sess_staffid"];
		
		
        $conn = getConnection();
		
                $companyId  =   $_POST["companyId"];
                $deptId     =   $_POST["deptId"];
                $follow     =   $_POST["follow"];
                $ticketId   =   $_POST["ticketId"];
                $count      =   $_POST["count"];
                /*
                 *  Fetch Company Departments
                 */
                if($companyId!=''){
                        $sql = "Select nDeptId, vDeptDesc from  sptbl_depts where nCompId='".$companyId."' order by vDeptDesc desc";
                        $rs = executeSelect($sql,$conn);
                        $dept     = array();
                        while($row=mysql_fetch_array($rs)){

                            $dept[$row['nDeptId']]   =   $row['vDeptDesc'];

                        }
                        if(!empty($dept))
                            echo json_encode($dept);
                        else
                            echo json_encode('');
                        
                        exit;
                }

                if($deptId!=''){
                        $sql = "Select s.nStaffId, s.vStaffname from sptbl_staffs s LEFT JOIN sptbl_staffdept d ON s.nStaffId = d.nStaffId
                                where d.nDeptId ='".$deptId."' order by s.vStaffname ASC";
                        $rs = executeSelect($sql,$conn);
                        $staff     = array();
                        while($row=mysql_fetch_array($rs)){

                            $staff[$row['nStaffId']]   =   $row['vStaffname'];

                        }
                        if(!empty($staff))
                            echo json_encode($staff);
                        else
                            echo json_encode('');

                        exit;
                }

                /*
                 *  Follow / Unfollow Tickets
                 */
                if($ticketId!='' && $follow!=''){

                        $sql_sel = "SELECT count(*) FROM sptbl_follow_tickets WHERE nTicketId = '".$ticketId."' AND nStaffId = '".$var_staffid."' AND vStaffType = 'A' ";
                        $rs_sel  = executeSelect($sql_sel,$conn);
                        $num_sel = mysql_fetch_array($rs_sel);

                        if($num_sel[0] == 0 && $follow == 1){

                            $sql = "INSERT INTO sptbl_follow_tickets (nFollowId,nTicketId,nStaffId,vStaffType) values('','".$ticketId."','".$var_staffid."','A')";
                            $rs = executeQuery($sql,$conn);
                            echo json_encode('');
                            exit;

                        }else{

                            $sql = "DELETE FROM sptbl_follow_tickets WHERE nTicketId = '".$ticketId."' AND nStaffId = '".$var_staffid."' AND vStaffType = 'A' ";
                            $rs = executeQuery($sql,$conn);
                            echo json_encode('');
                            exit;

                        }
                }

                else if($ticketId!=''){

                    $sql_sel = "SELECT count(*) FROM sptbl_follow_tickets WHERE nTicketId = '".$ticketId."' AND nStaffId = '".$var_staffid."' AND vStaffType = 'A' ";
                    $rs_sel  = executeSelect($sql_sel,$conn);
                    $num_sel = mysql_fetch_array($rs_sel);

                    echo json_encode(array('count' => $num_sel[0], 'id' => $ticketId));
                    exit;

                }

                else if($count == 'followcount'){

                    $sql_sel = "SELECT count(*) FROM sptbl_follow_tickets WHERE nStaffId = '".$var_staffid."' AND vStaffType = 'A' ";
                    $rs_sel  = executeSelect($sql_sel,$conn);
                    $num_sel = mysql_fetch_array($rs_sel);

                    echo json_encode(array('totCount' => $num_sel[0]));
                    exit;

                }

                exit;
?>		