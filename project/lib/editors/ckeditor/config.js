/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/*
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
*/
CKEDITOR.editorConfig = function( config )
{
    // Define changes to default configuration here. For example:
    // config.language = 'fr';

    config.uiColor = '#F6F6F6';
    config.fontSize_sizes = '8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;15/15px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px' ;
    config.forcePasteAsPlainText = true;
    config.format_tags = 'p;h2;h3;h4;h5;h6;pre;div'
    config.height = "400px";
    // config.protectedSource.push( //g ); // Allows PHP Code
    // config.shiftEnterMode = CKEDITOR.ENTER_BR;
    config.skin = 'kama';
    config.undoStackSize = 90;
    config.width = "98%";

    config.disableNativeSpellChecker = false;
    config.scayt_autoStartup = false;

    config.toolbarCanCollapse = false;
    config.toolbar = 'Cms';
    config.toolbar_Cms =
    [
        ['Source'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord'],
        ['Undo','Redo','-','SelectAll','RemoveFormat'],['Styles','Format','FontSize'],
        '/',
        ['Bold','Italic','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink','Anchor'], ['TextColor'], ['Maximize', 'ShowBlocks']
    ];
};