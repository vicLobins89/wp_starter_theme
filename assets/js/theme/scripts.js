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
} )();