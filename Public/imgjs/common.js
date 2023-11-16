function id(id) {
	return !id ? null : document.getElementById(id);
}
// function id(id) {
//      if (typeof jQuery == 'undefined' || (typeof id == 'string' && document.getElementById(id))) {
//           return document.getElementById(id);
//      } else if (typeof id == 'object' || !/^\w*id/.exec(id) ||
//           /^(body|div|span|a|input|textarea|button|img|ul|li|ol|table|tr|th|td)id/.exec(id)){
//         return jQuery(id);
//      }
//      return null;
// }
var BROWSER = {};
var USERAGENT = navigator.userAgent.toLowerCase();
browserVersion({
	'ie': 'msie',
	'firefox': '',
	'chrome': '',
	'opera': '',
	'safari': '',
	'mozilla': '',
	'webkit': '',
	'maxthon': '',
	'qq': 'qqbrowser',
	'rv': 'rv'
});
var CSSLOADED = [];
var JSLOADED = [];
var JSMENU = [];
JSMENU['active'] = [];
JSMENU['timer'] = [];
JSMENU['drag'] = [];
JSMENU['layer'] = 0;
JSMENU['zIndex'] = {
	'win': 200,
	'menu': 300,
	'dialog': 400,
	'prompt': 500
};
JSMENU['float'] = '';
 var EXTRAFUNC = [],
 	EXTRASTR = '';
 EXTRAFUNC['showmenu'] = [];

function _attachEvent(obj, evt, func, eventobj) {
	eventobj = !eventobj ? obj : eventobj;
	if (obj != null) {
		if (obj.addEventListener) {
			obj.addEventListener(evt, func, false);
		} else if (eventobj.attachEvent) {
			obj.attachEvent('on' + evt, func);
		}
	}
}

function _detachEvent(obj, evt, func, eventobj) {
	eventobj = !eventobj ? obj : eventobj;
	if (obj.removeEventListener) {
		obj.removeEventListener(evt, func, false);
	} else if (eventobj.detachEvent) {
		obj.detachEvent('on' + evt, func);
	}
}

function browserVersion(types) {
	var other = 1;
	for (i in types) {
		var v = types[i] ? types[i] : i;
		if (USERAGENT.indexOf(v) != -1) {
			var re = new RegExp(v + '(\\/|\\s|:)([\\d\\.]+)', 'ig');
			var matches = re.exec(USERAGENT);
			var ver = matches != null ? matches[2] : 0;
			other = ver !== 0 && v != 'mozilla' ? 0 : other;
		} else {
			var ver = 0;
		}
		eval('BROWSER.' + i + '= ver');
	}
	BROWSER.other = other;
}

function getEvent() {
	if (document.all) return window.event;
	func = getEvent.caller;
	while (func != null) {
		var arg0 = func.arguments[0];
		if (arg0) {
			if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {
				return arg0;
			}
		}
		func = func.caller;
	}
	return null;
}

function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

function in_array(needle, haystack) {
	if (typeof needle == 'string' || typeof needle == 'number') {
		for (var i in haystack) {
			if (haystack[i] == needle) {
				return true;
			}
		}
	}
	return false;
}


function preg_replace(search, replace, str, regswitch) {
	var regswitch = !regswitch ? 'ig' : regswitch;
	var len = search.length;
	for (var i = 0; i < len; i++) {
		re = new RegExp(search[i], regswitch);
		str = str.replace(re, typeof replace == 'string' ? replace : (replace[i] ? replace[i] : replace[0]));
	}
	return str;
}

function htmlspecialchars(str) {
	return preg_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], str);
}


var safescripts = {},
	evalscripts = [];

function $F(func, args, script) {
	var run = function() {
		var argc = args.length,
			s = '';
		for (i = 0; i < argc; i++) {
			s += ',args[' + i + ']';
		}
		eval('var check = typeof ' + func + ' == \'function\'');
		if (check) {
			eval(func + '(' + s.substr(1) + ')');
		} else {
			setTimeout(function() {
				checkrun();
			}, 50);
		}
	};
	var checkrun = function() {
		if (JSLOADED[src]) {
			run();
		} else {
			setTimeout(function() {
				checkrun();
			}, 50);
		}
	};
	script = script || 'common_extra';
	src = JSPATH + script + '.js?' + VERHASH;
	if (!JSLOADED[src]) {
		appendscript(src);
	}
	checkrun();
}

function appendscript(src, text, reload, charset) {
	var id = hash(src + text);
	if (!reload && in_array(id, evalscripts)) return;
	if (reload && id(id)) {
		id(id).parentNode.removeChild(id(id));
	}

	evalscripts.push(id);
	var scriptNode = document.createElement("script");
	scriptNode.type = "text/javascript";
	scriptNode.id = id;
	scriptNode.charset = charset ? charset : (BROWSER.firefox ? document.characterSet : document.charset);
	try {
		if (src) {
			scriptNode.src = src;
			scriptNode.onloadDone = false;
			scriptNode.onload = function() {
				scriptNode.onloadDone = true;
				JSLOADED[src] = 1;
			};
			scriptNode.onreadystatechange = function() {
				if ((scriptNode.readyState == 'loaded' || scriptNode.readyState == 'complete') && !scriptNode.onloadDone) {
					scriptNode.onloadDone = true;
					JSLOADED[src] = 1;
				}
			};
		} else if (text) {
			scriptNode.text = text;
		}
		document.getElementsByTagName('head')[0].appendChild(scriptNode);
	} catch (e) {}
}

function hash(string, length) {
	var length = length ? length : 32;
	var start = 0;
	var i = 0;
	var result = '';
	filllen = length - string.length % length;
	for (i = 0; i < filllen; i++) {
		string += "0";
	}
	while (start < string.length) {
		result = stringxor(result, string.substr(start, length));
		start += length;
	}
	return result;
}

function stringxor(s1, s2) {
	var s = '';
	var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var max = Math.max(s1.length, s2.length);
	for (var i = 0; i < max; i++) {
		var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
		s += hash.charAt(k % 52);
	}
	return s;
}

function showPreview(val, id) {
	var showObj = id(id);
	if (showObj) {
		showObj.innerHTML = val.replace(/\n/ig, "<bupdateseccoder />");
	}
}

function doane(event, preventDefault, stopPropagation) {
	var preventDefault = isUndefined(preventDefault) ? 1 : preventDefault;
	var stopPropagation = isUndefined(stopPropagation) ? 1 : stopPropagation;
	e = event ? event : window.event;
	if (!e) {
		e = getEvent();
	}
	if (!e) {
		return null;
	}
	if (preventDefault) {
		if (e.preventDefault) {
			e.preventDefault();
		} else {
			e.returnValue = false;
		}
	}
	if (stopPropagation) {
		if (e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
	}
	return e;
}

function showMenu(v) {
	var ctrlid = isUndefined(v['ctrlid']) ? v : v['ctrlid'];
	var showid = isUndefined(v['showid']) ? ctrlid : v['showid'];
	var menuid = isUndefined(v['menuid']) ? showid + '_menu' : v['menuid'];
	var ctrlObj = id(ctrlid);
	var menuObj = id(menuid);
	if (!menuObj) return;
	var mtype = isUndefined(v['mtype']) ? 'menu' : v['mtype'];
	var evt = isUndefined(v['evt']) ? 'mouseover' : v['evt'];
	var pos = isUndefined(v['pos']) ? '43' : v['pos'];
	var layer = isUndefined(v['layer']) ? 1 : v['layer'];
	var duration = isUndefined(v['duration']) ? 2 : v['duration'];
	var timeout = isUndefined(v['timeout']) ? 250 : v['timeout'];
	var maxh = isUndefined(v['maxh']) ? 600 : v['maxh'];
	var cache = isUndefined(v['cache']) ? 1 : v['cache'];
	var drag = isUndefined(v['drag']) ? '' : v['drag'];
	var dragobj = drag && id(drag) ? id(drag) : menuObj;
	var fade = isUndefined(v['fade']) ? 0 : v['fade'];
	var cover = isUndefined(v['cover']) ? 0 : v['cover'];
	var zindex = isUndefined(v['zindex']) ? JSMENU['zIndex']['menu'] : v['zindex'];
	var ctrlclass = isUndefined(v['ctrlclass']) ? '' : v['ctrlclass'];
	var winhandlekey = isUndefined(v['win']) ? '' : v['win'];
	if (winhandlekey && ctrlObj && !ctrlObj.getAttribute('fwin')) {
		ctrlObj.setAttribute('fwin', winhandlekey);
	}
	zindex = cover ? zindex + 500 : zindex;
	if (typeof JSMENU['active'][layer] == 'undefined') {
		JSMENU['active'][layer] = [];
	}

	for (i in EXTRAFUNC['showmenu']) {
		try {
			eval(EXTRAFUNC['showmenu'][i] + '()');
		} catch (e) {}
	}

	if (evt == 'click' && in_array(menuid, JSMENU['active'][layer]) && mtype != 'win') {
		hideMenu(menuid, mtype);
		return;
	}
	if (mtype == 'menu') {
		hideMenu(layer, mtype);
	}

	if (ctrlObj) {
		if (!ctrlObj.getAttribute('initialized')) {
			ctrlObj.setAttribute('initialized', true);
			ctrlObj.unselectable = true;

			ctrlObj.outfunc = typeof ctrlObj.onmouseout == 'function' ? ctrlObj.onmouseout : null;
			ctrlObj.onmouseout = function() {
				if (this.outfunc) this.outfunc();
				if (duration < 3 && !JSMENU['timer'][menuid]) {
					JSMENU['timer'][menuid] = setTimeout(function() {
						hideMenu(menuid, mtype);
					}, timeout);
				}
			};

			ctrlObj.overfunc = typeof ctrlObj.onmouseover == 'function' ? ctrlObj.onmouseover : null;
			ctrlObj.onmouseover = function(e) {
				doane(e);
				if (this.overfunc) this.overfunc();
				if (evt == 'click') {
					clearTimeout(JSMENU['timer'][menuid]);
					JSMENU['timer'][menuid] = null;
				} else {
					for (var i in JSMENU['timer']) {
						if (JSMENU['timer'][i]) {
							clearTimeout(JSMENU['timer'][i]);
							JSMENU['timer'][i] = null;
						}
					}
				}
			};
		}
	}

	if (!menuObj.getAttribute('initialized')) {
		menuObj.setAttribute('initialized', true);
		menuObj.ctrlkey = ctrlid;
		menuObj.mtype = mtype;
		menuObj.layer = layer;
		menuObj.cover = cover;
		if (ctrlObj && ctrlObj.getAttribute('fwin')) {
			menuObj.scrolly = true;
		}
		menuObj.style.position = 'absolute';
		menuObj.style.zIndex = zindex + layer;
		menuObj.onclick = function(e) {
			return doane(e, 0, 1);
		};
		if (duration < 3) {
			if (duration > 1) {
				menuObj.onmouseover = function() {
					clearTimeout(JSMENU['timer'][menuid]);
					JSMENU['timer'][menuid] = null;
				};
			}
			if (duration != 1) {
				menuObj.onmouseout = function() {
					JSMENU['timer'][menuid] = setTimeout(function() {
						hideMenu(menuid, mtype);
					}, timeout);
				};
			}
		}
		if (cover) {
			var coverObj = document.createElement('div');
			coverObj.id = menuid + '_cover';
			coverObj.style.position = 'fixed';
			coverObj.style.zIndex = menuObj.style.zIndex - 1;
			coverObj.style.left = coverObj.style.top = '0px';
			coverObj.style.width = '100%';
			coverObj.style.height = Math.max(document.documentElement.clientHeight, document.body.offsetHeight) + 'px';
			coverObj.style.backgroundColor = '#000';
			coverObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=50)';
			coverObj.style.opacity = 0.5;
			coverObj.onclick = function() {
				hideMenu();
			};
			id('append_parent').appendChild(coverObj);
			_attachEvent(window, 'load', function() {
				coverObj.style.height = Math.max(document.documentElement.clientHeight, document.body.offsetHeight) + 'px';
			}, document);
		}
	}
	if (drag) {
		dragobj.style.cursor = 'move';
		dragobj.onmousedown = function(event) {
			try {
				dragMenu(menuObj, event, 1);
			} catch (e) {}
		};
	}

	if (cover) id(menuid + '_cover').style.display = '';
	if (fade) {
		var O = 0;
		var fadeIn = function(O) {
			if (O > 100) {
				clearTimeout(fadeInTimer);
				return;
			}
			menuObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + O + ')';
			menuObj.style.opacity = O / 100;
			O += 20;
			var fadeInTimer = setTimeout(function() {
				fadeIn(O);
			}, 40);
		};
		fadeIn(O);
		menuObj.fade = true;
	} else {
		menuObj.fade = false;
	}
	menuObj.style.display = '';
	if (ctrlObj && ctrlclass) {
		ctrlObj.className += ' ' + ctrlclass;
		menuObj.setAttribute('ctrlid', ctrlid);
		menuObj.setAttribute('ctrlclass', ctrlclass);
	}
	if (pos != '*') {
		setMenuPosition(showid, menuid, pos);
	}
	if (BROWSER.ie && BROWSER.ie < 7 && winhandlekey && id('fwin_' + winhandlekey)) {
		id(menuid).style.left = (parseInt(id(menuid).style.left) - parseInt(id('fwin_' + winhandlekey).style.left)) + 'px';
		id(menuid).style.top = (parseInt(id(menuid).style.top) - parseInt(id('fwin_' + winhandlekey).style.top)) + 'px';
	}
	if (maxh && menuObj.scrollHeight > maxh) {
		menuObj.style.height = maxh + 'px';
		if (BROWSER.opera) {
			menuObj.style.overflow = 'auto';
		} else {
			menuObj.style.overflowY = 'auto';
		}
	}

	if (!duration) {
		setTimeout('hideMenu(\'' + menuid + '\', \'' + mtype + '\')', timeout);
	}

	if (!in_array(menuid, JSMENU['active'][layer])) JSMENU['active'][layer].push(menuid);
	menuObj.cache = cache;
	if (layer > JSMENU['layer']) {
		JSMENU['layer'] = layer;
	}
	var hasshow = function(ele) {
		while (ele.parentNode && ((typeof(ele['currentStyle']) === 'undefined') ? window.getComputedStyle(ele, null) : ele['currentStyle'])['display'] !== 'none') {
			ele = ele.parentNode;
		}
		if (ele === document) {
			return true;
		} else {
			return false;
		}
	};
	if (!menuObj.getAttribute('disautofocus')) {
		try {
			var focused = false;
			var tags = ['input', 'select', 'textarea', 'button', 'a'];
			for (var i = 0; i < tags.length; i++) {
				var _all = menuObj.getElementsByTagName(tags[i]);
				if (_all.length) {
					for (j = 0; j < _all.length; j++) {
						if ((!_all[j]['type'] || _all[j]['type'] != 'hidden') && hasshow(_all[j])) {
							_all[j].className += ' hidefocus';
							_all[j].focus();
							focused = true;
							var cobj = _all[j];
							_attachEvent(_all[j], 'blur', function() {
								cobj.className = trim(cobj.className.replace(' hidefocus', ''));
							});
							break;
						}
					}
				}
				if (focused) {
					break;
				}
			}
		} catch (e) {}
	}
}
var delayShowST = null;

var dragMenuDisabled = false;

function dragMenu(menuObj, e, op) {
	e = e ? e : window.event;
	if (op == 1) {
		if (dragMenuDisabled || in_array(e.target ? e.target.tagName : e.srcElement.tagName, ['TEXTAREA', 'INPUT', 'BUTTON', 'SELECT'])) {
			return;
		}
		JSMENU['drag'] = [e.clientX, e.clientY];
		JSMENU['drag'][2] = parseInt(menuObj.style.left);
		JSMENU['drag'][3] = parseInt(menuObj.style.top);
		document.onmousemove = function(e) {
			try {
				dragMenu(menuObj, e, 2);
			} catch (err) {}
		};
		document.onmouseup = function(e) {
			try {
				dragMenu(menuObj, e, 3);
			} catch (err) {}
		};
		doane(e);
	} else if (op == 2 && JSMENU['drag'][0]) {
		var menudragnow = [e.clientX, e.clientY];
		menuObj.style.left = (JSMENU['drag'][2] + menudragnow[0] - JSMENU['drag'][0]) + 'px';
		menuObj.style.top = (JSMENU['drag'][3] + menudragnow[1] - JSMENU['drag'][1]) + 'px';
		menuObj.removeAttribute('top_');
		menuObj.removeAttribute('left_');
		doane(e);
	} else if (op == 3) {
		JSMENU['drag'] = [];
		document.onmousemove = null;
		document.onmouseup = null;
	}
}

function setMenuPosition(showid, menuid, pos) {
	var showObj = id(showid);
	var menuObj = menuid ? id(menuid) : id(showid + '_menu');
	if (isUndefined(pos) || !pos) pos = '43';
	var basePoint = parseInt(pos.substr(0, 1));
	var direction = parseInt(pos.substr(1, 1));
	var important = pos.indexOf('!') != -1 ? 1 : 0;
	var sxy = 0,
		sx = 0,
		sy = 0,
		sw = 0,
		sh = 0,
		ml = 0,
		mt = 0,
		mw = 0,
		mcw = 0,
		mh = 0,
		mch = 0,
		bpl = 0,
		bpt = 0;

	if (!menuObj || (basePoint > 0 && !showObj)) return;
	if (showObj) {
		sxy = fetchOffset(showObj);
		sx = sxy['left'];
		sy = sxy['top'];
		sw = showObj.offsetWidth;
		sh = showObj.offsetHeight;
	}
	mw = menuObj.offsetWidth;
	mcw = menuObj.clientWidth;
	mh = menuObj.offsetHeight;
	mch = menuObj.clientHeight;

	switch (basePoint) {
		case 1:
			bpl = sx;
			bpt = sy;
			break;
		case 2:
			bpl = sx + sw;
			bpt = sy;
			break;
		case 3:
			bpl = sx + sw;
			bpt = sy + sh;
			break;
		case 4:
			bpl = sx;
			bpt = sy + sh;
			break;
	}
	switch (direction) {
		case 0:
			menuObj.style.left = (document.body.clientWidth - menuObj.clientWidth) / 2 + 'px';
			mt = (document.documentElement.clientHeight - menuObj.clientHeight) / 2;
			break;
		case 1:
			ml = bpl - mw;
			mt = bpt - mh;
			break;
		case 2:
			ml = bpl;
			mt = bpt - mh;
			break;
		case 3:
			ml = bpl;
			mt = bpt;
			break;
		case 4:
			ml = bpl - mw;
			mt = bpt;
			break;
	}
	var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
	var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
	if (!important) {
		if (in_array(direction, [1, 4]) && ml < 0) {
			ml = bpl;
			if (in_array(basePoint, [1, 4])) ml += sw;
		} else if (ml + mw > scrollLeft + document.body.clientWidth && sx >= mw) {
			ml = bpl - mw;
			if (in_array(basePoint, [2, 3])) {
				ml -= sw;
			} else if (basePoint == 4) {
				ml += sw;
			}
		}
		if (in_array(direction, [1, 2]) && mt < 0) {
			mt = bpt;
			if (in_array(basePoint, [1, 2])) mt += sh;
		} else if (mt + mh > scrollTop + document.documentElement.clientHeight && sy >= mh) {
			mt = bpt - mh;
			if (in_array(basePoint, [3, 4])) mt -= sh;
		}
	}
	if (pos.substr(0, 3) == '210') {
		ml += 69 - sw / 2;
		mt -= 5;
		if (showObj.tagName == 'TEXTAREA') {
			ml -= sw / 2;
			mt += sh / 2;
		}
	}
	if (direction == 0 || menuObj.scrolly) {
		if (BROWSER.ie && BROWSER.ie < 7) {
			if (direction == 0) mt += scrollTop;
		} else {
			if (menuObj.scrolly) mt -= scrollTop;
			menuObj.style.position = 'fixed';
		}
	}
	if (ml) menuObj.style.left = ml + 'px';
	if (mt) menuObj.style.top = mt + 'px';
	if (direction == 0 && BROWSER.ie && !document.documentElement.clientHeight) {
		menuObj.style.position = 'absolute';
		menuObj.style.top = (document.body.clientHeight - menuObj.clientHeight) / 2 + 'px';
	}
	if (menuObj.style.clip && !BROWSER.opera) {
		menuObj.style.clip = 'rect(auto, auto, auto, auto)';
	}
}

function hideMenu(attr, mtype) {
	attr = isUndefined(attr) ? '' : attr;
	mtype = isUndefined(mtype) ? 'menu' : mtype;
	if (attr == '') {
		for (var i = 1; i <= JSMENU['layer']; i++) {
			hideMenu(i, mtype);
		}
		return;
	} else if (typeof attr == 'number') {
		for (var j in JSMENU['active'][attr]) {
			hideMenu(JSMENU['active'][attr][j], mtype);
		}
		return;
	} else if (typeof attr == 'string') {
		var menuObj = id(attr);
		if (!menuObj || (mtype && menuObj.mtype != mtype)) return;
		var ctrlObj = '',
			ctrlclass = '';
		if ((ctrlObj = id(menuObj.getAttribute('ctrlid'))) && (ctrlclass = menuObj.getAttribute('ctrlclass'))) {
			var reg = new RegExp(' ' + ctrlclass);
			ctrlObj.className = ctrlObj.className.replace(reg, '');
		}
		clearTimeout(JSMENU['timer'][attr]);
		var hide = function() {
			if (menuObj.cache) {
				if (menuObj.style.visibility != 'hidden') {
					menuObj.style.display = 'none';
					if (menuObj.cover) id(attr + '_cover').style.display = 'none';
				}
			} else {
				menuObj.parentNode.removeChild(menuObj);
				if (menuObj.cover) id(attr + '_cover').parentNode.removeChild(id(attr + '_cover'));
			}
			var tmp = [];
			for (var k in JSMENU['active'][menuObj.layer]) {
				if (attr != JSMENU['active'][menuObj.layer][k]) tmp.push(JSMENU['active'][menuObj.layer][k]);
			}
			JSMENU['active'][menuObj.layer] = tmp;
		};
		if (menuObj.fade) {
			var O = 100;
			var fadeOut = function(O) {
				if (O == 0) {
					clearTimeout(fadeOutTimer);
					hide();
					return;
				}
				menuObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + O + ')';
				menuObj.style.opacity = O / 100;
				O -= 20;
				var fadeOutTimer = setTimeout(function() {
					fadeOut(O);
				}, 40);
			};
			fadeOut(O);
		} else {
			hide();
		}
	}
}

//关闭
function fetchOffset(obj, mode) {
	var left_offset = 0,
		top_offset = 0,
		mode = !mode ? 0 : mode;

	if (obj.getBoundingClientRect && !mode) {
		var rect = obj.getBoundingClientRect();
		var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
		var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
		if (document.documentElement.dir == 'rtl') {
			scrollLeft = scrollLeft + document.documentElement.clientWidth - document.documentElement.scrollWidth;
		}
		left_offset = rect.left + scrollLeft - document.documentElement.clientLeft;
		top_offset = rect.top + scrollTop - document.documentElement.clientTop;
	}
	if (left_offset <= 0 || top_offset <= 0) {
		left_offset = obj.offsetLeft;
		top_offset = obj.offsetTop;
		while ((obj = obj.offsetParent) != null) {
			position = getCurrentStyle(obj, 'position', 'position');
			if (position == 'relative') {
				continue;
			}
			left_offset += obj.offsetLeft;
			top_offset += obj.offsetTop;
		}
	}
	return {
		'left': left_offset,
		'top': top_offset
	};
}

var zoomstatus = 1;

function zoom(obj, zimg, nocover, pn, showexif) {
	$F('_zoom', arguments);
}
