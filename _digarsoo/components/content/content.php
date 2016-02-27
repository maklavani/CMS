<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		02/18/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ContentController extends Controller {
	// khandane menu
	public function menu($link)
	{
		if($link == 'article')
			return array('type' => 'ajax' , 'value' => Site::$base . _ADM . 'index.php?component=content&ajax=article');
		else if($link == 'category')
			return array('type' => 'ajax' , 'value' => Site::$base . _ADM . 'index.php?component=content&ajax=category');
	}

	public function ajax($ajax)
	{
		// upload yek file
		if(isset($_FILES['file']))
		{
			$file = $_FILES['file'];
			$session = Sessions::get_session('upload');

			if($session)
			{
				if(System::free_space(_SRC_SITE) <= $file['size'])
					echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_CONTENT_ERROR_FREE_SPACE') , System::get_file_size(System::free_space(_SRC_SITE))) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				else
				{					
					$session = json_decode($session);
					$source = $session->source;

					if($session->name == $file['name'])
					{
						$base = $file['name'];
						$dot_pos = strrpos($base , '.' , -1);
						$type = substr($base , $dot_pos - strlen($base) + 1);
						$name = preg_replace('/' . '.' . $type . '/' , '' , $base);
						$name = str_replace(" " , '_' , $name);
						$nameout = $name;

						if($nameout != '' && !Regex::cs($nameout , "text"))
							$nameout = 'upload';

						$number = 1;
						while (true) {
							if(System::has_file($source . $nameout . '.' . $type))
							{
								$number++;
								$nameout = $name . '_' . $number;
							}
							else
								break;
						}

						$name = $nameout . '.' . $type;

						if (move_uploaded_file($file["tmp_name"] , _SRC_SITE . $source . $name)) {
							echo json_encode(array('status' => true) , JSON_UNESCAPED_UNICODE);
						} else {
							echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_CONTENT_ERROR_MOVE_FILE') , $file['name']) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						}
					}
				}

				Sessions::delete_session('upload');
			}
			else
				echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_CONTENT_ERROR_MOVE_FILE') , $file['name']) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
		}

		if(isset($_GET['ajax']))
		{
			if($_GET['ajax'] == 'article')
				require_once _COMP . 'content/menu/article.php';
			else if($_GET['ajax'] == 'category')
				require_once _COMP . 'content/menu/category.php';
			else if($_GET['ajax'] == 'tags' && isset($_GET['q']))
			{
				$model = self::model('tags');
				echo json_encode($model->get_all($_GET['q']) , JSON_UNESCAPED_UNICODE);
			}
			else if($_GET['ajax'] == 'users' && isset($_GET['q']))
			{
				$model = self::model('upload');
				echo json_encode($model->get_all($_GET['q']) , JSON_UNESCAPED_UNICODE);
			}

			else if(isset($_POST['upload']) && isset($_POST['upload_value']) && isset($_POST['upload_source']) && isset($_POST['upload_name']))
			{
				if($_POST['upload'] == 'read'){
					if((Regex::cs($_POST['upload_value'] , "text") || ($_POST['upload_value'] == "" && User::$group == 2)) && System::has_file(str_replace("_DIR_" , "/" , $_POST['upload_value'])))
						echo json_encode(array('status' => true , 'html' => System::print_path(str_replace("_DIR_" , "/" , $_POST['upload_value']))) , JSON_UNESCAPED_UNICODE);
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'edit')
				{
					$src = str_replace("_DIR_" , "/" , $_POST['upload_value']);

					if(Regex::cs($src , 'source') && System::has_file($src))
					{
						$base = basename(_SRC_SITE . $src);
						$dot_pos = strrpos($base , '.' , -1);
						$type = substr($base , $dot_pos - strlen($base) + 1);
						$name = preg_replace('/' . '.' . $type . '/' , '' , $base);

						if(in_array($type , array('html' , 'css' , 'js' , 'json' , 'txt' , 'php' , 'xml' , 'svg')) || (User::$group == 2 && $type == 'htaccess'))
						{
							$html = "<textarea id=\"code\" file=\"" . str_replace("/" , "_DIR_" , $src) . "\">" . file_get_contents(_SRC_SITE . $src) . "</textarea>";
							echo json_encode(array('status' => true , 'html' => $html) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('status' => false) , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'save')
				{					
					$src = str_replace("_DIR_" , "/" , $_POST['upload_source']);

					if(Regex::cs($src , 'source') && System::has_file($src))
					{
						$content = htmlspecialchars_decode(json_decode($_POST['upload_value']));
						$file = fopen(_SRC_SITE . $src , "w");
						fwrite($file , $content);
						fclose($file);

						echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_EDITED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'savesetting')
				{
					$values = json_decode($_POST['upload_value'] , true);
					$src = $values['src'] . '.' . _COPR;

					if(Regex::cs($src , 'source') && System::has_file($src))
					{
						$content_file = json_decode(file_get_contents(_SRC_SITE . $src));

						$content_file->permission = $values['permission'];
						$content_file->deleted = (int)!$values['deleted'];
						$content_file->users = array();

						if(!empty($values['users']))
							foreach ($values['users'] as $key => $value) {
								if($value == Language::_('COM_CONTENT_UPLOAD_ALL'))
									$content_file->users[] = -1;
								else
									$content_file->users[] = $value;
							}

						$file = fopen(_SRC_SITE . $src , "w");
						fwrite($file , json_encode($content_file , JSON_PRETTY_PRINT));
						fclose($file);

						echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_UPLOAD_DONE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'delete')
				{
					$srcs = json_decode($_POST['upload_value']);
					$deleted = System::is_deleted($srcs);

					if(!$deleted)
					{
						$delete = System::delete_files($srcs);

						if(!$delete)
							echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_DELETED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						else
							echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_DELETED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_NOT_DELETED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'rename')
				{
					if(Regex::cs($_POST['upload_source'] , 'source') && System::has_file($_POST['upload_source']))
					{
						if(Regex::cs($_POST['upload_value'] , "text"))
						{
							$rename = System::rename($_POST['upload_value'] , $_POST['upload_source']);

							if($rename)
								echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_EXTRACT') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
							else
								echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_RENAMED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_NAME') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'pastecopy' || $_POST['upload'] == 'pastecut')
				{
					$srcs = json_decode($_POST['upload_value']);

					if(Regex::cs($_POST['upload_source'] , 'source') && System::has_file(str_replace("_DIR_" , "/" , $_POST['upload_source'])))
					{
						$copies = System::copies($srcs , str_replace("_DIR_" , "/" , $_POST['upload_source']));

						if(!$copies && $_POST['upload'] == 'pastecut')
						{
							$delete = System::delete_files($srcs);

							if(!$delete)
								echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_COPIES') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
							else
								echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_DELETED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						}
						else if(!$copies)
							echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_COPIES') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						else
							echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_COPIES') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'archivecopy' || $_POST['upload'] == 'archivecut')
				{
					$srcs = json_decode($_POST['upload_value']);

					if(Regex::cs($_POST['upload_name'] , "text"))
					{
						if(Regex::cs($_POST['upload_source'] , 'source') && System::has_file(str_replace("_DIR_" , "/" , $_POST['upload_source'])))
						{
							$archive = System::archive($srcs , str_replace("_DIR_" , "/" , $_POST['upload_source']) , $_POST['upload_name']);

							if(!$archive && $_POST['upload'] == 'archivecut')
							{
								$delete = System::delete_files($srcs);

								if(!$delete)
									echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_ARCHIVE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
								else
									echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_DELETED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
							}
							else if(!$archive)
								echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_ARCHIVE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
							else
								echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_ARCHIVE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_NAME') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'extract')
				{
					$srcs = json_decode($_POST['upload_value']);

					if(	Regex::cs(str_replace("_DIR_" , "/" , $_POST['upload_source']) , 'source') && System::has_file(str_replace("_DIR_" , "/" , $_POST['upload_source'])) && 
						Regex::cs($srcs[0] , 'source') && System::has_file($srcs[0]))
					{
						$extract = System::extract($srcs[0] , str_replace("_DIR_" , "/" , $_POST['upload_source']));

						if($extract)
							echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_EXTRACT') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						else
							echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_EXTRACT') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'download')
				{
					$srcs = json_decode($_POST['upload_value']);

					if(	Regex::cs(str_replace("_DIR_" , "/" , $_POST['upload_source']) , 'source') && System::has_file(str_replace("_DIR_" , "/" , $_POST['upload_source'])) && 
						Regex::cs($srcs[0] , 'source') && System::has_file($srcs[0]))
						echo json_encode(array('status' => true , 'src' => str_replace(Site::$base . 'uploads' , Site::$base . 'download' , Site::$base . $srcs[0])) , JSON_UNESCAPED_UNICODE);
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'new-folder')
				{
					if(Regex::cs(str_replace("_DIR_" , "/" , $_POST['upload_source']) , 'source') && System::has_file(str_replace("_DIR_" , "/" , $_POST['upload_source'])))
					{
						if($_POST['upload_value'] != "" && Regex::cs($_POST['upload_value'] , "text_with_space"))
						{
							$folder = System::new_folder($_POST['upload_value'] , str_replace("_DIR_" , "/" , $_POST['upload_source']));

							if($folder)
								echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_NEW_FOLDER') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
							else
								echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_NEW_FOLDER') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_NAME') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'new-file')
				{
					if(	Regex::cs(str_replace("_DIR_" , "/" , $_POST['upload_source']) , 'source') && System::has_file(str_replace("_DIR_" , "/" , $_POST['upload_source'])))
					{
						$name = explode("." , $_POST['upload_value']);

						if(isset($name[0]) && isset($name[1]) && $name[0] != "" && $name[1] != "" && Regex::cs($name[0] , "text") && Regex::cs($name[1] , "text"))
						{
							$file = System::new_file($name[0] . '.' . $name[1] , str_replace("_DIR_" , "/" , $_POST['upload_source']));

							if($file)
								echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span> " . Language::_('COM_CONTENT_NEW_FILE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
							else
								echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_NEW_FILE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_NAME') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'uploadform')
				{
					$html = "<form id=\"upload-form\" class=\"xa\" action=\"" . Site::$base . _ADM . "index.php?component=content&ajax\" enctype=\"multipart/form-data\" method=\"post\" done=\"" . Language::_('COM_CONTENT_DONE') . "\">";
						$html .= "<input id=\"file-input\" class=\"xa\" type=\"file\" name=\"files\" multiple>";
						$html .= "<div id=\"file-input-text\" class=\"xa\"><div class=\"file-input-text-top\">" . Language::_('DRAG_AND_DROP') . "</div><div class=\"file-input-text-bottom\">" . Language::_('BROWSE') . "</div></div>";
						$html .= "<div id=\"upload-list\" class=\"xa\"></div>";
					$html .= "</form>";

					echo json_encode(array('status' => true , 'html' => $html) , JSON_UNESCAPED_UNICODE);
				}
				else if($_POST['upload'] == 'setting')
				{
					if(System::has_file($_POST['upload_value']))
					{
						$source = $_POST['upload_value'];

						if(System::has_file($source . '.' . _COPR))
							$setting = json_decode(file_get_contents(_SRC_SITE . $source . '.' . _COPR) , true);
						else
						{
							$setting = array('permission' => 5 , 'downloads' => 0 , 'deleted' => 1 , 'users' => array('-1'));

							$file = fopen(_SRC_SITE . $source . '.' . _COPR , "w");
							fwrite($file , json_encode($setting , JSON_PRETTY_PRINT));
							fclose($file);
						}

						require_once _INC . 'output/fields.php';
						$fields = New Fields;
						$fields->name = 'COM_CONTENT_UPLOAD';
						$fields->action = Site::$base . _ADM . "index.php?component=content&ajax";
						$fields->method = 'post';
						$pages = array();

						$db = new Database;
						$db->table('permissions')->order('id DESC')->select()->process();
						$permission = $db->output();

						$permissions = array();
						foreach ($permission as $key => $value)
							$permissions[$value->id] = Language::_($value->name);

						$users = array();
						foreach ($setting['users'] as $key => $value){
							if($value == -1)
								$users[$value] = Language::_('COM_CONTENT_UPLOAD_ALL');
							else
								$users[$value] = $value;
						}

						$src = array(
										0 => array('type' => 'list' , 'name' => 'permission' , 'children' => $permissions , 'default' => $setting['permission'] , 'language' => false) , 
										1 => array('type' => 'radio' , 'name' => 'deleted' , 'children' => array(0 => "yes" , 1 => "no") , 'default' => !$setting['deleted']) ,  
										2 => array("type" => "ajax" , "name" => "users" , "attributes" => array("ajax" => Site::$base . _ADM . 'index.php?component=content&ajax=users') , 'default' => $users) , 
										3 => array('type' => 'html' , 'name' => 'address' , 'default' => $source) , 
										4 => array('type' => 'html' , 'name' => 'downloads' , 'default' => number_format($setting['downloads'])) , 
										5 => array('type' => 'hidden' , 'name' => 'src' , 'default' => $source)
									);

						$pages['src'] = $src;
						$fields->pages = $pages;
						$html = $fields->output(true);

						echo json_encode(array('status' => true , 'html' => $html) , JSON_UNESCAPED_UNICODE);
					}
				}
			}
			else if(isset($_GET['uploadper_size']) && isset($_GET['uploadper_name']) && isset($_GET['source']) && Regex::cs($_GET['uploadper_size'] , 'number'))
			{
				$source = str_replace("_DIR_" , "/" , $_GET['source']);

				if(Regex::cs($source , 'source') && System::has_file($source))
				{
					if(Configuration::$file_upload_size >= $_GET['uploadper_size'])
					{
						$base = $_GET['uploadper_name'];
						$dot_pos = strrpos($base , '.' , -1);
						$type = substr($base , $dot_pos - strlen($base) + 1);

						$file_types = array('ai' , 'bmp' , 'css' , 'csv' , 'doc' , 'docx' , 'eot' , 'gif' , 'html' , 
											'jpg' , 'jpeg' , 'js' , 'json' , 'mp3' , 'mp4' , 'ogg' , 'pdf' , 'php' , 
											'png' , 'ppt' , 'pptx' , 'psd' , 'rar' , 'svg' , 'swf' , 'ttf' , 'txt' , 
											'wav' , 'webm' , 'woff' , 'woff2' , 'xls' , 'xlsx' , 'xml' , 'zip');

						if(in_array($type , $file_types))
						{
							$session = json_encode(array('name' => $_GET['uploadper_name'] , 'source' => $source) , JSON_UNESCAPED_UNICODE);
							Sessions::set_session('upload' , $session);
							echo json_encode(array('status' => true) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_CONTENT_ERROR_TYPE') , $_GET['uploadper_name'] , implode(' ' , $file_types)) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_CONTENT_ERROR_SIZE') , $_GET['uploadper_name'] , System::get_file_size(Configuration::$file_upload_size)) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else
					echo json_encode(array('message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_CONTENT_ERROR_VALID_SRC') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
			}
		}
	}

	// khandane view
	public function view($view , $action)
	{
		if($view == 'article')
		{
			$model = self::model('article');

			if($action == 'default')
			{
				$articles = json_encode($model->get('article' , 'content') , JSON_UNESCAPED_UNICODE);
				View::read($articles);
			}
			else if($action == 'new')
				View::read();
			else if($action == 'edit')
			{
				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'article'))
				{
					$article = json_encode($model->get_with_id($_GET['id'] , 'article') , JSON_UNESCAPED_UNICODE);
					View::read($article);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=content&view=article');
			}
		}
		else if($view == 'category')
		{
			$model = self::model('category');

			if($action == 'default')
			{
				$category = json_encode($model->get('category' , 'content' , true) , JSON_UNESCAPED_UNICODE);
				View::read($category);
			}
			else if($action == 'new')
				View::read();
			else if($action == 'edit')
			{
				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'category'))
				{
					$category = json_encode($model->get_with_id($_GET['id'] , 'category') , JSON_UNESCAPED_UNICODE);
					View::read($category);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=content&view=category');
			}
		}
		else if($view == 'tags')
		{
			$model = self::model('tags');

			if($action == 'default')
			{
				$tags = json_encode($model->get('tags' , 'content') , JSON_UNESCAPED_UNICODE);
				View::read($tags);
			}
			else if($action == 'new')
				View::read();
			else if($action == 'edit')
			{
				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'tags'))
				{
					$tags = json_encode($model->get_with_id($_GET['id'] , 'tags') , JSON_UNESCAPED_UNICODE);
					View::read($tags);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=content&view=tags');
			}
		}
		else if($view == 'upload')
			View::read();
		else if($view == 'comments')
		{
			$model = self::model('comments');

			if($action == 'default')
			{
				$comments = json_encode($model->get('comments' , 'content' , true) , JSON_UNESCAPED_UNICODE);
				View::read($comments);
			}
			else if($action == 'new')
			{
				$model = self::model('comments');

				if(isset($_GET['article']) && Regex::cs($_GET['article'] , 'numeric') && $model->has_article($_GET['article']))
				{
					$article = $model->get_with_id($_GET['article'] , 'article');
					View::read(json_encode($article , JSON_UNESCAPED_UNICODE));
				}
				else
				{
					Controller::$action = 'select_article';
					View::read();
				}
			}
			else if($action == 'edit')
			{
				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'comments'))
				{
					$comments = json_encode($model->get_with_id($_GET['id'] , 'comments') , JSON_UNESCAPED_UNICODE);
					View::read($comments);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=content&view=comments');
			}
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if($view == 'article')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('article');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=content&view=article&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'article'))
					return Site::$base . _ADM . 'index.php?component=content&view=article&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'article'))
					$model->set_block($_POST['form-values'] , 'article');
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'article'))
					$model->set_unblock($_POST['form-values'] , 'article');
				else if($_POST['form-button'] == 'special' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'article'))
					$model->set_special($_POST['form-values'] , 'article');
				else if($_POST['form-button'] == 'unspecial' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'article'))
					$model->set_unspecial($_POST['form-values'] , 'article');
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'article'))
					$model->delete_items($_POST['form-values'] , 'article');
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=article';
				else
				{
					$model = self::model('article');
					
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'content/view/article/new.json') , 'COM_CONTENT_ARTICLE_' , 'field_input_');
					$result += $checks->check();

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_ARTICLE')));
						return Site::$base . _ADM . 'index.php?component=content&view=article&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_ARTICLE')));
						return Site::$base . _ADM . 'index.php?component=content&view=article&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_ARTICLE')));
						return Site::$base . _ADM . 'index.php?component=content&view=article';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=article';
				else
				{
					$model = self::model('article');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'article'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'content/view/article/edit.json') , 'COM_CONTENT_ARTICLE_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_article();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_ARTICLE')));
							return Site::$base . _ADM . 'index.php?component=content&view=article&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_article();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_ARTICLE')));
							return Site::$base . _ADM . 'index.php?component=content&view=article';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=content&view=article';
				}
			}
		}
		else if($view == 'category')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('category');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=content&view=category&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'category'))
					return Site::$base . _ADM . 'index.php?component=content&view=category&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'category'))
					$model->set_block($_POST['form-values'] , 'category');
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'category'))
					$model->set_unblock($_POST['form-values'] , 'category');
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'category'))
				{
					$result = false;

					if($result = $model->has_item($_POST['form-values'] , 'article' , 'category' , false))
						Messages::add_message('warning' , sprintf(Language::_('WARNING_DELETE') , Language::_('COM_CONTENT_CATEGORY') , Language::_('COM_CONTENT_ARTICLE')));
					else if($result = $model->has_newsfeed($_POST['form-values']))
						Messages::add_message('warning' , sprintf(Language::_('WARNING_DELETE') , Language::_('COM_CONTENT_CATEGORY') , Language::_('COM_CONTENT_NEWSFEED')));
					else if($result = $model->has_item($_POST['form-values'] , 'category' , 'parent' , false))
						Messages::add_message('warning' , sprintf(Language::_('WARNING_DELETE') , Language::_('COM_CONTENT_CATEGORY') , Language::_('COM_CONTENT_CATEGORY')));
					
					if(!$result)
						$model->delete_items($_POST['form-values'] , 'category');
				}
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=category';
				else
				{
					$model = self::model('category');
					
					// check kardane form
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'content/view/category/new.json') , 'COM_CONTENT_CATEGORY_' , 'field_input_');
					$result += $checks->check();

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_CATEGORY')));
						return Site::$base . _ADM . 'index.php?component=content&view=category&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_CATEGORY')));
						return Site::$base . _ADM . 'index.php?component=content&view=category&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_CATEGORY')));
						return Site::$base . _ADM . 'index.php?component=content&view=category';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=category';
				else
				{
					$model = self::model('category');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'category'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'content/view/category/edit.json') , 'COM_CONTENT_CATEGORY_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_category();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_CATEGORY')));
							return Site::$base . _ADM . 'index.php?component=content&view=category&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_category();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_CATEGORY')));
							return Site::$base . _ADM . 'index.php?component=content&view=category';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=content&view=category';
				}
			}
		}
		else if($view == 'tags')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('tags');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=content&view=tags&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'tags'))
					return Site::$base . _ADM . 'index.php?component=content&view=tags&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'tags'))
					$model->delete_items($_POST['form-values'] , 'tags');
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=tags';
				else
				{
					$model = self::model('tags');
					
					// check kardane form
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'content/view/tags/new.json') , 'COM_CONTENT_TAGS_' , 'field_input_');
					$result += $checks->check();

					if(!$result)
					{
						$tag = $model->get_with_name($_POST['field_input_name']);
						if ($tag) 
						{
							Messages::add_message('error' , sprintf(Language::_('COM_CONTENT_ERROR_SAME_TAGS') , $_POST['field_input_name']));
							$result = true;
						}
					}

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_TAGS')));
						return Site::$base . _ADM . 'index.php?component=content&view=tags&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_TAGS')));
						return Site::$base . _ADM . 'index.php?component=content&view=tags&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_TAGS')));
						return Site::$base . _ADM . 'index.php?component=content&view=tags';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=tags';
				else
				{
					$model = self::model('tags');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'tags'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'content/view/tags/edit.json') , 'COM_CONTENT_TAGS_' , 'field_input_');
						$result += $checks->check();

						if(!$result)
						{
							$tag = $model->get_with_name($_POST['field_input_name']);
							if ($tag && $tag[0]->id != $_GET['id']) 
							{
								Messages::add_message('error' , sprintf(Language::_('COM_CONTENT_ERROR_SAME_TAGS') , $_POST['field_input_name']));
								return false;
							}
						}

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_tags();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_TAGS')));
							return Site::$base . _ADM . 'index.php?component=content&view=tags&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_tags();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_TAGS')));
							return Site::$base . _ADM . 'index.php?component=content&view=tags';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=content&view=tags';
				}
			}
		}
		else if($view == 'comments')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('comments');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=content&view=comments&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'comments'))
					return Site::$base . _ADM . 'index.php?component=content&view=comments&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'comments'))
				{
					$model->update_article($_POST['form-values'] , 0 , -1 , 0);
					$model->set_block($_POST['form-values'] , 'comments');
				}
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'comments'))
				{
					$model->update_article($_POST['form-values'] , 0 , 1 , 0);
					$model->set_unblock($_POST['form-values'] , 'comments');
				}
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'comments'))
				{
					$model->update_article($_POST['form-values'] , -1 , 0 , 1);
					$model->delete_items($_POST['form-values'] , 'comments');
				}
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=comments';
				else
				{
					$model = self::model('comments');

					if(isset($_POST['field_input_article_select']))
					{
						if($article = $model->get_with_id($_POST['field_input_category_select'] , "article"))
							return Site::$base . _ADM . 'index.php?component=store&view=comments&action=new&article=' . $article[0]->id;
						else
							Messages::add_message('error' , Language::_('COM_CONTENT_COMMENTS'));
					}
					else if(isset($_GET['category']) && $model->has_category($_GET['category']))
					{
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'content/view/comments/new.json') , 'COM_CONTENT_COMMENTS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$id = $model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_COMMENTS')));
							return Site::$base . _ADM . 'index.php?component=content&view=comments&action=edit&id=' . $id;
						}
						else if(!$result && $_POST['form-button'] == 'savenew')
						{
							$model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_COMMENTS')));
							return Site::$base . _ADM . 'index.php?component=content&view=comments&action=new';
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_CONTENT_COMMENTS')));
							return Site::$base . _ADM . 'index.php?component=content&view=comments';
						}
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=content&view=comments';
				else
				{
					$model = self::model('comments');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'comments'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'content/view/comments/edit.json') , 'COM_CONTENT_COMMENTS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_comments();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_COMMENTS')));
							return Site::$base . _ADM . 'index.php?component=content&view=comments&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_comments();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_CONTENT_COMMENTS')));
							return Site::$base . _ADM . 'index.php?component=content&view=comments';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=content&view=comments';
				}
			}
		}

		return false;
	}
}