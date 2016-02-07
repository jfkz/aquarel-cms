<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License
Name        : Distillate  
Description : A two-column, fixed-width design with dark color scheme.
Version     : 1.0
Released    : 20100427
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{$page.meta_title|default:""}</title>
    
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    
    <link type="text/css" href="{$site.theme_path|default:""}/style.css" rel="stylesheet" media="screen" />
    
    {if !empty($js)}
    {foreach from=$js item=script}
    <script type="text/javascript" src="{$script}"></script>
    {/foreach}
    {/if}
    
    <script type="text/javascript" src="{$site.theme_path|default:""}/javascript/jquery.gallerax-0.2.js"></script>
    <script type="text/javascript" src="{$site.theme_path|default:""}/javascript/script.js"></script>
</head>
<body>
    <div id="header-wrapper">
    	<div id="header">
	    <div id="logo">
	    	<h1><a>{$site.name|default:""}</a></h1>
		
	    	<p>{$site.slogan|default:""}</p>
	    </div>
    	</div>
    </div>
    <div id="page">
    	<div id="page-bgtop">
	    <div id="page-bgbtm">
	    	<div id="content">
		    <div>
		    	<div id="gallery">
			    <div id="gallery-background"><img src="{$site.theme_path}/images/img06.png" alt="" width="600" height="340" class="output" /></div>
			    <div id="gallery-bgthumb">
			    	<ul class="thumbnails">
				    <li><img src="{$site.theme_path}/images/img06.png" title="LA Skyline ; Upwards shot of a small portion of Los Angeles' skyline." alt="" width="128" height="88" /></li>
				    <li><img src="{$site.theme_path}/images/6.jpg" title="Leaf ; A close up shot of a green leaf." alt="" width="100" height="75" /></li>
				    <li><img src="{$site.theme_path}/images/7.jpg" title="Chinese Bell ; A large bell inscribed with Chinese characters." alt="" width="100" height="75" /></li>
				    <li><img src="{$site.theme_path}/images/8.jpg" title="Ladybird ; A close up shot of a ladybird making its way across a leaf." alt="" width="100" height="75" /></li>
			    	</ul>
			    	<br class="clear" />
			    </div>
		    	</div><!-- end #gallery-->
		    </div>
		    <div class="post">
		    	<h2 class="title">{$page.title|default:""}</h2>
		    	<p class="meta">about this</p>
		    	<div style="clear: both;">&nbsp;</div>
		    	<div class="entry">
			    {$page.text|default:""}
			    
			    <p class="links"><a href="#">Read More</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Comments</a></p>
		    	</div>
		    </div>
		    <div style="clear: both;">&nbsp;</div>
	    	</div><!-- end #content -->
	    	
	    	{if isset($blocks.left)}
	    	<div id="sidebar" class="left-block">
		    <ul>
		    {foreach from=$blocks.left key=block_name item=block}
		    <li>
		    	{$block}
		    </li>
		    {/foreach}
		    </ul>
	    	</div><!-- end #sidebar -->
		{/if}
	    	
	    	<div style="clear: both;">&nbsp;</div>
	    </div>
    	</div>
    </div><!-- end #page -->
    
    <div id="footer">
    	<p>
	    Copyright (c) 2010 {$site.name|default:""}. All rights reserved.
	    Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.
    	</p>
    </div><!-- end #footer -->
</body>
</html>