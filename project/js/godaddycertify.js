function loadCertifier()
{
    if(confirm("Proceed only if you already haven't certified your Godaddy account."))
        {
            popup('cms?section=godaddycertify');
        }
}

function popup(url)
{
    console.log(url);
    url = window.location.href.split('?')[0];
    url+= '?section=godaddycertify';
    console.log(url);
    
 var width  = 400;
 var height = 400;
 var left   = (screen.width  - width)/2;
 var top    = (screen.height - height)/2;
 var params = 'width='+width+', height='+height;
 params += ', top='+top+', left='+left;
 params += ', directories=no';
 params += ', location=no';
 params += ', menubar=no';
 params += ', resizable=no';
 params += ', scrollbars=no';
 params += ', status=no';
 params += ', toolbar=no';
 newwin=window.open(url,'windowname5', params);
 if (window.focus) {newwin.focus()}
 return false;
}