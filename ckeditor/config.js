/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'ru';
	//config.uiColor = '#AADC6E';
	
	config.skin = 'office2003';
	config.toolbar = 'Full';

config.toolbar_Full =
[
	['Undo','Redo','-','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteFromWord'],
	['Bold','Italic','Strike','Subscript','Superscript'],
	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	['Find','Replace','-','SelectAll','RemoveFormat'],
	'/',
	['Link','Unlink','Anchor'],
	['TextColor','BGColor'],
	['uploader','Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
	['Styles','Format','FontSize'],
	['ShowBlocks','Source'],['Maximize']
];

config.toolbar_Basic =
[
	['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
];



};


