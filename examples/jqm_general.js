CheckTopMenuDotted = function () {
	var menu = $('nav.mega-menu');
	var menuMoreItem = menu.find('td.js-dropdown');
	if (menu.parents('.collapse').css('display') == 'none') {
		return false;
	}

	var block_w = menu.closest('div').actual('width');
	var menu_w = menu.find('table').actual('outerWidth');
	var afterHide = false;

	while (menu_w > block_w) {
		menuItemOldSave = menu.find('td').not('.nosave').last();
		if (menuItemOldSave.length) {
			menuMoreItem.show();
			menuItemNewSave = '<li class="' + (menuItemOldSave.hasClass('dropdown') ? 'dropdown-submenu ' : '') + (menuItemOldSave.hasClass('active') ? 'active ' : '') + '" data-hidewidth="' + menu_w + '">' + menuItemOldSave.find('.wrap').html() + '</li>';
			menuItemOldSave.remove();
			menuMoreItem.find('> .wrap > .dropdown-menu').prepend(menuItemNewSave);
			menu_w = menu.find('table').actual('outerWidth');
			afterHide = true;
		}
		else {
			break;
		}
	}

	if (!afterHide) {
		do {
			var menuItemOldSaveCnt = menuMoreItem.find('.dropdown-menu').find('li').length;
			menuItemOldSave = menuMoreItem.find('.dropdown-menu').find('li').first();
			if (!menuItemOldSave.length) {
				menuMoreItem.hide();
				break;
			}
			else {
				var hideWidth = menuItemOldSave.attr('data-hidewidth');
				if (hideWidth > block_w) {
					break;
				}
				else {
					menuItemNewSave = '<td class="' + (menuItemOldSave.hasClass('dropdown-submenu') ? 'dropdown ' : '') + (menuItemOldSave.hasClass('active') ? 'active ' : '') + '" data-hidewidth="' + block_w + '"><div class="wrap">' + menuItemOldSave.html() + '</div></td>';
					menuItemOldSave.remove();
					$(menuItemNewSave).insertBefore(menu.find('td.js-dropdown'));
					if (!menuItemOldSaveCnt) {
						menuMoreItem.hide();
						break;
					}
				}
			}
			menu_w = menu.find('table').actual('outerWidth');
		}
		while (menu_w <= block_w);
	}

	menu.find('td').css('visibility', 'visible');

	return false;
}

CheckTopVisibleMenu = function (that) {
	var dropdownMenu = $('.dropdown-menu:visible').last();
	if (dropdownMenu.length) {
		var isMenuType2 = arCorporationOptions['THEME']['TOP_MENU'] = 'SECOND';
		dropdownMenu.find('a').css('white-space', '');
		dropdownMenu.css('left', '');
		dropdownMenu.css('right', '');
		dropdownMenu.removeClass('toright');
		if (isMenuType2) {
			dropdownMenu.css('margin-left', '');
		}

		var dropdownMenu_left = dropdownMenu.offset().left;
		if (typeof (dropdownMenu_left) != 'undefined') {
			var menu = dropdownMenu.parents('.mega-menu');
			var menu_width = menu.outerWidth();
			var menu_left = menu.offset().left;
			var menu_right = menu_left + menu_width;
			var isToRight = dropdownMenu.parents('.toright').length > 0;
			var parentsDropdownMenus = dropdownMenu.parents('.dropdown-menu');
			var isHasParentDropdownMenu = parentsDropdownMenus.length > 0;
			if (isHasParentDropdownMenu) {
				var parentDropdownMenu_width = parentsDropdownMenus.first().outerWidth();
				var parentDropdownMenu_left = parentsDropdownMenus.first().offset().left;
				var parentDropdownMenu_right = parentDropdownMenu_width + parentDropdownMenu_left;
			}
			var addleft = 0;

			if (parentDropdownMenu_right + dropdownMenu.outerWidth() > menu_right) {
				dropdownMenu.find('a').css('white-space', 'normal');
			}

			if (isMenuType2 && !isHasParentDropdownMenu) {
				var punktMenu = dropdownMenu.parents('td');
				if ((dropdownMenu.outerWidth() < punktMenu.outerWidth()) || punktMenu.index() > 0) {
					dropdownMenu.css('left', '50%');
					dropdownMenu.css('margin-left', '-' + Math.floor(dropdownMenu.outerWidth() / 2) + 'px');
					dropdownMenu_left = dropdownMenu.offset().left;
				}
			}

			var dropdownMenu_width = dropdownMenu.outerWidth();
			var dropdownMenu_right = dropdownMenu_left + dropdownMenu_width;

			if (dropdownMenu_right > menu_right || isToRight) {
				addleft = menu_right - dropdownMenu_right;
				if (isHasParentDropdownMenu || isToRight) {
					dropdownMenu.css('left', 'auto');
					dropdownMenu.css('right', '100%');
					dropdownMenu.addClass('toright');
				}
				else {
					var dropdownMenu_curLeft = parseInt(dropdownMenu.css('left'));
					dropdownMenu.css('left', (dropdownMenu_curLeft + addleft) + 'px');
				}
			}
		}
	}
}

var CheckPopupTop = function () {
	var popup = $('.jqmWindow.show');
	if (popup.length) {
		var documentScollTop = $(document).scrollTop();
		var windowHeight = $(window).height();
		var popupTop = parseInt(popup.css('top'));
		var popupHeight = popup.height();

		if (windowHeight >= popupHeight) {
			// center
			popupTop = (windowHeight - popupHeight) / 2;
		}
		else {
			if (documentScollTop > documentScrollTopLast) {
				// up
				popupTop -= documentScollTop - documentScrollTopLast;
			}
			else if (documentScollTop < documentScrollTopLast) {
				// down
				popupTop += documentScrollTopLast - documentScollTop;
			}

			if (popupTop + popupHeight < windowHeight) {
				// bottom
				popupTop = windowHeight - popupHeight;
			}
			else if (popupTop > 0) {
				// top
				popupTop = 0;
			}
		}
		popup.css('top', popupTop + 'px');
	}
}

CheckStickyFooter = function () {
	$(window).resize(function () {
		try {
			var footerHeight = $('footer').outerHeight();
			$('footer').css('margin-top', '-' + footerHeight + 'px');
			$('.body').css('margin-bottom', '-' + footerHeight + 'px');
			$('.main[role=main]').css('padding-bottom', footerHeight + 'px');
			$('.wrapper_404').css('padding-bottom', footerHeight / 4 + 'px');
		}
		catch (e) { }
	});
}

function getGridSize(counts) {
	var z = parseInt($('.body_media').css('top'));
	return (z == 2 ? counts[0] : z == 1 ? counts[1] : counts[2]);
}

function CheckBigBanner() {
	var z = parseInt($(".body").css("top"));
	if (z) {
		$('.banners .big .item .image').css('height', '');
		$('.banners .big .item .info').css('height', '');
		$('.banners .big .item').css('height', '');
		$('.banners .big .item').each(function () {
			var bg = $(this).find('.image').data('background');
			if (typeof (bg) == "undefined") {
				bg = { "width": ws, "height": hs };
			}
			//$(this).find('.image').css('background-size', bg.width + 'px ' + bg.height + 'px');
			$(this).find('.image').css('background-size', 'cover');
		});
		$('.banners .big .flex-control-nav').css({ 'top': '', 'bottom': '' });
		$('.banners .big .flex-direction-nav a').css({ 'top': '', 'margin-top': '' });
		$('.banners .first').css('height', '');
	}
	else {
		var firstSlide = $('.banners .big .item').first();
		var firstSlideImage = firstSlide.find('.image');
		var ws = firstSlide.outerWidth();
		var defaultBanerWidth = 728;
		var defaultBanerHeight = 398;
		var hs = Math.round(defaultBanerHeight * ws / defaultBanerWidth);
		$('.banners .big .item .image').height(hs);
		var maxInfoHeight = 0;
		$('.banners .big .item .info').css('height', '');
		$('.banners .big .item').each(function () {
			var bg = $(this).find('.image').data('background');
			if (typeof (bg) == 'undefined') {
				bg = { 'width': ws, 'height': hs };
			}
			var nw = Math.round(ws * bg.width / defaultBanerWidth);
			var nh = Math.round(hs * bg.height / defaultBanerHeight);
			//$(this).find('.image').css('background-size', nw + 'px ' + nh + 'px');
			$(this).find('.image').css('background-size', 'cover');
			var infoHeight = parseInt($(this).find('.info').css('height'));
			if (maxInfoHeight < infoHeight) {
				maxInfoHeight = infoHeight;
			}
		});
		$('.banners .big .item .info').css('height', maxInfoHeight + 'px');
		$('.banners .big .flex-control-nav').css({ 'top': (hs - 25) + 'px', 'bottom': 'inherit' });
		$('.banners .big .flex-direction-nav a').css({ 'top': 0, 'margin-top': (Math.round(hs / 2) - 20) + 'px' });
		$('.banners .first').css('height', 'auto');
		$('.banners .big .item').css('height', 'auto');
	}
}

function CheckFlexSlider() {
	$('.flexslider:not(.thmb)').each(function () {
		var slider = $(this);
		var counts = slider.data('flexslider').vars.counts;
		if (typeof (counts) != 'undefined') {
			var cnt = getGridSize(counts);
			var to0 = (cnt != slider.data('flexslider').vars.minItems || cnt != slider.data('flexslider').vars.maxItems || cnt != slider.data('flexslider').vars.move);
			if (to0) {
				slider.data('flexslider').vars.minItems = cnt;
				slider.data('flexslider').vars.maxItems = cnt;
				slider.data('flexslider').vars.move = cnt;
				slider.flexslider(0);
				slider.resize();
				slider.resize(); // twise!
			}
		}
	});
}

scrollToTop = function () {
	var _isScrolling = false;
	// Append Button
	$('body').append($('<a />').addClass('scroll-to-top').attr({ 'href': '#', 'id': 'scrollToTop' }).append($('<i />').addClass('fa fa-chevron-up fa-white')));
	$('#scrollToTop').click(function (e) {
		e.preventDefault();
		$('body, html').animate({ scrollTop: 0 }, 500);
		return false;
	});
	// Show/Hide Button on Window Scroll event.
	$(window).scroll(function () {
		if (!_isScrolling) {
			_isScrolling = true;
			if ($(window).scrollTop() > 150) {
				$('#scrollToTop').stop(true, true).addClass('visible');
				_isScrolling = false;
			} else {
				$('#scrollToTop').stop(true, true).removeClass('visible');
				_isScrolling = false;
			}
		}
	});
}

$.fn.equalizeHeights = function (outer) {
	var maxHeight = this.map(function (i, e) {
		$(e).css('height', '');
		if (outer == true) {
			return $(e).actual('outerHeight');
		} else {
			return $(e).actual('height');
		}
	}).get();

	for (var i = 0, c = maxHeight.length; i < c; ++i) {
		if (maxHeight[i] % 2) {
			--maxHeight[i];
		}
	}

	return this.height(Math.max.apply(this, maxHeight));
}

$.fn.sliceHeight = function (options) {
	function _slice(el) {
		el.each(function () {
			$(this).css('line-height', '');
			$(this).css('height', '');
		});
		if (typeof (options.autoslicecount) == 'undefined' || options.autoslicecount !== false) {
			var elw = (el.first().hasClass('item') ? el.first().outerWidth() : el.first().parents('.item').outerWidth());
			var elsw = el.first().parents('.items').outerWidth();
			if (!elsw) {
				elsw = el.first().parents('.row').outerWidth();
			}
			if (elsw && elw) {
				options.slice = Math.floor(elsw / elw);
			}
		}
		if (options.slice) {
			for (var i = 0; i < el.length; i += options.slice) {
				$(el.slice(i, i + options.slice)).equalizeHeights(options.outer);
			}
		}
		if (options.lineheight) {
			var lineheightAdd = parseInt(options.lineheight);
			if (isNaN(lineheightAdd)) {
				lineheightAdd = 0;
			}
			el.each(function () {
				$(this).css('line-height', ($(this).actual('height') + lineheightAdd) + 'px');
			});
		}
	}

	var options = $.extend({
		slice: null,
		outer: false,
		lineheight: false,
		autoslicecount: true
	}, options);

	var el = $(this);
	_slice(el);

	BX.addCustomEvent('onWindowResize', function (eventdata) {
		ignoreResize.push(true);
		_slice(el);
		ignoreResize.pop();
	});
}

// exec callback on selector exists
waitingExists = function (selector, delay, callback) {
	delay = parseInt(delay);
	delay = (delay < 0 ? 0 : delay);

	if (typeof (callback) === 'function') {
		if (!$(selector).length) {
			setTimeout(function () {
				waitingExists(selector, delay, callback);
			}, delay);
		}
		else {
			callback();
		}
	}
}

// exec callback on selector not exists
waitingNotExists = function (selector, delay, callback) {
	delay = parseInt(delay);
	delay = (delay < 0 ? 0 : delay);

	if (typeof (callback) === 'function') {
		if ($(selector).length) {
			setTimeout(function () {
				waitingNotExists(selector, delay, callback);
			}, delay);
		}
		else {
			callback();
		}
	}
}

function onLoadjqm(hash) {
	var name = $(hash.t).data('name'),
		top = (($(window).height() > hash.w.height()) ? Math.floor(($(window).height() - hash.w.height()) / 2) : 0) + 'px';
	$.each($(hash.t).get(0).attributes, function (index, attr) {
		if (/^data\-autoload\-(.+)$/.test(attr.nodeName)) {
			var key = attr.nodeName.match(/^data\-autoload\-(.+)$/)[1];
			var el = $('input[name="' + key.toUpperCase() + '"]');
			el.val($(hash.t).data('autoload-' + key)).attr('readonly', 'readonly');
			el.attr('title', el.val());
		}
	});
	if ($(hash.t).data('autohide')) {
		$(hash.w).data('autohide', $(hash.t).data('autohide'));
	}
	if (name == 'order_product') {
		if ($(hash.t).data('product')) {
			$('input[name="PRODUCT"]').val($(hash.t).data('product')).attr('readonly', 'readonly').attr('title', $('input[name="PRODUCT"]').val());
		}
	}
	if (name == 'question') {
		if ($(hash.t).data('product')) {
			$('input[name="NEED_PRODUCT"]').val($(hash.t).data('product')).attr('readonly', 'readonly').attr('title', $('input[name="NEED_PRODUCT"]').val());
		}
	}
	hash.w.addClass('show').css({ 'margin-left': '-' + hash.w.width() / 2 + 'px', 'top': top, 'opacity': 1 });
}

function onHide(hash) {
	if ($(hash.w).data('autohide')) {
		eval($(hash.w).data('autohide'));
	}
	hash.w.css('opacity', 0).hide();
	hash.w.empty();
	hash.o.remove();
	hash.w.removeClass('show');
}

var InitFlexSlider = function () {
	$(".flexslider:not(.thmb)").each(function () {
		var slider = $(this);
		var options;
		var defaults = {
			animationLoop: false,
			controlNav: false,
			directionNav: true
		}
		var config = $.extend({}, defaults, options, slider.data("plugin-options"));
		if (typeof (config.counts) != "undefined") {
			config.maxItems = getGridSize(config.counts);
			config.minItems = getGridSize(config.counts);
			config.move = getGridSize(config.counts);
			config.itemWidth = 200;
		}
		config.after = config.start = function (slider) {
			var eventdata = { slider: slider };
			BX.onCustomEvent('onSlide', [eventdata]);
		}

		slider.flexslider(config).addClass("flexslider-init");
		if (config.controlNav)
			slider.addClass("flexslider-control-nav");
		if (config.directionNav)
			slider.addClass("flexslider-direction-nav");
	});
}

$.fn.jqmEx = function () {
	//console.log("start");
	$(this).each(function () {
		var _this = $(this);
		// console.log(_this);
		var name = _this.data('name');

		if (name.length) {
			// console.log(arCorporationOptions);
			var script = arCorporationOptions['SITE_DIR'] + 'ajax/form.php';
			var paramsStr = ''; var trigger = ''; var arTriggerAttrs = {};
			$.each(_this.get(0).attributes, function (index, attr) {
				// console.log(attr.nodeName);
				var attrName = attr.nodeName;
				var attrValue = _this.attr(attrName);
				trigger += '[' + attrName + '=\"' + attrValue + '\"]';
				arTriggerAttrs[attrName] = attrValue;
				if (/^data\-param\-(.+)$/.test(attrName)) {
					var key = attrName.match(/^data\-param\-(.+)$/)[1];
					paramsStr += key + '=' + attrValue + '&';
					// console.log(paramsStr);
				}
			});
			//console.log(paramsStr);
			if (!('data-page-url' in arTriggerAttrs))
				arTriggerAttrs['data-page-url'] = location.href;

			var triggerAttrs = JSON.stringify(arTriggerAttrs);
			var encTriggerAttrs = encodeURIComponent(triggerAttrs);
			script += '?' + paramsStr + 'data-trigger=' + encTriggerAttrs;
			if (!$('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').length) {
				//console.log(script);
				if (_this.attr('disabled') != 'disabled') {
					$('body').find('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').remove();
					$('body').append('<div class="' + name + '_frame jqmWindow" style="width:500px" data-trigger="' + encTriggerAttrs + '"></div>');
					$('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').jqm({ trigger: trigger, onLoad: function (hash) { onLoadjqm(hash); }, onHide: function (hash) { onHide(hash); }, ajax: script }).jqmShow({}); // add .jqmShow({})
				}
			}
			// debugger;
		}
	});
}


$(document).ready(function () {
	//scrollToTop();
	CheckTopMenuDotted();
	CheckStickyFooter();
	setTimeout(function () { $(window).resize(); }, 350); // need to check resize flexslider & menu
	$(window).scroll();

	if (arCorporationOptions['THEME']['USE_DEBUG_GOALS'] === 'Y') {
		$.cookie('_ym_debug', 1, { path: '/', });
	}
	else {
		$.cookie('_ym_debug', null, { path: '/', });
	}

	$.extend($.validator.messages, {
		required: BX.message('JS_REQUIRED'),
		email: BX.message('JS_FORMAT'),
		equalTo: BX.message('JS_PASSWORD_COPY'),
		minlength: BX.message('JS_PASSWORD_LENGTH'),
		remote: BX.message('JS_ERROR')
	});

	$.validator.addMethod(
		'regexp', function (value, element, regexp) {
			var re = new RegExp(regexp);
			return this.optional(element) || re.test(value);
		},
		BX.message('JS_FORMAT')
	);

	$.validator.addMethod(
		'filesize', function (value, element, param) {
			return this.optional(element) || (element.files[0].size <= param)
		},
		BX.message('JS_FILE_SIZE')
	);

	$.validator.addMethod(
		'date', function (value, element, param) {
			var status = false;
			if (!value || value.length <= 0) {
				status = true;
			}
			else {
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4})$');
				var matches = re.exec(value);
				if (matches) {
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]));
				}
			}
			return status;
		}, BX.message('JS_DATE')
	);

	$.validator.addMethod(
		'datetime', function (value, element, param) {
			var status = false;
			if (!value || value.length <= 0) {
				status = true;
			}
			else {
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2})$');
				var matches = re.exec(value);
				if (matches) {
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1], matches[6], matches[7]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]) && (composedDate.getHours() == matches[6]) && (composedDate.getMinutes() == matches[7]));
				}
			}
			return status;
		}, BX.message('JS_DATETIME')
	);

	$.validator.addMethod(
		'extension', function (value, element, param) {
			param = typeof param === 'string' ? param.replace(/,/g, '|') : 'png|jpe?g|gif';
			return this.optional(element) || value.match(new RegExp('.(' + param + ')$', 'i'));
		}, BX.message('JS_FILE_EXT')
	);

	$.validator.addMethod(
		'captcha', function (value, element, params) {
			return $.validator.methods.remote.call(this, value, element, {
				url: arCorporationOptions['SITE_DIR'] + 'ajax/check-captcha.php',
				type: 'post',
				data: {
					captcha_word: value,
					captcha_sid: function () {
						return $(element).closest('form').find('input[name="captcha_sid"]').val();
					}
				}
			});
		},
		BX.message('JS_ERROR')
	);

	/*reload captcha*/
	$('body').on('click', '.refresh', function (e) {
		e.preventDefault();
		$.ajax({
			url: arCorporationOptions['SITE_DIR'] + 'ajax/captcha.php'
		}).done(function (text) {
			$('.captcha_sid').val(text);
			$('.captcha_img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + text);
		});
	});

	$.validator.addMethod(
		'recaptcha', function (value, element, param) {
			var id = $(element).closest('form').find('.g-recaptcha').attr('data-widgetid');
			if (typeof id !== 'undefined') {
				return grecaptcha.getResponse(id) != '';
			}
			else {
				return true;
			}
		}, BX.message('JS_RECAPTCHA_ERROR')
	);

	$.validator.addMethod(
		'processing_approval', function (value, element, param) {
			return $(element).is(':checked');
		}, BX.message('JS_PROCESSING_ERROR')
	);

	$.validator.addClassRules({
		'phone': {
			regexp: arCorporationOptions['THEME']['VALIDATE_PHONE_MASK']
		},
		'confirm_password': {
			equalTo: 'input[name="REGISTER\[PASSWORD\]"]',
			minlength: 6
		},
		'password': {
			minlength: 6
		},
		'inputfile': {
			extension: arCorporationOptions['THEME']['VALIDATE_FILE_EXT'],
			filesize: 5000000
		},
		'datetime': {
			datetime: ''
		},
		'captcha': {
			captcha: ''
		},
		'recaptcha': {
			recaptcha: ''
		},
		'processing_approval': {
			processing_approval: ''
		}
	});

	InitFlexSlider();

	// for check flexslider bug in composite & ajax mode
	waitingExists('.detail .galery .flexslider', 1000, function () {
		InitFlexSlider();
		setTimeout(function () {
			$(window).resize();
		}, 350);
	});

	/*check mobile device*/
	if (jQuery.browser.mobile) {
		$('.style-switcher').addClass('hidden');
		$('.hint span').remove();

		$('*[data-event="jqm"]').on('click', function (e) {
			e.preventDefault();
			var _this = $(this);
			var name = _this.data('name');

			if (name.length) {
				var script = arCorporationOptions['SITE_DIR'] + 'form/';
				var paramsStr = ''; var arTriggerAttrs = {};
				$.each(_this.get(0).attributes, function (index, attr) {
					var attrName = attr.nodeName;
					var attrValue = _this.attr(attrName);
					arTriggerAttrs[attrName] = attrValue;
					if (/^data\-param\-(.+)$/.test(attrName)) {
						var key = attrName.match(/^data\-param\-(.+)$/)[1];
						paramsStr += key + '=' + attrValue + '&';
					}
				});

				if (!('data-page-url' in arTriggerAttrs))
					arTriggerAttrs['data-page-url'] = location.href;

				var triggerAttrs = JSON.stringify(arTriggerAttrs);
				var encTriggerAttrs = encodeURIComponent(triggerAttrs);
				script += '?name=' + name + '&' + paramsStr + 'data-trigger=' + encTriggerAttrs;

				location.href = script;
			}
		});

		// $('.fancybox').removeClass('fancybox');
	}
	else {
		$('span[data-event="jqm"]').on('click', function (e) {
			e.preventDefault();
			$(this).jqmEx();
			// $(this).trigger('click'); // remove 
		});
	}

	$('a.fancybox:has(img)').fancybox();

	// Responsive Menu Events
	var addActiveClass = false;

	$('#mainMenu li.dropdown > a, #mainMenu li.dropdown-submenu > a').on('click', function (e) {
		var _th = $(this);
		e.preventDefault();

		addActiveClass = _th.parent().hasClass('resp-active');

		if (!_th.closest('.dropdown-menu').length)
			$('#mainMenu').find('.resp-active').removeClass('resp-active');
		if (!addActiveClass)
			_th.closest("li").addClass("resp-active");
		else
			_th.closest("li").removeClass("resp-active");

	});

	$(document).on('click', '.mega-menu .dropdown-menu', function (e) {
		e.stopPropagation()
	});

	$(document).on('click', '.mega-menu .dropdown-toggle.more-items', function (e) {
		e.preventDefault();
	});

	$('.table-menu .dropdown,.table-menu .dropdown-submenu,.table-menu .dropdown-toggle').on('mouseenter', function () {
		CheckTopVisibleMenu();
	});

	$(".mega-menu .search-item").on("click", function (e) {
		e.preventDefault();
		$(".menu-and-search .search").toggleClass("hide");
	});

	$(".mega-menu ul.nav .search input").on("keypress", function (e) {
		if (e.keyCode == 13) {
			var inputValue = $(this).val();
			$(".menu-and-search .search input").val(inputValue);
			$(".menu-and-search form").submit();
		}
	});

	$(".mega-menu ul.nav .search button").on("click", function (e) {
		e.preventDefault();
		var inputValue = $(this).parents(".search").find("input").val();
		$(".menu-and-search .search input").val(inputValue);
		$(".menu-and-search form").submit();
	});

	/* toggle */
	var $this = this,
		previewParClosedHeight = 25;

	$('section.toggle > label').prepend($('<i />').addClass('icon icon-plus'));
	$('section.toggle > label').prepend($('<i />').addClass('icon icon-minus'));
	$('section.toggle.active > p').addClass('preview-active');
	$('section.toggle.active > div.toggle-content').slideDown(350, function () { });

	$('section.toggle > label').click(function (e) {
		var parentSection = $(this).parent(),
			parentWrapper = $(this).parents('div.toogle'),
			previewPar = false,
			isAccordion = parentWrapper.hasClass('toogle-accordion');

		if (isAccordion && typeof (e.originalEvent) != 'undefined') {
			parentWrapper.find('section.toggle.active > label').trigger('click');
		}

		parentSection.toggleClass('active');

		// Preview Paragraph
		if (parentSection.find('> p').get(0)) {
			previewPar = parentSection.find('> p');
			var previewParCurrentHeight = previewPar.css('height');
			previewPar.css('height', 'auto');
			var previewParAnimateHeight = previewPar.css('height');
			previewPar.css('height', previewParCurrentHeight);
		}

		// Content
		var toggleContent = parentSection.find('> div.toggle-content');

		if (parentSection.hasClass('active')) {
			$(previewPar).animate({
				height: previewParAnimateHeight
			}, 350, function () {
				$(this).addClass('preview-active');
			});
			toggleContent.slideDown(350, function () { });
		}
		else {
			$(previewPar).animate({
				height: previewParClosedHeight
			}, 350, function () {
				$(this).removeClass('preview-active');
			});
			toggleContent.slideUp(350, function () { });
		}
	});

	/* accordion */
	$('.accordion-head').on('click', function (e) {
		e.preventDefault();
		if ($(this).hasClass('accordion-open')) {
			$(this).addClass('accordion-close').removeClass('accordion-open');
		} else {
			$(this).addClass('accordion-open').removeClass('accordion-close');
		}
	});

	/* progress bar */
	$('[data-appear-progress-animation]').each(function () {
		var $this = $(this);
		$this.appear(function () {
			var delay = ($this.attr('data-appear-animation-delay') ? $this.attr('data-appear-animation-delay') : 1);
			if (delay > 1)
				$this.css('animation-delay', delay + 'ms');
			$this.addClass($this.attr('data-appear-animation'));

			setTimeout(function () {
				$this.animate({
					width: $this.attr('data-appear-progress-animation')
				}, 1500, 'easeOutQuad', function () {
					$this.find('.progress-bar-tooltip').animate({
						opacity: 1
					}, 500, 'easeOutQuad');
				});
			}, delay);
		}, { accX: 0, accY: -50 });
	});

	$('a[rel=tooltip]').tooltip();
	$('span[data-toggle=tooltip]').tooltip();

	$('select.sort').on('change', function () {
		location.href = $(this).val();
	});

	setTimeout(function (th) {
		$('.catalog.group.list .item').each(function () {
			var th = $(this);
			if ((tmp = th.find('.image').outerHeight() - th.find('.text_info').outerHeight()) > 0) {
				th.find('.text_info .titles').height(th.find('.text_info .titles').outerHeight() + tmp);
			}
		})
	}, 50);

	/*item galery*/
	$('.thumbs .item a').on('click', function (e) {
		e.preventDefault();
		$('.thumbs .item').removeClass('current');
		$(this).closest('.item').toggleClass('current');
		$('.slides li' + $(this).attr('href')).addClass('current').siblings().removeClass('current');
	});
});

var waitCounter = function (idCounter, delay, callback) {
	var obCounter = window['yaCounter' + idCounter];
	if (typeof obCounter == 'object') {
		if (typeof callback == 'function') {
			callback();
		}
	}
	else {
		setTimeout(function () {
			waitCounter(idCounter, delay, callback);
		}, delay);
	}
}

var waitReCaptcha = function (delay, callback) {
	if (typeof grecaptcha == 'object' && typeof grecaptcha.render === 'function') {
		if (typeof callback == 'function') {
			callback();
		}
	}
	else {
		setTimeout(function () {
			waitReCaptcha(delay, callback);
		}, delay);
	}
}

var reCaptchaRender = function (response) {
	if ($('.g-recaptcha:not(.rendered)').length) {
		waitReCaptcha(50, function () {
			$('.g-recaptcha:not(.rendered)').each(function () {
				$this = $(this);
				$this.addClass('rendered')
				var id = grecaptcha.render($this[0], {
					sitekey: $this.attr('data-sitekey'),
					theme: $this.attr('data-theme'),
					size: $this.attr('data-size'),
					callback: $this.attr('data-callback'),
				});
				$this.attr('data-widgetid', id);
			});
		});
	}
}

var reCaptchaVerify = function (response) {
	$('.g-recaptcha.rendered').each(function () {
		var id = $(this).attr('data-widgetid');
		if (typeof (id) !== 'undefined') {
			if (grecaptcha.getResponse(id) != '') {
				$(this).closest('form').find('.recaptcha').valid();
			}
		}
	});
}

// Events
var timerScroll = false, ignoreScroll = [], documentScrollTopLast = $(document).scrollTop();
$(window).scroll(function () {
	CheckPopupTop();
	if (!ignoreScroll.length) {
		if (timerScroll) {
			clearTimeout(timerScroll);
			timerScroll = false;
		}
		timerScroll = setTimeout(function () {
			BX.onCustomEvent('onWindowScroll', false);
		}, 100);
	}
	documentScrollTopLast = $(document).scrollTop();
});

var timerResize = false, ignoreResize = [];
$(window).resize(function () {
	CheckPopupTop();
	if (!ignoreResize.length) {
		if (timerResize) {
			clearTimeout(timerResize);
			timerResize = false;
		}
		timerResize = setTimeout(function () {
			BX.onCustomEvent('onWindowResize', false);
		}, 100);
	}
});

BX.addCustomEvent('onWindowScroll', function (eventdata) {
	try {
		ignoreScroll.push(true);
	}
	catch (e) { }
	finally {
		ignoreScroll.pop();
	}
});

BX.addCustomEvent('onWindowResize', function (eventdata) {
	try {
		ignoreResize.push(true);
		CheckTopMenuDotted();
		CheckTopVisibleMenu();
		CheckBigBanner();
		CheckFlexSlider();
	}
	catch (e) { }
	finally {
		ignoreResize.pop();
	}
});

BX.addCustomEvent('onSlide', function (eventdata) {
	try {
		ignoreResize.push(true);
		if (eventdata) {
			var slider = eventdata.slider;
			if (slider) {
				// add classes .curent & .shown to slide
				slider.find('.item').removeClass('current');
				var curSlide = slider.find('.item.flex-active-slide');
				var curSlideIndex = curSlide.attr('data-slide_index');
				if (curSlideIndex.length) {
					curSlide.addClass('current');
					slider.find('.item[data-slide_index=' + curSlideIndex + ']').addClass('shown');
					slider.resize();
				}
			}
		}
	}
	catch (e) { }
	finally {
		ignoreResize.pop();
	}
});

BX.addCustomEvent('onCounterGoals', function (eventdata) {
	if (arCorporationOptions['THEME']['USE_YA_COUNTER'] === 'Y') {
		var idCounter = arCorporationOptions['THEME']['YA_COUNTER_ID'];
		idCounter = parseInt(idCounter);

		if (typeof eventdata != 'object') {
			eventdata = { goal: 'undefined' };
		}
		if (typeof eventdata.goal != 'string') {
			eventdata.goal = 'undefined';
		}

		if (idCounter) {
			try {
				waitCounter(idCounter, 50, function () {
					var obCounter = window['yaCounter' + idCounter];
					if (typeof obCounter == 'object') {
						obCounter.reachGoal(eventdata.goal);
					}
				});
			}
			catch (e) {
				console.error(e)
			}
		}
		else {
			console.info('Bad counter id!', idCounter);
		}
	}
})