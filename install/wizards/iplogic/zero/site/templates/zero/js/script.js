// get full window width including scroll line (like in css)
fullWindowWidth = function() {
	$('body').css('overflow', 'hidden');
	let WindowWidth = $(window).width();
	$('body').css('overflow', 'auto');
	return WindowWidth;
}

// validate integer
isInt = function(field) {
	if (+field != field || field.toString().indexOf(".") != -1) {
		return false;
	} else {
		return true;
	}
}

$(function(){

	$(window).resize(function () {
		/*//use actual height plugin
		$('').equalHeights();*/
	});

	$(document).ready(function() {

		/* POPUPS */

		$('#underlayer').on('click',function(){
			var el = $('#underlayer').attr("data-el");
			$(el).toggle(500);
			$('#underlayer').toggle(100);
			$('#underlayer').removeAttr("data-el");
		});

		$('.modal-trigger').on('click',function(){
			var cl = "."+$(this).attr("data-modal");
			$('#underlayer').attr("data-el",cl);
			$('#underlayer').toggle(100);
			$(cl).fadeIn(500);
		});

		$(document).on('click','.modal .close', function(){
			$('#underlayer').click();
		});

		/* ACTIONS */

		$(window).resize();

	});
});