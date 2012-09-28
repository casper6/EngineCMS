/* Misc functions */
function absPosition(obj) {
    var ox=0,oy=0;
    while (obj) {
        ox += obj.offsetLeft;
        oy += obj.offsetTop;
        if (obj.offsetParent == null) obj = (obj.nodeName=="BODY")?null:obj.parentNode;
        if (obj!=null) obj = obj.offsetParent;
    }
    return {x:ox,y:oy};
}
function str_split_digit(str,d) {
	var p=[], s=str.length;
	for (var x=0;(s-x)>=1;x+=d)
		p.push(str.substring(x,(x+d)));
	return p;
}
function RGB2HEX(r,g,b) {
	var cv = function(d) {
		var c = "0123456789ABCDEF";
		return String(c.charAt(Math.floor(d/16)))+String(c.charAt(d-(Math.floor(d/16)*16)));
	};
	return cv(r)+cv(g)+cv(b);
}
function addHandler(o,e,h) {
    if(typeof o.addEventListener!='undefined')
        o.addEventListener(e,h,false);
    else if(typeof o.attachEvent!='undefined')
        o.attachEvent('on'+e,h);
    else
        throw "Incompatible browser";
}
function preventDefault(e) {
    e = e || window.event;
    (typeof e.preventDefault!="undefined")?e.preventDefault():e.returnValue=false;
}
function set_query(oElem) {oElem.href += document.MSearch.q.value; return 0;}
function CheckQ(oForm) {if (oForm.q.value.length != 0) { return true; } else { document.location.href=""; return false;}};
function changeMenu(id) {
	var divmenu=new Array('fff','sss');
	for (i=0;i<divmenu.length;i++) {
		myid=divmenu[i]+'c';
		if (document.getElementById(divmenu[i])){
        	if (divmenu[i] == id){
				document.getElementById(divmenu[i]).className='on';
				document.getElementById(myid).style.display='';
			} else {
				document.getElementById(divmenu[i]).className='noon';
				document.getElementById(myid).style.display='none';
			}
		}
	}
}
function softColor(startColor, stopColor, callback, speed, currentColor, end, it) {
	if (!it&&!end) {
		end = 100*speed;
		it = 0;
	}
	var x,y,z;
	var rgb = [];
	var startColorRGB = str_split_digit(startColor, 2);
	var stopColorRGB = str_split_digit(stopColor, 2);
	var newColor;
	if (currentColor) {
		var currentColorRGB = str_split_digit(currentColor, 2);
	}
	for (var i=0;i<startColorRGB.length&&i<stopColorRGB.length;i++) {
		x = parseInt(startColorRGB[i],16);
		y = parseInt(stopColorRGB[i],16);
		z = Math.round((currentColor?(parseInt(currentColorRGB[i],16)):x)-((x-y)*speed));
		(z>255)?z=255:(z<0)?z=0:null;
		rgb.push(z);
	}
	newColor = RGB2HEX.apply(null,rgb);
	var a = parseInt(startColor,16);
	var b = parseInt(stopColor,16);
	var c = parseInt(newColor,16);
	var _stop = false;
	if (((a>b&&c<=b)||(a<=b&&c>=b))||it>=end) {
		_stop = true;
		newColor=stopColor;
	}
	callback.apply(null,[newColor,_stop]);
	if (_stop) return;
	var t = arguments.callee;
	setTimeout(function(){
		t.apply(null,[startColor,stopColor,callback,speed,newColor,end,(++it)]);
	},70);
	return;
}
function fieldPasteFocusLight(field,str) {
	if (typeof field == "string")
		field = document.getElementById(field);
	if (!field)
		return false;
	field.focus();
	field.value = str;
	if (field.getAttribute("operation") == "true") return false;
	softColor("ffff66","FFFFFF",function(c, _stop){
		if (_stop) {
			field.setAttribute("operation","false");
		}
		field.style.background="#"+c;
	},0.1);
	field.setAttribute("operation","true");
	var form = document.forms.MSearch;
	form.setAttribute("action","");
	return true;
}
/* BEGIN Other AJAX Class */
function AJAX(url, args, keyHash, method, timeout, bCached, callbackFunc, callbackPhase) {
    this.url = url;
    this.args = args;
    this.keyHash = keyHash;
    this.method = method.toLowerCase();
    this.timeout = timeout;
    this.timeoutID = null;
    this.bCached = bCached;
    this.callback = callbackFunc;
    this.callbackPhase = callbackPhase.toLowerCase();
    this.request = new this.XHR();
}
AJAX.prototype = {
    XHR : function() {
        var req = false;
        if (window.XMLHttpRequest) {
            try {req = new XMLHttpRequest();}
            catch (e){}
        } else if (window.ActiveXObject) {
            try {req = new ActiveXObject('Msxml2.XMLHTTP');}
            catch (e) {
                try {req = new ActiveXObject('Microsoft.XMLHTTP');}
                catch (e){alert("Ваш браузер не поддерживает AJAX!");}
            }
        }
        return req;
    },
    send : function() {
        if (!this.request) return false;
        var correctURL=this.url, correctArgs = "";
        if (this.args && this.args.length > 0) {
            correctArgs += ((this.method=="get")?"?":"")+this.args;
        }
        if (this.keyHash) {
            correctArgs += ((correctArgs.length>0)?"&":"?")+"key="+this.keyHash;
        }
        if (this.method == "get") {
            correctURL += correctArgs;
            correctArgs = null;
        }
        var t = this;
        if (t.timeout) {
            t.timeoutID = setTimeout(function(){
                //alert("AJAX Request Abort");
                t.request.abort();
            },t.timeout);
        }
        this.request.onreadystatechange = function(){
            var a = t;
            var params = a;
            var complete = false;
            try {
                if (a.request.readyState == 4) {
                    complete = true;
                }
                switch (a.callbackPhase) {
                case "complete":
                    if (a.request.readyState == 4) {
                        if (a.request.status == 200) {
                            a.callback(a.request.responseText);
                        } else {
                            a.callback(a, true);
                        }
                    }
                break;
                case "process":
                    a.callback(a.request);
                break;
                }
            }
            catch (e) {complete = true;}
            if (complete) {
                a.timeout?clearTimeout(a.timeoutID):null;
            }
        };
        this.request.open(this.method, correctURL, true);
        (this.method == "post")?this.request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"):null;
        (!this.bCached)?this.request.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 1970 00:00:00 GMT"):null;
        this.request.send(correctArgs);
        return true;
    }
}
/* END Other AJAX Class */
/* BEGIN SUGGEST */
function Suggest(c) {
	this.addObjectToThis = function(obj) {
		for (var x in obj) {
			if (typeof this[x] != "undefined") {
				//alert("Reserver var \"" + x + "\".\nOld value to be rewrite.");
			}
			this[x] = obj[x];
		}
	};
	/* Flags */
	this._operOff = false;
	this._operIgnored = false;
	this._operTimeout = false;
	this._operRequest = false;
	/* Timeout */
	this.sReqDelay = 200;
	this.sReqTimeout = false;
	/* Cache */
	this.cache = {};
	this.cacheCounter = 0;
	this.cacheLimit = 100;
	this.cacheResponses = {};
	
	this.addObjectToThis(c);
	
	/* URL */
	this.sReqUrl = null;
	this.sReqHS = this.sReqUrlHost + this.sReqUrlScript;
	if (!this.sReqMethod) this.sReqMethod = "GET";
	this.fieldCurrentValue = null;
	this.fieldCurrentValueEncode = null;
	this.fieldCurrentValueLength = null;
	this.fieldPrevValue = null;
	this.fieldPrevValueEncode = null;
	this.fieldPrevValueLength = null;
	/* AJAX Response */
	this.response = null;
	/* Suggest */
	this.suggestMouseOver = false;
	this.suggestArea = null;
	this.suggestAreaHTML = null;
	this.suggestBlock = null;
	this.suggestOpen = false;
	this.suggestItems = null;
	this.suggestItemSelect = null;
	this.selectArrowPause = false;
	this.selectArrowPauseLength = 80;
	
	this.field.setAttribute("autocomplete","off");
	this.field.focus();
	this.setFieldEvents();
	this.setFormSubmitHandler();
}
Suggest.prototype = {
	cacheCheck : function() {
		if ((this.cache[this.fieldCurrentValueEncode] || this.cache[this.fieldCurrentValueEncode] === null) && this.cacheResponses[this.fieldCurrentValueEncode]) {
			return true;
		}
		return false;
	},
	cacheClean : function() {
		this.cache = {};
		this.cacheCounter = 0;
		this.cacheResponses = {};
	},
	cacheWrite : function() {
		this.cache[this.fieldCurrentValueEncode] = (this.response=="OK")?this.suggestAreaHTML:null;
		this.cacheResponses[this.fieldCurrentValueEncode] = this.response;
		this.cacheCounter++;
	},
	compareValues : function() {
		if (this.fieldPrevValueEncode !== encodeURIComponent(this.field.value.toLowerCase())) {
			this.getFieldValue();
			return false;
		}
		return true;
	},
	defineReqUrlParam : function() {
		var coolParam = "";
		coolParam = "q="+this.fieldCurrentValueEncode+"&cp="+this.fieldCurrentValueLength;
		return coolParam;
	},
	destroyThis : function() {
		this._operOff = true;
	},
	fieldFocusMoveToEnd : function() {
		if (this.field.createTextRange) {
			var c=0,b=0;
			var d=this.field.createTextRange(),e=document.selection.createRange();
			if(d.inRange(e)){
				d.setEndPoint("EndToStart",e);
				b=d.text.length;
				d.setEndPoint("EndToEnd",e);
				c=d.text.length;
			}
		} else if (this.field.selectionStart) {
			var end = this.field.value.length;
			this.field.setSelectionRange(end,end);
		}
		this.field.focus();
	},
	fieldValueCheck : function() {
		var r = /^[ \s]*$/i;
		r = r.test(this.field.value);
		return r?false:true;
	},
	formSubmit : function() {
		this.suggestAreaDestroy();
		if (this.formSubmitFunc) {
			this.formSubmitFunc.apply(this.form,this.formSubmitFuncAttr);
		} else {
			this.form.submit();
		}
	},
	getFieldValue : function() {
		this.fieldCurrentValue = this.field.value;
		this.fieldCurrentValueLength = this.fieldCurrentValue.length;
		this.fieldCurrentValueEncode = encodeURIComponent(this.fieldCurrentValue.toLowerCase());
	},
	getResultInCache : function() {
		if (this.cacheResponses[this.fieldCurrentValueEncode] == "OK") {
			this.suggestAreaCreate(this.cache[this.fieldCurrentValueEncode],true);	
		} else {
			if (this.suggestOpen) {
				this.suggestAreaDestroy();
			}	
		}
		if (this.cacheCounter > this.cacheLimit) {
			this.cacheClean();
		}
	},
	keyEvents : function(keyCode) {
		switch (keyCode) {
		case 13:
			if (this.suggestOpen && this.suggestItemSelect !== null) {
				this.field.value = this.suggestItems[this.suggestItemSelect].firstChild.firstChild.innerHTML;
			}
		break;
		case 27:
			if (this.suggestOpen) {
				if (this.suggestItemSelect !== null) {
					this.field.value = this.fieldPrevValue;
				}
				this.suggestAreaDestroy();
			}
		break;
		case 38:
			if (this.suggestOpen) {
				this.suggestItemArrowSelect("up");
			}
		break;
		case 40:
			if (this.suggestOpen) {
				this.suggestItemArrowSelect("down");
			} else {
				this.getResultInCache();
			}
		break;
		}
		return false;
	},
	keyFilter : function(keyCode) {
		switch (keyCode) {
			case null:
			case 9:
			case 13:
			case 16:
			case 17:
			case 18:
			case 27:
			case 38:
			case 40:
				return false;
			break;
			default:
				return true;
			break;
		}
	},
	responseHandler : function(res,err) {
		if (err) {
			this._operRequest = false;
			return false;
		}
		res = eval("("+res+")");
		
		if (res.AJAXResponse == "OK" && res.response.length == 1 && res.response[0].query == this.fieldCurrentValue.toLowerCase()) {
			res.AJAXResponse = "ERR-NO_RESPONSE";
		}
		
		this.response = res.AJAXResponse;
		if (res.AJAXResponse == "OK") {
			this.suggestAreaCreate(res.response);
		} else {
			if (this.suggestOpen) {
				this.suggestAreaDestroy();
			}
		}
		this.cacheWrite();
		if (!this.compareValues() && this.fieldCurrentValueLength != 0) {
			if (this.cacheCheck()) {
				this.getResultInCache();
				this.setPrevFieldValue();
			} else {
				this.setOperTimeout();
				return false;
			}
		}
		
		this.setPrevFieldValue();
		
		this._operRequest = false;
		return true;
	},
	sendRequest : function() {
		var t = this;
		var req = new AJAX(this.sReqHS,this.defineReqUrlParam(),false,this.sReqMethod,false,false,function(response, error){t.responseHandler.apply(t,[response,error]);},"complete");
		if (req.send()) {
			this._operRequest = true;
			clearTimeout(this._operTimeout);
			this._operTimeout = false;
		}
	},
	setFieldEvents : function() {
		var t = this;
		var f = function (e) {
			e = e || window.event;
			t.switcher.call(t,e);
		};
		addHandler(this.field,"keydown",f);
		addHandler(this.field,"keyup",f);
		addHandler(this.field,"blur",function(){
			if (t.suggestOpen && !t.suggestMouseOver) {
				if (t.suggestItemSelect !== null) {
					t.field.value = t.fieldPrevValue;
				}
				t.suggestAreaDestroy.call(t);
			}
		});
		addHandler(document.body,"click",function(){(t.suggestOpen && !t.suggestMouseOver)?t.suggestAreaDestroy.call(t):null;});
	},
	setFormSubmitHandler : function() {
		if (this.formSubmitFunc) {
			var t = this;
			addHandler(this.form,"submit",function(e){
				e = e || window.event;
				preventDefault(e);
				t.formSubmit.call(t);
			});
			return true;
		}
		return false;
	},
	setIgnored : function(value) {
		var old_value = this._operIgnored;
		this._operIgnored = value;
		return old_value;
	},
	setOperTimeout : function() {
		var t = this;
		this._operTimeout = setTimeout(function(){
			t.sendRequest.call(t);
			t.setPrevFieldValue.call(t);
		},this.sReqDelay);
	},
	setPrevFieldValue : function() {
		this.fieldPrevValue = this.fieldCurrentValue;
		this.fieldPrevValueEncode = this.fieldCurrentValueEncode;
		this.fieldPrevValueLength = this.fieldCurrentValueLength;
	},
	suggestBlockPosCorrect : function(obj) {
		var w = this.field.offsetWidth;
		var h = this.field.offsetHeight;
		var pos = absPosition(this.field);
		var x = pos.x;
		var y = pos.y;
		obj.style.left = x + "px";
		obj.style.top = (y+h) + "px";
		obj.style.width = 350 + "px";
		return obj;
	},
	suggestAreaCreate : function(res, cache) {
		if (!this.suggestBlockID) return;
		this.suggestCleanItemCounters();
		var sb,i;
		var ie5 = (navigator.userAgent.toLowerCase().indexOf("msie 5") != -1)?true:false;
		var ie6 = (navigator.userAgent.toLowerCase().indexOf("msie 6") != -1)?true:false;
		if (!document.getElementById(this.suggestBlockID) && this.suggestBlock === null) {
			var t = this;
			var f = function(e, click) {
				e = e || window.event;
				var obj = e.target || e.srcElement;
				while (obj) {
					if (obj.id == t.suggestBlockID) {
						return;
					}
					if (click && obj.nodeName == "A" && obj.className == "sClose") {
						t.suggestAreaDestroy();
						t.destroyThis();
						return;
					} else if (obj.nodeName == "TR") {
						break;
					} else {
						obj = obj.parentNode;
					}
				}
				var arg = (click)?[obj,true]:[obj];
				t.suggestItemMouseSelect.apply(t,arg);
			};
			var wrp = document.createElement("div");
			var inr = document.createElement("div");
			var sar = document.createElement("div");
			var clsB = document.createElement("div");
			var clsL = document.createElement("a");
			var crnTR = document.createElement("b");
			var crnBL = document.createElement("b");
			var crnBR = document.createElement("b");
			var brdR = document.createElement("b");
			var brdB = document.createElement("b");
			crnTR.className = "sCrnTR";
			crnBL.className = "sCrnBL";
			crnBR.className = "sCrnBR";
			brdR.className = "sBrdR";
			brdB.className = "sBrdB";
			this.suggestBlock = document.createElement("div");
			this.suggestBlock.id = this.suggestBlockID;
			this.suggestBlock.style.position = "absolute";
			this.suggestBlockPosCorrect(this.suggestBlock);
			wrp.className = "sWrap";
			inr.className = "sInnr";
			sar.id = "g"+"ogo"+"S"+"ug"+"ges"+"tA"+"r"+"ea"+"_"+Math.round(Math.random()*1000000);
			sar.setAttribute("copyright","GoGo.Ru");
			clsL.className = "sClose";
			clsL.innerHTML = "закрыть";
			clsB.className = "sClose";
			clsB.appendChild(clsL);
			wrp.appendChild(inr);
			inr.appendChild(sar);
			inr.appendChild(clsB);
			wrp.appendChild(brdR);
			wrp.appendChild(brdB);
			wrp.appendChild(crnTR);
			wrp.appendChild(crnBL);
			wrp.appendChild(crnBR);
			this.suggestArea = sar;
			this.suggestBlock.appendChild(wrp);
			this.suggestBlock.appendChild(brdR);
			this.suggestBlock.appendChild(brdB);
			this.suggestBlock.appendChild(crnTR);
			this.suggestBlock.appendChild(crnBL);
			this.suggestBlock.appendChild(crnBR);
			sb = this.suggestBlock;
			addHandler(window,"resize",function(){t.suggestBlockPosCorrect.call(t,sb)});
			addHandler(this.suggestBlock,"mouseover",function(e){f(e);t.suggestMouseOver=true;});
			addHandler(this.suggestBlock,"mouseout",function(){t.suggestMouseOver=false;});

			addHandler(this.suggestBlock,"click",function(e){f(e,true);});
			addHandler(this.suggestBlock,"contextmenu",function(e){preventDefault(e);f(e,true);});
			document.body.appendChild(this.suggestBlock);
		}
		if (cache) {
			this.suggestArea.innerHTML = res;
		} else {
			if (this.suggestArea.firstChild) {
				this.suggestArea.innerHTML = "";	
			}
			this.suggestArea.appendChild(this.suggestAreaItemCreate(res));
			this.suggestAreaHTML = this.suggestArea.innerHTML;
		}
		if (ie6) this.suggestBlock.style.visibility = "hidden";
		this.suggestBlock.style.visibility = "";
		this.suggestItems = this.suggestArea.getElementsByTagName("tbody")[0].childNodes;
		this.suggestOpen = true;
		if (!this.fieldValueCheck()) {
			this.suggestAreaDestroy();
		}
	},
	suggestAreaDestroy : function() {
		if (this.suggestArea === null) return;
		this.suggestCleanItemCounters();
		this.suggestBlock.style.visibility = "hidden";
		this.suggestOpen = false;
	},
	suggestCleanItemCounters : function() {
		this.suggestItems = this.suggestItemSelect = null;
	},
	suggestAreaItemCreate : function(res) {
		var table, tbody, tr, td1, td1d;
		try {
		table = document.createElement("table");
		tbody = document.createElement("tbody");
		for (i=0; i<res.length; i++) {
			tr = document.createElement("tr");
			tr.className = "rn";
			td1 = document.createElement("td");
			td1d = document.createElement("div");
			td1d.innerHTML = res[i].query;
			td1.appendChild(td1d);
			tr.appendChild(td1);
			tbody.appendChild(tr);
			td1.className = "squery";
		}
		table.appendChild(tbody);
		table.className = "sList";
		} catch (e) {alert(e);}
		return table;
	},
	suggestItemArrowSelect : function(arr) {
		if (!this.suggestItems) return;
		if (this.selectArrowPause) return;
		var n = this.suggestItems;
		var nl = n.length - 1;
		var v,t=this;
		if (this.suggestItemSelect === null) {
			if (arr == "down") {
				this.suggestItemSelect = 0;
			} else {
				this.suggestItemSelect = nl;
			}
		} else {
			n[this.suggestItemSelect].className = "rn";
			if (arr == "down") {
				this.suggestItemSelect++;
			} else {
				this.suggestItemSelect--;
			}
		}
		if (this.suggestItemSelect < 0 || this.suggestItemSelect > nl) {
			this.suggestItemSelect = null;
			v = this.fieldPrevValue;
		} else {
			n[this.suggestItemSelect].className = "ra";
			v = n[this.suggestItemSelect].firstChild.firstChild.innerHTML;
		}
		this.field.value = v;
		this.selectArrowPause = true;
		setTimeout(function(){
			t.selectArrowPause = false;
		},this.selectArrowPauseLength);
		setTimeout(function(){
			t.fieldFocusMoveToEnd.call(t);
		},10);
		return arr;
	},
	suggestItemMouseSelect : function(obj,click) {
		if (click) {
			var value = obj.firstChild.firstChild.innerHTML;
			this.field.value = value;
			this.formSubmit();
			return;
		}
		if (!this.suggestItems) return;
		var n = this.suggestItems;
		var nl = n.length - 1;
		if (this.suggestItemSelect !== null) {
			n[this.suggestItemSelect].className = "rn";
		}
		obj.className = "ra";
		for (var i=0;i<=nl;i++) {
			if (n[i].className == "ra") {
				this.suggestItemSelect = i;
				break;
			}
		}
	},
	switcher : function(e) {
		if (this._operOff) return false;
		var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : null;
		if (this._operIgnored) return false;
		if (this._operTimeout) return false;
		if (this._operRequest) return false;
		if (!this.keyFilter(keyCode)) {
			if (e.type == "keydown") {
				this.keyEvents(keyCode);
			}
			return false;
		}
		this.getFieldValue();
		if (!this.fieldValueCheck()) {
			if (this.suggestOpen) {
				this.suggestAreaDestroy();
			}
		} else {
			if (this.cacheCheck()) {
				this.getResultInCache();
				this.setPrevFieldValue();
			} else {
				this.setOperTimeout();	
			}
		}
	}
}
/* END SUGGEST */

/*
 * (c)2008 DriverX
 */
