<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/21/2015
	*	last edit		11/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SEO {
	private static $instance;

	// sakhtane instance baraye estefade az tavabe static
	public static function get_instance() 
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		return self::$instance;
	}

	// khuruji dadane seo
	public static function output()
	{
		$output = "";

		if(Configuration::$seo && !in_array(Components::$name , array('profile' , 'users')))
		{
			if(Configuration::$analytics != "")
				$output .= "<script>
			function gaTracker(id){
				jQuery.getScript('" . Site::$base . "media/js/library/analytics.js');
				window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
				ga('create' , id , 'auto');
				ga('send', 'pageview');
			}

			gaTracker('" . Configuration::$analytics . "');
			ga('send' , 'pageview');

			function gaTrack(path , title) {
				ga('set', {page: path , title: title});
				ga('send' , 'pageview');
			}

			gaTrack(window.location.pathname , document.title);
		</script>\n";

			if(Configuration::$tag_manager != "")
			{
				$output .= "\n\t\t<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=" . Configuration::$tag_manager . "\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>";
				$output .= "\n\t\t<script>
			(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','" . Configuration::$tag_manager  . "');
\t\t</script>";
			}
		}

		return $output;
	}
}

$seo = SEO::get_instance();