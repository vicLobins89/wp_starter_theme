/*
 * Scripts running in the theme
 *
*/

( function() {
	/*
	* Get Viewport Dimensions
	*/
	function updateViewportDimensions() {
		"use strict";
		var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
		return { width:x,height:y };
	}
	// setting the viewport width
	var viewport = updateViewportDimensions();

	/*
	* Set is-mobile class on devices for use in css
	*/
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		document.body.className += ' ' + 'is-mobile';
	}

	/*
	* Add/remove ACF column widths from data-width attributes
	*/
	function adjustColumnWidths(){
		viewport = updateViewportDimensions();
		var columnElement = document.querySelectorAll("[data-width]");

		for( var i = 0; i < columnElement.length; i++ ) {
			var dataWidth = columnElement[i].dataset.width;

			if( viewport.width < 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				columnElement[i].style.width = "";
			} else {
				columnElement[i].style.width = dataWidth;
			}
		}
	}
	adjustColumnWidths();

	window.onresize = function(){
		adjustColumnWidths();
	};
} )();