<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/27/2015
	*	last edit		07/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CLassicMail {
	public function get($subject , $message)
	{
		$output = "
		<!DOCTYPE html>
			<html>
				<head>
					<meta content=\"text/html;charset=utf-8\" http-equiv=\"Content-Type\" />
					<meta content=\"utf-8\" http-equiv=\"encoding\" />
					<meta content=\"width = device-width , initial-scale = 1.0\" name=\"viewport\" />
					<title>Classic</title>
					<style type=\"text/css\">
						#wrapper
						{
							display: inline-block;
							width: 100%;
							margin: 0px;
							font-size: 15px;
							line-height: 30px;
						" . (Language::$direction == "rtl" ? "direction: rtl; text-align: right; " : "direction: ltr; text-align: left; ") .
						"}

						#title
						{
							margin: 0px;
							padding: 0px 10px;
							line-height: 70px;
							color: #CFD8DC;
							background-color: #455A64;
							text-align: center;
						}

						#content
						{
							position: relative;
							margin: 0px;
							padding: 10px;
							font-size: 14px;
							line-height: 30px;
							color: #888;
							background-color: #eee;
						}

						#content a
						{
							display: inline-block;
							color: #0288D1;
							text-decoration: none;
						}

						#content a:hover { color: #03A9F4; }

						hr
						{
							display: block;
							height: 1px;
			    			margin: 0.2em 0;
			    			padding: 0;
			    			border: 0;
			    			border-top: 1px solid #d2d2d2;
						}

						#footer
						{
							margin: 0px;
							font-size: 12px;
							padding: 0px 10px;
							color: #eee;
							background-color: #607D8B;
						}

						#footer a
						{
							display: inline-block;
							color: #ddd;
							text-decoration: none;
						}

						#footer a:hover { color: #fff; }
					</style>
				</head>
				<body>
					<div id=\"wrapper\">
						<h2 id=\"title\">" . $subject . "</h2>
						<h3 id=\"content\">" . $message . "</h3>
						<h4 id=\"footer\"><a href=\"http://www." . _COPS . "\">" . Language::_('SITE_COPYRIGHT_DESC') . "</a></h4>
					</div>
				</body>
			</html>
		";
		return $output;
	}
}