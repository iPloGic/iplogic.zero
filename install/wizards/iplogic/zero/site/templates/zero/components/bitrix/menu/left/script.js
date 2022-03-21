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
		$lis = $('.left-menu').find('li'),
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

	$(document).ready(function() {

		// click on open/close menu icon
		$(".left-menu .dropdown li .open-icon").on('click', function() {
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

	});

});