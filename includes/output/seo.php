<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	12/21/2015
	*	last edit		01/10/2016
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
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','" . Site::$base . "media/js/library/analytics.js','ga');
			ga('create', '" . Configuration::$analytics . "', 'auto');
			ga('send', 'pageview');
\t\t</script>";

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