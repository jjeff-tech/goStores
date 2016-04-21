// JavaScript Document

function rateProduct(id)
{
    //alert(id); return false;
    var windowWidth             = $(window).width();
    var windowHeight            = $(window).height();
    var popupHeight             = $(".jqRatingPop").height();
    var popupWidth              = $(".jqRatingPop").width();
    var pageScrollTop           = document.documentElement.scrollTop ? document.documentElement.scrollTop : (document.body.scrollTop ? document.body.scrollTop : 0);
    var addCommentBoxTop        = pageScrollTop + (windowHeight - popupHeight) /2;
    var addCommentBoxLeft       = (windowWidth - popupWidth) / 2;
    //alert(addCommentBoxTop);
    //return false;

    $("#jqRatingPop_"+id).css({
        position : "absolute",
        left : addCommentBoxLeft,
        top: addCommentBoxTop
    }).fadeIn("slow");

    return false;
}
function rateKB()
{
   
    //alert(id); return false;
    var windowWidth             = $(window).width();
    var windowHeight            = $(window).height();
    var popupHeight             = $(".jqRatingPop").height();
    var popupWidth              = $(".jqRatingPop").width();
    var pageScrollTop           = document.documentElement.scrollTop ? document.documentElement.scrollTop : (document.body.scrollTop ? document.body.scrollTop : 0);
    var addCommentBoxTop        = pageScrollTop + (windowHeight - popupHeight) /2;
    var addCommentBoxLeft       = (windowWidth - popupWidth) / 2;
    //alert(addCommentBoxTop);
    //return false;
   /*  $("#jqRatingPop").css({
        position : "absolute",
        left : addCommentBoxLeft,
        top: addCommentBoxTop
    });*/
    
    $("#jqRatingPop").css({
        position : "absolute",
        left : addCommentBoxLeft,
        top: addCommentBoxTop
    }).fadeIn("slow");

    return false;
}



function closeProductRating(id)
{

    $("#jqRatingPop_"+id).fadeOut("slow");
    return false;
}

function closeKBRating()
{

    $("#jqRatingPop").fadeOut("slow");
    return false;
}



function submitProductRating(id)
{
    
    var ticket_id 	= 	$("#hid_ticket_id").val();
    var user_id         =	$("#hid_user_id").val();
    var comment       =       $("#txtComment_"+id).val();
	
    var clickedStarVal  = $('input[name=star'+id+']:checked').val();

    if(clickedStarVal  == 0 ||  typeof clickedStarVal=="undefined")
    {
        alert("Please select a rating for this staff!");
        return false;
    }
    else
    {
        var ajaxSubmitOption 	= {
            url      : "review-ajax.php",
            data     : {
                ticket_id : ticket_id,
                user_id : user_id,
                clickedStarVal : clickedStarVal,
                staff_id : id,
                comment :comment
            },
            type     : "post",
            dataType : "json",
            success  : function(data)
            {
                                             
                if(data.error)
                {
                    alert('Sorry, your rating  is not updated. Please try again!');
                }
                if(data.duplicate)
                {
                    alert('Sorry, You have already posted rating for this staff.!');
                }
                else if(data.success)
                {
                    $("#jqRatingPop_"+id).fadeOut("slow");
                    alert("Your Rating Posted Successfully .");
                    parent.location.reload();

                }
            },
            beforeSend : function()
            {
                                            
            },
            complete : function()
            {
                                        
            }
        };
        $.ajax(ajaxSubmitOption);
    }
    return false;
}

function submitKBRating()
{
    
  
    var kb_id           =   $("#txtKbSearchid").val();
    var user_id 	=   $("#hid_user_id").val();
    var site_url        =   $("#site_url").val();
    
    
    var clickedStarVal  = $('input[name=star]:checked').val();

    if(clickedStarVal  == 0 ||  typeof clickedStarVal=="undefined")
    {
        alert("Please select a rating !");
        return false;
    }
    else
    {
        var ajaxSubmitOption 	= {
            url      : site_url+"ajaxKbRating.php",
            data     : {
                        kb_id : kb_id,
                        user_id : user_id,
                        clickedStarVal : clickedStarVal
                        
            },
            type     : "post",
            dataType : "json",
            success  : function(data)
            {
                                
                if(data.error)
                {
                    alert('Sorry, your rating  is not updated. Please try again!');
                }
                if(data.duplicate)
                {
                    alert('Sorry, You have already posted rating for this entry.!');
                }
                else if(data.success)
                {
                    $("#jqRatingPop").fadeOut("slow");
                    alert("Your Rating Posted Successfully .");
                    //parent.location.reload();

                }
            },
            beforeSend : function()
            {

            },
            complete : function()
            {

            }
        };
        $.ajax(ajaxSubmitOption);
    }
    return false;
}

