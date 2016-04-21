<div class="right_column">
    <div class="form_container">
        <div class="form_top">Settlement Request</div>
          
       <div class="dboard_tab_contents marg20_top">
            <div class="dashboard_heading">Make a Request </div><br/> 
    <?php if (!empty(PageContext::$response->message)) { ?>
            <div >               
                <?php echo PageContext::$response->message; ?>
            </div>
        <?php } ?>
    <?php if($this->editFormDisplay==1) {?>
<form id="frmaddrequest" name="frmaddrequest" method="post" action="<?php echo BASE_URL.'user/addrequest';?>" >
<table width="98%" cellspacing="0" cellpadding="0" border="0" align="center" class="form_tbls">
  <tbody>    
    <tr>
        <th width="25%" valign="middle" align="left"> Requested Amount <?php $c=CURRENCY_SYMBOL; echo $sy=(!empty($c)) ? '('.$c.')' : ""; ?><span style="color: red">*</span> </th>
      <td  valign="middle" align="left"><input type="text" value="<?php echo $this->pageInfo->nRequestedAmount ;?>" name="nRequestedAmount" id="nRequestedAmount" class="txt_area" validate="required:true"></td>
    </tr>
    <tr>
      <th valign="middle" align="left" >  Description<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $this->pageInfo->tUserComments ;?>" name="tUserComments" id="tUserComments" class="txt_area" validate="required:true" minlength="4">
          <input type="hidden" id="nId" name="nId" value="<?php echo $this->pageInfo->nId ;?>">
      </td>
    </tr>     
    <tr>
      <th>&nbsp;</th>
      <td valign="middle" align="left"><input type="submit" class="button_orange" name="btnProfile" value="Save Changes" >
      </td>
    </tr>
</table>
</form>
            <?php } ?>
        </div>

    </div>
</div>
<script type="text/javascript">
    $('form').submit(function() {
    $(this).find('input[type="submit"]').attr('disabled', 'disabled');
   
    });
    </script>

