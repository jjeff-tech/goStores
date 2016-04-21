/*
Function to validate role
*/
function validateRole(){
     if($('#role').val()=='') {
        $('#role_error').html('This field is required');
     }else{
         $('#role_error').html('');
     }
        var flag = false;
       $("input[name='moduleAccess[]']").each(function(i, j){
            if($(j).attr('checked')){
                flag = true;
            }
       });
       if(!flag)
            $('#module_error').html('This field is required');
        else
            $('#module_error').html('');
        if($('#role').val()=='' ||!flag){
            return false;
        }
     return true;
} // End Function

function validateListSearch(){    
    if($('#search').val()=='') {
         alert('Enter search text to continue');
         $('#search').focus();
         return false;
     }
     return true;
}

function confirmBox(action, item){
    var conf = confirm("Are you sure you want to "+action+" "+item);
    if(conf==true){
        return true;
    } else {
        return false;
    }
}

function validatePlan(){
     if($('#plan').val()=='') {
         alert('Enter Plan Name');
         $('#plan').focus();
         return false;
     }
     if($('#description').val()=='') {
         alert('Enter Description');
         $('#description').focus();
         return false;
     }

    
     return true;
} // End Function

function deletePlan(idPlan){
    var conf = confirm("Are you sure you want to delete this Plan ?");
    var rootUrl = BASE_URL+'admin/plan/drop';
    if(conf==true) {
        $.ajax({
          type: "POST",
          url: rootUrl,
          data: { id: idPlan }
        }).success(function( msg ) {
          //alert( "Data Saved: " + msg );
          //item_
          $('#item_'+idPlan).remove();
        });
    } else {
        return false;
    }
    return true;

} // End Function

function deleteListItem(idItem, actionUrl, itemLabel){
    var conf = confirm("Are you sure you want to delete this "+itemLabel+" ?");
    
    if(conf==true) {
        $.ajax({
          type: "POST",
          url: actionUrl,
          data: { id: idItem }
        }).success(function( msg ) {
          // On success remove the item
          if(msg!='') {
              alert(msg);
          }
          $('#item_'+idItem).remove();
        });
    } else {
        return false;
    }
    return true;

} // End Function

function validatePlanPurchaseCategory(){
     if($('#category').val()=='') {
         alert('Enter Plan Pruchase Category Name');
         $('#category').focus();
         return false;
     }
     if($('#description').val()=='') {
         alert('Enter Description');
         $('#description').focus();
         return false;
     }


     return true;
} // End Function

function validatePlanPurchaseCategoryDetail(){

     if($('#category').val()=='') {
         alert('Select Plan Pruchase Category');
         $('#category').focus();
         return false;
     }

     if($('#description').val()=='') {
         alert('Enter Description');
         $('#description').focus();
         return false;
     }
     
     if($('#amount').val()=='') {
         alert('Enter Amount');
         $('#amount').focus();
         return false;         
     } else if(isNaN($('#amount').val())) {
         alert('Enter valid Amount');
         $('#amount').focus();
         return false;
     }
     
     return true;
} // End Function

function validatePlanPackage(){

     if($('#plan').val()=='') {
         alert('Select Plan');
         $('#plan').focus();
         return false;
     }

     if($('#description').val()=='') {
         alert('Enter Description');
         $('#description').focus();
         return false;
     }

     if($('#amount').val()=='') {
         alert('Enter Amount');
         $('#amount').focus();
         return false;
     } else if(isNaN($('#amount').val())) {
         alert('Enter valid Amount');
         $('#amount').focus();
         return false;
     }

     return true;
} // End Function

function deleteProduct(idItem, actionUrl, itemLabel){
    var conf = confirm("Are you sure you want to delete this "+itemLabel+" ?");

    if(conf==true) {
        $.ajax({
          type: "POST",
          url: actionUrl,
          data: { id: idItem }
        }).success(function( msg ) {
          // On success remove the item
          if(msg=='serviceexists') {
              alert('Unable to delete the Product. Services under this product are in use');
          }else if(msg!='') {
              alert(msg);
              $('#item_'+idItem).remove();
          }
          
        });
    } else {
        return false;
    }
    return true;

} // End Function

