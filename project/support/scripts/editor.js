
function loadCKEditor(container, width){
CKEDITOR.replace( container,
        {

            /*toolbar :
            [
                ['Source','Bold','Italic','Underline', '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-', 'NumberedList','BulletedList','-','Outdent','Indent'],
                ['Format','Font','FontSize', 'TextColor','Image','Smiley']
            ],*/
        toolbar :
            [
                ['Source','-','Bold','Italic','Underline', '-', 'NumberedList','BulletedList', '-', 'NumberedList','BulletedList'],
                ['Font','FontSize','Image']
            ],

        filebrowserBrowseUrl : siteUrl+'ckeditor/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : siteUrl+'ckeditor/ckfinder/ckfinder.html?type=Images',
	filebrowserFlashBrowseUrl : siteUrl+'ckeditor/ckfinder/ckfinder.html?type=Flash',
	filebrowserUploadUrl : 
 	   siteUrl+'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&currentFolder=/archive/',
	filebrowserImageUploadUrl : 
	   siteUrl+'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=/cars/',
	filebrowserFlashUploadUrl : siteUrl+'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        
           });

     CKEDITOR.config.width = width;
     CKEDITOR.config.height = 150;

}
