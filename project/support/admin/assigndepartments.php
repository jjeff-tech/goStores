<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheeshpa@armia.com>                                  |
// |                                                                                                            |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/assigndepartments.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_ASSIGN_DEPARTMENT ?></title>
<?php include("./includes/headsettings.php"); ?>
<script  type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
    
    $("select[name='availabledept']").change(function(){
     $("select[name='alotteddept']").val('');
    });

    $("select[name='alotteddept']").change(function(){
    $("select[name='availabledept']").val('');
    });
});

<!--
function saveMe(AssignDepartment) {
                        var strValues = "";
                        var boxLength = AssignDepartment.alotteddept.length;
                        var count = 0;
                        if(AssignDepartment.cmbDepartment.value=="0"){
                           alert('<?php echo MESSAGE_JS_DEPARTMENT_SELECTION_ERROR; ?>');
                           return false;
                        }
                        if (boxLength != 0) {
                                        for (i = 0; i < boxLength; i++) {
                                                        if (count == 0) {
                                                                        strValues = AssignDepartment.alotteddept.options[i].value;
                                                        }
                                                        else {
                                                                strValues = strValues + "," + AssignDepartment.alotteddept.options[i].value;
                                                        }
                                                        count++;
                                           }
                        }
                        if (strValues.length == 0) {
                              document.frmAssignDepartment.tosave.value=strValues;
                                        document.frmAssignDepartment.postback.value="A";
                                    document.frmAssignDepartment.method="post";
                                    document.frmAssignDepartment.submit();
                        }
                        else {
                                document.frmAssignDepartment.tosave.value=strValues;
                                        document.frmAssignDepartment.postback.value="A";
                                    document.frmAssignDepartment.method="post";
                                    document.frmAssignDepartment.submit();
                        }
}

function changedepartment(){
  document.frmAssignDepartment.postback.value="C";
  document.frmAssignDepartment.method="post";
  document.frmAssignDepartment.submit();
}

function alloted(AssignDepartment){

            var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignDepartment.availabledept.length;
                        var av_boxLength = AssignDepartment.alotteddept.length;
                        for (i = 0; i < av_boxLength; i++) {

                                if (AssignDepartment.alotteddept.options[i].selected) {
                                                                  arrSelected_index[count]=i;
                                                arrSelected_text[count] = AssignDepartment.alotteddept.options[i].text;
                                                                arrSelected[count] = AssignDepartment.alotteddept.options[i].value;
                                                                count++;
                                }

                        }
                        if(arrSelected.length<=0){
                                alert('<?php echo MESSAGE_JS_SELECTION_ERROR; ?>');
                                        return false;
                        }

                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignDepartment.availabledept.options[boxLength] = newoption;

                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignDepartment.alotteddept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
                        AssignDepartment.alotteddept.selectedIndex=0;
}
function makeavailableall(AssignDepartment){
            var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignDepartment.availabledept.length;
                        var av_boxLength = AssignDepartment.alotteddept.length;
                        for (i = 0; i < av_boxLength; i++) {
                                        arrSelected_index[count]=i;
                        arrSelected_text[count] = AssignDepartment.alotteddept.options[i].text;
                                        arrSelected[count] = AssignDepartment.alotteddept.options[i].value;
                                        count++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignDepartment.availabledept.options[boxLength] = newoption;
                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignDepartment.alotteddept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
                        AssignDepartment.alotteddept.selectedIndex=-1;
}
function availbaletoalloted(AssignDepartment) {
                    var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignDepartment.alotteddept.length;
                        var choice_boxLength = AssignDepartment.availabledept.length;
                        for (i = 0; i < choice_boxLength; i++) {
                                if (AssignDepartment.availabledept.options[i].selected) {
                                                                  arrSelected_index[count]=i;
                                                arrSelected_text[count] = AssignDepartment.availabledept.options[i].text;
                                                                arrSelected[count] = AssignDepartment.availabledept.options[i].value;
                                                                count++;
                                }

                        }
                        if(arrSelected.length<=0){
                                alert('<?php echo MESSAGE_JS_SELECTION_ERROR; ?>');
                                        return false;
                        }

                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignDepartment.alotteddept.options[boxLength] = newoption;
                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignDepartment.availabledept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
                        AssignDepartment.availabledept.selectedIndex=0;
}

function makeallottedall(AssignDepartment) {
                    var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignDepartment.alotteddept.length;
                        var choice_boxLength = AssignDepartment.availabledept.length;
                        for (i = 0; i < choice_boxLength; i++) {
                                        arrSelected_index[count]=i;
                                arrSelected_text[count] = AssignDepartment.availabledept.options[i].text;
                                    arrSelected[count] = AssignDepartment.availabledept.options[i].value;
                                        count++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignDepartment.alotteddept.options[boxLength] = newoption;
                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignDepartment.availabledept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
}

-->
</script>
</head>

<body bgcolor="#EDEBEB">
<!--  Top Part  -->
<?php
        include("./includes/top.php");
?>
<!--  Top Ends  -->
        <!-- header  -->
    <?php
                include("./includes/header.php");
        ?>
        <!-- end header -->

          <div class="content_column_small">

			<!-- sidelinks -->

                          <?php
                                include("./includes/adminside.php");
                        ?>

                   <!-- End of side links -->


			</div>
			
			
			<div class="content_column_big">
			
			
					<!-- admin header -->
					<?php
							//include("./includes/adminheader.php");
					?>
					<!--  end admin header -->

                  <!-- Detail Section -->

                  <?php
                          include("./includes/assigndepartments.php");
                  ?>

          <!-- End Detail section -->
          
			
			
			</div>

		  
		  
		  
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
</html>