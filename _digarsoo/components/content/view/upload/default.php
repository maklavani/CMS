<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

Templates::package('fields');
Templates::package('select2');

$setting = System::init_cookie_file('content_upload' , 'uploads/');
echo System::get_toolbar(array('view' => array('grid' , 'list') , 'toolbar' => array('delete' , 'rename' , 'copy' , 'cut' , 'paste' , 'archive' , 'extract' , 'new-folder' , 'new-file' , 'eraser' , 'download' , 'upload' , 'setting')));
echo System::get_view('content_upload' , $setting['source'] , $setting['view'] , '#content-in' , "#content" , true , 'multiple' , array('all'));