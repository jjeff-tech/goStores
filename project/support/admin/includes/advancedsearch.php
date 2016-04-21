<script>
    function searchOnEnter(event){ 
       if(event.keyCode == '13'){ 
           document.frmSearch.btSearch.focus();
       }
    }
</script>

<div class="content_section">
    <form name="frmSearch" action="advancedresult.php" method="post">

        <div class="content_section_title"><h4><?php echo HEADING_TICKET_SEARCH ?></h4></div>

        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor">
                <td width="22%" class="pad_left"><?php echo TEXT_COMPANY ?></td>
                <td width="26%" class="pad_left">
                    <select name="cmbCompLp" class="comm_input1b input_width1">
                        <option value=""> </option>
                    </select> 						</td>
                <td  width="26%" class="pad_left"><div>
                        <select name="cmbCompOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td width="39%"  class="pad_left"><select name="cmbCompany" class="comm_input1b input_width1"  onKeyPress="searchOnEnter(event)">
                        <option value=""> </option>
                        <?php
                        $sql = "Select c.nCompId,c.vCompName from sptbl_companies c	 WHERE vDelStatus='0' ";
                        $rs = executeSelect($sql,$conn);
                        if (mysql_num_rows($rs) > 0) {
                            while($row = mysql_fetch_array($rs)) {
                                ?>
                        <option value="<?php echo($row["nCompId"]); ?>"><?php echo(htmlentities($row["vCompName"]));?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor pad_left">
                <td  class="pad_left"><?php echo TEXT_DEPARTMENT ?></td>
                <td  class="pad_left"><select name="cmbDeptLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td  class="pad_left"><div>
                        <select name="cmbDeptOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td  class="pad_left"><select name="txtDepartment" class="comm_input1b input_width1"  onKeyPress="searchOnEnter(event)">
                        <option value=""> </option>
                        <?php

                        $sql2 = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from
								sptbl_depts d  inner join sptbl_companies c
								 on d.nCompId = c.nCompId  ";
                        $lst_dept_opt = "";
                        $rs_dept = executeSelect($sql2,$conn);
                        if (mysql_num_rows($rs_dept) > 0) {
                            while($row = mysql_fetch_array($rs_dept)) {
                                $lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\"";
//										if($var_deptid == $row["nDeptId"]) 
//											$lst_dept_opt .= " selected ";

                                $lst_dept_opt .=" >" . htmlentities($row["description"]) . "</option>";
                            }
                        }
                        echo($lst_dept_opt);

//						echo($lst_dept_opt);
                        ?>
                    </select></td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor pad_left">
                <td  class="pad_left"><?php echo TEXT_STATUS ?></td>
                <td  class="pad_left"><select name="cmbStatusLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td  class="pad_left"><div>
                        <select name="cmbStatusOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td class="pad_left"><input name="txtStatus" id="txtStatus" type="text" class="comm_input1btxt input_width1" maxlength="20" onKeyPress="searchOnEnter(event)"></td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="whitebasic">
                <td  class="pad_left"><?php echo TEXT_OWNER ?></td>
                <td  class="pad_left"><select name="cmbOwnerLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td  class="pad_left"><div>
                        <select name="cmbOwnerOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td  class="pad_left"><input name="txtOwner" id="txtOwner" type="text" class="comm_input1btxt input_width1" maxlength="20" onKeyPress="searchOnEnter(event)"></td>
            </tr>
            <tr align="left" bgcolor="#FFFFFF" class="bodycolor">
                <td class="pad_left"><?php echo TEXT_USER ?></td>
                <td  class="pad_left"><select name="cmbUserLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td  class="pad_left"><div>
                        <select name="cmbUserOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td  class="pad_left"><input name="txtUser" id="txtUser" type="text" class="comm_input1btxt input_width1" maxlength="20" onKeyPress="searchOnEnter(event)"></td>
            </tr>
            <tr align="left" class="whitebasic">
                <td  class="pad_left"><?php echo TEXT_TICKETNO ?></td>
                <td  class="pad_left"><select name="cmbTktLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td  class="pad_left"><div>
                        <select name="cmbTktOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n"><?php echo(TEXT_NOT); ?> <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td  class="pad_left"><input name="txtTicketNo" id="txtTicketNo" type="text"class="comm_input1btxt input_width1" maxlength="20" onKeyPress="searchOnEnter(event)"></td>
            </tr>
            <tr align="left" class="whitebasic">
                <td  class="pad_left"><?php echo TEXT_QUESTION ?></td>
                <td  class="pad_left"><select name="cmbQstLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td  class="pad_left"><div>
                        <select name="cmbQstOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td  class="pad_left"><input name="txtQuestion" id="txtQuestion" type="text" class="comm_input1btxt input_width1" maxlength="20" onKeyPress="searchOnEnter(event)"></td>
            </tr>
            <tr align="left" class="whitebasic">
                <td  class="pad_left"><?php echo TEXT_TITLE ?> </td>
                <td  class="pad_left"><select name="cmbTitleLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td  class="pad_left"><div>
                        <select name="cmbTitleOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td class="pad_left"><input name="txtTitle" id="txtTitle" type="text" class="comm_input1btxt input_width1" maxlength="20" onKeyPress="searchOnEnter(event)"></td>
            </tr>
            <tr align="left" class="whitebasic">
                <td class="pad_left"><?php echo TEXT_LABEL ?> </td>
                <td class="pad_left"><select name="cmbLabelLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td class="pad_left"><div>
                        <select name="cmbLabelOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <?php
// label starts here
                $sqllabel = "Select l.nLabelId,l.vLabelname from sptbl_labels l where l.nStaffId='$var_staffid'";
                $lst_label_opt = "";
                $rs_label = executeSelect($sqllabel,$conn);
                if (mysql_num_rows($rs_label) > 0) {
                    while($row = mysql_fetch_array($rs_label)) {
                        $lst_label_opt .= "<option value=\"" . $row["nLabelId"] . "\">" . htmlentities($row["vLabelname"]) . "</option>";
                    }
                }
                mysql_free_result($rs_label);
// label end

                ?>
                <td class="pad_left">
                    <select name="txtLabel" class="comm_input1b input_width1" onKeyPress="searchOnEnter(event)">
                        <option value=""> </option>
                        <?php
                        echo($lst_label_opt);
                        ?>
                    </select>
                </td>
            </tr>
            <!--Newly Added on 280709 starts-->
            <tr align="left" class="whitebasic">
                <td  class="pad_left"><?php echo TEXT_EMAIL ?> </td>
                <td class="pad_left"><select name="cmbEmailLp" class="comm_input1b input_width1">
                        <option value="and"><?php echo(TEXT_AND); ?></option>
                        <option value="or"><?php echo(TEXT_OR); ?></option>
                    </select></td>
                <td class="pad_left"><div>
                        <select name="cmbEmailOp" class="comm_input1b input_width1">
                            <option value="m"><?php echo(TEXT_MATCHES);?></option>
                            <option value="n">Not <?php echo(TEXT_MATCHES);?></option>
                        </select>
                    </div></td>
                <td class="pad_left"><input name="txtEmail" id="txtEmail" type="text" class="comm_input1btxt input_width1"  onKeyPress="searchOnEnter(event)"></td>
            </tr>
            <!--Newly Added on 280709 ends-->
            <tr align="left" class="whitebasic">
                <td colspan=4 align="left" >
                    <div class="content_section_title"><h4><?php echo TEXT_DATE_RANGE ?></h4></Div>


                </td>
            </tr>
            <tr align="left" class="whitebasic">
                <td colspan="4" align="center" class="subtbl">

                    <div class="content_search_container">
                        <div class="left rightmargin topmargin">
                            <?php echo TEXT_FROM ?>
                        </div>

                        <div class="left rightmargin">
                            <input name="txtFrom" id="txtFrom" type="text" class="comm_input_txtbx left" size="30" readonly="true" style="margin-right: 5px;">
                            <input name="btFrom"  id="btFrom" type="button" class="secondary_btn left" value="" onClick="" style="margin-right: 50px;">
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField    	: "txtFrom",
                                    button        	: "btFrom",
                                    ifFormat      	: "%m/%d/%Y %H:%M",       // format of the input field
                                    showsTime      	: true,
                                    timeFormat     	: "24"
                                });
                            </script>
                        </div>

                        <div class="left rightmargin topmargin">
                            <?php echo TEXT_TO ?>
                        </div>

                        <div class="left">
                            <input name="txtTo" id="txtTo" type="text" class="comm_input_txtbx left" size="30" readonly="true" style="margin-right: 5px;">
                            <input name="btTo"  id="btTo" type="button" class="secondary_btn left" value="" onClick="">
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField    	: "txtTo",
                                    button        	: "btTo",
                                    ifFormat      	: "%m/%d/%Y %H:%M",       // format of the input field
                                    showsTime      	: true,
                                    timeFormat     	: "24"
                                });
                            </script>
                        </div>
						
						<div>
            <input type="hidden" name="mt" value="y">
            <input name="btSearch" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SEARCH; ?>" onClick="javascript:clickSearch();">
        </div>
						
						
                        <div class="clear"></div>
                    </div>



                </td>
            </tr>
        </table>


        



    </form>
</div>