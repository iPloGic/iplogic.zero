(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {
	var windowWidth = $(window).width(); // Store the window width
  	var windowHeight = $(window).height(); // Store the window height
	var flexObjects = [], // Array of all flexMenu objects
		resizeTimeout;
	// When the page is resized, adjust the flexMenus.
	function adjustFlexMenu() {
		if ($(window).width() !== windowWidth || $(window).height() !== windowHeight) {
			windowWidth = $(window).width(); // Store the window width if it changed
			windowHeight = $(window).height(); // Store the window height if it changed
			$(flexObjects).each(function () {
				$(this).flexMenu({
					'undo' : true
				}).flexMenu(this.options);
			});
		}
	}
	function collapseAllExcept($menuToAvoid) {
		var $activeMenus,
			$menusToCollapse;
		$activeMenus = $('li.flexMenu-viewMore.active');
		$menusToCollapse = $activeMenus.not($menuToAvoid);
		$menusToCollapse.removeClass('active').find('> ul').hide();
	}
	$(window).resize(function () {
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(function () {
			adjustFlexMenu();
		}, 50);
	});
	$.fn.flexMenu = function (options) {
		var checkFlexObject,
			s = $.extend({
				'threshold' : 2, // [integer] If there are this many items or fewer in the list, we will not display a "View More" link and will instead let the list break to the next line. This is useful in cases where adding a "view more" link would actually cause more things to break  to the next line.
				'cutoff' : 2, // [integer] If there is space for this many or fewer items outside our "more" popup, just move everything into the more menu. In that case, also use linkTextAll and linkTitleAll instead of linkText and linkTitle. To disable this feature, just set this value to 0.
				'linkText' : 'More', // [string] What text should we display on the "view more" link?
				'linkTitle' : '', // [string] What should the title of the "view more" button be?
				'linkTextAll' : 'Menu', // [string] If we hit the cutoff, what text should we display on the "view more" link?
				'linkTitleAll' : '', // [string] If we hit the cutoff, what should the title of the "view more" button be?
				'showOnHover' : true, // [boolean] Should we we show the menu on hover? If not, we'll require a click. If we're on a touch device - or if Modernizr is not available - we'll ignore this setting and only show the menu on click. The reason for this is that touch devices emulate hover events in unpredictable ways, causing some taps to do nothing.
				'popupAbsolute' : true, // [boolean] Should we absolutely position the popup? Usually this is a good idea. That way, the popup can appear over other content and spill outside a parent that has overflow: hidden set. If you want to do something different from this in CSS, just set this option to false.
				'popupClass' : '', // [string] If this is set, this class will be added to the popup
				'undo' : false, // [boolean] Move the list items back to where they were before, and remove the "View More" link.
				'mobileBreackdown' : 670 // [integer] Max mobile screen width
			}, options);
		this.options = s; // Set options on object
		checkFlexObject = $.inArray(this, flexObjects); // Checks if this object is already in the flexObjects array
		if (checkFlexObject >= 0) {
			flexObjects.splice(checkFlexObject, 1); // Remove this object if found
		} else {
			flexObjects.push(this); // Add this object to the flexObjects array
		}
		return this.each(function () {
			var $this = $(this),
				$items = $this.find('> li'),
				$firstItem = $items.first(),
				$lastItem = $items.last(),
				numItems = $this.find('li').length,
				firstItemTop = Math.floor($firstItem.offset().top),
				firstItemHeight = Math.floor($firstItem.outerHeight(true)),
				$lastChild,
				keepLooking,
				$moreItem,
				$moreLink,
				numToRemove,
				allInPopup = false,
				$menu,
				i;
			function needsMenu($itemOfInterest) {
				var result = (Math.ceil($itemOfInterest.offset().top) >= (firstItemTop + firstItemHeight)) ? true : false;
				$('body').css('overflow', 'hidden');
				var widthNoScrollBars = $(window).width();
				$('body').css('overflow', 'auto');
				//console.log(widthNoScrollBars);
				if (widthNoScrollBars < s.mobileBreackdown) { return false; }
				// Values may be calculated from em and give us something other than round numbers. Browsers may round these inconsistently. So, let's round numbers to make it easier to trigger flexMenu.
				return result;
			}
			if (needsMenu($lastItem) && numItems > s.threshold && !s.undo && $this.is(':visible')) {
				var $popup = $('<ul class="flexMenu-popup menu-v dropdown" style="display:none;' + ((s.popupAbsolute) ? ' position: absolute;' : '') + '"></ul>');
				// Add class if popupClass option is set
				$popup.addClass(s.popupClass);
				// Move all list items after the first to this new popup ul
        for (i = numItems; i > 1; i--) {
					// Find all of the list items that have been pushed below the first item. Put those items into the popup menu. Put one additional item into the popup menu to cover situations where the last item is shorter than the "more" text.
					$lastChild = $this.find('> li:last-child');
					keepLooking = (needsMenu($lastChild));
					// If there only a few items left in the navigation bar, move them all to the popup menu.
					if ((i - 1) <= s.cutoff) { // We've removed the ith item, so i - 1 gives us the number of items remaining.
						$($this.children().get().reverse()).appendTo($popup);
						allInPopup = true;
						break;
					}
					if (!keepLooking) {
						break;
					} else {
						$lastChild.appendTo($popup);
					}
				}
				if (allInPopup) {
					$this.append('<li class="flexMenu-viewMore flexMenu-allInPopup"><a href="#" title="' + s.linkTitleAll + '">' + s.linkTextAll + '</a></li>');
				} else {
					$this.append('<li class="flexMenu-viewMore"><a href="#" title="' + s.linkTitle + '">' + s.linkText + '</a></li>');
				}
				$moreItem = $this.find('> li.flexMenu-viewMore');
				/// Check to see whether the more link has been pushed down. This might happen if the link immediately before it is especially wide.
				if (needsMenu($moreItem)) {
					$this.find('> li:nth-last-child(2)').appendTo($popup);
				}
				// Our popup menu is currently in reverse order. Let's fix that.
				$popup.children().each(function (i, li) {
					$popup.prepend(li);
				});
				$moreItem.append($popup);
				$moreLink = $this.find('> li.flexMenu-viewMore > a');
				$moreLink.click(function (e) {
					// Collapsing any other open flexMenu
					collapseAllExcept($moreItem);
					//Open and Set active the one being interacted with.
					$popup.toggle();
					$moreItem.toggleClass('active');
					e.preventDefault();
				});
				if (s.showOnHover && (typeof Modernizr !== 'undefined') && !Modernizr.touch) { // If requireClick is false AND touch is unsupported, then show the menu on hover. If Modernizr is not available, assume that touch is unsupported. Through the magic of lazy evaluation, we can check for Modernizr and start using it in the same if statement. Reversing the order of these variables would produce an error.
					$moreItem.hover(
						function () {
							$popup.show();
							$(this).addClass('active');
						},
						function () {
							$popup.hide();
							$(this).removeClass('active');
						});
				}
			} else if (s.undo && $this.find('ul.flexMenu-popup')) {
				$menu = $this.find('ul.flexMenu-popup');
				numToRemove = $menu.find('li').length;
				for (i = 1; i <= numToRemove; i++) {
					$menu.find('> li:first-child').appendTo($this);
				}
				$menu.remove();
				$this.find('> li.flexMenu-viewMore').remove();
			}
		});
	};
}));


/* Modernizr 2.5.3 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-touch-cssclasses-teststyles-prefixes
 */
;window.Modernizr=function(a,b,c){function w(a){j.cssText=a}function x(a,b){return w(m.join(a+";")+(b||""))}function y(a,b){return typeof a===b}function z(a,b){return!!~(""+a).indexOf(b)}function A(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:y(f,"function")?f.bind(d||b):f}return!1}var d="2.5.3",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n={},o={},p={},q=[],r=q.slice,s,t=function(a,c,d,e){var f,i,j,k=b.createElement("div"),l=b.body,m=l?l:b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),k.appendChild(j);return f=["&#173;","<style>",a,"</style>"].join(""),k.id=h,(l?k:m).innerHTML+=f,m.appendChild(k),l||(m.style.background="",g.appendChild(m)),i=c(k,a),l?k.parentNode.removeChild(k):m.parentNode.removeChild(m),!!i},u={}.hasOwnProperty,v;!y(u,"undefined")&&!y(u.call,"undefined")?v=function(a,b){return u.call(a,b)}:v=function(a,b){return b in a&&y(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=r.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(r.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(r.call(arguments)))};return e});var B=function(c,d){var f=c.join(""),g=d.length;t(f,function(c,d){var f=b.styleSheets[b.styleSheets.length-1],h=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"",i=c.childNodes,j={};while(g--)j[i[g].id]=i[g];e.touch="ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch||(j.touch&&j.touch.offsetTop)===9},g,d)}([,["@media (",m.join("touch-enabled),("),h,")","{#touch{top:9px;position:absolute}}"].join("")],[,"touch"]);n.touch=function(){return e.touch};for(var C in n)v(n,C)&&(s=C.toLowerCase(),e[s]=n[C](),q.push((e[s]?"":"no-")+s));return w(""),i=k=null,e._version=d,e._prefixes=m,e.testStyles=t,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+q.join(" "):""),e}(this,this.document);
