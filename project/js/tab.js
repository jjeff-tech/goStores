/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function (){
     /* Tab Code  */
    $(".tab_style").hide();
    if(active_tab)
        $("#"+active_tab).fadeIn();
    $(".tab_hdr ul li").click(function() {
        $(".tab_hdr li").removeClass('coursetab_active');
        $(this).addClass("coursetab_active");
        $(".tab_style").hide();
        var selected_tab = $(this).find("a").attr("href");
        $('#'+selected_tab).fadeIn();
        return false;
    });
     /* Tab Code End */

});