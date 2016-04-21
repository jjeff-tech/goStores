  <?php
            $loop=0;
            foreach(PageContext::$response->newrecord  as $record) {  ?>
          <?php if(PageContext::$response->action=="add") { ?>  <tr> <?php } ?>
                    <?php
                    foreach(PageContext::$response->detail_section_config->listColumns as $col) {
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
                        $relationUrl=BASE_URL."cms?parent_section=".PageContext::$response->parent_section."&parent_id=".$record->$parentKey."&section=".$val->section; ?>
                <td class="table-header">
                            <?php echo $record->$key; ?>&nbsp;<a href=<?php echo $relationUrl; ?>>Manage<?php
                            } ?></a>
                </td>
                <td>
                        <?php if(PageContext::$response->viewAction) { ?>
                   
                    <a class="jqShowDetails" href="<?php echo  PageContext::$response->currentURL;?>&action=showDetails#<?php echo $record->$parentKey?>">Details</a>&nbsp;
                            <?php } ?>
                        <?php if(PageContext::$response->editAction) { ?>
                    <a class="cms_list_operation action_edit jqEditRecord" href="<?php echo  PageContext::$response->currentURL;?>&action=edit&<?php echo $parentKey;?>=<?php echo $record->$parentKey?>#addForm">Edit</a>&nbsp;
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

         <?php if(PageContext::$response->action=="add") { ?>  </tr> <?php } ?>

                <?php  
            }

?>