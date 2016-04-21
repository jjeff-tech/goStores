function checkValue(){
        var splFlag = true;
        if($("input[name='note[]']").length > 0){
            $("input[name='note[]']").each(function(i,j){
              var itemid=$(j).attr("id");
              var itemPostfix =itemid.split('note_')[1];

              var erMsg = '';
              $('#err_'+itemPostfix).html('');
              if($(j).val()==''){
                  erMsg +='Enter note';
                  splFlag=false;
              }
              if($('#cost_'+itemPostfix).val()==''){
                  erMsg +=(erMsg=='')? '' : ', ';
                  erMsg +='Enter cost';
                  splFlag=false;
              }else if(isNaN($('#cost_'+itemPostfix).val())){
                  erMsg +=(erMsg=='')? '' : ', ';
                  erMsg +='Enter cost in number format';
                  $('#cost_'+itemPostfix).val('0');
                  splFlag=false;
              }
               if(!$("input[name='capture["+itemPostfix+"]']").is(':checked')){
                  erMsg +=(erMsg=='')? '' : ', ';
                  erMsg +='Choose capturing method';
                  splFlag=false;
               }

              if(erMsg!=''){
                  $('#err_'+itemPostfix).html(erMsg);
              }

            });
        }
        if(splFlag==true){

            return true;

        } else {

            return false;
        }

    } // End Function

    function dropSpecials(item){
        var conf = confirm('Are you sure you want to remove this Specials ? ');
        if(conf){
            $('#specials_'+item).remove();
        }
    } // End Function