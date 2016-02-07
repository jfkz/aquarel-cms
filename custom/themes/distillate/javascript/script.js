$(document).ready(function()
    {
    $('#gallery').gallerax({
        outputSelector: 		'.output',			// Output selector
        thumbnailsSelector:		'.thumbnails li img',		// Thumbnails selector
        fade: 			'fast',				// Transition speed (fast)
        navNextSelector:		'.nav li a.navNext',		// 'Next' selector
        navPreviousSelector:	'.nav li a.navPrevious'		// 'Previous' selector
    });
    });