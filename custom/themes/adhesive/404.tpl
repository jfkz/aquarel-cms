<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Adhesive
Description: A three-column, fixed-width blog design.
Version    : 1.0
Released   : 20100406

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$page.meta_title}</title>
<meta name="keywords" content="" />
<meta name="Adhesive" content="" />
<link href="{$site.theme_path}/style.css" rel="stylesheet" type="text/css" media="screen" />
{$page.js}
</head>
<body>
<div id="wrapper">
	<!-- start header -->
	<div id="header">
		<div id="logo">
			<h1><a href="/">{$site.name}</a></h1>
			<p>{$site.slogan}</p>
		</div>
		<div id="menu">
			{load_module module=site_menu plugin=level_1 page=$page}
		</div>
	</div>
	<!-- end header -->
	<!-- start page -->
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
				<div id="sidebar1" class="sidebar">
					<ul>
						<li>
							{load_module module=site_menu plugin=level_2 page=$page}
						</li>
						<li>
							<h2>Текстовый блок</h2>
							<p>Текст внутри блока.</p>
						</li>
					</ul>
				</div>
				<!-- start content -->
				<div id="content">
					<div class="post">
						<h1 class="title"><a href="#">{$page.title}</a></h1>
						<p class="byline"><em>about this</em></p>
						<div class="entry">
							{$page.text}
							<p class="links"><a href="#" class="more">Read More</a></p>
						</div>
					</div>
				</div>
				<!-- end content -->
				<!-- start sidebars -->
				<div id="sidebar2" class="sidebar">
					<ul>
						<li>
							<h2>Текстовый блок</h2>
							<p>Текст внутри блока.</p>
						</li>
					</ul>
				</div>
				<!-- end sidebars -->
				<div style="clear: both;">&nbsp;</div>
			</div>
			<!-- end page -->
		</div>
	</div>
</div>
<div id="footer-wrapper">
	<div id="footer">
		<p>Copyright (c) 2010 {$site.name}. All rights reserved. Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
	</div>
</div>
</body>
</html>
