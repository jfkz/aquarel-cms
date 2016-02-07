<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Memoranda  
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20100801

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
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
				<div id="content">
					<div class="post">
						<h2 class="title"><a href="#">{$page.title}</a></h2>
						<p class="meta"><span class="date">date</span><span class="posted">about</span></p>
						<div style="clear: both;">&nbsp;</div>
						<div class="entry">
							{$page.text}
						</div>
					</div>
					<div style="clear: both;">&nbsp;</div>
				</div><!-- end #content -->
				
				<div id="sidebar">
					<div id="logo">
						<h1><a href="/">{$site.name}</a></h1>
						<p>{$site.slogan}</p>
					</div>
					<div id="menu">
						{load_module module=site_menu plugin=level_1 page=$page}
					</div>
					<ul>
						<!--li>
							<div id="search" >
								<form method="get" action="#">
									<div>
										<input type="text" name="s" id="search-text" value="" />
										<input type="submit" id="search-submit" value="GO" />
									</div>
								</form>
							</div>
							<div style="clear: both;">&nbsp;</div>
						</li-->
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
			</div>
		</div>
	</div><!-- end #page -->
</div>
<div id="footer">
	<p>Copyright (c) 2010 {$site.name}. All rights reserved. Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
<!-- end #footer -->
</body>
</html>
