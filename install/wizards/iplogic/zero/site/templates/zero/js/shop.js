$(function(){

	// cart information refresh
	function refreshCart() {
		$.post(globalJS.siteTemplateDir + '/ajax/cart.php','upcart=1',function(data_){
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

	/*$(window).resize(function () {

	});*/

	$(document).ready(function() {

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
			$.post(globalJS.siteTemplateDir + '/ajax/cart.php','add2cart=1&id='+id+'&qty='+qty,function(data_){  //   console.log(data_);
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


		/* ACTIONS */

		refreshCart();

	});
});