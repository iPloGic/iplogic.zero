$(function(){

	// animation of element hide
	function showEl(ob){
		$(ob).slideDown(300);
	}

	// animation of element hide
	function hideEl(ob){
		$(ob).slideUp(300);
	}

	// show or hide menu on click
	function onClickOpenHide(ob) {
		if (ob.css('display')!='none') {
			hideEl(ob);
			return false;
		}
		else { showEl(ob); }
		return true;
	}

	// hide menu tree
	function hideTree(ob){
		ob.find('ul').each(function(){
			hideEl($(this));
		});
	}

	// set actual direction for menu nav icons
	function setMenuNavIcons() {
		var icon;
		$lis = $('.top-menu').find('li'),
			$lis.each(function(){
				icon = $(' > .open-icon > i', this);  console.log(icon);
				icon.removeAttr('class');
				if ($(" > ul", this).css('display')!='none') {
					icon.addClass('fas fa-angle-up');
				}
				else {
					icon.addClass('fas fa-angle-down');
				}
			});
	}

	// open/close mobile menu
	function menuOpenHide() {
		ob = $(".top-menu");
		if (ob.css('display')=='none') {
			ob.css('display', 'block');
			ob.animate({left:0},400);
		}
		else {
			ob.animate({left:-320},400);
			setTimeout(hideMobMenu,400);
		}
	}
	function hideMobMenu() {
		$(".top-menu").css('display', 'none');
	}



	$(document).ready(function() {

		$(window).resize(function () {
			if (fullWindowWidth() < 978) {
				$(".top-menu > ul").removeClass("menu-h").addClass("menu-v clickable");
			}
			else {
				$(".top-menu > ul").removeClass("menu-v clickable").addClass("menu-h");
			}
		});

		// click on toogler
		$(".mobile-menu-toggler").on('click', function() {
			menuOpenHide();
		});

		// click on open/close menu icon
		$(".top-menu .dropdown li .open-icon").on('click', function() {
			var loc = $(this).parent();
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
			setTimeout(setMenuNavIcons,320);
			return false;
		});

		$(window).resize();

	});

});