$(document).ready(function(){

    $('.accountAction').click(function(){
         var cn = confirm("Are you sure you want to continue?");

         if(cn==true){
             return true;
         } else {
             return false;
         }
    });

});

