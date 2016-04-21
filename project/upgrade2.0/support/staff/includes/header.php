<div class="header_row">
	<div class="header_cnt sitewidth">
		
		<div class="header_left">
		<div class="mainlogo">
<?php
              $img_logo = $_SESSION["sess_logourl"];
              $img_logo =str_replace('%','%25',$img_logo);
                ?>
			<a href="<?php echo SITE_URL."/staff/staffmain.php"; ?>"><img src="./../<?php echo( $img_logo); ?>" border="0"></a>
		</div>		
		<div class="clear"></div>
	</div>

            <div class="left">
             <?php
               include("includes/inteligentsearch.php");
             ?>
            </div>


	<div class="header_right">
		<div class="header_info right">
			<h4><span><?php echo(htmlentities($_SESSION["sess_helpdesktitle"])); ?></span>&nbsp;-&nbsp;STAFF PANEL</h4>
			<h5 align="right">
				<?php
						if(isset($_SESSION["sess_staffname"]) and $_SESSION["sess_staffname"]!="" ){
								echo TEXT_LOGGED_IN_AS;
								echo "&nbsp;<b>".stripslashes($_SESSION["sess_staffname"])."</b>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'>".TEXT_LOGOUT."</a>";
						}else{
						echo "&nbsp;";
						}
				?>		
			</h5>
		</div>
	
	</div>
	<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>
<!-- Top menu -->
	<div class="menu_row">
		<div class="menu_cnt sitewidth">
			<div class="menu_left">
			<div class="lang_selector">
				<?php 
                        if ($_SESSION["sess_langchoice"] == "1") {
                        $sql="Select vLangCode,vLangDesc from sptbl_lang order by vLangDesc ";
                        $result=mysql_query($sql,$conn);
                ?>
                        <form name="frmLanguage" action="staffmain.php" method="post">
                                <label><?php echo(TEXT_SELECT_LANGUAGE); ?></label>&nbsp;&nbsp;<select name="cmbLan" class="selectbox1" onChange="javascript:changeLanguage();" >
                                <?php
                                        if (mysql_num_rows($result) > 0) {
                                                while($row = mysql_fetch_array($result)) {
                                                        echo("<option value=\"" . htmlentities($row["vLangCode"]) . "\">" . $row["vLangDesc"] . "</option>");
                                                }
                                        }
                                ?>
                                </select>&nbsp;
                        <script>
                                var lc = '<?php echo($_SESSION["sess_language"]); ?>';
                                document.frmLanguage.cmbLan.value=lc;

                                function changeLanguage() {
                                        document.frmLanguage.method="post";
										document.frmLanguage.post_back.value ="CL";
                                        document.frmLanguage.submit();
                                }
                        </script>
						<input type="hidden" name="post_back" value="">
                        </form>
                <?php
                        }
                 ?>
			</div>
			</div>
			<div class="menu_right">
				<ul class="topmenu">
				<?php 
				if($_SESSION['newticket_msg_alert']==1) {
                                    //********* Get ticket count
                                    $lst_dept = $_SESSION['departmentids'] ;  // it is  set in the  dept_overview.php file
                                    //echo "<pre>";print_r($_SESSION);echo"</pre>";
                                    $sql = "Select count(t.nTicketId) as newtccount from sptbl_tickets t left join sptbl_replies rp on (rp.dDate=t.dLastAttempted and rp.nTicketId=t.nTicketId) where t.vDelStatus='0' AND t.vStatus='open' AND t.nLabelId=0";
                                    $qryopt="";

                                    if($var_deptid != "") {
                                        $arr_dept = explode(",",$lst_dept);
                                        $pflag = false;
                                        for($i=0;$i<count($arr_dept);$i++) {
                                            if ($var_deptid == $arr_dept[$i]) {
                                                $pflag = true;
                                                break;
                                            }
                                        }
                                        if ($pflag == true) {
                                            $qryopt .= " AND t.nDeptId = '" . addslashes($var_deptid) . "' ";
                                        }
                                        else {
                                            $qryopt .= " AND t.nDeptId IN($lst_dept) ";
                                        }
                                    }
                                    else {
                                        $qryopt .= " AND t.nDeptId IN($lst_dept) ";
                                    }

                                    $sql .= $qryopt;
                                    //echo $sql;
                                     $rs_new_tic_count = executeSelect($sql,$conn);
                                     $new_tic_count = 0;
                                    if (mysql_num_rows($rs_new_tic_count) > 0) {
                                        $row_new_tic_count = mysql_fetch_array($rs_new_tic_count);
                                        $new_tic_count = $row_new_tic_count['newtccount'];
                                    }
                                    //*****
                                //}
                                    //$new_tic_count = 99;
                                    ?>
                               
				<li class="alert"><div class="alert_tckts"><?php echo $new_tic_count;?></div><a href="newtickets.php"><?php echo HEADING_TICKETS_NEW;?></a></li>
				<?php
				} if($_SESSION['pvt_msg_alert']==1) {

                                    $sql = "Select PM.nPMId,PM.vPMTitle,PM.dDate,PM.vStatus,S.vStaffName as 'FromName',S1.vStaffName as 'ToName'
                                             from sptbl_pvtmessages PM inner join sptbl_staffs S on PM.nFrmStaffId = S.nStaffId inner join
                                             sptbl_staffs S1 on PM.nToStaffId = S1.nStaffId WHERE PM.nToStaffId = '".$_SESSION["sess_staffid"]."' ";

                                    $totalrows = mysql_num_rows(executeSelect($sql,$conn));

                                    ?>

				<li class="alert"><div class="alert_msg"><?php echo $totalrows;?></div><a href="pvtmessages.php"><?php echo HEADING_STAFF_NEW_MESSAGE;?></a></li>
				<?php
				}
				?>
				<li><a href="staffmain.php" <?php if($page == 'home' || $page == '' ){ ?> class="selected" <?php } ?>><?php echo HEADING_HOME ?></a></li>
				<li><a href="tickets.php?tp=o&mt=y" <?php if($page == 'tickets-o'){ ?> class="selected" <?php } ?>><?php echo HEADING_OPEN_TICKETS ?></a></li>
				<li><a href="tickets.php?tp=c&mt=y" <?php if($page == 'tickets-c'){ ?> class="selected" <?php } ?>><?php echo HEADING_CLOSED_TICKETS ?></a></li>
				<li><a href="knowledgebase.php" <?php if($page == 'KB'){ ?> class="selected" <?php } ?>><?php echo HEADING_KNOWLEDGEBASE ?></a></li>
				<li><a href="editprofile.php" <?php if($page == 'Preference'){ ?> class="selected" <?php } ?>><?php echo HEADING_PREFERENCES ?></a></li>
				<li><a href="search.php" <?php if($page == 'Search'){ ?> class="selected" <?php } ?>><?php echo HEADING_SEARCH ?></a></li>
				</ul>
			</div>
		</div>	
		
	</div>
<!-- Top menu ends -->


<!-- Content area starts-->
<div class="content_row">
	<div class="content_area sitewidth">




