$(document).ready(function(){
     if($('#auto_set').attr('checked')){
        $('#err_div').slideDown('slow');
    }
    $('#auto_set').click(function(){divToggle(this)});
});

function divToggle(elem)
{
    if($(elem).attr('checked')){
        $('#err_div').slideDown('slow');
    }
    else{
        $('#err_div').slideUp('slow');
    }
}
