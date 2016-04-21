/*
SEARCH Function
 # used for submitting url in search
 */

function getSearchResults(currentURL)
{
	if(document.getElementById("searchField")){
    var searchField=document.getElementById("searchField").options[document.getElementById("searchField").selectedIndex].value;
	}
        if(document.getElementById("searchText")){
            var searchText=document.getElementById("searchText").value;
        }

    var url=searchURL+"&searchField="+searchField+"&searchText="+searchText;
    window.location.href=encodeURI(url);

}

$(document).ready(function() {
    //if any form eroor exist, add # to focus form error
    if( formError==1)
        window.location.hash   =   "addForm";
    // For search button click
    $("#section_search_button").click(function(){

        getSearchResults(currentURL);
    });
    // For search button click
    $("#section_search_button").click(function(){

        getSearchResults(currentURL);
    });
    $("#search_form").submit(function(){
        getSearchResults(currentURL);
        return false;
    });
    // For displaying Form
    $(".addrecord").click(function () {
        $(".listForm").show();


    });

    // for back button
    $(".jhistoryBack").click(function () {
        window.history.back();

    });


    // For conformation for deleting an entry
    $(".action_delete").click(function () {
        var r=confirm("Are you sure you want to delete this record!")
        if (r	==	false) return false;
    });
    // For validation message displayed in the form
    $('span.field-validation-valid, span.field-validation-error').each(function () {
        $(this).addClass('help-inline');
    });
    // For from validation
    //    $('form').validate({
    //        errorClass:'error',
    //        validClass:'success',
    //        errorElement:'span',
    //        highlight: function (element, errorClass, validClass) {
    //            $(element).parents("div[class='clearfix']").addClass(errorClass).removeClass(validClass);
    //        },
    //        unhighlight: function (element, errorClass, validClass) {
    //            $(element).parents(".error").removeClass(errorClass).addClass(validClass);
    //        }
    //    });
    // Configure form validation messages here
    //    $.extend($.validator.messages, {
    //        required: "This field is required",
    //        remote: 'needs to get fixed',
    //        email: 'is an invalid email address',
    //        url: 'is not a valid URL',
    //        date: 'is not a valid date',
    //        dateISO: 'is not a valid date (ISO)',
    //        number: 'is not a valid number',
    //        digits: 'needs to be digits',
    //        creditcard: 'is not a valid credit card number',
    //        equalTo: 'is not the same value again',
    //        accept: 'is not a value with a valid extension',
    //        maxlength: jQuery.validator.format('needs to be more than {0} characters'),
    //        minlength: jQuery.validator.format('needs to be at least {0} characters'),
    //        rangelength: jQuery.validator.format('needs to be a value between {0} and {1} characters long'),
    //        range: jQuery.validator.format('needs to be a value between {0} and {1}'),
    //        max: jQuery.validator.format('needs to be a value less than or equal to {0}'),
    //        min: jQuery.validator.format('needs to be a value greater than or equal to {0}')
    //    });
    // for goto in pagination

    $('.goto').live('keyup', function(e) {

        if ( e.keyCode === 13 ) { // 13 is enter key

            var page = parseInt($('.goto').val());
            var no_of_pages = totalResulPages;
            if(page >0 && page <= no_of_pages){
                var url=currentURL+"&page="+page;
                window.location.href=encodeURI(url);
            }else{
                alert('Enter a Page between 1 and '+no_of_pages);
                $('.goto').val("").focus();
                return false;
            }

        }

    });
    // for autocomplete
    $( ".ui-autocomplete-input" ).click(function (){

        var id=$(this).attr('id');
        var sourceUrl=$('#source_'+id).val();
        $( ".ui-autocomplete-input" ).autocomplete({

            source: MAIN_URL+sourceUrl,
            //minLength:2,
            select: function( event, ui ) {
                $("#selected_"+id).attr("value",ui.item.id+":"+ui.item.label);
            }
        });

    });
    $('#myModal').modal('hide');
    $('#settingtab a:last').tab('show');
    $(".generateReport").click(function(){
        var reportStartDate     =   $("#reportStartDate").val();
        var reportEndDate       =   $("#reportEndDate").val();
        window.location=MAIN_URL+"cms/cms/getreport?reportStartDate="+reportStartDate+"&reportEndDate="+reportEndDate+"&requestHeader="+requestHeader;
        $('#myModal').modal('hide');
        return false;
        var data = 'reportStartDate='+reportStartDate+"&reportEndDate="+reportEndDate+"&requestHeader="+requestHeader;
        $.ajax({

            url: MAIN_URL+"cms/cms/getreport",
            type: "POST",
            data: data,
            cache: false,
            success: function(res) {
                alert(res);
                $('#myModal').modal('hide');

            }
        });
    });

});
// for intializing date picker object
$(function () {

    //for hiding sections and groups in privilages
    //
    //$(".jqSectionDiv").hide();
    // For hiding Form by Cancel Button click
    $("#entity_type").live("change",function(){
        if($("#entity_type").val()=="section"){
            $(".jqGroupDiv").hide();
            $(".jqSectionDiv").show();
        }
        else{
            $(".jqGroupDiv").show();
            $(".jqSectionDiv").hide();
        }

    });
    $(".jqPrivilegeForm").live("click",function(){
        if($("#entity_type").val()=="section"){
            if($("#section_entity_id").val()==""){
                alert("Please select section");
                return false;
            }
        }
        else if($("#entity_type").val()=="group"){

            if($("#group_entity_id").val()==""){
                alert("Please select group");
                return false;
            }
        }
        else{
            alert("Please select entity type");
            return false;
        }
    });

  $(".jqSubmitForm").live("click",function(){
//      var generalForm = $("#jqCmsForm");
//        generalForm.validation();
//         if(generalForm.validate()==false) {
//             alert("ddd");
//         }
//alert("ff0");return false;
  });
      $(".jqRoleForm").live("click",function(){

        if($("#role_name").val()==""){
            alert("Please enter role");
            return false;
        }

        $('#newrole').submit();

    });
    $(".jqUserForm").live("click",function(){

        if($("#username").val()==""){
            alert("Please enter username");
            return false;
        }
        if($("#password").val()==""){
            alert("Please enter password");
            return false;
        }
        if($("#email").val()==""){
            alert("Please enter email");
            return false;
        }
        else
        {
            if(!validateEmail($("#email").val()))
            {
                alert("Please enter valid email");
                return false;
            }

        }
        $('#newuser').submit();

    });
    $(".jqCPForm").live("click",function(){

        if($("#cpassword").val()==""){
            alert("Please enter old password");
            return false;
        }
        if($("#newpassword").val()==""){
            alert("Please enter new password");
            return false;
        }
        if($("#cnewpassword").val()==""){
            alert("Please confirm new password");
            return false;
        }
        if($("#cnewpassword").val()!=$("#newpassword").val()){


            alert("Password mismatch ");
            return false;

        }
        $('#cpform').submit();

    });
    $("#cpform").submit(function(e){
        // e.preventDefault();
        });
    $(".cancelButton").click(function () {
        $(".listForm").hide();

    });
//    $(".textfield_date").datepicker({
//      dateFormat: 'mm/dd/yy'
//  });
  $(".textfield_date").datepicker({
      dateFormat: date_separator
  });

    $('.tooltiplink').tooltip({
        placement: "right"
    });
    $('.tooltiplink').live("click",function(){
        return false;
    });
    $( ".ui-autocomplete-input" ).ready(function (){


        var id=$( ".ui-autocomplete-input" ).attr('id');
        var sourceUrl=$('#source_'+id).val();
        $( ".ui-autocomplete-input" ).autocomplete({

            source: MAIN_URL+sourceUrl,
            //minLength:2,
            create: function(event, ui) {
                var selectedValue   =   $( "#"+id ).attr('value');


            },
            select: function( event, ui ) {

                $("#selected_"+id).attr("value",ui.item.id+":"+ui.item.label);
            }
        });

    });
    $(".jqCustom").live("click",function(){
        var url = ($(this).val());
        var id=$(this).attr('id');
        if(id){
            var idsplitvar    =   id.split("button_");
            var selectedId  =    idsplitvar[1];
            var  selectedValueArray    =   selectedId.split(":");
            var primarykey   =   selectedValueArray[2];

            $.ajax({
                url:url,
                type:'get',
                dataType:'html',

                success:function(data) {
alert("Record updated successfully");
                    location.reload();
                },
                beforeSend:function() {


                },
                complete:function(){



                }
            });

        }

        return false;
    });
    $(".jqCloseButton").live("click",function(){
        $("#popup").hide();
    });
    $(".jqPopupLink").live("click",function(){

        var id=$(this).attr('id');
        var  url=$(this).val();
        if(id){
            var idsplitvar    =   id.split("link_");
            var selectedId  =    idsplitvar[1];



            $.ajax({
                url:url,
                type:'get',
                dataType:'html',

                success:function(data) {

                    $("#popup").show();
                    $("#popupBody").html(data);
                },
                beforeSend:function() {


                },
                complete:function(){



                }
            });

        }

        return false;
    });
    $(".jqHeaderPopupLink").live("click",function(){
        var  url=$(this).attr('href');

        $.ajax({
            url:url,
            type:'get',
            dataType:'html',

            success:function(data) {

                $("#popup").show();
                $("#popupBody").html(data);
            },
            beforeSend:function() {


            },
            complete:function(){



            }
        });
        return false;
    });
// for  WYSIWYG Text and HTML Editor
  $('.jqModalViewDiv').css({
        width: 'auto',
        'margin-left': function () {
            return -($(this).width() / 2);
        }
    });

});
function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if( !emailReg.test( $email ) ) {
        return false;
    } else {
        return true;
    }
}
