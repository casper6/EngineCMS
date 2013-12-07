﻿/* Scrollyeah - v0.3.1 - 2013-08-23
   https://github.com/artpolikarpov/scrollyeah
   Copyright (c) 2013 Artem Polikarpov; Licensed MIT */
(function(e){function t(t){var a=t||window.event,n=[].slice.call(arguments,1),o=0,s=0,r=0;return t=e.event.fix(a),t.type="mousewheel",t.wheelDelta&&(o=t.wheelDelta/120),t.detail&&(o=-t.detail/3),r=o,void 0!==a.axis&&a.axis===a.HORIZONTAL_AXIS&&(r=0,s=-1*o),void 0!==a.wheelDeltaY&&(r=a.wheelDeltaY/120),void 0!==a.wheelDeltaX&&(s=-1*a.wheelDeltaX/120),n.unshift(t,o,s,r),e.event.dispatch.apply(this,n)}function a(e){for(var t={},a=0;f.length>a;a++){var n=f[a][0],o=f[a][1];if(e){var s=e.attr("data-"+n);s&&("number"==o?(s=Number(s),isNaN(s)||(t[n]=s)):"boolean"==o?"true"==s?t[n]=!0:(s="false")&&(t[n]=!1):"string"==o&&(t[n]=s))}else t[n]=f[a][2]}return t}function n(e){return{left:e}}function o(t,a){function o(e,t){var a=C-(C-L)/2;return(e-a)*t.data("parallax")}function s(){return!1}function f(e){clearTimeout(e.data("backAnimate"))}function v(e,o,s,r,i){var l=isNaN(o)?0:o;f(e),r&&(l=r,e.data({backAnimate:setTimeout(function(){v(e,o,Math.max(u,s/2),!1,i)},s)})),e.stop().animate(n(l),s,h),!i&&a.triggerScrollyeah&&(clearInterval(S),S=setInterval(function(){t.trigger("scrollyeah",M.position()[X])},25),clearTimeout(z),z=setTimeout(function(){clearInterval(S)},s+100))}function m(e,o,s){return f(o),o.stop(),e===!1&&(e=o.position()[X]),o.css(n(e)),s?void 0:(E=e,a.triggerScrollyeah&&t.trigger("scrollyeah",E),E)}function g(e){T>x?(I=!0,t.addClass("scrollyeah_active"),a.shadows&&(t.addClass("scrollyeah_shadow"),L>=e?t.removeClass("scrollyeah_shadow_no-left").addClass("scrollyeah_shadow_no-right"):e>=C?t.removeClass("scrollyeah_shadow_no-right").addClass("scrollyeah_shadow_no-left"):t.removeClass("scrollyeah_shadow_no-left scrollyeah_shadow_no-right"))):(a.disableIfFit?(I=!1,t.removeClass("scrollyeah_active")):(I=!0,t.addClass("scrollyeah_active")),a.shadows&&t.removeClass("scrollyeah_shadow"))}function w(){T=M.width(),x=t.width(),D=t.height(),C=a.centerIfFit?Math.max((x-T)/2,0):0,L=Math.min(-(T-x),C),M.data({maxPos:C,minPos:L}),L>E&&m(L,M),E>C&&m(C,M),k.each(function(){var t=e(this);m(o(E,t),t,!0)}),g(E)}function y(s,h,u){function c(t){function a(){x=(new Date).getTime(),b=y,p=_,F=[[x,y]],T=m(!1,M),k.each(function(){var t=e(this);m(o(E,t),t,!0)}),s()}if((r||2>t.which)&&I)if(r){if(r&&1==t.targetTouches.length)y=t.targetTouches[0][O],_=t.targetTouches[0][P],a(),M[0].addEventListener("touchmove",v,!1),M[0].addEventListener("touchend",w,!1);else if(r&&t.targetTouches.length>1)return!1}else y=t[O],t.preventDefault(),a(),d.bind("mousemove.scrollyeah",v),d.bind("mouseup.scrollyeah",w)}function f(s,r,i,l){return Math.abs(i)>Math.abs(l)?(s.preventDefault(),clearTimeout(z),A||(m(!1,M),k.each(function(){var t=e(this);m(o(E,t),t,!0)}),A=!0),z=setTimeout(function(){A=!1},100),E-=Math.round(25*i),L>E&&(E=L),E>C&&(E=C),M.css(n(E)),a.triggerScrollyeah&&t.trigger("scrollyeah",E),k.each(function(){var t=e(this);t.css(n(o(E,t)))}),g(E),!1):void 0}function v(s){function i(){s.preventDefault(),D=(new Date).getTime(),F.push([D,y]);var r=b-y;E=T-r,E>C?(E=Math.round(E+(C-E)/1.5),R="left"):L>E?(E=Math.round(E+(L-E)/1.5),R="right"):R=!1,M.css(n(E)),a.triggerScrollyeah&&t.trigger("scrollyeah",E),k.each(function(){var t=e(this);t.css(n(o(E,t)))}),h(E,r,R)}r?r&&1==s.targetTouches.length&&(y=s.targetTouches[0][O],_=s.targetTouches[0][P],j?Y&&i():(Math.abs(y-b)-Math.abs(_-p)>=-5&&(Y=!0,s.preventDefault()),j=!0)):(y=s[O],i())}function w(e){if(!r||!e.targetTouches.length){Y=!1,j=!1,r?(M[0].removeEventListener("touchmove",v,!1),M[0].removeEventListener("touchend",w,!1)):(d.unbind("mouseup.scrollyeah",w),d.unbind("mousemove.scrollyeah",v)),S=(new Date).getTime();var t,a,n,o,s=-E,h=S-l;for(i=0;F.length>i;i++)t=Math.abs(h-F[i][0]),0==i&&(a=t,n=S-F[i][0],o=F[i][1]),a>=t&&(a=t,n=F[i][0],o=F[i][1]);var c=o-y,f=c>=0,m=S-n,g=l>=m,_=S-X,b=f===N;u(s,m,g,_,b,c,e),X=S,N=f}}var y,_,b,p,T,x,D,N,S,z,A,F=[],X=0,Y=!1,j=!1,R=!1;r?M[0].addEventListener("touchstart",c,!1):M.mousedown(c).mousewheel(f)}function _(){}function b(a,n){clearTimeout(F),A||(A=!0,M.addClass("scrollyeah__shaft_grabbing")),Math.abs(n)>=5&&!Y&&(Y=!0,e("a",t).bind("click",s)),g(a)}function p(a,n,r,i,h,c){F=setTimeout(function(){Y=!1,e("a",t).unbind("click",s)},l),A=!1,M.removeClass("scrollyeah__shaft_grabbing"),a=-a;var d,f=a,m=2*u;if(a>C)f=C,m/=2;else if(L>a)f=L,m/=2;else if(r){c=-c;var w=c/n;f=Math.round(a+250*w);var y=.04;f>C?(d=Math.abs(f-C),m=Math.abs(m/(250*w/(Math.abs(250*w)-d*(1-y)))),f=C,d=f+d*y):L>f&&(d=Math.abs(f-L),m=Math.abs(m/(250*w/(Math.abs(250*w)-d*(1-y)))),f=L,d=f-d*y)}E=f,f!=a&&(v(M,f,m,d,!1),k.each(function(){var t=e(this);v(t,o(f,t),m,o(d,t),!0)}),g(f))}t.data({ini:!0}).wrapInner('<div class="scrollyeah__wrap"><div class="scrollyeah__shaft"></div></div>'),e(".scrollyeah__wrap",t).css({width:a.maxWidth});var M=e(".scrollyeah__shaft",t);a.shadows&&e('<i class="scrollyeah__shadow scrollyeah__shadow_prev"></i><i class="scrollyeah__shadow scrollyeah__shadow_next"></i>').appendTo(t);var T,x,D,C,L,E=0,I=!0,k=e(".scrollyeah__parallax",M),N=e(".scrollyeah__disable",M);k.each(function(){var t=e(this),a=Number(t.attr("data-parallaxRate"));t.data({parallax:a})});var S,z;w(),c.bind("resize load",w),r&&window.addEventListener("orientationchange",w,!1);var A,F,X="left",O="pageX",P="pageY",Y=!1;y(_,b,p),N.bind("click mousedown mouseup mousemove",function(e){e.stopPropagation()}).css({"-webkit-user-select":"auto","-moz-user-select":"auto","-o-user-select":"auto","-ms-user-select":"auto","user-select":"auto",cursor:"auto"})}e.extend({bez:function(t){var a="bez_"+e.makeArray(arguments).join("_").replace(".","p");if("function"!=typeof e.easing[a]){var n=function(e,t){var a=[null,null],n=[null,null],o=[null,null],s=function(s,r){return o[r]=3*e[r],n[r]=3*(t[r]-e[r])-o[r],a[r]=1-o[r]-n[r],s*(o[r]+s*(n[r]+s*a[r]))},r=function(e){return o[0]+e*(2*n[0]+3*a[0]*e)},i=function(e){for(var t,a=e,n=0;14>++n&&(t=s(a,0)-e,!(.001>Math.abs(t)));)a-=t/r(a);return a};return function(e){return s(i(e),1)}};e.easing[a]=function(e,a,o,s,r){return s*n([t[0],t[1]],[t[2],t[3]])(a/r)+o}}return a}});var s=["DOMMouseScroll","mousewheel"];e.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var e=s.length;e;)this.addEventListener(s[--e],t,!1);else this.onmousewheel=t},teardown:function(){if(this.removeEventListener)for(var e=s.length;e;)this.removeEventListener(s[--e],t,!1);else this.onmousewheel=null}},e.fn.extend({mousewheel:function(e){return e?this.bind("mousewheel",e):this.trigger("mousewheel")},unmousewheel:function(e){return this.unbind("mousewheel",e)}});var r="ontouchstart"in document,l=300,h=e.bez([.1,0,.25,1]),u=333,c=e(window),d=e(document),f=[["maxWidth","number",999999],["shadows","boolean",!0],["disableIfFit","boolean",!0],["centerIfFit","boolean",!1],["triggerScrollyeah","boolean",!1]];e.fn.scrollyeah=function(t){"undefined"==typeof scrollyeahDefaults&&(scrollyeahDefaults={});var n=e.extend(a(),e.extend({},scrollyeahDefaults,t));return this.each(function(){var t=e(this);t.data("ini")||o(t,n)}),this},e(function(){e(".scrollyeah").each(function(){var t=e(this);t.scrollyeah(a(t))})})})(jQuery);