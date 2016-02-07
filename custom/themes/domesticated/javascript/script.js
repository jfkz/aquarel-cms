$(document).ready(function()
    {
    $('#foobar').slidertron({
	viewerSelector:		'.viewer',
	reelSelector:		'.viewer .reel',
	slidesSelector:		'.viewer .reel .slide',
	navPreviousSelector:	'.previous',
	navNextSelector:	'.next',
	navFirstSelector:	'.first',
	navLastSelector:	'.last'
        });
    });