!(function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? (module.exports = e()) : "function" == typeof define && define.amd ? define(e) : ((t = t || self).i18nextXHRBackend = e());
})(this, function () {
    "use strict";
    function i(t, e) {
        for (var n = 0; n < e.length; n++) {
            var o = e[n];
            (o.enumerable = o.enumerable || !1), (o.configurable = !0), "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o);
        }
    }
    var t = [],
        r = t.forEach,
        a = t.slice;
    function e(t) {
        return (e =
            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                ? function (t) {
                    return typeof t;
                }
                : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t;
                })(t);
    }
    function l(t) {
        return (l =
            "function" == typeof Symbol && "symbol" === e(Symbol.iterator)
                ? function (t) {
                    return e(t);
                }
                : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : e(t);
                })(t);
    }
    function u(t, e) {
        if (e && "object" === l(e)) {
            var n,
                o = "",
                i = encodeURIComponent;
            for (n in e) o += "&" + i(n) + "=" + i(e[n]);
            if (!o) return t;
            t = t + (-1 !== t.indexOf("?") ? "&" : "?") + o.slice(1);
        }
        return t;
    }
    function n(t, e, n, o, i) {
        console.log(o);
        o && "object" === l(o) && (i || (o._t = new Date()), (o = u("", o).slice(1))), e.queryStringParams && (t = u(t, e.queryStringParams));
        try {
            var r = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("MSXML2.XMLHTTP.3.0");
            r.open(o ? "POST" : "GET", t, 1),
            e.crossDomain || r.setRequestHeader("X-Requested-With", "XMLHttpRequest"),
                (r.withCredentials = !!e.withCredentials),
            o && r.setRequestHeader("Content-type", "application/x-www-form-urlencoded"),
            r.overrideMimeType && r.overrideMimeType("application/json");
            var a = e.customHeaders;
            if ((a = "function" == typeof a ? a() : a)) for (var s in a) r.setRequestHeader(s, a[s]);
            (r.onreadystatechange = function () {
                3 < r.readyState && n && n(r.responseText, r);
            }),
                r.send(o);
        } catch (t) {
            console && console.log(t);
        }
    }
    function s() {
        return {
            loadPath: "/locales/{{lng}}/{{ns}}.json",
            addPath: "/locales/add/{{lng}}/{{ns}}",
            allowMultiLoading: !1,
            parse: JSON.parse,
            parsePayload: function (t, e, n) {
                return (o = n || ""), (n = e) in (e = {}) ? Object.defineProperty(e, n, { value: o, enumerable: !0, configurable: !0, writable: !0 }) : (e[n] = o), e;
                var o;
            },
            crossDomain: !1,
            ajax: n,
        };
    }
    t = (function () {
        function n(t) {
            var e = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : {};
            !(function (t, e) {
                if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function");
            })(this, n),
                this.init(t, e),
                (this.type = "backend");
        }
        var t, e, o;
        return (
            (t = n),
            (e = [
                {
                    key: "init",
                    value: function (t) {
                        var e = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : {};
                        (this.services = t),
                            (this.options = (function (n) {
                                return (
                                    r.call(a.call(arguments, 1), function (t) {
                                        if (t) for (var e in t) void 0 === n[e] && (n[e] = t[e]);
                                    }),
                                        n
                                );
                            })(e, this.options || {}, s()));
                    },
                },
                {
                    key: "readMulti",
                    value: function (t, e, n) {
                        var o = this.options.loadPath;
                        "function" == typeof this.options.loadPath && (o = this.options.loadPath(t, e));
                        e = this.services.interpolator.interpolate(o, { lng: t.join("+"), ns: e.join("+") });
                        this.loadUrl(e, n);
                    },
                },
                {
                    key: "read",
                    value: function (t, e, n) {
                        var o = this.options.loadPath;
                        "function" == typeof this.options.loadPath && (o = this.options.loadPath([t], [e]));
                        e = this.services.interpolator.interpolate(o, { lng: t, ns: e });
                        this.loadUrl(e, n);
                    },
                },
                {
                    key: "loadUrl",
                    value: function (i, r) {
                        var a = this;
                        this.options.ajax(i, this.options, function (t, e) {
                            if (500 <= e.status && e.status < 600) return r("failed loading " + i, !0);
                            if (400 <= e.status && e.status < 500) return r("failed loading " + i, !1);
                            var n, o;
                            try {
                                n = a.options.parse(t, i);
                            } catch (t) {
                                o = "failed parsing " + i + " to json";
                            }
                            if (o) return r(o, !1);
                            r(null, n);
                        });
                    },
                },
                {
                    key: "create",
                    value: function (t, e, n, o) {
                        var i = this;
                        "string" == typeof t && (t = [t]);
                        var r = this.options.parsePayload(e, n, o);
                        t.forEach(function (t) {
                            t = i.services.interpolator.interpolate(i.options.addPath, { lng: t, ns: e });
                            i.options.ajax(t, i.options, function (t, e) {}, r);
                        });
                    },
                },
            ]) && i(t.prototype, e),
            o && i(t, o),
                n
        );
    })();
    return (t.type = "backend"), t;
});
