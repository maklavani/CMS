<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/24/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

Templates::add_css(Site::$base . 'components/profile/css/user.css');
Templates::add_js(Site::$base . 'components/profile/js/user.js');

$db = new Database();
$pd = json_decode(htmlspecialchars_decode($params[0]->profile));

// Image
$image = json_decode(htmlspecialchars_decode($params[0]->image));
if(!empty($image[0]))
	$image_out = $image[0];
else
	$image_out = "components/profile/images/user.png";
?>
<div class="user xa">
	<div class="user-form-header xa">
		<h2 class="user-title xa s77 m8 l85"><?php echo Language::_('COM_PROFILE'); ?></h2>
	</div>

	<div class="user-in xa">
		<div class="user-form-list xa s25 m3 l2">
			<div class="progress xa"><div class="progress-in xa"></div></div>

			<div class="user-form-list-box xa">
				<div class="user-image xa">
					<div class="image xa" style="background-image: url(<?php echo Site::$base . $image_out; ?>)"></div>

					<form class="xa files" enctype="multipart/form-data" action="<?php echo Site::$base . 'index.php?component=profile&ajax=profile'; ?>" save="<?php echo Language::_('SAVE'); ?>" cancel="<?php echo Language::_('CANCEL'); ?>" window="<?php echo Site::$full_link; ?>">
						<input class="file-input xa" name="file" type="file">
						<div class="xa"><?php echo Language::_('COM_PROFILE_EDIT'); ?></div>
					</form>
				</div>
			</div>
		</div>

		<div class="user-profile xa s75 m7 l8">
			<form id="profile-form" action="<?php echo Site::$full_link; ?>" class="xa" method="post">
				<button class="user-btn x5 s32 m32 l2 ex5 es0 margin-side user-btn-success">
					<div class="user-btn-in xa">
						<span class="after-float icon-save xa"></span>
						<p class="xa"><?php echo Language::_("COM_PROFILE_SAVE"); ?></p>
					</div>
				</button>

				<div class="user-form-list xa s5">
					<div class="user-form-list-box xa">
						<h3 class="xa user-select"><?php echo Language::_("COM_PROFILE"); ?></h3>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_name" pattern=".{1,255}" required placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_NAME"); ?>" value="<?php echo $params[0]->name; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_NAME"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_family" pattern=".{1,255}" required placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_FAMILY"); ?>" value="<?php echo $params[0]->family; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_FAMILY"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_username" pattern="[a-z0-9_\.]{6,100}" required placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_USERNAME"); ?>" value="<?php echo $params[0]->username; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_USERNAME"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="password" name="field_input_password" pattern=".{6,32}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_PASSWORD"); ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_PASSWORD"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="password" name="field_input_repassword" pattern=".{6,32}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_REPASSWORD"); ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_REPASSWORD"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_mobile" pattern="09[0-9]{9}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_MOBILE"); ?>" value="<?php echo $params[0]->mobile; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_MOBILE"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_tel" pattern="[0-9]{4,15}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_TEL"); ?>" value="<?php echo isset($pd->tel) ? $pd->tel : ''; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_TEL"); ?></label>
						</div>

						<div class="user-input xa">
							<textarea class="after-float x6 s5 m6 l7" name="field_input_address" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_ADDRESS"); ?>"><?php echo isset($pd->address) ? $pd->address : ''; ?></textarea>
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_ADDRESS"); ?></label>
						</div>

						<div class="user-input xa">
							<textarea class="after-float x6 s5 m6 l7" name="field_input_favorites" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_FAVORITES"); ?>"><?php echo isset($pd->favorites) ? $pd->favorites : ''; ?></textarea>
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_FAVORITES"); ?></label>
						</div>
					</div>
				</div>

<?php if(in_array(User::$group , array(1 , 2 , 3 , 4))): ?>
				<div class="user-form-list xa s5">
					<div class="user-form-list-box xa">
						<h3 class="xa user-select"><?php echo Language::_("COM_PROFILE_LEGAL"); ?></h3>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_legal_name" pattern=".{1,255}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_NAME"); ?>" value="<?php echo isset($pd->legal_name) ? $pd->legal_name : ''; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_NAME"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_legal_manager" pattern=".{1,255}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_MANAGER"); ?>" value="<?php echo isset($pd->legal_manager) ? $pd->legal_manager : ''; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_MANAGER"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_legal_register_number" pattern=".{1,255}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_REGISTER_NUMBER"); ?>" value="<?php echo isset($pd->legal_register_number) ? $pd->legal_register_number : ''; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_REGISTER_NUMBER"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_legal_tel" pattern="[0-9]{4,15}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_TEL"); ?>" value="<?php echo isset($pd->legal_tel) ? $pd->legal_tel : ''; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_TEL"); ?></label>
						</div>

						<div class="user-input xa">
							<input class="after-float x6 s5 m6 l7" type="text" name="field_input_legal_fax" pattern="[0-9]{4,15}" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_FAX"); ?>" value="<?php echo isset($pd->legal_fax) ? $pd->legal_fax : ''; ?>">
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_FAX"); ?></label>
						</div>

						<div class="user-input xa">
							<textarea class="after-float x6 s5 m6 l7" name="field_input_legal_address" placeholder="<?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_ADDRESS"); ?>"><?php echo isset($pd->legal_address) ? $pd->legal_address : ''; ?></textarea>
							<label class="after-float x4 s5 m4 l3"><?php echo Language::_("COM_PROFILE_USER_FIELD_INPUT_LEGAL_ADDRESS"); ?></label>
						</div>
					</div>
				</div>
<?php endif; ?>

				<input name="form-button" value="save" type="hidden">
				<input name="form-action" value="<?php echo Site::$full_link_text; ?>" type="hidden">
			</form>
		</div>
	</div>

</div>