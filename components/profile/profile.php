<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/24/2015
	*	last edit		05/17/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ProfileController extends Controller {
	public function ajax()
	{
		$section_array = array("profile");

		if(User::$login)
		{
			if(isset($_FILES['file']))
			{
				$file = $_FILES['file'];
				$name = "";
				$code = "";
				$component = "";
				$address = "";
				$section = Sessions::get_session('section');

				if(System::free_space(_COMP) > $file['size'] && Configuration::$file_upload_size >= $file['size'] && in_array($section , $section_array))
				{
					$base = $file['name'];
					$dot_pos = strrpos($base , '.' , -1);
					$type = substr($base , $dot_pos - strlen($base) + 1);
					$file_types = array('gif' , 'jpg' , 'jpeg' , 'png');

					if(in_array($type , $file_types))
					{
						if($section == "profile")
						{
							$model = self::model('user');
							$user = $model->get_with_id(User::$id , 'users');
							$model->create_source($user[0]->code);

							$address = "component/users/" . $user[0]->code . "/";
							$component = 'users';
							$name = 'upload-' . Regex::random_string(10);
							$code = $user[0]->code;
						}

						$url = '/component/' . $component . '/' . $code . '/' . $name . '.' . $type;

						if (move_uploaded_file($file["tmp_name"] , _SRC . 'uploads' . $url))
						{
							Sessions::set_session('upload_profile_image' , $url);
							Sessions::set_session('upload_profile_image_details' , json_encode(array('a' => $address , 'cn' => $component , 'n' => $name , 'c' => $code) , JSON_UNESCAPED_UNICODE));
							echo json_encode(array('url' => Site::$base . 'download' . $url , 'status' => true) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_PROFILE_ERROR_UPLOAD') , $file['name']) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_PROFILE_ERROR_TYPE') , $_GET['uploadper_name'] , implode(' ' , $file_types)) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				}
				else if(isset($_GET['uploadper_name']))
					echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_PROFILE_ERROR_SIZE') , $_GET['uploadper_name'] , System::get_file_size(Configuration::$file_upload_size)) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
			}
			else if(isset($_GET['ajax']) && $_GET['ajax'] == 'profile')
			{
				// Check Kardane Size Va Pasvand Tasvir
				if(	isset($_GET['uploadper_size']) && isset($_GET['uploadper_name']) && Regex::cs($_GET['uploadper_size'] , "numeric") && 
					isset($_GET['section']) && in_array($_GET['section'] , $section_array))
				{
					if(Configuration::$file_upload_size >= $_GET['uploadper_size'])
					{
						$base = $_GET['uploadper_name'];
						$dot_pos = strrpos($base , '.' , -1);
						$type = substr($base , $dot_pos - strlen($base) + 1);

						$file_types = array('gif' , 'jpg' , 'jpeg' , 'png');

						if(in_array($type , $file_types))
						{
							Sessions::set_session('section' , $_GET['section']);
							echo json_encode(array('status' => true) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_PROFILE_ERROR_TYPE') , $_GET['uploadper_name'] , implode(' ' , $file_types)) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . sprintf(Language::_('COM_PROFILE_ERROR_SIZE') , $_GET['uploadper_name'] , System::get_file_size(Configuration::$file_upload_size)) . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
				} else {
					$session = Sessions::get_session('upload_profile_image');
					$session_details = Sessions::get_session('upload_profile_image_details');

					if($session && $session_details)
					{
						$src = $session;

						Sessions::delete_session('upload_profile_image');
						Sessions::delete_session('upload_profile_image_details');

						if(	System::has_file('uploads' . $src) && 
							isset($_GET['width']) && Regex::cs($_GET['width'] , "numeric") && $_GET['width'] > 0 && 
							isset($_GET['height']) && Regex::cs($_GET['height'] , "numeric") && $_GET['height'] > 0 && 
							isset($_GET['crop_width']) && Regex::cs($_GET['crop_width'] , "numeric") && $_GET['crop_width'] > 0 && 
							isset($_GET['crop_height']) && Regex::cs($_GET['crop_height'] , "numeric") && $_GET['crop_height'] > 0 && 
							isset($_GET['top']) && (Regex::cs($_GET['top'] , "numeric") || $_GET['top'] == 0) && $_GET['top'] >= 0 && 
							isset($_GET['left']) && (Regex::cs($_GET['left'] , "numeric") || $_GET['left'] == 0) && $_GET['left'] >= 0){
							
							// Dorost kardan Tasvire Base
							$base = 'uploads' . $src;
							$dot_pos = strrpos($base , '.' , -1);
							$type = substr($base , $dot_pos - strlen($base) + 1);

							// Image
							$image = false;

							if($type == 'jpg' || $type == 'jpeg')
								$image = imagecreatefromjpeg(_SRC . $base);
							else if($type == 'png')
								$image = imagecreatefrompng(_SRC . $base);
							else if($type == 'gif')
								$image = imagecreatefromgif(_SRC . $base);

							if($image){
								$width = imagesx($image);
		  						$height = imagesy($image);

		  						if($_GET['width'] >= $_GET['crop_width'] && $_GET['height'] >= $_GET['crop_height'])
		  						{
		  							// Mohasebeye Size
									$nesbat_wid = $_GET['width'] / $width;
		  							$nesbat_height = $_GET['height'] / $height;

		  							// Sakhtane Tasvire Khali
		  							$crop = imagecreatetruecolor(floor($_GET['crop_width'] / $nesbat_wid) , floor($_GET['crop_height'] / $nesbat_height));
		  							imagecolortransparent($crop , imagecolorallocate($crop , 0 , 0 , 0));
									
									// Copy Tasvire Crop
									imagecopyresampled($crop , $image , 
										0 , 
										0 , 
										floor(1 * $_GET['left'] / $nesbat_wid) , 
										floor(1 * $_GET['top'] / $nesbat_height)  , 
										$width , $height , $width , $height);

		  								if($type == 'jpg' || $type == 'jpeg')
											imagejpeg($crop , _SRC . $base);
										else if($type == 'png')
											imagepng($crop , _SRC . $base);
										else if($type == 'gif')
											imagegif($crop , _SRC . $base);

									// Gereftane Details
									$details = json_decode($session_details);
									$inners_check = array();

									// Sakhtane File Haye Marbute
									if($details->cn == 'users')
									{
										$file = "/component/" . $details->cn . "/" . $details->c . "/profile." .  $type;
										$thumbnail = "/component/" . $details->cn . "/" . $details->c . "/thumbnail_profile." .  $type;
										$inners_check[] = "uploads" . $file;
										$inners_check[] = "uploads" . $thumbnail;

										$model = self::model('user');
										$images = htmlspecialchars(json_encode(array('download' . $file) , JSON_UNESCAPED_UNICODE));
										$model->update_users_image($images);
									}

									// Copy And resize
									if(isset($file) && isset($thumbnail))
									{
										copy(_SRC . $base , _SRC . 'uploads' . $file);
										copy(_SRC . $base , _SRC . 'uploads' . $thumbnail);
										System::resize_image(_SRC . 'uploads' . $file , _SRC . 'uploads' . $thumbnail , $type , 200);
									}

									// file haye ezafi pak mishavasnd
									$inners = scandir(_SRC . 'uploads/' . $details->a);
									if(!empty($inners))
										foreach ($inners as $file)
											if(!in_array($file , array("." , ".." , "index.html" , "." . _COPR)) && !in_array("uploads/" . $details->a . $file , $inners_check))
												unlink(_SRC . "uploads/" . $details->a . $file);

									echo json_encode(array('status' => true , 'message' => "<div class=\"message xa\"><span class=\"icon-success\"></span>" . Language::_('COM_PROFILE_UPLOADED') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
		  						}
		  						else
		  							echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_PROFILE_ERROR_RESIZE_SIZE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);
							}
						}
						else
							echo json_encode(array('status' => false , 'message' => "<div class=\"message xa\"><span class=\"icon-error\"></span>" . Language::_('COM_PROFILE_ERROR_RESIZE') . "</div><div class=\"icon-close\"></div>") , JSON_UNESCAPED_UNICODE);					
					}
				}
			}
		}
	}

	// khandane view
	public function view($view)
	{
		if(User::$login)
		{
			if(User::$authentication)
				Messages::add_message('warning' , Language::_('COM_PROFILE_WARNING_FIRST_IDENTITY'));

			$params = array();

			if(is_array($view) && !empty($view))
			{
				$view_check = str_replace("-" , " " , $view[0]);

				if($view_check == Language::_('COM_PROFILE_USER'))
					Controller::$view = $view = 'user';
			}

			if($view == 'user')
			{
				$model = self::model('user');
				$params = $model->get_with_id(User::$id , 'users');
				View::read(json_encode($params , JSON_UNESCAPED_UNICODE));
			}
			else
				Messages::add_message('error' , Language::_('ERROR_INVLAID_LINK'));
		}
		else
		{
			Messages::add_message('error' , Language::_('COM_PROFILE_ERROR_LOGIN'));
			Site::goto_link(Site::$base . Language::_('COM_PROFILE_USERS') . '/' . str_replace(" " , "-" , Language::_('COM_PROFILE_USERS_SIGNIN')));
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if(is_array($view) && !empty($view))
		{
			$view_check = str_replace("-" , " " , $view[0]);

			if($view_check == Language::_('COM_PROFILE_USER'))
				Controller::$view = $view = 'user';
		}

		if(is_array($view))
			return false;

		if($view == 'user') 
		{
			$model = self::model('user');
			$result = false;

			if(User::$username != $_POST['field_input_username'] && $model->get_with_field('username' , $_POST['field_input_username']))
			{
				Messages::add_message('error' , Language::_('COM_PROFILE_ERROR_EXIST_USERSNAME'));
				return false;
			}

			if($_POST['field_input_password'] != $_POST['field_input_repassword'])
			{
				Messages::add_message('error' , Language::_('COM_PROFILE_ERROR_PASSWORD_DOES_NOT_MATCH'));
				return false;
			}

			require_once _INC . 'output/checks.php';
			$checks = New Checks(file_get_contents(_COMP . 'profile/view/user/default.json') , 'COM_PROFILE_USER_' , 'field_input_');
			$result += $checks->check();

			if(!$result && $_POST['form-button'] == 'save')
			{
				$model->update_users();
				Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_PROFILE_USER')));
				return Site::$base . Language::_('COM_PROFILE') . '/' . Language::_('COM_PROFILE_USER');
			}
		}

		return false;
	}
}