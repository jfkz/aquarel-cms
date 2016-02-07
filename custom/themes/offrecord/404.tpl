<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : OffRecord 
Description: A two-column, fixed-width design for 1024x768 screen resolutions.
Version    : 1.0
Released   : 20100705
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$page.meta_title}</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="{$site.theme_path}/style.css" rel="stylesheet" type="text/css" media="screen" />
{$page.js}
</head>
<body>
<!-- end #header-wrapper -->
<div id="header">
	<div id="logo">
		<h1><a href="/">{$site.name}</a></h1>
		<p><em>{$site.slogan}</em></p>
	</div>
	<div id="menu">
		{load_module module=site_menu plugin=level_1 page=$page}
	</div><!-- end #menu -->
</div>
<!-- end #header -->
<hr />
<div id="page">
	<div id="page-bgtop">
		<div id="content">
			<div class="post">
				<p class="meta"><em><span class="date">about</span></em></p>
				<h2 class="title"><a href="#">{$page.title}</a></h2>
				<div class="entry">
					{$page.text}
				</div>
			</div>
		</div><!-- end #content -->
		
		<div id="sidebar">
			<ul>
				<li>
					{load_module module=site_menu plugin=level_2 page=$page}
				</li>
				<li>
					<h2>Текстовый блок</h2>
					<p>Текст внутри блока.</p>
				</li>
			</ul>
		</div><!-- end #sidebar -->
		
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #page -->
</div>
<div id="footer">
	<p>Copyright (c) 2010 {$site.name}. All rights reserved. Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
<!-- end #footer -->
</body>
</html>