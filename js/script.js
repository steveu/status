/* Author:

*/



$(function() {

	$('ul.answers li').tap(function() {
		$(this).addClass('flip');
	});

	$('div.stage').swipeLeft(function(){
		alert('swipe left');
	});
});