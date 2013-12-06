jQuery && (!function(a) {
    a(function() {
        a.expr[":"].uncached = function(b) {
            if (!a(b).is('img[src!=""]'))
                return !1;
            var c = new Image;
            return c.src = b.src, !c.complete
        };
        var b = [], c = 500, d=!1, e = ["backgroundImage", "borderImage", "borderCornerImage", "listStyleImage", "cursor"], f = /url\(\s*(['"]?)(.*?)\1\s*\)/g;
        if (window.navigator && "preview" === window.navigator.loadPurpose)
            return a(".lightload").css("transition", "none"), a(".lightload").css("opacity", "1"), !1;
        var g = function(a, b) {
            var d = a.data("hold");
            if (d&&!a.data("_holding"))
                return a.data("_holding", !0), setTimeout(function() {
                g(a, !0)
            }, d), !1;
            if (a.data("_holding")&&!b)
                return !1;
            var e = a.data("_spinner");
            e && e.stop(), a.css("transition", "opacity " + c + "ms ease-out"), a.css("opacity", "1");
            var f = a.data("style-2");
            f && a.attr("style", a.attr("style") + "; " + f), a.data("_fired", !0), h()
        }, h = function(a) {
            a && b.push(a);
            for (var c in b) {
                var d = b[c];
                if (d.data("_fired"));
                else {
                    var e, f=!1;
                    if (e = d.data("_waitFor")) {
                        for (; ;) {
                            if (!e.data("_fired")) {
                                if (e[0].id == d[0].id) {
                                    f=!0;
                                    break
                                }
                                if (e = e.data("_waitFor"))
                                    continue
                            }
                            break
                        }(d.data("_waitFor").data("_fired") || f) && g(d)
                    } else 
                        g(d)
                    }
            }
        };
        a(".lightload").each(function() {
            var b = a(this), g=!1, i = {}, j=!1, k=!1, l = 0, m = 0, n = "", o = "", p = c, q = {};
            b.$prev = d;
            var r = function() {
                b.data("continue") && b.data("_waitFor", b.$prev), b.data("await") && b.data("_waitFor", a("#" + b.data("await"))), h(b)
            }, s = function() {
                m++, m == l && setTimeout(r, b.data("slow"))
            };
            if (b.data("opaque") && b.css("opacity", 1), q = b.data("effect") ||!1, p = b.data("duration") || c, q) {
                var t = {}, u = ["", "-webkit-"], v = "transform", w = "transform-origin", x = b.data("up") || 0, y = b.data("down") || 0, z = b.data("left") || 0, A = b.data("right") || 0, B = b.data("angle") || "0", C = b.data("scale")||-1, D = b.data("origin") || "50% 50%";
                if (y && (x = "-" + y, "--" == x.substr(0, 2) && (x = x.substr(2)))
                    , A && (z = "-" + A, "--" == z.substr(0, 2) && (z = z.substr(2))), "relax" == q && (-1 == C && (C = .92), "50% 50%" == D && (D = "top"), t = {
                    one: "scaleY(" + C + ")",
                    two: "scaleY(1)",
                    orn: D,
                    crv: "cubic-bezier(0, 0, 0.001, 1)"
                }), "slide" == q && (x || (x = "20px"), t = {
                    one : "translate(" + z + "," + x + ")", two : "translate(0,0)", crv : "cubic-bezier(0, 0.9, 0.1, 1)"
                }), "zoom" == q && (-1 == C && (C = .5), t = {
                    one : "scale(" + C + ")", two : "scale(1)", orn : D, crv : "cubic-bezier(0, 0.75, 0.25, 1)"
                }), "screw" == q && (-1 == C && (C = .5), B || (B = 90), t = {
                    one: "scale(" + C + ") rotate(" + B + "deg)",
                    two: "scale(1) rotate(0)",
                    orn: D,
                    crv: "cubic-bezier(0, 0.75, 0.25, 1)"
                }), t)for (var E = 0; E < u.length; ++E)
                    n += u[E] + v + ": " + t.one + "; " + u[E] + w + ": " + t.orn + "; ", o += u[E] + v + ": " + t.two + "; " + u[E] + "transition: opacity " + p + "ms ease-out, " + u[E] + v + " " + p + "ms " + t.crv + "; ";
                b.data("style-1", n), b.data("style-2", o)
            }
            if (n || (n = b.data("style-1")), n && b.attr("style", b.attr("style") + "; " + n), b.find("*").addBack().each(function() {
                var b = a(this);
                b.is("img:uncached") && b.attr("src") && (i[b.attr("src")]=!0);
                for (var c = 0; c < e.length; ++c) {
                    var d, g = e[c], h = b.css(g), j =- 1;
                    if (h && (j = h.indexOf("url(")) >= 0)
                        for (; null !== (d = f.exec(h));)
                            i[d[2]]=!0
                }
            }), Object.keys(i).length > 0 && (j = b.data("spin")))try {
                k = new Spinner({
                    lines: 12,
                    length: 4,
                    width: 2,
                    radius: 8,
                    corners: 0,
                    rotate: 0,
                    color: "rgba(96, 96, 96, .75)",
                    hwaccel: !0
                }), g = a("<div />"), g.css({
                    position: "absolute",
                    width: b.width(),
                    height: Math.min(b.height(), document.body.clientHeight - b.offset().top)
                }), b.before(g), k.spin(g[0]), b.data("_spinner", k)
            } catch (F) {}
            for (var E in i) {
                var G = new Image;
                G.src = E, l++, G.width > 0 ? s() : a(G).on("load error", s)
            }
            l++, s(), d = b
        })
    })
}(jQuery), document.write("<style>.lightload { opacity: 0; }</style>"));
