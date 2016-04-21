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
        include("./languages/".$_SP_language."/assignstaff.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_ASSIGN_STAFF ?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
   
<!--
function saveMe(AssignStaff) {
  
                        var strValues = "";
                        var boxLength = AssignStaff.alotteddept.length;
                        var count = 0;
                        if(AssignStaff.cmbstaff.value=="0"){
                           alert('<?php echo MESSAGE_JS_STAFF_SELECTION_ERROR; ?>');
                           return false;
                        }
                        if(boxLength=="0"){
                           alert('<?php echo MESSAGE_JS_DEPT_SELECTION_ERROR; ?>');
                           return false;
                        }
                        if (boxLength != 0) {
                                        for (i = 0; i < boxLength; i++) {
                                                        if (count == 0) {
                                                                        strValues = AssignStaff.alotteddept.options[i].value;
                                                        }
                                                        else {
                                                                strValues = strValues + "," + AssignStaff.alotteddept.options[i].value;
                                                        }
                                                        count++;
                                           }
                        }
                        if (strValues.length == 0) {
                              document.frmAssignStaff.tosave.value=strValues;
                                        document.frmAssignStaff.postback.value="A";
                                    document.frmAssignStaff.method="post";
                                    document.frmAssignStaff.submit();
                        }
                        else {
                                document.frmAssignStaff.tosave.value=strValues;
                                        document.frmAssignStaff.postback.value="A";
                                    document.frmAssignStaff.method="post";
                                    document.frmAssignStaff.submit();


                      }
}

function changestaff(){
  document.frmAssignStaff.postback.value="C";
  document.frmAssignStaff.method="post";
  document.frmAssignStaff.submit();

}

function alloted(AssignStaff){

            var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignStaff.availabledept.length;
                        var av_boxLength = AssignStaff.alotteddept.length;
                        for (i = 0; i < av_boxLength; i++) {

                                if (AssignStaff.alotteddept.options[i].selected) {
                                                                  arrSelected_index[count]=i;
                                                arrSelected_text[count] = AssignStaff.alotteddept.options[i].text;
                                                                arrSelected[count] = AssignStaff.alotteddept.options[i].value;
                                                                count++;
                                }

                        }
                        if(arrSelected.length<=0){
                                alert('<?php echo MESSAGE_JS_DEPT_SELECTION_ERROR; ?>');
                                        return false;
                        }

                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignStaff.availabledept.options[boxLength] = newoption;

                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignStaff.alotteddept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
                        AssignStaff.alotteddept.selectedIndex=0;
}
function makeavailableall(AssignStaff){
            var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignStaff.availabledept.length;
                        var av_boxLength = AssignStaff.alotteddept.length;
                        for (i = 0; i < av_boxLength; i++) {
                                        arrSelected_index[count]=i;
                        arrSelected_text[count] = AssignStaff.alotteddept.options[i].text;
                                        arrSelected[count] = AssignStaff.alotteddept.options[i].value;
                                        count++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignStaff.availabledept.options[boxLength] = newoption;
                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignStaff.alotteddept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
                        AssignStaff.alotteddept.selectedIndex=-1;
}
function availbaletoalloted(AssignStaff) {
                    var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignStaff.alotteddept.length;
                        var choice_boxLength = AssignStaff.availabledept.length;
                        for (i = 0; i < choice_boxLength; i++) {
                                if (AssignStaff.availabledept.options[i].selected) {
                                                                  arrSelected_index[count]=i;
                                                arrSelected_text[count] = AssignStaff.availabledept.options[i].text;
                                                                arrSelected[count] = AssignStaff.availabledept.options[i].value;
                                                                count++;
                                }

                        }
                        if(arrSelected.length<=0){
                                alert('<?php echo MESSAGE_JS_DEPT_SELECTION_ERROR; ?>');
                                        return false;
                        }

                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignStaff.alotteddept.options[boxLength] = newoption;
                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignStaff.availabledept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
                        AssignStaff.availabledept.selectedIndex=0;
}

function makeallottedall(AssignStaff) {
                    var i;
                        var x;
                        var arrSelected = new Array();
                        var arrSelected_text = new Array();
                        var arrSelected_index = new Array();
                        var isNew = true;
                        var count = 0;
                        var del_rows=0;
            var boxLength = AssignStaff.alotteddept.length;
                        var choice_boxLength = AssignStaff.availabledept.length;
                        for (i = 0; i < choice_boxLength; i++) {
                                        arrSelected_index[count]=i;
                                arrSelected_text[count] = AssignStaff.availabledept.options[i].text;
                                    arrSelected[count] = AssignStaff.availabledept.options[i].value;
                                        count++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                                newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
                                            AssignStaff.alotteddept.options[boxLength] = newoption;
                                                boxLength++;
                        }
                        for (x = 0; x < arrSelected.length; x++) {
                                        AssignStaff.availabledept.options[ arrSelected_index[x]-del_rows]=null;
                                                del_rows++;
                        }
}

-->
</script>
</head>

<body>
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
                          include("./includes/assignstaff.php");
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