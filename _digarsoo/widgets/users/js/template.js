jQuery(document).ready(function(){
	jQuery('.users-icon').click(function(){
		jQuery('.users-icon').toggleClass('showing');
		jQuery('.users-list').toggleClass('showing');
	});
});