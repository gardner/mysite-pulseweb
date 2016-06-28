/**
 * Created with JetBrains PhpStorm.
 * User: Julian Warren
 * Date: 2/10/13
 * Time: 2:30 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * AddressFinder
 * Copyright 2012 Abletech
 *
 */
(function () {
    var e;
    (e = window.AF) == null && (window.AF = {}), AF.TOKEN = "aaddcfa38b26e65330108190dd642a79f2ce04b738a68077dd28f15eb81d9f237ccbdea9b982a9d1ef09ecbb7eb5d67b463a96a36390aaf9325db2d93cf52a0z", AF.SERVER = "" + document.location.protocol + "//www.addressfinder.co.nz", AF.CSS = "/assets/v2.css"
}).call(this), function () {
    var _ref;
    (_ref = window.AF) == null && (window.AF = {}), AF.objParams = function (e) {
        var t, n, r;
        return n = function () {
            var n;
            n = [];
            for (t in e)r = e[t], n.push("" + t + "=" + r);
            return n
        }(), n.join("&")
    }, AF.executeCallback = function () {
        var afScript, callback_name, regex, script, scripts, _i, _len;
        scripts = document.getElementsByTagName("script"), regex = /\/assets\/v2\/widget/gi;
        for (_i = 0, _len = scripts.length; _i < _len; _i++) {
            script = scripts[_i];
            if (regex.test(script.src)) {
                afScript = script;
                break
            }
        }
        if (afScript != null) {
            callback_name = afScript.getAttribute("data-callback");
            if (callback_name != null)return eval("window." + callback_name + "()")
        }
    }
}.call(this);
var JSON;
JSON || (JSON = {}), function () {
    "use strict";
    function f(e) {
        return e < 10 ? "0" + e : e
    }

    function quote(e) {
        return escapable.lastIndex = 0, escapable.test(e) ? '"' + e.replace(escapable, function (e) {
            var t = meta[e];
            return typeof t == "string" ? t : "\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + e + '"'
    }

    function str(e, t) {
        var n, r, i, s, o = gap, u, a = t[e];
        a && typeof a == "object" && typeof a.toJSON == "function" && (a = a.toJSON(e)), typeof rep == "function" && (a = rep.call(t, e, a));
        switch (typeof a) {
            case"string":
                return quote(a);
            case"number":
                return isFinite(a) ? String(a) : "null";
            case"boolean":
            case"null":
                return String(a);
            case"object":
                if (!a)return"null";
                gap += indent, u = [];
                if (Object.prototype.toString.apply(a) === "[object Array]") {
                    s = a.length;
                    for (n = 0; n < s; n += 1)u[n] = str(n, a) || "null";
                    return i = u.length === 0 ? "[]" : gap ? "[\n" + gap + u.join(",\n" + gap) + "\n" + o + "]" : "[" + u.join(",") + "]", gap = o, i
                }
                if (rep && typeof rep == "object") {
                    s = rep.length;
                    for (n = 0; n < s; n += 1)typeof rep[n] == "string" && (r = rep[n], i = str(r, a), i && u.push(quote(r) + (gap ? ": " : ":") + i))
                } else for (r in a)Object.prototype.hasOwnProperty.call(a, r) && (i = str(r, a), i && u.push(quote(r) + (gap ? ": " : ":") + i));
                return i = u.length === 0 ? "{}" : gap ? "{\n" + gap + u.join(",\n" + gap) + "\n" + o + "}" : "{" + u.join(",") + "}", gap = o, i
        }
    }

    typeof Date.prototype.toJSON != "function" && (Date.prototype.toJSON = function (e) {
        return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null
    }, String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function (e) {
        return this.valueOf()
    });
    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, gap, indent, meta = {"\b": "\\b", "	": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", '"': '\\"', "\\": "\\\\"}, rep;
    typeof JSON.stringify != "function" && (JSON.stringify = function (e, t, n) {
        var r;
        gap = "", indent = "";
        if (typeof n == "number")for (r = 0; r < n; r += 1)indent += " "; else typeof n == "string" && (indent = n);
        rep = t;
        if (!t || typeof t == "function" || typeof t == "object" && typeof t.length == "number")return str("", {"": e});
        throw new Error("JSON.stringify")
    }), typeof JSON.parse != "function" && (JSON.parse = function (text, reviver) {
        function walk(e, t) {
            var n, r, i = e[t];
            if (i && typeof i == "object")for (n in i)Object.prototype.hasOwnProperty.call(i, n) && (r = walk(i, n), r !== undefined ? i[n] = r : delete i[n]);
            return reviver.call(e, t, i)
        }

        var j;
        text = String(text), cx.lastIndex = 0, cx.test(text) && (text = text.replace(cx, function (e) {
            return"\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice(-4)
        }));
        if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, "")))return j = eval("(" + text + ")"), typeof reviver == "function" ? walk({"": j}, "") : j;
        throw new SyntaxError("JSON.parse")
    })
}(), !function (e, t) {
    typeof module != "undefined" ? module.exports = t() : typeof define == "function" && define.amd ? define(e, t) : this[e] = t()
}("reqwest", function () {
    function handleReadyState(e, t, n) {
        return function () {
            e && e[readyState] == 4 && (twoHundo.test(e.status) ? t(e) : n(e))
        }
    }

    function setHeaders(e, t) {
        var n = t.headers || {}, r;
        n.Accept = n.Accept || defaultHeaders.accept[t.type] || defaultHeaders.accept["*"], !t.crossOrigin && !n[requestedWith] && (n[requestedWith] = defaultHeaders.requestedWith), n[contentType] || (n[contentType] = t.contentType || defaultHeaders.contentType);
        for (r in n)n.hasOwnProperty(r) && e.setRequestHeader(r, n[r])
    }

    function generalCallback(e) {
        lastValue = e
    }

    function urlappend(e, t) {
        return e + (/\?/.test(e) ? "&" : "?") + t
    }

    function handleJsonp(e, t, n, r) {
        var i = uniqid++, s = e.jsonpCallback || "callback", o = e.jsonpCallbackName || "reqwest_" + i, u = new RegExp("((^|\\?|&)" + s + ")=([^&]+)"), a = r.match(u), f = doc.createElement("script"), l = 0;
        a ? a[3] === "?" ? r = r.replace(u, "$1=" + o) : o = a[3] : r = urlappend(r, s + "=" + o), win[o] = generalCallback, f.type = "text/javascript", f.src = r, f.async = !0, f.onload = f.onreadystatechange = function () {
            if (f[readyState] && f[readyState] !== "complete" && f[readyState] !== "loaded" || l)return!1;
            f.onload = f.onreadystatechange = null, e.success && e.success(lastValue), lastValue = undefined, head.removeChild(f), l = 1
        }, head.appendChild(f)
    }

    function getRequest(e, t, n) {
        var r = (e.method || "GET").toUpperCase(), i = typeof e == "string" ? e : e.url, s = e.processData !== !1 && e.data && typeof e.data != "string" ? reqwest.toQueryString(e.data) : e.data || null, o;
        return(e.type == "jsonp" || r == "GET") && s && (i = urlappend(i, s), s = null), e.type == "jsonp" ? handleJsonp(e, t, n, i) : (o = xhr(), o.open(r, i, !0), setHeaders(o, e), o.onreadystatechange = handleReadyState(o, t, n), e.before && e.before(o), o.send(s), o)
    }

    function Reqwest(e, t) {
        this.o = e, this.fn = t, init.apply(this, arguments)
    }

    function setType(e) {
        var t = e.match(/\.(json|jsonp|html|xml)(\?|$)/);
        return t ? t[1] : "js"
    }

    function init(o, fn) {
        function complete(e) {
            o.timeout && clearTimeout(self.timeout), self.timeout = null, o.complete && o.complete(e)
        }

        function success(resp) {
            var r = resp.responseText;
            if (r)switch (type) {
                case"json":
                    try {
                        resp = win.JSON ? win.JSON.parse(r) : eval("(" + r + ")")
                    } catch (err) {
                        return error(resp, "Could not parse JSON in response", err)
                    }
                    break;
                case"js":
                    resp = eval(r);
                    break;
                case"html":
                    resp = r
            }
            fn(resp), o.success && o.success(resp), complete(resp)
        }

        function error(e, t, n) {
            o.error && o.error(e, t, n), complete(e)
        }

        this.url = typeof o == "string" ? o : o.url, this.timeout = null;
        var type = o.type || setType(this.url), self = this;
        fn = fn || function () {
        }, o.timeout && (this.timeout = setTimeout(function () {
            self.abort()
        }, o.timeout)), this.request = getRequest(o, success, error)
    }

    function reqwest(e, t) {
        return new Reqwest(e, t)
    }

    function normalize(e) {
        return e ? e.replace(/\r?\n/g, "\r\n") : ""
    }

    function serial(e, t) {
        var n = e.name, r = e.tagName.toLowerCase(), i = function (e) {
            e && !e.disabled && t(n, normalize(e.attributes.value && e.attributes.value.specified ? e.value : e.text))
        };
        if (e.disabled || !n)return;
        switch (r) {
            case"input":
                if (!/reset|button|image|file/i.test(e.type)) {
                    var s = /checkbox/i.test(e.type), o = /radio/i.test(e.type), u = e.value;
                    (!s && !o || e.checked) && t(n, normalize(s && u === "" ? "on" : u))
                }
                break;
            case"textarea":
                t(n, normalize(e.value));
                break;
            case"select":
                if (e.type.toLowerCase() === "select-one")i(e.selectedIndex >= 0 ? e.options[e.selectedIndex] : null); else for (var a = 0; e.length && a < e.length; a++)e.options[a].selected && i(e.options[a])
        }
    }

    function eachFormElement() {
        var e = this, t, n, r, i = function (t, n) {
            for (var i = 0; i < n.length; i++) {
                var s = t[byTag](n[i]);
                for (r = 0; r < s.length; r++)serial(s[r], e)
            }
        };
        for (n = 0; n < arguments.length; n++)t = arguments[n], /input|select|textarea/i.test(t.tagName) && serial(t, e), i(t, ["input", "select", "textarea"])
    }

    function serializeQueryString() {
        return reqwest.toQueryString(reqwest.serializeArray.apply(null, arguments))
    }

    function serializeHash() {
        var e = {};
        return eachFormElement.apply(function (t, n) {
            t in e ? (e[t] && !isArray(e[t]) && (e[t] = [e[t]]), e[t].push(n)) : e[t] = n
        }, arguments), e
    }

    var win = window, doc = document, twoHundo = /^20\d$/, byTag = "getElementsByTagName", readyState = "readyState", contentType = "Content-Type", requestedWith = "X-Requested-With", head = doc[byTag]("head")[0], uniqid = 0, lastValue, xmlHttpRequest = "XMLHttpRequest", isArray = typeof Array.isArray == "function" ? Array.isArray : function (e) {
        return e instanceof Array
    }, defaultHeaders = {contentType: "application/x-www-form-urlencoded", accept: {"*": "text/javascript, text/html, application/xml, text/xml, */*", xml: "application/xml, text/xml", html: "text/html", text: "text/plain", json: "application/json, text/javascript", js: "application/javascript, text/javascript"}, requestedWith: xmlHttpRequest}, xhr = win[xmlHttpRequest] ? function () {
        return new XMLHttpRequest
    } : function () {
        return new ActiveXObject("Microsoft.XMLHTTP")
    };
    return Reqwest.prototype = {abort: function () {
        this.request.abort()
    }, retry: function () {
        init.call(this, this.o, this.fn)
    }}, reqwest.serializeArray = function () {
        var e = [];
        return eachFormElement.apply(function (t, n) {
            e.push({name: t, value: n})
        }, arguments), e
    }, reqwest.serialize = function () {
        if (arguments.length === 0)return"";
        var e, t, n = Array.prototype.slice.call(arguments, 0);
        return e = n.pop(), e && e.nodeType && n.push(e) && (e = null), e && (e = e.type), e == "map" ? t = serializeHash : e == "array" ? t = reqwest.serializeArray : t = serializeQueryString, t.apply(null, n)
    }, reqwest.toQueryString = function (e) {
        var t = "", n, r = encodeURIComponent, i = function (e, n) {
            t += r(e) + "=" + r(n) + "&"
        };
        if (isArray(e))for (n = 0; e && n < e.length; n++)i(e[n].name, e[n].value); else for (var s in e) {
            if (!Object.hasOwnProperty.call(e, s))continue;
            var o = e[s];
            if (isArray(o))for (n = 0; n < o.length; n++)i(s, o[n]); else i(s, e[s])
        }
        return t.replace(/&$/, "").replace(/%20/g, "+")
    }, reqwest.compat = function (e, t) {
        return e && (e.type && (e.method = e.type) && delete e.type, e.dataType && (e.type = e.dataType), e.jsonpCallback && (e.jsonpCallbackName = e.jsonpCallback) && delete e.jsonpCallback, e.jsonp && (e.jsonpCallback = e.jsonp)), new Reqwest(e, t)
    }, reqwest
}), function () {
    var e, t = [].slice;
    (e = window.NeatComplete) == null && (window.NeatComplete = {}), NeatComplete.Dispatch = function () {
        function e() {
        }

        return e.prototype.setOption = function (e, t) {
            return this.options[e] = t, this
        }, e.prototype.getOption = function (e) {
            return this.options[e]
        }, e.prototype.on = function (e, t) {
            var n, r, i;
            return(r = this.subs) == null && (this.subs = {}), (i = (n = this.subs)[e]) == null && (n[e] = []), this.subs[e].push(t), this
        }, e.prototype.trigger = function () {
            var e, n, r, i, s, o, u;
            r = arguments[0], e = 2 <= arguments.length ? t.call(arguments, 1) : [];
            if (((o = this.subs) != null ? o[r] : void 0) != null) {
                u = this.subs[r];
                for (i = 0, s = u.length; i < s; i++)n = u[i], n.apply(this, e)
            }
            return this
        }, e
    }()
}.call(this), function () {
    var e;
    (e = window.NeatComplete) == null && (window.NeatComplete = {}), NeatComplete.addDomEvent = function (e, t, n) {
        return e.attachEvent != null ? (e["e" + t + n] = n, e["" + t + n] = function () {
            return e["e" + t + n](window.event)
        }, e.attachEvent("on" + t, e["" + t + n])) : e.addEventListener(t, n, !1)
    }, NeatComplete.removeDomEvent = function (e, t, n) {
        return e.detachEvent != null ? e.detachEvent("on" + t, e["" + t + n]) : e.removeEventListener(t, n, !1)
    }, Array.prototype.indexOf || (Array.prototype.indexOf = function (e) {
        var t, n, r, i;
        if (typeof this == "undefined" || this === null)throw new TypeError;
        i = Object(this), n = i.length >>> 0;
        if (n === 0)return-1;
        r = 0, arguments.length > 0 && (r = Number(arguments[1]), r !== r ? r = 0 : r !== 0 && r !== Infinity && r !== -Infinity && (r = (r > 0 || -1) * Math.floor(Math.abs(r))));
        if (r >= n)return-1;
        t = r >= 0 ? r : Math.max(n - Math.abs(r), 0);
        while (t < n) {
            if (t in i && i[t] === e)return t;
            t++
        }
        return-1
    })
}.call(this), function () {
    var e;
    (e = window.NeatComplete) == null && (window.NeatComplete = {}), NeatComplete._Result = function () {
        function e(e, t) {
            var n, r, i, s;
            this.service = e, this.options = t, this.widget = this.service.widget, this.renderer = this.service.options.renderer || this.widget.options.renderer, this.value = (n = this.options) != null ? n.value : void 0, this.score = ((r = this.options) != null ? r.score : void 0) || 0, this.identifier = (i = this.options) != null ? i.identifier : void 0, this.data = ((s = this.options) != null ? s.data : void 0) || {}
        }

        return e.prototype.render = function () {
            return this.li = document.createElement("li"), this.li.innerHTML = this.renderer != null ? this.renderer(this.value, this.data) : this.value, this.li.className = this.widget.options.item_class, this.addEvents(), this.li
        }, e.prototype.addEvents = function () {
            var e = this;
            return NeatComplete.addDomEvent(this.li, "click", function (t) {
                return e.selectItem(), t.preventDefault ? t.preventDefault() : t.returnValue = !1
            }), NeatComplete.addDomEvent(this.li, "mouseover", function () {
                return e.highlight()
            }), NeatComplete.addDomEvent(this.li, "mouseout", function () {
                return e.unhighlight()
            }), NeatComplete.addDomEvent(this.li, "mousedown", function () {
                return e.widget.mouseDownOnSelect = !0
            }), NeatComplete.addDomEvent(this.li, "mouseup", function () {
                return e.widget.mouseDownOnSelect = !1
            })
        }, e.prototype.selectItem = function () {
            return this.service.trigger("result:select", this.value, this.data), this.widget.highlighted = this, this.widget.selectHighlighted()
        }, e.prototype.highlight = function () {
            var e;
            return(e = this.widget.highlighted) != null && e.unhighlight(), this.li.className = "" + this.li.className + " " + this.widget.options.hover_class, this.widget.highlighted = this
        }, e.prototype.unhighlight = function () {
            return this.widget.highlighted = null, this.li.className = this.li.className.replace(new RegExp(this.widget.options.hover_class, "gi"), "")
        }, e
    }()
}.call(this), function () {
    var e, t = function (e, t) {
        return function () {
            return e.apply(t, arguments)
        }
    }, n = {}.hasOwnProperty, r = function (e, t) {
        function i() {
            this.constructor = e
        }

        for (var r in t)n.call(t, r) && (e[r] = t[r]);
        return i.prototype = t.prototype, e.prototype = new i, e.__super__ = t.prototype, e
    };
    (e = window.NeatComplete) == null && (window.NeatComplete = {}), NeatComplete.Service = function (e) {
        function n(e, n, r, i) {
            var s = this;
            this.widget = e, this.name = n, this.search_fn = r, this.options = i != null ? i : {}, this._response = t(this._response, this), this.results = [], this.response = function (e, t) {
                return s._response.apply(s, arguments)
            }
        }

        return r(n, e), n.prototype.search = function (e) {
            return this.last_query = e, this.ready = !1, this.search_fn(e, this.response)
        }, n.prototype._response = function (e, t) {
            var n, r, i;
            this.results = [];
            if (this.last_query === e) {
                this.results = [];
                for (r = 0, i = t.length; r < i; r++)n = t[r], this.results.push(new NeatComplete._Result(this, n));
                return this.ready = !0, this.widget.showResults()
            }
        }, n
    }(NeatComplete.Dispatch)
}.call(this), function () {
    var e = function (e, t) {
        return function () {
            return e.apply(t, arguments)
        }
    }, t = {}.hasOwnProperty, n = function (e, n) {
        function i() {
            this.constructor = e
        }

        for (var r in n)t.call(n, r) && (e[r] = n[r]);
        return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
    };
    NeatComplete.Widget = function (t) {
        function r(t, n) {
            this.element = t, this.options = n != null ? n : {}, this._onBlur = e(this._onBlur, this), this._onKeyDown = e(this._onKeyDown, this), this._onKeyPress = e(this._onKeyPress, this), this._onFocus = e(this._onFocus, this), this.enabled = !0, this.element.setAttribute("autocomplete", "off"), this.services = [], this._applyDefaults(), this._addListeners(), this.output = document.createElement("ul"), this.output.className = this.options.list_class, this._applyStyle("display", "none"), this._applyStyle("position", this.options.position), document.body.appendChild(this.output), this
        }

        return n(r, t), r.prototype.defaults = {max_results: 10, list_class: "nc_list", item_class: "nc_item", hover_class: "nc_hover", footer_class: "nc_footer", empty_class: "nc_empty", error_class: "nc_error", position: "absolute"}, r.prototype.addService = function (e, t, n) {
            var r;
            return n == null && (n = {}), this.services.push(r = new NeatComplete.Service(this, e, t, n)), r
        }, r.prototype.disable = function () {
            return this.enabled = !1, this
        }, r.prototype.enable = function () {
            return this.enabled = !0, this
        }, r.prototype.destroy = function () {
            document.body.removeChild(this.output), this.element.removeAttribute("autocomplete")
        }, r.prototype._applyDefaults = function () {
            var e, t, n, r;
            n = this.defaults, r = [];
            for (e in n)t = n[e], this.getOption(e) == null ? r.push(this.setOption(e, t)) : r.push(void 0);
            return r
        }, r.prototype._addListeners = function () {
            return NeatComplete.addDomEvent(this.element, "focus", this._onFocus), NeatComplete.addDomEvent(this.element, "keypress", this._onKeyPress), NeatComplete.addDomEvent(this.element, "keydown", this._onKeyDown), NeatComplete.addDomEvent(this.element, "blur", this._onBlur)
        }, r.prototype._removeListeners = function () {
            return NeatComplete.removeDomEvent(this.element, "focus", this._onFocus), NeatComplete.removeDomEvent(this.element, "keypress", this._onKeyPress), NeatComplete.removeDomEvent(this.element, "keydown", this._onKeyDown), NeatComplete.removeDomEvent(this.element, "blur", this._onBlur)
        }, r.prototype._onFocus = function (e) {
            return this.focused = !0
        }, r.prototype._onKeyPress = function (e) {
            var t, n;
            t = e.which || e.keyCode;
            if (this.visible && t === 13)return(n = this.highlighted) != null && n.selectItem(), e.preventDefault ? e.preventDefault() : e.returnValue = !1, !1
        }, r.prototype._onKeyDown = function (e) {
            var t, n, r = this;
            t = e.which || e.keyCode;
            switch (t) {
                case 38:
                    return this.visible && this._moveHighlight(-1), !1;
                case 40:
                    return this.visible && this._moveHighlight(1), !1;
                case 9:
                    if (this.visible)return(n = this.highlighted) != null ? n.selectItem() : void 0;
                    break;
                case 27:
                    return this._hideResults();
                case 37:
                case 39:
                case 13:
                    break;
                default:
                    return this._timeout != null && clearTimeout(this._timeout), this._timeout = setTimeout(function () {
                        return r._getSuggestions()
                    }, 400)
            }
        }, r.prototype._onBlur = function (e) {
            if (!this.mouseDownOnSelect)return this.focused = !1, this._hideResults()
        }, r.prototype._moveHighlight = function (e) {
            var t, n, r;
            return t = this.highlighted != null ? this.results.indexOf(this.highlighted) : -1, (n = this.highlighted) != null && n.unhighlight(), t += e, t < -1 ? t = this.results.length - 1 : t >= this.results.length && (t = -1), (r = this.results[t]) != null && r.highlight(), this.element.value = this.highlighted != null ? this.highlighted.value : this._val
        }, r.prototype._getSuggestions = function () {
            var e, t, n, r, i;
            if (!this.enabled)return;
            this._val = this.element.value, this.error_content = null;
            if (this._val !== "") {
                r = this.services, i = [];
                for (t = 0, n = r.length; t < n; t++)e = r[t], i.push(e.search(this._val));
                return i
            }
            return this._hideResults()
        }, r.prototype._applyStyle = function (e, t) {
            return this.output.style[e] = t
        }, r.prototype._getPosition = function () {
            var e, t;
            t = this.element, e = {top: t.offsetTop + t.offsetHeight, left: t.offsetLeft};
            while (t = t.offsetParent)e.top += t.offsetTop, e.left += t.offsetLeft;
            return e
        }, r.prototype._hideResults = function () {
            var e, t, n, r, i;
            this.visible = !1, this._applyStyle("display", "none"), this.results = [], r = this.services, i = [];
            for (t = 0, n = r.length; t < n; t++)e = r[t], i.push(e.results = []);
            return i
        }, r.prototype._displayResults = function () {
            var e;
            return this.visible = !0, e = this._getPosition(), this._applyStyle("left", "" + e.left + "px"), this._applyStyle("top", "" + e.top + "px"), this._applyStyle("display", "block")
        }, r.prototype._renderItem = function (e, t) {
            var n, r = this;
            return n = document.createElement("li"), n.innerHTML = e, t != null && (n.className = t), NeatComplete.addDomEvent(n, "mousedown", function () {
                return r.mouseDownOnSelect = !0
            }), NeatComplete.addDomEvent(n, "mouseup", function () {
                return r.mouseDownOnSelect = !1
            }), n
        }, r.prototype._renderFooter = function () {
            return this._renderItem(this.options.footer_content, this.options.footer_class)
        }, r.prototype._renderEmpty = function () {
            return this._renderItem(this.options.empty_content, this.options.empty_class)
        }, r.prototype._servicesReady = function () {
            var e, t, n, r, i;
            t = [], i = this.services;
            for (n = 0, r = i.length; n < r; n++)e = i[n], t.push(e.ready);
            return t.indexOf(!1) < 0
        }, r.prototype.showResults = function () {
            var e, t, n, r, i, s, o, u, a;
            if (this._servicesReady()) {
                this.results = [], this.output.innerHTML = "", u = this.services;
                for (r = 0, s = u.length; r < s; r++)n = u[r], this.results = this.results.concat(n.results);
                if (this.results.length) {
                    this.results = this.results.sort(function (e, t) {
                        return t.score - e.score
                    }), this.results = this.results.slice(0, +(this.getOption("max_results") - 1) + 1 || 9e9), a = this.results;
                    for (i = 0, o = a.length; i < o; i++)t = a[i], this.output.appendChild(t.render());
                    this.options.footer_content != null && (e = this._renderFooter()) !== "" && this.output.appendChild(e), this._displayResults()
                } else this.error_content ? (this.output.appendChild(this._renderItem(this.error_content, this.options.error_class)), this._displayResults()) : this.options.empty_content != null ? (this.output.appendChild(this._renderEmpty()), this._displayResults(), this.trigger("results:empty")) : this._hideResults();
                this.trigger("results:update")
            }
        }, r.prototype.selectHighlighted = function () {
            this.element.value = this.highlighted.value, this._hideResults(), this.trigger("result:select", this.highlighted.value, this.highlighted.data)
        }, r
    }(NeatComplete.Dispatch)
}.call(this), function () {
    var e, t = {}.hasOwnProperty, n = function (e, n) {
        function i() {
            this.constructor = e
        }

        for (var r in n)t.call(n, r) && (e[r] = n[r]);
        return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
    };
    (e = window.AF) == null && (window.AF = {}), AF.Widget = function (e) {
        function t(e, n, r) {
            var i, s, o, u = this;
            this.element = e, this.api_key = n, this.options = r != null ? r : {}, t.__super__.constructor.call(this, this.element, this.options), this.paid = !0, this.options.manual_style || this._addCSS(), this._applyStyle("position", this.options.position), o = {renderer: this.options.renderer}, i = new AddressFinder._AddressService(this, o), i.on("result:select:pre", function (e, t) {
                return u.trigger("address:select:pre", e, t)
            }), i.on("result:select", function (e, t) {
                return u.trigger("address:select", e, t)
            }), s = new AddressFinder._LocationService(this, o), s.on("result:select:pre", function (e, t) {
                return u.trigger("location:select:pre", e, t)
            }), s.on("result:select", function (e, t) {
                return u.trigger("location:select", e, t)
            }), this.services.push(i), this.services.push(s)
        }

        return n(t, e), t.prototype.defaults = {max_results: 10, list_class: "af_list", item_class: "af_item", hover_class: "af_hover", footer_class: "af_footer", empty_class: "af_empty", error_class: "af_error", manual_style: !1, show_addresses: !0, show_locations: !0, position: "absolute"}, t.prototype.addService = function (e, t, n) {
            var r;
            return n == null && (n = {}), this.services.push(r = new AF.Service(this, e, t, n)), r
        }, t.prototype.showResults = function () {
            var e;
            return(e = this.options).footer_content || (e.footer_content = ""), t.__super__.showResults.apply(this, arguments)
        }, t.prototype._addCSS = function () {
            var e;
            return e = document.createElement("link"), e.type = "text/css", e.rel = "stylesheet", e.href = AF.SERVER + AF.CSS, e.media = "screen", document.createStyleSheet != null ? document.createStyleSheet(AF.SERVER + AF.CSS) : document.getElementsByTagName("head")[0].appendChild(e)
        }, t.prototype._renderFooter = function () {
            var e;
            return this.paid ? this.options.footer_content !== "" ? this._renderItem(this.options.footer_content, this.options.footer_class) : "" : (e = this._renderItem("powered by <a href='http://www.addressfinder.co.nz' target='_blank' >AddressFinder</a>", this.options.footer_class), e.style.cssText = "display: block !important; visibility: visible !important; opacity: 1 !important; height: auto !important;", e)
        }, t
    }(NeatComplete.Widget), AF.Service = function (e) {
        function t() {
            return t.__super__.constructor.apply(this, arguments)
        }

        return n(t, e), t
    }(NeatComplete.Service), AF._AFService = function (e) {
        function t(e, t) {
            this.widget = e, this.options = t != null ? t : {}, this.results = []
        }

        return n(t, e), t.prototype.search = function (e) {
            var t, n = this;
            if (this.performSearch() && e.length > 2)return this.widget.error = null, this.last_query = e, t = AF.objParams({q: encodeURIComponent(e), key: this.widget.api_key, widget_token: AF.TOKEN, format: "json", max: this.widget.options.max_results}), this.extraParams() && (t += "&" + this.extraParams()), reqwest({url: "" + AF.SERVER + "/api/" + this.search_type + "?" + t, type: "jsonp", jsonpCallback: "callback", success: function (e) {
                var t, r, i, s;
                n.results = [], s = e.completions.slice(0, +n.widget.options.max_results + 1 || 9e9);
                for (r = 0, i = s.length; r < i; r++)t = s[r], n.results.push(new AddressFinder._Result(n, {value: t.a, score: n.sort_value, data: t}));
                return n.widget.paid = e.paid, e.error_code != null && (n.widget.error_content = "Error: <a href='//addressfinder.co.nz/docs/api_errors#error_" + e.error_code + "' target='_blank'>" + e.message + "</a>"), n.widget.showResults()
            }})
        }, t
    }(NeatComplete.Dispatch), AF._AddressService = function (e) {
        function t() {
            return t.__super__.constructor.apply(this, arguments)
        }

        return n(t, e), t.prototype.search_type = "address", t.prototype.sort_value = -10, t.prototype.performSearch = function () {
            return this.widget.getOption("show_addresses")
        }, t.prototype.extraParams = function () {
            if (this.widget.getOption("address_params") != null)return AF.objParams(this.widget.getOption("address_params"))
        }, t
    }(AF._AFService), AF._LocationService = function (e) {
        function t() {
            return t.__super__.constructor.apply(this, arguments)
        }

        return n(t, e), t.prototype.search_type = "location", t.prototype.sort_value = -1, t.prototype.performSearch = function () {
            return this.widget.getOption("show_locations")
        }, t.prototype.extraParams = function () {
            if (this.widget.getOption("location_params") != null)return AF.objParams(this.widget.getOption("location_params"))
        }, t
    }(AF._AFService), AF._Result = function (e) {
        function t() {
            return t.__super__.constructor.apply(this, arguments)
        }

        return n(t, e), t.prototype.selectItem = function () {
            var e, t = this;
            if (((e = this.data) != null ? e.pxid : void 0) != null && !this.widget.info_loading)return this.widget.info_loading = !0, this.service.trigger("result:select:pre", this.value, this.data), reqwest({url: "" + AF.SERVER + "/api/" + this.service.search_type + "/info", data: {pxid: this.data.pxid, widget_token: AddressFinder.TOKEN, format: "json", key: this.widget.api_key}, type: "jsonp", jsonpCallback: "callback", success: function (e) {
                return t.data = e, t.service.trigger("result:select", t.value, t.data), t.widget.highlighted = t, t.widget.selectHighlighted(), t.widget.info_loading = !1
            }})
        }, t
    }(NeatComplete._Result), window.AddressFinder = AF, AF.executeCallback()
}.call(this);
