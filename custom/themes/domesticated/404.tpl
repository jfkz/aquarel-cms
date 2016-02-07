<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Domesticated
Description: A two-column, fixed-width design for 1024x768 screen resolutions.
Version    : 1.0
Released   : 20100701

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$page.meta_title}</title>
<meta name="keywords" content="" />
<meta name="description" content="" />

<link href="{$site.theme_path}/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="{$site.theme_path}/slidertron.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
@import "slidertron.css";
</style>

{$page.js}
<script type="text/javascript" src="{$site.theme_path}/javascript/jquery.slidertron-0.1.js"></script>
<script type="text/javascript" src="{$site.theme_path}/javascript/script.js"></script>

</head>
<body>
<!-- end #header-wrapper -->
<div id="header">
	<div id="logo">
		<h1><a href="/">{$site.name}</a></h1>
		<p><em><a>{$site.slogan}</a></em></p>
	</div>
	<div id="menu">
		{load_module module=site_menu plugin=level_1 page=$page}
	</div>
	<!-- end #menu -->
</div>
<!-- end #header -->
<hr />
<div id="wrapper">
	<!-- end #logo -->
	<div id="two-columns">
		<div class="col1">
			<div id="foobar">
				<div class="navigation"> <a href="#" class="first">[ &lt;&lt; ]</a> &nbsp; <a href="#" class="previous">[ &lt; ]</a> &nbsp; <a href="#" class="next">[ &gt; ]</a> &nbsp; <a href="#" class="last">[ &gt;&gt; ]</a> </div>
				<div class="viewer">
					<div class="reel">
						<div class="slide"> <img src="{$site.theme_path}/images/1.jpg" alt=""> <span>This is the first slide.</span> </div>
						<div class="slide"> <img src="{$site.theme_path}/images/2.jpg" alt=""> <span>This is the second slide.</span> </div>
						<div class="slide"> <img src="{$site.theme_path}/images/3.jpg" alt=""> <span>This is the third slide.</span> </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col2">
			<blockquote>&#8220;&nbsp;{$site.slogan}&nbsp;&#8221;</blockquote>
		</div>
	</div>
</div>
<div id="page">
	<div id="page-bgtop">
		<div id="content">
			<div class="post">
				<p class="meta"><em>about this</em></p>
				<h2 class="title"><a href="#">{$page.title}</a></h2>
				<div class="entry">
					{$page.text}
				</div>
			</div>
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
			</ul>
		</div>
		<!-- end #sidebar -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer">
	<p>Copyright (c) 2010 {$site.name}. All rights reserved. Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
<!-- end #footer -->
</body>
</html>
