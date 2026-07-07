/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		//{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		//{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		//{ name: 'links', groups: [ 'links' ] },
		//{ name: 'insert', groups: [ 'insert' ] },
		//{ name: 'forms', groups: [ 'forms' ] },
		//{ name: 'tools', groups: [ 'tools' ] },
		//{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		//{ name: 'others', groups: [ 'others' ] },
		//'/',
		//{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		//{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		//{ name: 'styles', groups: [ 'styles' ] },
		//{ name: 'colors', groups: [ 'colors' ] },
		//{ name: 'about', groups: [ 'about' ] }
		{ name: 'basicstyles', groups: [ 'basicstyles' ] },
		{ name: 'paragraph', groups: [ 'list' ] }
	];
	config.removeButtons = 'Image,Strike,About,HorizontalRule,Paste';
	config.height = '100px';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	
	// MY
	config.language = 'ru';
	config.toolbarCanCollapse = true;
	// MATH
	config.extraPlugins = 'lineutils,mathjax,widget';
	config.mathJaxLib = '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_HTMLorMML';

	/* Filebrowser routes */
	// The location of an external file browser, that should be launched when "Browse Server" button is pressed.
//	config.filebrowserBrowseUrl = "/ckeditor/attachment_files";

	// The location of an external file browser, that should be launched when "Browse Server" button is pressed in the Flash dialog.
//	config.filebrowserFlashBrowseUrl = "/ckeditor/attachment_files";

	// The location of a script that handles file uploads in the Flash dialog.
//	config.filebrowserFlashUploadUrl = "/ckeditor/attachment_files";

	// The location of an external file browser, that should be launched when "Browse Server" button is pressed in the Link tab of Image dialog.
//	config.filebrowserImageBrowseLinkUrl = "/ckeditor/pictures";

	// The location of an external file browser, that should be launched when "Browse Server" button is pressed in the Image dialog.
//	config.filebrowserImageBrowseUrl = "/ckeditor/pictures";

	// The location of a script that handles file uploads in the Image dialog.
//	config.filebrowserImageUploadUrl = "/ckeditor/pictures";

	// The location of a script that handles file uploads.
//	config.filebrowserUploadUrl = "/ckeditor/attachment_files";

//	config.allowedContent = true;
};
