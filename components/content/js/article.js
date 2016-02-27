jQuery(document).ready(function(){
	var type = "" , message = "" , parent = "";

	jQuery('.article-likes.access .icon-like').click(function(){
		type = "like";
		message = "";
		parent = "";
		ajax_send();
	});

	jQuery('.article-likes.access .icon-dislike').click(function(){
		type = "dislike";
		message = "";
		parent = "";
		ajax_send();
	});

	jQuery('.comment-likes.access .icon-like').click(function(){
		type = "likecomment";
		message = jQuery(this).parent().parent().parent().parent().attr("code");
		parent = "";
		ajax_send();
	});

	jQuery('.comment-likes.access .icon-dislike').click(function(){
		type = "dislikecomment";
		message = jQuery(this).parent().parent().parent().parent().attr("code");
		parent = "";
		ajax_send();
	});

	jQuery('.article-comment-form button').click(function(){
		type = "comment";
		message = jQuery('.article-comment-form form').serialize();
		ajax_send();
		return false;
	});

	jQuery('.answer-to-comment').click(function(){
		parent = jQuery(this).parent().parent().attr("code");
		jQuery(".article-comment-form h3").after().append("<div class=\"answer-to-comment-title xa\">" + jQuery(this).text() + ": " + jQuery(this).parent().children("span:first-child").text() + "<span class=\"icon-close\"></span></div>");
		jQuery("body , html").animate({scrollTop: jQuery(".article-comment-form").offset().top} , 500);
	});

	jQuery(document).on("click" , ".answer-to-comment-title span" , function(){
		parent = "";
		jQuery(this).parent().remove();
	});

	function ajax_send(){
		jQuery.ajax({
			url: jQuery(".article").attr("ajax"),
			type: "GET",
			data: {
				type: type,
				message: message,
				parent: parent
			},
			success: function(result){
				console.log(result);
				if(result)
					result = jQuery.parseJSON(result);

				if(typeof result.status != "undefined" && result.status == true){
					if(typeof result.information != "undefined" && typeof result.information.likes != "undefined" && typeof result.information.dislikes != "undefined"){
						if(message == ""){
							jQuery('.article-likes .icon-like').next().text(result.information.likes);
							jQuery('.article-likes .icon-dislike').next().text(result.information.dislikes);
						} else {
							jQuery('[code="' + message + '"] > .comment-toolbar > .comment-likes > p > .icon-like').next().text(result.information.likes);
							jQuery('[code="' + message + '"] > .comment-toolbar > .comment-likes > p > .icon-dislike').next().text(result.information.dislikes);
						}
					}

					else if(typeof result.reload != "undefined" && result.reload == true){
						location.reload();
					}
				}

				if(typeof result.message != "undefined")
					alert(result.message);

				if(type == "comment")
					jQuery(".captcha-button.icon-refresh").click();
			}
		});
	}
});