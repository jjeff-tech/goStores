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
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/escalations.php");
        $conn = getConnection();

        // Fetching all Rules
        $sql_rules = "SELECT nERId, nDeptId, nResponseTime, nStaffId, nResponseCount, eRespTimeSetting,	eRespCountSetting FROM sptbl_escalationrules WHERE nStatus = '0'
                        ORDER BY nDeptId ASC";
        $rs_rules = mysql_query($sql_rules) or die(mysql_error());
        $rule_array =   array();        
        while( $row_rules     =   mysql_fetch_array($rs_rules) ){

            if(!empty ($rule_array[$row_rules['nDeptId']])){ // If dept array not empty

                $newarray   =   array(
                                        "RuleId"    => $row_rules['nERId'],
                                        "Staff"     => $row_rules['nStaffId'],
                                        "TimeSett"  => $row_rules['eRespTimeSetting'],
                                        "CountSett" => $row_rules['eRespCountSetting'],
                                        "Time"      => $row_rules['nResponseTime'],
                                        "Count"     => $row_rules['nResponseCount'],
                                    ) ;
                array_push($rule_array[$row_rules['nDeptId']], $newarray);

            }else{

                $rule_array[$row_rules['nDeptId']] =  array(
                                                        array(
                                                        "RuleId"    => $row_rules['nERId'],
                                                        "Staff"     => $row_rules['nStaffId'],
                                                        "TimeSett"  => $row_rules['eRespTimeSetting'],
                                                        "CountSett" => $row_rules['eRespCountSetting'],
                                                        "Time"      => $row_rules['nResponseTime'],
                                                        "Count"     => $row_rules['nResponseCount'],
                                                    ));
            }//end else
        }//end while

        
        /*
         *  Esclalate tickets based on Response Time
         */
        $sql_mins = "SELECT TIMEDIFF(r1.dDate,t1.dPostDate) AS TimeBreak , r1.nReplyId, t1.nTicketId, t1.nDeptId FROM sptbl_tickets t1 LEFT JOIN  sptbl_replies r1 ON t1.nTicketId = r1.nTicketId
                    WHERE r1.nReplyId IN (
                        SELECT MIN(r.nReplyId) FROM sptbl_replies r WHERE  r.nTicketId IN (
                            SELECT nTicketId FROM sptbl_tickets t )
                        AND r.vDelStatus = '0' GROUP BY r.nTicketId ORDER BY r.nTicketId ASC  )
                    AND t1.vDelStatus = '0' AND t1.vStatus != 'closed' ORDER BY t1.nDeptId ASC
                ";     
	
        $result = mysql_query($sql_mins) or die(mysql_error());
        
        while( $row     =   mysql_fetch_array($result) ){
            //echo $row['TimeBreak'].'<br>';

            list ($hr, $min, $sec) = explode(':',$row['TimeBreak']);

            $totalmins = 0;

            $totalmins = (((int)$hr) * 60 ) + (((int)$min) ) ;

            // Rule Corresponding to dept exists
            if(!empty ($rule_array[$row['nDeptId']])){

                checkResponseTime($row['nDeptId'],$row['nTicketId'],$totalmins);

            }//end if
            
        }//end while

        /*
         *  Esclalate tickets based on Response Count
         */
        $sql_count  =   "SELECT Count(*) AS TotalCount, t1.nTicketId, t1.nDeptId FROM sptbl_replies r1 LEFT JOIN sptbl_tickets t1 ON t1.nTicketId = r1.nTicketId
                         WHERE  r1.nTicketId IN (
                            SELECT nTicketId FROM sptbl_tickets t WHERE t.vDelStatus = '0' AND t.vStatus != 'closed' )
                         AND r1.vDelStatus = '0' GROUP BY r1.nTicketId ORDER BY r1.nTicketId ASC  ";

        $rs_count = mysql_query($sql_count) or die(mysql_error());

        while( $row_count     =   mysql_fetch_array($rs_count) ){
            //echo $row['TotalCount'].'<br>';

            // Rule Corresponding to dept exists
            if(!empty ($rule_array[$row_count['nDeptId']])){

                checkResponseCount($row_count['nDeptId'],$row_count['nTicketId'],$row_count['TotalCount']);

            }//end if

        }//end while


        /*
         *  Esclalate tickets if No Reply
         */
        $sql_noreply   =    "SELECT TIMEDIFF(now(),t1.dPostDate) AS TimeBreak, t1.nTicketId, t1.nDeptId FROM sptbl_tickets t1 WHERE t1.nTicketId NOT IN (
                                SELECT r.nTicketId FROM sptbl_replies r WHERE r.vDelStatus = '0' )
                             AND t1.vDelStatus = '0' AND t1.vStatus != 'closed' ORDER BY t1.nDeptId ASC   ";

        $rs_noreply    =    mysql_query($sql_noreply) or die(mysql_error());

        while( $row_noreply     =   mysql_fetch_array($rs_noreply) ){
            //echo $row['TimeBreak'].'<br>';

            list ($hr, $min, $sec) = explode(':',$row_noreply['TimeBreak']);

            $totalmins = 0;

            $totalmins = (((int)$hr) * 60 ) + (((int)$min) ) ;

            // Rule Corresponding to dept exists
            if(!empty ($rule_array[$row_noreply['nDeptId']])){

                checkResponseTime($row_noreply['nDeptId'],$row_noreply['nTicketId'],$totalmins);

            }//end if

        }//end while
        

        /*
         * Function to escalate ticket based on response time
         */
        function checkResponseTime($deptId,$ticketId,$minute){
        global $rule_array;

                foreach ($rule_array[$deptId] as $array) {                   

                    if($array['TimeSett'] == 'Y'){

                        if($minute > $array['Time']){

                            //echo $ticketId.' '.$minute.'<br>';
                            $sql_updateTime =   "UPDATE  sptbl_tickets SET nOwner = '".$array['Staff']."', vStatus = 'escalated' WHERE nTicketId = '".$ticketId."' ";
                            mysql_query($sql_updateTime) or die(mysql_error());
                            break;

                        }//end if

                    }//end if

                }//end foreach


        }//end function

        /*
         * Function to escalate ticket based on response count
         */
        function checkResponseCount($deptId,$ticketId,$count){
        global $rule_array;

                foreach ($rule_array[$deptId] as $array) {

                    if($array['CountSett'] == 'Y'){

                        if($count > $array['Count']){

                            //echo $ticketId.' '.$count.'<br>';
                            $sql_updateCount =   "UPDATE  sptbl_tickets SET nOwner = '".$array['Staff']."', vStatus = 'escalated' WHERE nTicketId = '".$ticketId."' ";
                            mysql_query($sql_updateCount) or die(mysql_error());
                            break;

                        }//end if

                    }//end if

                }//end foreach


        }//end function



?>
