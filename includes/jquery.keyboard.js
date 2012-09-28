jQuery.fn.keyboard = function(keys, fn, single) {
		jQuery.keyboard.init(this);
		var keycodes = jQuery.keyboard.getKeyCodes(keys);
		this.keyboardOfficeFuncs[keycodes.join('.')] =
				fn ? {keys:keycodes, fn:fn, single:single} : null;
		return this;
}

jQuery.keyboard = {
		init : function ($obj) {
				if (!$obj.keyboardOfficeFuncs) {
						$obj.keyboardOfficeFuncs   = {};
						$obj.keyboardOfficePressed = {};
						$obj.keydown(function (e) {
								jQuery.keyboard.set($obj, e || event, 1);
						});
						$obj.keyup(function (e) {
								jQuery.keyboard.setControl($obj, e);
								jQuery.keyboard.checkBinds($obj);
								jQuery.keyboard.set($obj, e || event, 0);
						});
				}
				return $obj;
		},
		setControl : function ($obj, e) {
				var kc = e.keyCode;
				var values = {};
				var keys   = jQuery.keyboard.keys;
				values[keys.ctrl]  = (kc == keys.ctrl  ? 1 : 0) || e.ctrlKey;
				values[keys.alt]   = (kc == keys.alt   ? 1 : 0) || e.altKey;
				values[keys.shift] = (kc == keys.shift ? 1 : 0) || e.shiftKey;
				for (var i in values) {
						$obj.keyboardOfficePressed[i] = values[i];
				}
		},
		set : function ($obj, e, value) {
				$obj.keyboardOfficePressed[e.keyCode] = value;
				jQuery.keyboard.setControl($obj, e);
		},
		getKeyCodes : function (keys) {
				// ['shift', 'ctrl'] => [16, 17]
				var keycodes = [];
				if (typeof keys == 'string') {
						keys = keys.split(' ');
				}
				for (var i in keys) {
						if (typeof keys[i] == 'string') {
								if (jQuery.keyboard.keys[keys[i]]) {
										keycodes.push(jQuery.keyboard.keys[keys[i]]);
								} else {
										throw 'No such index: «' + keys[i] + '»';
								}
						} else if (typeof keys[i] == 'number') {
								keycodes.push(keys[i]);
						} else {
								throw 'Wrong key type: «' + (typeof keys[i]) + '»';
						}
				}
				return keycodes.sort();
		},
		checkKeys : function ($obj, keys, single) {
				var pressed = $obj.keyboardOfficePressed;

				var arrayHas = function (array, value) {
						for (var i in array) {
								if (value == array[i]) {
										return true;
								}
						}
						return false;
				}

				// Checkes if all keys, we need, are pressed
				for (var i in keys) {
						if (!pressed[keys[i]]) {
								return false;
						}
				}

				// Checkes if no other keys are pressed
				if (single) {
						for (var k in pressed) {
								if (pressed[k] && !arrayHas(keys, k)) {
										return false;
								}
						}
				}

				return true;
		},
		checkBinds : function ($obj) {
				var funcs = $obj.keyboardOfficeFuncs;
				var check = jQuery.keyboard.checkKeys;
				var oldkbfn = $obj.keyboardfn;
				for (var i in funcs) {
						var bind = funcs[i];
						if (bind && check($obj, bind.keys, bind.single)) {
								$obj.keyboardfn = bind.fn;
								$obj.keyboardfn()
						}
				}
				$obj.keyboardfn = oldkbfn;
				return true;
		},
		keys : {
				// Alphabet
				a:65, b:66, c:67, d:68, e:69,
				f:70, g:71, h:72, i:73, j:74,
				k:75, l:76, m:77, n:78, o:79,
				p:80, q:81, r:82, s:83, t:84,
				u:85, v:86, w:87, x:88, y:89, z:90,
				// Numbers
				0:48, 1:49, 2:50, 3:51, 4:52,
				5:53, 6:54, 7:55, 8:56, 9:57,
				// Controls
				tab:   9, enter:13, shift: 16,
				ctrl: 17, alt:  18, esc:   27, space:32,
				// Arrows
				aLeft:37, aUp:  38, aRight:39, aDown:40
		}
};