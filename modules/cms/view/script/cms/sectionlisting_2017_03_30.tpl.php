 
<div class="section_list_view ">

    <div class="row have-margin">
        <span class="legend"><?php if(PageContext::$response->parentSectionName!="") {
                echo "Section : ".PageContext::$response->parentSectionName." [".PageContext::$response->parentBreadCrumbName."] &raquo; Subsection: ".PageContext::$response->sectionData->section_name;
                ?>
            <span class=" pull-right">


                <small><a  class="jhistoryBack">&laquo;&nbsp;Back</a></small></span>


                <?php
            } else {
                echo "Section : ".PageContext::$response->sectionData->section_name;
            } ?> </span>
        <?php if(PageContext::$response->message!="") { ?><div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">×</button>  <?php echo PageContext::$response->message ?></div> <?php } ?>
       <?php if( pageContext::$response->errorMessage!="") { ?> <div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">×</button>   <?php echo  pageContext::$response->errorMessage; ?></div><?php } ?>
        <?php if(PageContext::$response->section_config->report) { ?>
        

        <div  class=" pull-left">

            <!--  <a data-toggle="modal" href="#report" class="btn btn-info">Export</a>&nbsp; -->


        </div>
            <?php } ?>
        <?php if(count(PageContext::$response->searchableCoumnsList)) { ?>


        <div  class="input-append pull-right">
            <form name="search_form" id="search_form">
                <select name="searchField" id="searchField"  class="input-medium have-margin10 ">
                        <?php
                        foreach(PageContext::$response->searchableCoumnsList as $key => $val ) {
                            ?>
                    <option value="<?php echo $key;?>"  <?php if(PageContext::$response->request['searchField']==$key) { ?>selected="selected" <?php } ?>><?php echo $val ?></option>
                            <?php } ?>
                </select>
                <input type="text" class="input-medium have-margin10 " placeholder="search" maxlength="50" name="searchText" id="searchText" value="<?php echo PageContext::$response->request['searchText'];?>">
                <input type="button"  class="btn btn-info searchBtn " id="section_search_button" value="Search" >
            </form>
        </div>
            <?php } ?>
    </div> 


    <table  id="tbl_activities" class="cms_listtable table  table-striped table-bordered table-hover " >
        <tbody>
            <tr>
                <!-- RENDER LIST HEADER -->            
                <?php foreach(PageContext::$response->listColumns as $col) { ?>
                <th class="table-header">
                        <?php
                        $colName=PageContext::$response->columns->$col->name;
                        $orderType="ASC";
                        $sortClass="";
                        if(PageContext::$response->columns->$col->sortable) {
                            if(PageContext::$response->request['orderField'] == $col) {
                                if(strtolower(PageContext::$response->request['orderType'])=="asc") {
                                    $orderType="DESC";
                                    $sortClass  =   "icon-chevron-up";
                                }
                                else {
                                    $orderType="ASC";
                                    $sortClass  =   "icon-chevron-down ";
                                }
                            }
                            $sortUrl=PageContext::$response->currentURL."&orderField=".$col."&orderType=".$orderType;
                            ?>
                    <a class="cms_list_operation" href="<?php echo $sortUrl;?>"> <?php echo PageContext::$response->columns->$col->listHeaderPrefix." ".$colName." ".PageContext::$response->columns->$col->listHeaderPostfix; ?></a>&nbsp;<i class="<?php echo $sortClass;?>"></i>&nbsp;

                            <?php }else echo  PageContext::$response->columns->$col->listHeaderPrefix." ".$colName." ".PageContext::$response->columns->$col->listHeaderPostfix; ?>
                </th>
                    <?php  }

                foreach(PageContext::$response->combineTables as $combineTable => $combineOptions) {
                    foreach($combineOptions->combineColumns as $col) {?>

                <th class="table-header">
                            <?php
                            $colName=PageContext::$response->columns->$col->name;
                            $orderType="ASC";
                            $sortClass="";
                            if(PageContext::$response->columns->$col->sortable) {
                                if(PageContext::$response->request['orderField'] == $col) {
                                    if(strtolower(PageContext::$response->request['orderType'])=="asc") {
                                        $orderType="DESC";
                                        $sortClass  =   "icon-chevron-down";
                                    }
                                    else {
                                        $orderType="ASC";
                                        $sortClass  =   "icon-chevron-up";
                                    }
                                }
                                $sortUrl=PageContext::$response->currentURL."&orderField=".$col."&orderType=".$orderType;
                                ?>
                    <a class="cms_list_operation" href="<?php echo $sortUrl;?>"> <?php echo PageContext::$response->columns->$col->listHeaderPrefix." ".$colName." ".PageContext::$response->columns->$col->listHeaderPostfix; ?></a><i class="<?php echo $sortClass;?>"></i>&nbsp;

                                <?php }else echo  PageContext::$response->columns->$col->listHeaderPrefix." ".$colName." ".PageContext::$response->columns->$col->listHeaderPostfix; ?>
                </th>
                        <?php  }
                }

                foreach(PageContext::$response->combineReferenceColumn as $col) { ?>
                <th class="table-header">
                        <?php
                        $colName=PageContext::$response->columns->$col->name;
                        $orderType="ASC";
                        if(PageContext::$response->columns->$col->sortable) {
                            if(PageContext::$response->request['orderField'] == $col) {
                                if(strtolower(PageContext::$response->request['orderType'])=="asc")
                                    $orderType="DESC";
                                else
                                    $orderType="ASC";
                            }
                            $sortUrl=PageContext::$response->currentURL."&orderField=".$col."&orderType=".$orderType;
                            ?>
                    <a class="cms_list_operation" href="<?php echo $sortUrl;?>"> <?php echo PageContext::$response->columns->$col->listHeaderPrefix." ".$colName." ".PageContext::$response->columns->$col->listHeaderPostfix; ?></a><i class="icon-camera-retro"></i>&nbsp;

                            <?php }else echo  PageContext::$response->columns->$col->listHeaderPrefix." ".$colName." ".PageContext::$response->columns->$col->listHeaderPostfix; ?>
                </th>
                    <?php  }
                foreach(PageContext::$response->relations as $col) { ?>
                <th class="table-header listingTableHeadTh">
                        <?php    echo $col->name;  ?> 
                </th>
                    <?php }  ?>
                <th class="span2 listingTableHeadTh">Operations</th>
                <?php if(PageContext::$response->section_config->publishColumn) { ?>
                <th class="listingTableHeadTh">Publish</th>
                    <?php }?>
            </tr>

            <!--  RENDER RECORDS  -->
            <?php
$loop=0;
            foreach(PageContext::$response->listData  as $record) { ?>
            <tr>
                    <?php
                    foreach(PageContext::$response->section_config->listColumns as $col) {
                        $colType=PageContext::$response->columns->$col->editoptions->type;
                        ?>

                <td><?php if($colType   ==  "file") {
                                echo $record->$col;
                            }
                            else if(PageContext::$response->columns->$col->listoptions) {
 $enumArray  =    explode("{cms_separator}",$record->$col);
                                        
                                            echo $enumArray[1];
                                        


                            }

                            else if(PageContext::$response->columns->$col->popupoptions) {

                                      echo $record->$col;


                            }
                            else if(PageContext::$response->columns->$col->editoptions->enumvalues) {
                                foreach(PageContext::$response->columns->$col->editoptions->enumvalues as $enumKey  => $enumValue) {

                                    if($enumKey==$record->$col)
                                        echo $enumValue;
                                }
                            }
                            else { 
                                if(PageContext::$response->columns->$col->externalNavigation || PageContext::$response->columns->$col->customColumn )
                                        echo $record->$col;
                                        else
                                echo substr(strip_tags($record->$col),0,30);
                            }  ?></td>
                        <?php  }

                    foreach(PageContext::$response->combineTables as $combineTable => $combineOptions) {
                        foreach($combineOptions->combineColumns as $col) {?>

                <td><?php if($colType   ==  "file") {
                                    echo $record->$col;
                                }
                                else if(PageContext::$response->columns->$col->editoptions->enumvalues) {
                                    foreach(PageContext::$response->columns->$col->editoptions->enumvalues as $enumKey  => $enumValue) {

                                        if($enumKey==$record->$col)
                                            echo $enumValue;
                                    }
                                }
                                else {
                                    echo substr($record->$col,0,30);
                                }  ?></td>
                            <?php  }
                    }
                    $parentKey = PageContext::$response->section_config->keyColumn;
                    ?>
                    <?php  foreach(PageContext::$response->relations as $key => $val) {
                        $relationUrl=BASE_URL."cms?parent_section=".PageContext::$request['section']."&parent_id=".$record->$parentKey."&section=".$val->section; ?>
                <td class="table-header">
                            <?php echo $record->$key; ?>&nbsp;<a href=<?php echo $relationUrl; ?>>Manage<?php
                            } ?></a>
                </td>
                <td>
                        <?php if(PageContext::$response->viewAction) { ?>
                    <a data-toggle="modal" href="#<?php echo $record->$parentKey?>">View</a>&nbsp;
                            <?php } ?>
                        <?php if(PageContext::$response->editAction) { ?>
                    <a class="cms_list_operation action_edit" href="<?php echo  PageContext::$response->currentURL;?>&action=edit&<?php echo $parentKey;?>=<?php echo $record->$parentKey?>#addForm">Edit</a>&nbsp;
                            <?php } ?>
                        <?php if(PageContext::$response->deleteAction) { ?>
                    <a class="cms_list_operation action_delete" href="<?php echo  PageContext::$response->currentURL;?>&action=delete&<?php echo $parentKey;?>=<?php echo $record->$parentKey?>">Delete</a>
                            <?php } ?>
                        <?php   foreach(PageContext::$response->customOperationsList[$loop] as $key => $customOperations) {
                            echo $customOperations->link; ?>

                        
                      <?php } ?>
                </td>

                    <?php if(PageContext::$response->section_config->publishColumn) {
                        $publish_col = PageContext::$response->section_config->publishColumn;
                        if($record->$publish_col) { ?>
                <td><a class="cms_list_operation action_unpublish" href="<?php echo PageContext::$response->currentURL; ?>&action=unpublish&<?php echo $parentKey;?>=<?php echo $record->$parentKey?>"><button class="btn btn-mini btn-success" type="button">Unpublish</button></a></td>
                            <?php }else { ?>
                <td><a class="cms_list_operation action_publish" href="<?php echo PageContext::$response->currentURL; ?>&action=publish&<?php echo $parentKey;?>=<?php echo $record->$parentKey?>"><button class="btn btn-mini btn-danger" type="button">Publish&nbsp;&nbsp;&nbsp;&nbsp;</button></a></td>
                            <?php }
                    }
                    ?>

            </tr>

                <?php  $loop++;}

            if(PageContext::$response->totalResultsNum==0) {    ?>
            <tr>  <td colspan="<?php echo PageContext::$response->columnNum; ?>">
                    No Data Found !!

                </td>
                    <?php
                }
                ?>


        </tbody>
    </table>
    <div class="">
        <div class="section_list_operations ull-left span3 pagination">
            <?php if(PageContext::$response->addAction) { ?>
            <a href="<?php echo  PageContext::$response->currentURL;?>&action=add#addForm" class="addrecord btn btn-info">Add Record</a>
                <?php } ?>
              <?php if( PageContext::$response->section_config->report) { ?>
            &nbsp;<a data-toggle="modal" href="#report" class="btn btn-info">Export</a>&nbsp;
              <?php } ?>
        </div>
        <?php   // outputting the pages

        if (PageContext::$response->resultPageCount > 1) {

            ?>
        <div class="pagination pagination-right ull-right">

                <?php
                echo PageContext::$response->pagination;
                ?>
        </div>  <?php
        }
        ?>

        <div style="clear:both"></div>
    </div>

    <?php    foreach(PageContext::$response->listData  as $record) {
        ?>
    <div id="<?php echo $record->$parentKey?>" class="modal hide fade in" style="display: none; ">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3>
                    <?php
                    if(PageContext::$response->section_config->detailHeaderColumnPrefix)
                        echo PageContext::$response->section_config->detailHeaderColumnPrefix." ";
                    if(PageContext::$response->section_config->detailHeaderContent) { ?><?php echo PageContext::$response->section_config->detailHeaderContent; ?><?php } else {
                        foreach(PageContext::$response->section_config->detailHeaderColumns as $col) {
                            if(trim($record->$col)!="-") {
                                echo strip_tags($record->$col)." ";
                            }
                        }
                    }
                    if( PageContext::$response->section_config->detailHeaderColumnPostfix)
                     echo "  ".PageContext::$response->section_config->detailHeaderColumnPostfix;
                    ?></h3>
        </div>
        <div class="modal-body">
            <table class="table  table-bordered table-hover table-condensed">

                    <?php

                    foreach(PageContext::$response->section_config->detailColumns as $col) {
                        if(PageContext::$response->columns->$col->editoptions->type!="password")
                                {
                           
                                	if(PageContext::$response->section_config->columns->$col->disableDetailedView!="false")
                                	{
                                		 
                        ?>

                <tr><td class="span3"><?php echo PageContext::$response->columns->$col->name?>&nbsp;</td>
                    <td class="span6">
                                <?php

                                if( PageContext::$response->columns->$col->listoptions) {
                                    foreach(PageContext::$response->columns->$col->listoptions->enumvalues as $enumKey  => $enumValue) {
                                        $enumArray  =    explode("{cms_separator}",$record->$col);
                                        if($enumKey==strip_tags($enumArray[0])) {
                                            echo $enumValue;
                                        }
                                    }
                                    }
                                    else if(PageContext::$response->columns->$col->editoptions->enumvalues) {
                                        foreach(PageContext::$response->columns->$col->editoptions->enumvalues as $enumKey  => $enumValue) {

                                            if($enumKey==strip_tags($record->$col)) {
                                                echo $enumValue;
                                            }
                                        }
                                    }
                                    else { ?>

                                        <?php  if(PageContext::$response->columns->$col->editoptions->type=="file" || PageContext::$response->columns->$col->editoptions->type=="htmlEditor") {
                                            echo stripslashes($record->$col); 
                                        }
                                        if(PageContext::$response->columns->$col->customColumn) {
                                             echo $record->$col;
                                        }
                                        else { 
                                            echo strip_tags($record->$col);
                                        }
                                    }?>
                    </td>
                </tr>

                        <?php }  } } ?>
            </table>

        </div>
        <div class="modal-footer">

            <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
        <?php }

    ?>

    <div class="modal" id="report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 id="myModalLabel">Export to Excel</h4>
        </div>
        <div class="modal-body">
            <p>Choose date range </p>
            From: <input type="text"  placeholder="Date" class="textfield_date" id="reportStartDate" value="<?php echo PageContext::$response->monthStartDate;?>">
            To: <input type="text"  placeholder="Date" class="textfield_date" id="reportEndDate" value="<?php echo PageContext::$response->currentDate;?>">
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary generateReport" >Download</button>
        </div>
        
    </div>
    <div class="modal" id="popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; ">
        <div class="is-padded">
            <button type="button" class="close jqCloseButton" data-dismiss="modal" aria-hidden="true">×</button>
            
        </div>
        <div class="modal-body" id="popupBody">
            </div>
        <div class="modal-footer">
            <button class="btn jqCloseButton" data-dismiss="modal" aria-hidden="true">Close</button>
           
        </div>

    </div>
</div>