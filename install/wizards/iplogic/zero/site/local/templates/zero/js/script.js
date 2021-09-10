$(function(){

	/*// validate integer
	isInt = function(field) {
		if (+field != field || field.toString().indexOf(".") != -1) {
			return false;
		} else {
			return true;
		}
	}*/

	/* MENU */

	/*// animation of element hide
	function showEl(ob){
		$(ob).slideDown(300);
	}*/

	/*// animation of element hide
	function hideEl(ob){
		$(ob).slideUp(300);
	}*/

	/*// show or hide menu on click
	function onClickOpenHide(ob) {
		if (ob.css('display')!='none') { 
			hideEl(ob);
			return false; 
		}
		else { showEl(ob); }
		return true;
	}*/

	/*// hide menu tree
	function hideTree(ob){
		ob.find('ul').each(function(){
			hideEl($(this));
		});
	}*/

	/*// set actual direction for menu nav icons
	function setMenuNavIcons() {
		var icon;
		$lis = $('.catalog_nav').find('li'),
		$lis.each(function(){
			icon = $(' > a > i.open-icon', this);
			icon.removeAttr('class');
			icon.addClass('open-icon');
			if ($(" > ul", this).css('display')!='none') {
				icon.addClass('fa fa-caret-down');
			}
			else {
				icon.addClass('fa fa-caret-right');
			}
		});
	}*/

	/* /MENU */

	// cart information refresh
	function refreshCart() {
		$.post('/bitrix/ajax/cart.php','upcart=1',function(data_){
			var data=$.parseJSON(data_);
			$('#cart .count').html(data.GOODS);
			$('#cart .summ').html(data.SUMM);
		});
	}

	/*// check quantity input
	function checkQuantity(val,max) {
		if ( !isInt(val) ) return 1;
		if (val<1) return 1;
		if (val>max) return max;
		return val;
	}*/

	$(window).resize(function () {
		/*//use actual height plugin
		$('').equalHeights();*/
	});

	$(document).ready(function() {

		/*// click on toogler
		$("#toggler").on('click', function() {
			 onClickOpenHide($(""));
		});*/

		/*// click on open/close menu icon
		$(".dropdown li a i.open-icon").on('click', function() {
			var loc = $(this).parent().parent();
			var sub = loc.children('ul');
			var lis = loc.siblings();
			lis.each(function(){
				if ($(" > ul", this).length) {
					hideTree($(this));
				}
			});
			var open = true;
			if (sub.css('display')!='none') { open = false; }
			if (open) {
				showEl(sub);
			}
			else {
				hideTree(loc);
			}
			setTimeout(setMenuNavIcons,305);
			return false;
		});*/

		// add to basket
		// uncomment and change lines by situation
		$('.add-to-basket').on('click',function(e){
			e.preventDefault();
			var id = $(this).attr("data-id"),
				ob = $(this)/*,
				qty_w = $(this).parent().siblings('.quantity'),
				qty = qty_w.children('input').val()*/;
			/*ob.parent().attr('href','/personal/cart/');
			ob.addClass('light');
			ob.removeClass('add-to-basket');
			ob.unbind('click');
			ob.text('В корзине');
			qty_w.children('input').attr("disabled",true); 
			qty_w.children('div').removeAttr('class');*/ 
			$.post('/bitrix/ajax/cart.php','add2cart=1&id='+id+'&qty='+qty,function(data_){  //   console.log(data_);
				var data=$.parseJSON(data_);
				$('#cart .count').html(data.GOODS);
				$('#cart .summ').html(data.SUMM+" &#8381;");
				BX.onCustomEvent('OnBasketChange');
			});
		});

		/*// change quantity
		$(".catalog-list-item .quantity div").on('click', function() {
			var input = $(this).siblings("input"),
				val = input.val(),
				max = input.attr('data-max');
				if ($(this).hasClass('minus'))
					val = val-1;
				if ($(this).hasClass('plus'))
					val = +val+1;
				input.val(checkQuantity(val,max));
		});
		$(".catalog-list-item .quantity input").on('change', function() {
			var val = +$(this).val(),
				max = $(this).attr('data-max');
				$(this).val(checkQuantity(val,max));
		});*/

		/* POPUPS */

		$('#underlayer').on('click',function(){
			var el = $('#underlayer').attr("data-el");
			if (el==".search-form") {
				$('.search-form').toggle(500);
			}
			// add conditions for all popups here
			$('#underlayer').toggle(100);
			$('#underlayer').removeAttr("data-el");
		});

		$('#search').on('click',function(){
			$('#underlayer').attr("data-el",".search-form");
			$('#underlayer').toggle(100);
			$('.search-form').fadeIn(500);
		});

		/* ACTIONS */

		refreshCart();

		$(window).resize();

	});
});