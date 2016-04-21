<div class="right_column">
    <div class="form_container">
        <div class="form_top">Settlements</div>
        <div>
            
            <div class="table-responsive marg20_top">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="formstyle">
                <tbody>
                    <tr class="heading1">
                        <td width="8%" align="left" class="sl_no_padding">Sl No.</td>
                        <td width="12%" align="left" nowrap>Requested Amount</td>
                        <td width="40%" align="left">Description</td>
                        <td width="10%" align="left">Status</td>
                        <td width="15%" align="left">Requested Date</td>
                        <td width="15%" align="left">Options</td>
                    </tr>
                    <?php
                    $i = 0;
                    if (!empty($this->pageContents)) {
                        $i = $this->pageInfo['base'];
                        foreach ($this->pageContents as $row) {
                            $i++;
                            $className = ($i % 2) ? 'column2' : 'column1';
                            ?>
                            <tr class="<?php echo $className; ?>">
                                <td align="left"><?php echo $i; ?></td>
                                <td align="left"><?php echo CURRENCY_SYMBOL . ' ' . $row->nRequestedAmount; ?></td>
                                <td align="left"><?php echo htmlspecialchars($row->tUserComments); ?></td>
                                <td align="left"><?php echo $row->eStatus; ?></td>
                                <td align="left"><?php echo Utils::formatDateUS($row->dCreatedOn, false, 'date'); ?></td>
                                <td align="left">
                                    <?php
                                    if($row->eStatus == 'Pending'){
                                    ?>
                                    <a href="<?php echo BASE_URL . 'user/addRequest?id='.$row->nId; ?>">Edit</a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr class="column1">
                            <td align="center" colspan="6">No Results Found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            </div>
            <div class="more_entries">
                <div class="wp-pagenavi">
                    <?php
                    if (!empty($this->pageContents) && $this->pageInfo['maxPages'] > 1) {
                        echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL . 'user/settlements/');
                    }
                    ?>
                </div>
            </div>

            <div>
                <input class="button_orange marg10_col" type="button" value="Make A Request" name="btnAdd" onclick="window.location.href='<?php echo BASE_URL . 'user/addRequest'; ?>'">
            </div>

        </div>

    </div>
</div>

