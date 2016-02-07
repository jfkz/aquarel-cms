<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : EarthlingTwo  
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20090918
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$page.meta_title}</title>
<link href="{$site.theme_path}/style.css" rel="stylesheet" type="text/css" media="screen" />
{$page.js}
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="logo">
			<h1><a href="/">{$site.name}</a></h1>
			<p>{$site.slogan}</p>
		</div>
	</div>
	<!-- end #header -->
	<div id="menu">
		{load_module module=site_menu plugin=level_1 page=$page}
	</div>
	<!-- end #menu -->
	<div id="page">
		<div id="content">
			<div id="banner"><img src="{$site.theme_path}/images/img07.jpg" alt="" /></div>
			<div class="post">
				<h2 class="title"><a href="#">{$page.title}</a></h2>
				<p class="meta">about</p>
				<div class="entry">
					{$page.text}
				</div>
				<p class="links"><a href="#" class="more">Read More</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="comments">Comments (33)</a></p>
			</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<ul>
				<li>
					{load_module module=site_menu plugin=level_2 page=$page}
				</li>
				<li>
					<h2>Текстовый блок</h2>
					<p>Текст внутри блока.</p>
				</li>
				<li>
					<h2>Categories</h2>
					<ul>
						<li><span>09.13.09</span><a href="#">Vestibulum risus vitae</a></li>

						<li><span>09.13.09</span><a href="#">Condimentum et molestie</a></li>
						<li><span>09.13.09</span><a href="#">Facilisis sed vestibulum</a></li>
						<li><span>09.13.09</span><a href="#">Ipsum primis et sed luctus </a></li>
						<li><span>09.13.09</span><a href="#">Ultrices posuere nulla </a></li>
						<li><span>09.13.09</span><a href="#">Accumsan lorem sodales </a></li>

						<li><span>09.13.09</span><a href="#">Scelerisque consectetur </a></li>
						<li><span>09.13.09</span><a href="#">Maecenas quam aliquet</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<!-- end #sidebar -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer-content">
	<div class="column1">
		<h2>Текстовый блок</h2>
		<p>Текст внутри блока.</p>
	</div>
	<div class="column2">
			{load_module module=site_menu plugin=level_1 page=$page}
	</div>
</div>
<div id="footer">
	<p>Copyright (c) 2010 {$site.name}. All rights reserved. Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
<!-- end #footer -->
</body>
</html>