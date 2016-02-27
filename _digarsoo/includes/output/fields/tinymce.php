<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		08/31/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class TinymceField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('tinymce');
		Templates::add_js("
			tinymce.init({
				mode : 'textareas' ,
				selector: '.tinymce-content-" . $name . "',
			    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
			    toolbar2: 'print preview media | forecolor backcolor emoticons',
			    plugins: [
			        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			        'searchreplace wordcount visualblocks visualchars code fullscreen',
			        'insertdatetime media nonbreaking save table contextmenu directionality',
			        'emoticons template paste textcolor colorpicker textpattern'
			    ],
			    height: 400,
 				document_base_url: '" . Site::$base . "'
				" . ((Language::$direction == 'rtl') ? " , content_css: '" . Site::$base . "media/js/library/tinymce/css/rtl.css'" : "") . "
			});
		" , true);

		$output = "<textarea class=\"tinymce-content-" . $name . "\" name=\"field_input_" . $name .  "\" tabindex=\"" . $tabindex . "\" " . $attributes . ">" . $default . "</textarea>";

		if(!empty($children) && is_array($children))
		{
			if(in_array('image' , $children))
			{
				Templates::package('pupup');
				Templates::package('image_tinymce');

				$setting = System::init_cookie_file('image_tinymce' , 'uploads/');
				$toolbar = System::get_toolbar(array('view' => array('grid' , 'list') , 'toolbar' => array('delete' , 'rename' , 'new-folder' , 'upload')));
				$view = System::get_view('image_tinymce' , $setting['source'] , $setting['view'] , '.active-page' , '#popup' , false , 'multiple' , array('gif' , 'jpg' , 'jpeg' , 'png'));
				$upload = json_encode(array('toolbar' => $toolbar , 'view' => $view) , JSON_UNESCAPED_UNICODE);

				$output .= "<input type=\"hidden\" name=\"image_tinymce\">";
				$output .= "<div class=\"image_tinymce x5 s4 m3 l2\" upload=\"" . htmlentities($upload) . "\" image=\"" . Language::_('SELECT') . "\" save=\"" . Language::_('SAVE') . "\" CANCEL=\"" . Language::_('CANCEL') . "\">" . Language::_('IMAGE') . "</div>";
			}

			if(in_array('article' , $children))
			{
				Templates::package('select2');
				Templates::package('popup');
				Templates::package('article_tinymce');
				Templates::package('toolbar');

				$output .= "<input type=\"hidden\" name=\"article_tinymce\">";
				$output .= "<input type=\"hidden\" name=\"article_name_tinymce\">";
				$output .= "<div class=\"article_tinymce x5 s4 m3 l2\" ajax=\"" . Site::$base . _ADM . "index.php?component=content&ajax=article\" title=\"" . Language::_('SELECT') . "\" save=\"" . Language::_('SAVE') . "\" CANCEL=\"" . Language::_('CANCEL') . "\">" . Language::_('ARTICLE') . "</div>";
			}
		}

		return $output;
	}
}