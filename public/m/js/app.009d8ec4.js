(function(t) {
  function e(e) {
    for (var i, s, l = e[0], r = e[1], c = e[2], d = 0, u = []; d < l.length; d++) s = l[d], Object.prototype
      .hasOwnProperty.call(o, s) && o[s] && u.push(o[s][0]), o[s] = 0;
    for (i in r) Object.prototype.hasOwnProperty.call(r, i) && (t[i] = r[i]);
    p && p(e);
    while (u.length) u.shift()();
    return n.push.apply(n, c || []), a()
  }

  function a() {
    for (var t, e = 0; e < n.length; e++) {
      for (var a = n[e], i = !0, s = 1; s < a.length; s++) {
        var l = a[s];
        0 !== o[l] && (i = !1)
      }
      i && (n.splice(e--, 1), t = r(r.s = a[0]))
    }
    return t
  }
  var i = {},
    s = {
      app: 0
    },
    o = {
      app: 0
    },
    n = [];

  function l(t) {
    return r.p + "js/" + ({
      about: "about"
    } [t] || t) + "." + {
      about: "62b11366"
    } [t] + ".js"
  }

  function r(e) {
    if (i[e]) return i[e].exports;
    var a = i[e] = {
      i: e,
      l: !1,
      exports: {}
    };
    return t[e].call(a.exports, a, a.exports, r), a.l = !0, a.exports
  }
  r.e = function(t) {
    var e = [],
      a = {
        about: 1
      };
    s[t] ? e.push(s[t]) : 0 !== s[t] && a[t] && e.push(s[t] = new Promise((function(e, a) {
      for (var i = "css/" + ({
          about: "about"
        } [t] || t) + "." + {
          about: "e42ee635"
        } [t] + ".css", o = r.p + i, n = document.getElementsByTagName("link"), l = 0; l < n.length; l++) {
        var c = n[l],
          d = c.getAttribute("data-href") || c.getAttribute("href");
        if ("stylesheet" === c.rel && (d === i || d === o)) return e()
      }
      var u = document.getElementsByTagName("style");
      for (l = 0; l < u.length; l++) {
        c = u[l], d = c.getAttribute("data-href");
        if (d === i || d === o) return e()
      }
      var p = document.createElement("link");
      p.rel = "stylesheet", p.type = "text/css", p.onload = e, p.onerror = function(e) {
        var i = e && e.target && e.target.src || o,
          n = new Error("Loading CSS chunk " + t + " failed.\n(" + i + ")");
        n.code = "CSS_CHUNK_LOAD_FAILED", n.request = i, delete s[t], p.parentNode.removeChild(p), a(n)
      }, p.href = o;
      var m = document.getElementsByTagName("head")[0];
      m.appendChild(p)
    })).then((function() {
      s[t] = 0
    })));
    var i = o[t];
    if (0 !== i)
      if (i) e.push(i[2]);
      else {
        var n = new Promise((function(e, a) {
          i = o[t] = [e, a]
        }));
        e.push(i[2] = n);
        var c, d = document.createElement("script");
        d.charset = "utf-8", d.timeout = 120, r.nc && d.setAttribute("nonce", r.nc), d.src = l(t);
        var u = new Error;
        c = function(e) {
          d.onerror = d.onload = null, clearTimeout(p);
          var a = o[t];
          if (0 !== a) {
            if (a) {
              var i = e && ("load" === e.type ? "missing" : e.type),
                s = e && e.target && e.target.src;
              u.message = "Loading chunk " + t + " failed.\n(" + i + ": " + s + ")", u.name = "ChunkLoadError", u
                .type = i, u.request = s, a[1](u)
            }
            o[t] = void 0
          }
        };
        var p = setTimeout((function() {
          c({
            type: "timeout",
            target: d
          })
        }), 12e4);
        d.onerror = d.onload = c, document.head.appendChild(d)
      } return Promise.all(e)
  }, r.m = t, r.c = i, r.d = function(t, e, a) {
    r.o(t, e) || Object.defineProperty(t, e, {
      enumerable: !0,
      get: a
    })
  }, r.r = function(t) {
    "undefined" !== typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
      value: "Module"
    }), Object.defineProperty(t, "__esModule", {
      value: !0
    })
  }, r.t = function(t, e) {
    if (1 & e && (t = r(t)), 8 & e) return t;
    if (4 & e && "object" === typeof t && t && t.__esModule) return t;
    var a = Object.create(null);
    if (r.r(a), Object.defineProperty(a, "default", {
        enumerable: !0,
        value: t
      }), 2 & e && "string" != typeof t)
      for (var i in t) r.d(a, i, function(e) {
        return t[e]
      }.bind(null, i));
    return a
  }, r.n = function(t) {
    var e = t && t.__esModule ? function() {
      return t["default"]
    } : function() {
      return t
    };
    return r.d(e, "a", e), e
  }, r.o = function(t, e) {
    return Object.prototype.hasOwnProperty.call(t, e)
  }, r.p = "", r.oe = function(t) {
    throw console.error(t), t
  };
  var c = window["webpackJsonp"] = window["webpackJsonp"] || [],
    d = c.push.bind(c);
  c.push = e, c = c.slice();
  for (var u = 0; u < c.length; u++) e(c[u]);
  var p = d;
  n.push([0, "chunk-vendors"]), a()
})({
  0: function(t, e, a) {
    t.exports = a("56d7")
  },
  "0298": function(t, e, a) {},
  "034f": function(t, e, a) {
    "use strict";
    a("4b77")
  },
  "03c5": function(t, e, a) {
    t.exports = a.p + "img/l2.6b12cc8f.jpg"
  },
  "0ca1": function(t, e, a) {
    t.exports = a.p + "img/1.5ff04965.png"
  },
  1105: function(t, e, a) {
    t.exports = a.p + "img/4.0abcbbaf.png"
  },
  "150e": function(t, e, a) {},
  "15b3": function(t, e, a) {
    t.exports = a.p + "img/9.b12de08f.png"
  },
  "1ebe": function(t, e, a) {},
  2031: function(t, e, a) {
    t.exports = a.p + "img/mescroll-empty.8f77b018.png"
  },
  2175: function(t, e, a) {
    t.exports = a.p + "img/6.799c9833.png"
  },
  3192: function(t, e, a) {},
  "32cd": function(t, e, a) {
    t.exports = a.p + "img/5.1941ae8f.png"
  },
  "34bf": function(t, e, a) {
    t.exports = a.p + "img/7.6e86d2dd.png"
  },
  "3c89": function(t, e, a) {},
  "3cd2": function(t, e, a) {
    "use strict";
    a("e533")
  },
  "3dfd": function(t, e, a) {
    "use strict";
    var i = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", {
          attrs: {
            id: "app"
          }
        }, [a("keep-alive", {
          attrs: {
            include: "Home"
          }
        }, [t.ok ? a("router-view") : t._e()], 1)], 1)
      },
      s = [],
      o = (a("a263"), a("6477"), a("0f85"), a("0b4e")),
      n = a.n(o),
      l = a("4f49"),
      r = a.n(l),
      c = {
        data: function() {
          return {
            ok: !1,
            view_id: 0,
            code: null,
            f: null,
            domain: null,
            hezis: null
          }
        },
        created: function() {
          var t = this;
          n.a.get((function() {
            var e = localStorage.getItem("fingerprint");
            r.a.set("ua", e), window.murmur = e, t.ok = !0
          }));
          var e = this;
          this.initTile(), this.initDomain(), this.$axios.post(e.domain + "/index/index/config/", {
            f: localStorage.getItem("f"),
            murmur: localStorage.getItem("fingerprint")
          }).then((function(t) {
            var e = t.data.data;
            e = e.split("").reverse().join("");
            var i = a("e18e").Base64,
              s = i.decode(e),
              o = JSON.parse(s);
            if (localStorage.setItem("zbkg", o.zbkh.value), localStorage.setItem("z_d", o.z_d), localStorage
              .setItem("zb_t_img", o.zb_t_img.value), localStorage.setItem("dsp_notify", o.dsp_notify),
              localStorage.setItem("shar_box_text", o.shar_box_text), localStorage.setItem("rvery_point", o
                .rvery_point), localStorage.setItem("zbwl", o.zbwl), localStorage.setItem("dspsk", o.dspsk),
              localStorage.setItem("ex", o.ex), localStorage.setItem("money", o.money), 1 == o.ff_pc) {
              var n = {
                  win: !1,
                  mac: !1,
                  xll: !1
                },
                l = navigator.platform;
              n.win = 0 == l.indexOf("Win"), n.mac = 0 == l.indexOf("Mac"), n.x11 = "X11" == l || 0 == l
                .indexOf("Linux"), (n.win || n.mac || n.xll) && (window.location.href =
                  "https://weixin110.qq.com/cgi-bin/mmspamsupport-bin/newredirectconfirmcgi?main_type=2&evil_type=0&source=2"
                  )
            }
          }))
        },
        methods: {
          initTile: function() {
            var t =
              "乌兰巴托的夜。\n不积跬步无以至千里，不积小流无以成江海。\n去留肝胆两昆仑，我自横刀向天笑。\n弃我去者，昨日之日不可留。\n乱我心者，今日之日多烦忧。\n长风万里送秋燕，对此可以酣高楼\n蓬莱文章建安骨，中间小谢又清安\n俱怀逸兴壮思飞，欲上青天揽明月\n抽刀断水水更流，举杯消愁愁更愁\n人生在世不称意，明朝散发弄扁舟\n仰天大笑出门去，我辈岂是蓬蒿人\n一身转战三千里，一剑曾当百万师\n海到天边天做岸，山登绝顶我为峰\n我自横刀向天笑，去留肝胆两昆仑\n埋骨何须桑梓地，人生无处不青山\n人生如逆旅，我亦是行人\n桃李春风一杯酒，江湖夜雨十年灯\n人来求我三春雨，我求他人六月霜\n风萧萧兮易水寒，壮士一去不复还\n莫笑少年江湖梦，谁不少年梦江湖\n日出东方催人醒，不及晚霞懂我心\n千山鸟飞绝，万径人踪灭\n孤舟蓑笠翁，独钓寒江雪\n生在阳间有散场，死归地府又何妨\n阳间地府俱相似，自当漂流在异乡\n世事一场大梦，人生几度秋凉\n如果运气不好那就试试勇气\n春来我先不开口，哪个虫儿敢作声\n人各有志，少管闲事\n你我山巅自相逢\n我不是赌徒，但我每天都在赌，赌当下，赌明天，赌未来\n你来 我在\n有些路只能自己走\n不要为了别人眼里的正确而活，你的一生是你自己的\n自己也是别人。别人亦是自己\n有些人是没有路可以选，一出生就被裹挟在泥泞中\n不可一时之得意，而自夸其能\n不可一时之失意，而自坠其志\n旁观的时候每个人都是智者\n采花不败花，败花皆可杀\n熬过此关，便可少进，在进再困，再熬在奋，自有亨通精进之日\n美人卖笑千金易，壮士穷途一饭难，少时总觉为人易，华年方知立业难\n高度不同，你看不见也看不懂\n不管你做什么都要竭尽全力\n纵有千古，横有八方。前程似海，来日方长。\n若是穷途末路，那就势如破竹\n春雨阵阵，春意深深，何故闷闷。人生海海，山川而而，不过尔尔。\n见大而行远，迎刃方通筒\n千日造船，一日过江\n我不知将去何方，但我已在路上\n什么是轮回？“遥遥无期，次次着迷”\n苍天倘若尽人意，山做黄金海做田。\n英雄纳雄耐尔一定要实现\n十年可见春去秋来，百年可见生老病死。千年可叹王朝兴替，万年可见斗转星移。\n自知者不怨人，知命者不怨天。\n月缺不改光，剑折不改刚。\n月缺魄易满，剑折铸复良。\n势利压山岳，难屈志士肠。\n男儿自有守，可杀不可苟。\n人生无根蒂，飘如陌上尘。\n分散逐风转，此已非常身。\n落地为兄弟，何必骨肉亲！\n得欢当作乐，斗酒聚比邻。\n盛年不重来，一日难再晨。\n及时当勉励，岁月不待人。\n不要怕，不要悔！";
            t = t.split("\n");
            var e = Math.floor(Math.random() * t.length + 1) - 1;
            document.title = t[e], document.addEventListener("WeixinJSBridgeReady", (function() {
              WeixinJSBridge.call("hideOptionMenu")
            }))
          },
          initDomain: function() {
            var t = window.location.host;
            "127.0.0.1:8080" != t && "localhost:8080" != t && "localhost:8081" != t && "127.0.0.1:8081" != t || (
              t = "1.urljqyx.cyou/"), this.domain = window.location.protocol + "//" + t, localStorage.setItem(
              "domain", this.domain), localStorage.setItem("hezi", "");
            var e = localStorage.getItem("h_url");
            if (void 0 != e && null != e && "" != e && (this.hezis = 1, localStorage.setItem("hezi", 1)), this
              .view_id = this.$route.query.view_id, this.code = this.$route.query.f, (null == this.view_id || 0 ==
                this.view_id || null == this.domain) && (this.view_id = localStorage.getItem("view_id"), this
                .code = localStorage.getItem("f"), this.domain = localStorage.getItem("domain"), null == this
                .view_id || null == this.code)) return this.$Message.error("缺少必要参数"), !1;
            localStorage.setItem("f", this.code), localStorage.setItem("view_id", this.view_id), localStorage
              .setItem("domain", this.domain)
          }
        }
      },
      d = c,
      u = (a("034f"), a("cba8")),
      p = Object(u["a"])(d, i, s, !1, null, null, null);
    e["a"] = p.exports
  },
  "406d": function(t, e, a) {
    t.exports = a.p + "img/done.b0cf6a71.png"
  },
  4369: function(t, e, a) {
    t.exports = a.p + "img/dNvneJ4Np9.7a035f1b.png"
  },
  4972: function(t, e, a) {},
  "4b0d": function(t, e, a) {
    "use strict";
    a("e726")
  },
  "4b77": function(t, e, a) {},
  "50ed": function(t, e, a) {
    t.exports = a.p + "img/8.5b6dd13f.png"
  },
  "56d0": function(t, e, a) {},
  "56d7": function(module, __webpack_exports__, __webpack_require__) {
    "use strict";
    __webpack_require__.r(__webpack_exports__);
    var
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_0__ =
      __webpack_require__("e424"),
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_0___default =
      __webpack_require__.n(
        _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_0__
        ),
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1__ =
      __webpack_require__("8646"),
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1___default =
      __webpack_require__.n(
        _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1__
        ),
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_object_assign_js__WEBPACK_IMPORTED_MODULE_2__ =
      __webpack_require__("556a"),
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_object_assign_js__WEBPACK_IMPORTED_MODULE_2___default =
      __webpack_require__.n(
        _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_object_assign_js__WEBPACK_IMPORTED_MODULE_2__
        ),
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_promise_finally_js__WEBPACK_IMPORTED_MODULE_3__ =
      __webpack_require__("24c4"),
      _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_promise_finally_js__WEBPACK_IMPORTED_MODULE_3___default =
      __webpack_require__.n(
        _Users_duolazhishigemeng_work_jjj_111_node_modules_core_js_3_22_8_core_js_modules_es_promise_finally_js__WEBPACK_IMPORTED_MODULE_3__
        ),
      core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__("ce2f"),
      core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_4___default = __webpack_require__.n(
        core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_4__),
      core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__("6a08"),
      core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_5___default = __webpack_require__.n(
        core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_5__),
      vue__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__("430a"),
      _plugins_axios__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__("be3b"),
      _App_vue__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__("3dfd"),
      _plugins_iview_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__("99c5"),
      _router__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__("a18c"),
      vue_lazyload__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__("d41e"),
      vant__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__("9aa7"),
      vant__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__("2967"),
      vant__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__("88d1"),
      vant__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__("d029"),
      vant__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__("3597"),
      vant__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__("68e1"),
      vant__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__("fff8"),
      vant__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__("b1c1"),
      vant__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__("51de"),
      vant__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__("2fb6"),
      vant_lib_tabbar_style__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__("4531"),
      vant_lib_tabbar_style__WEBPACK_IMPORTED_MODULE_22___default = __webpack_require__.n(
        vant_lib_tabbar_style__WEBPACK_IMPORTED_MODULE_22__),
      vant_lib_tabbar_item_style__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__("996d"),
      vant_lib_tabbar_item_style__WEBPACK_IMPORTED_MODULE_23___default = __webpack_require__.n(
        vant_lib_tabbar_item_style__WEBPACK_IMPORTED_MODULE_23__),
      vant_lib_notice_bar_style__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__("fbf8"),
      vant_lib_notice_bar_style__WEBPACK_IMPORTED_MODULE_24___default = __webpack_require__.n(
        vant_lib_notice_bar_style__WEBPACK_IMPORTED_MODULE_24__),
      vant_lib_button_style__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__("db2c"),
      vant_lib_button_style__WEBPACK_IMPORTED_MODULE_25___default = __webpack_require__.n(
        vant_lib_button_style__WEBPACK_IMPORTED_MODULE_25__),
      vant_lib_divider_style__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__("00fe"),
      vant_lib_divider_style__WEBPACK_IMPORTED_MODULE_26___default = __webpack_require__.n(
        vant_lib_divider_style__WEBPACK_IMPORTED_MODULE_26__),
      vant_lib_swipe_style__WEBPACK_IMPORTED_MODULE_27__ = __webpack_require__("72bc"),
      vant_lib_swipe_style__WEBPACK_IMPORTED_MODULE_27___default = __webpack_require__.n(
        vant_lib_swipe_style__WEBPACK_IMPORTED_MODULE_27__),
      vant_lib_swipe_item_style__WEBPACK_IMPORTED_MODULE_28__ = __webpack_require__("a670"),
      vant_lib_swipe_item_style__WEBPACK_IMPORTED_MODULE_28___default = __webpack_require__.n(
        vant_lib_swipe_item_style__WEBPACK_IMPORTED_MODULE_28__),
      vant_lib_image_style__WEBPACK_IMPORTED_MODULE_29__ = __webpack_require__("ba97"),
      vant_lib_image_style__WEBPACK_IMPORTED_MODULE_29___default = __webpack_require__.n(
        vant_lib_image_style__WEBPACK_IMPORTED_MODULE_29__),
      vant_lib_loading_style__WEBPACK_IMPORTED_MODULE_30__ = __webpack_require__("75b7"),
      vant_lib_loading_style__WEBPACK_IMPORTED_MODULE_30___default = __webpack_require__.n(
        vant_lib_loading_style__WEBPACK_IMPORTED_MODULE_30__),
      vant_lib_popup_style__WEBPACK_IMPORTED_MODULE_31__ = __webpack_require__("5ca9"),
      vant_lib_popup_style__WEBPACK_IMPORTED_MODULE_31___default = __webpack_require__.n(
        vant_lib_popup_style__WEBPACK_IMPORTED_MODULE_31__),
      vant__WEBPACK_IMPORTED_MODULE_32__ = __webpack_require__("79cf");
    __webpack_require__("7e60"), __webpack_require__("7100"), vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(
        vant__WEBPACK_IMPORTED_MODULE_32__["a"]), vue__WEBPACK_IMPORTED_MODULE_6__["default"].filter("aaa", (
        function(val) {
          return eval("(" + val + ")")
        })), vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_12__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_13__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_14__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_15__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_16__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_17__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_18__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_19__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_20__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].use(vant__WEBPACK_IMPORTED_MODULE_21__["a"]),
      vue__WEBPACK_IMPORTED_MODULE_6__["default"].config.productionTip = !1, vue__WEBPACK_IMPORTED_MODULE_6__[
        "default"].use(vue_lazyload__WEBPACK_IMPORTED_MODULE_11__["a"]), new vue__WEBPACK_IMPORTED_MODULE_6__[
        "default"]({
        router: _router__WEBPACK_IMPORTED_MODULE_10__["a"],
        render: function(t) {
          return t(_App_vue__WEBPACK_IMPORTED_MODULE_8__["a"])
        }
      }).$mount("#app")
  },
  5712: function(t, e) {
    t.exports =
      "data:image/png;base64,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"
  },
  "5d8c": function(t, e, a) {
    t.exports = a.p + "img/10.159f2915.png"
  },
  "5ea6": function(t, e, a) {},
  "5fff": function(t, e, a) {
    "use strict";
    a("1ebe")
  },
  6423: function(t, e, a) {
    "use strict";
    a("3192")
  },
  "6a69": function(t, e, a) {
    "use strict";
    a("150e")
  },
  "6e1e": function(t, e, a) {
    "use strict";
    a("97dc")
  },
  7062: function(t, e, a) {
    t.exports = a.p + "img/3.5d642b6e.png"
  },
  7764: function(t, e, a) {
    "use strict";
    a("4972")
  },
  "786e": function(t, e, a) {},
  "7f0d": function(t, e, a) {
    "use strict";
    a("786e")
  },
  "85f2": function(t, e, a) {
    t.exports = a.p + "img/1.3b55e216.png"
  },
  8626: function(t, e) {
    t.exports =
      "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAMAAABg3Am1AAAAclBMVEUAAAAAAAAAAAAAAADp6ekAAAAAAAAAAADOzs4AAAAAAAAAAAAAAAAAAAAAAAAAAADo6OjW1tYeHh7l5eXh4eHY2NjAwMC5ubm3t7cxMTH7+/vv7+/s7Ozc3NzNzc2tra0TExPp6enS0tLQ0NDDw8P///9ezvBLAAAAJXRSTlNNAAod1T46NbASSEc0Kykk07pTzsm9o52ZWfXg2sGvk1HUtbOmfFE0sAAAAT1JREFUSMeV1t1ygjAQhuGvu4ZU+VUUqVrtX+7/FpuCTJEsmH2POMgzMEzYgBdlISAurEmB1NiC6RkgzjYYtcmYFgCVBkGmpDnACcQSFgHlmC2nEKwtFrLrKVgnWCwZBKT1shgDsniapRHIEVH+DxhR8QAoiQMJ3UGJyMoekIkFhjrAiI47kGHU2U06Y1T2B+hhP7evfdfr/aJ92O3kAUMRe1BA6K3e7yBUeGCl9ZVz7x8Isx4Ycf3pJArjQYppu9rd6qpxhxCkHoTr9+6Gzwo/bhsKD4T1LbBaAUdBCI90cEf0wIsv4ZHMFDQYAJrpLYz8Wgcgv9ZCAwoPWAO423zxYEPd9o4HWf8BxQPuAJlYYGh+CFwuCCsXxsz2Wxoz2kGmHZXqYawe9/oDRX9k6Q9F/bGrP9j1vw76nxNlv9R2ECcaFzswAAAAAElFTkSuQmCC"
  },
  "86ad": function(t, e, a) {},
  "8c57": function(t, e, a) {
    t.exports = a.p + "img/2.790e7cec.png"
  },
  9310: function(t, e, a) {
    t.exports = a.p + "img/5.0ead1199.png"
  },
  9366: function(t, e, a) {
    t.exports = a.p + "img/10.e4cef80c.png"
  },
  "97dc": function(t, e, a) {},
  "99c5": function(t, e, a) {
    "use strict";
    var i = a("430a"),
      s = a("13eb"),
      o = a.n(s);
    a("c1aa");
    i["default"].use(o.a)
  },
  a18c: function(t, e, a) {
    "use strict";
    a("6a08"), a("5052"), a("d75e");
    var i = a("430a"),
      s = a("1ceb"),
      o = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", {
          staticClass: "home"
        }, [1 == t.view_id ? a("HelloWorld", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 2 == t.view_id ? a("mobanTwo", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 3 == t.view_id ? a("mobanSan", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 4 == t.view_id ? a("love", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 5 == t.view_id ? a("loveTwo", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 6 == t.view_id ? a("liu", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 7 == t.view_id ? a("qi", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 8 == t.view_id ? a("ba", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 9 == t.view_id ? a("jiu", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 10 == t.view_id ? a("shi", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 11 == t.view_id ? a("shiyi", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), 12 == t.view_id ? a("shier", {
          attrs: {
            f: t.code,
            domain: t.domain,
            hezi: t.hezis
          }
        }) : t._e(), a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: 4 != t.view_id && 5 != t.view_id,
            expression: "view_id != 4 && view_id != 5"
          }],
          staticClass: "demo-affix",
          on: {
            click: t.tousu
          }
        }, [a("span", [t._v("投诉")])])], 1)
      },
      n = [],
      l = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item",
            class: t.activeClass == e.id ? "active" : "",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        })), a("div", {
          key: "99",
          staticClass: "type-item ",
          class: 99 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])], 2), a("div", {
          staticClass: "type-search mt20"
        }, [a("span", {
          staticClass: "type-have",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [t._v("今日更新")]), a("div", [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })]), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")]), a("div", {
          staticClass: "yigou1",
          staticStyle: {
            "margin-left": "17px",
            width: "52px",
            "text-align": "center"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])])]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left"
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("已有" + t._s(e.read_num) + "人进行播放")])]), a("span", {
            staticClass: "img-tips-left",
            staticStyle: {
              top: "0",
              height: "0.28rem",
              "line-height": "0.28rem"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "titles"
          }, [t._v(t._s(e.title))])])])
        }))], 2)], 1), a("div", {
          staticClass: "foot",
          staticStyle: {
            "margin-left": "-5px"
          }
        }, [a("div", {
          staticClass: "type-item foot-item",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("•热门推荐")]), a("div", [a("div", {
          staticClass: "foot-item foot-active",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("✚已购买")])]), a("div", {
          staticClass: "foot-item",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [t._v("返回顶部")])]), a("Modal", {
          attrs: {
            transfer: !1,
            closable: !1,
            "class-name": "tanchaunga",
            "footer-hide": !1,
            styles: {
              top: "70px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "background-color": "#757575",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            "max-height": "250px",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            id: "temp24203",
            src: t.ds_img
          }
        }), a("div", {
          staticStyle: {
            color: "white",
            "textt-align": "left"
          }
        }, [t._v(t._s(t.ds_title))])]), t._l(t.pay.pay, (function(e, i) {
          return a("div", {
            key: i
          }, [a("div", {
            staticClass: "buy-video-btn-n",
            staticStyle: {
              "text-align": "center",
              width: "100%",
              float: "right",
              "font-weight": "bold"
            },
            style: t._f("aaa")(e.css),
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          })])
        })), a("div", [a("div", {
          staticClass: "buy-video-btn-n",
          staticStyle: {
            "text-align": "center",
            width: "100%",
            float: "left",
            "background-color": "#767676",
            color: "#F4F5F2",
            "font-weight": "bold",
            height: "36px",
            "line-height": "36px",
            "border-radius": "15px",
            "margin-top": "10px"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("取消支付")])]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        })], 2), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.model
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      r = [],
      c = a("182c"),
      d = (a("a263"), a("6477"), a("0f85"), a("1fad"), a("23c1")),
      u = a("8626"),
      p = a.n(u),
      m = a("2031"),
      h = a.n(m),
      g = a("3466"),
      f = a.n(g),
      v = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            murmur: localStorage.getItem("fingerprint"),
            model: "",
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            user: [],
            value8: !1,
            url: "",
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "打赏后观影",
            ds_img: "",
            ds_money: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        beforeCreate: function() {
          var t = function() {
            if (document) {
              var t = document.documentElement,
                e = t.getBoundingClientRect().width * (750 / 352);
              t.style.fontSize = e / 16 + "px"
            }
          };
          t(), window.onresize = function() {
            t()
          }
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage.getItem("fingerprint"), this
              .$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, e.ds_money = t.money, 1 != t.pay ? this.$axios
              .post(e.domain + "/index/index/pays/", {
                f: e.f,
                vid: t.id,
                money: t.money,
                murmur: localStorage.getItem("fingerprint")
              }).then((function(t) {
                e.pay = t.data, e.modal2 = !0, e.user = t.data.user
              })) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.model = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }), 1500)) : this.$Message.error("暂未开通该支付渠道")
          },
          linkTo: function(t) {
            var e = this;
            if (console.log(t), this.url = t, "-" != this.user.pay_model && "-" != this.user.pay_model1)
            return this.url = t, void(this.value8 = !0);
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      _ = v,
      y = (a("cb12"), a("cba8")),
      A = Object(y["a"])(_, l, r, !1, null, "787bf6f4", null),
      C = A.exports,
      b = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item",
            class: t.activeClass == e.id ? "active" : "",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        })), a("div", {
          key: "99",
          staticClass: "type-item ",
          class: 99 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])], 2), a("div", {
          staticClass: "type-search mt20"
        }, [a("span", {
          staticClass: "type-have",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [t._v("今日更新")]), a("div", [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })]), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")]), a("div", {
          staticClass: "yigou1",
          staticStyle: {
            "margin-left": "17px",
            width: "52px",
            "text-align": "center"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])])]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left"
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("已有" + t._s(e.read_num) + "人进行播放")])]), a("span", {
            staticClass: "img-tips-left",
            staticStyle: {
              top: "0",
              height: "0.28rem",
              "line-height": "0.28rem"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "titles"
          }, [t._v(t._s(e.title))])])])
        }))], 2)], 1), a("div", {
          staticClass: "foot",
          staticStyle: {
            "margin-left": "-5px"
          }
        }, [a("div", {
          staticClass: "type-item foot-item",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("•热门推荐")]), a("div", [a("div", {
          staticClass: "foot-item foot-active",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("✚已购买")])]), a("div", {
          staticClass: "foot-item",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [t._v("返回顶部")])]), a("Modal", {
          attrs: {
            transfer: !1,
            closable: !1,
            "class-name": "tanchaunga",
            "footer-hide": !1,
            styles: {
              top: "70px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "background-color": "#757575",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            "max-height": "250px",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            id: "temp24203",
            src: t.ds_img
          }
        }), a("div", {
          staticStyle: {
            color: "white",
            "textt-align": "left"
          }
        }, [t._v(t._s(t.ds_title))])]), t._l(t.pay.pay, (function(e, i) {
          return a("div", {
            key: i
          }, [a("div", {
            staticClass: "buy-video-btn-n",
            staticStyle: {
              "text-align": "center",
              width: "100%",
              float: "right",
              "font-weight": "bold"
            },
            style: t._f("aaa")(e.css),
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          })])
        })), a("div", [a("div", {
          staticClass: "buy-video-btn-n",
          staticStyle: {
            "text-align": "center",
            width: "100%",
            float: "left",
            "background-color": "#767676",
            color: "#F4F5F2",
            "font-weight": "bold",
            height: "36px",
            "line-height": "36px",
            "border-radius": "15px",
            "margin-top": "10px"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("取消支付")])]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        })], 2), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.model
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      w = [],
      k = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            murmur: localStorage.getItem("fingerprint"),
            model: "",
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            user: [],
            value8: !1,
            url: "",
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            ds_money: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        beforeCreate: function() {
          var t = function() {
            if (document) {
              var t = document.documentElement,
                e = t.getBoundingClientRect().width * (750 / 352);
              t.style.fontSize = e / 16 + "px"
            }
          };
          t(), window.onresize = function() {
            t()
          }
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage.getItem("fingerprint"), this
              .$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, e.ds_money = t.money, 1 != t.pay ? this.$axios
              .post(e.domain + "/index/index/pays/", {
                f: e.f,
                vid: t.id,
                money: t.money,
                murmur: localStorage.getItem("fingerprint")
              }).then((function(t) {
                e.pay = t.data, e.modal2 = !0, e.user = t.data.user
              })) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.model = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }))) : this.$Message.error("暂未开通该支付渠道")
          },
          linkTo: function(t) {
            var e = this;
            if (this.url = t, "-" != this.user.pay_model && "-" != this.user.pay_model1) return this.url = t,
              void(this.value8 = !0);
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      x = k,
      S = (a("3cd2"), Object(y["a"])(x, b, w, !1, null, "6f260491", null)),
      E = S.exports,
      I = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item",
            class: t.activeClass == e.id ? "active" : "",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        })), a("div", {
          key: "99",
          staticClass: "type-item ",
          class: 99 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])], 2), a("div", {
          staticClass: "type-search mt20"
        }, [a("span", {
          staticClass: "type-have",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [t._v("今日更新")]), a("div", [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })]), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")]), a("div", {
          staticClass: "yigou1",
          staticStyle: {
            "margin-left": "17px",
            width: "52px",
            "text-align": "center"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])])]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left"
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("已有" + t._s(e.read_num) + "人进行播放")])]), a("span", {
            staticClass: "img-tips-left",
            staticStyle: {
              top: "0",
              height: "0.28rem",
              "line-height": "0.28rem"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "titles"
          }, [t._v(t._s(e.title))])])])
        }))], 2)], 1), a("div", {
          staticClass: "foot",
          staticStyle: {
            "margin-left": "-5px"
          }
        }, [a("div", {
          staticClass: "type-item foot-item",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("•热门推荐")]), a("div", [a("div", {
          staticClass: "foot-item foot-active",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("✚已购买")])]), a("div", {
          staticClass: "foot-item",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [t._v("返回顶部")])]), a("Modal", {
          attrs: {
            transfer: !1,
            closable: !1,
            "class-name": "tanchaunga",
            "footer-hide": !1,
            styles: {
              top: "70px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "background-color": "#757575",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            "max-height": "250px",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            id: "temp24203",
            src: t.ds_img
          }
        }), a("div", {
          staticStyle: {
            color: "white",
            "textt-align": "left"
          }
        }, [t._v(t._s(t.ds_title))])]), t._l(t.pay.pay, (function(e, i) {
          return a("div", {
            key: i
          }, [a("div", {
            staticClass: "buy-video-btn-n",
            staticStyle: {
              "text-align": "center",
              width: "100%",
              float: "right",
              "font-weight": "bold"
            },
            style: t._f("aaa")(e.css),
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          })])
        })), a("div", [a("div", {
          staticClass: "buy-video-btn-n",
          staticStyle: {
            "text-align": "center",
            width: "100%",
            float: "left",
            "background-color": "#767676",
            color: "#F4F5F2",
            "font-weight": "bold",
            height: "36px",
            "line-height": "36px",
            "border-radius": "15px",
            "margin-top": "10px"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("取消支付")])]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        })], 2), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.model
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      B = [],
      P = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            murmur: localStorage.getItem("fingerprint"),
            model: "",
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            user: [],
            value8: !1,
            url: "",
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "打赏后观影",
            ds_img: "",
            ds_money: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        beforeCreate: function() {
          var t = function() {
            if (document) {
              var t = document.documentElement,
                e = t.getBoundingClientRect().width * (750 / 352);
              t.style.fontSize = e / 16 + "px"
            }
          };
          t(), window.onresize = function() {
            t()
          }
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage.getItem("fingerprint"), this
              .$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, e.ds_money = t.money, 1 != t.pay ? this.$axios
              .post(e.domain + "/index/index/pays/", {
                f: e.f,
                vid: t.id,
                money: t.money,
                murmur: localStorage.getItem("fingerprint")
              }).then((function(t) {
                e.pay = t.data, e.modal2 = !0, e.user = t.data.user
              })) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.model = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }))) : this.$Message.error("暂未开通该支付渠道")
          },
          linkTo: function(t) {
            var e = this;
            if (this.url = t, "-" != this.user.pay_model && "-" != this.user.pay_model1) return this.url = t,
              void(this.value8 = !0);
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      z = P,
      U = (a("7f0d"), Object(y["a"])(z, I, B, !1, null, "1ed196d5", null)),
      O = U.exports,
      M = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          ref: "header",
          staticClass: "headers"
        }, [a("li", {
          staticClass: "agency",
          on: {
            click: t.doFav
          }
        }, [t._m(0)]), a("router-link", {
          attrs: {
            to: "/cat"
          }
        }, [a("li", {
          staticClass: "search"
        }, [t._v("搜索关键字.......")])]), a("li", {
          staticClass: "request"
        }, [a("router-link", {
          attrs: {
            to: "/tousu"
          }
        }, [a("span", {
          staticClass: "Request-btn",
          staticStyle: {
            "font-size": "0.8rem",
            color: "rgb(84 95 116)",
            "font-weight": "500"
          }
        }, [t._v("投诉")])])], 1)], 1), a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp,
            hardwareClass: "asd"
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "4"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("Carousel", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: t.lunbo,
            expression: "lunbo"
          }],
          attrs: {
            autoplay: "",
            loop: ""
          },
          model: {
            value: t.value2,
            callback: function(e) {
              t.value2 = e
            },
            expression: "value2"
          }
        }, [a("CarouselItem", [a("div", {
          staticClass: "demo-carousel"
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.l1
          }
        })])]), a("CarouselItem", [a("div", {
          staticClass: "demo-carousel"
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.l2
          }
        })])])], 1), a("div", {
          ref: "channel",
          staticClass: "channel"
        }, [a("ul", [a("li", {
          staticClass: "licat",
          attrs: {
            "data-cid": "0"
          }
        }, [a("a", {
          key: "-1",
          class: -1 == t.activeClass ? "active" : "",
          staticStyle: {
            color: "black"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [a("div", {
          staticClass: "thumb"
        }, [a("img", {
          attrs: {
            src: t.allImg,
            width: "48",
            height: "48"
          }
        })]), a("span", [t._v("全部")])])]), t._l(t.cat, (function(e, i) {
          return a("li", {
            key: i,
            staticClass: "licat",
            attrs: {
              "data-cid": "0"
            }
          }, [a("a", {
            staticStyle: {
              color: "black"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [a("img", {
            attrs: {
              src: t.ico[i],
              width: "48",
              height: "48"
            }
          }), a("span", [t._v(t._s(e.title))])])])
        })), a("div", {
          staticStyle: {
            clear: "both"
          }
        })], 2)]), a("div", {
          ref: "NoticeBox",
          staticClass: "NoticeBox"
        }, [a("div", {
          staticClass: "NoticeContentBox",
          staticStyle: {
            overflow: "hidden",
            position: "relative"
          }
        }, [a("marquee", [a("span", {
          staticStyle: {
            color: "orange"
          }
        }, [t._v("温馨提示：如果付款没有跳转，请到已购买里观看。保存链接或二维码，长期免费观看")])])], 1)]), t._l(t.dataList, (function(
          e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))]), a("span", {
            staticClass: "desc"
          }, [t._v(t._s(e.read_num) + "人观看  好评:" + t._s(e.read_num1) + "%")])])])
        }))], 2)], 1)]), a("van-tabbar", {
          attrs: {
            route: "",
            "active-color": "#ee0a24",
            fixed: !0,
            "inactive-color": "#000"
          },
          on: {
            change: t.dsp
          },
          model: {
            value: t.footerActiveClass,
            callback: function(e) {
              t.footerActiveClass = e
            },
            expression: "footerActiveClass"
          }
        }, [a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/",
            icon: "home-o",
            name: "首页"
          }
        }, [t._v("首页")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/cat",
            icon: "apps-o",
            name: "分类"
          }
        }, [t._v("分类")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            icon: "like-o",
            name: "短视频"
          }
        }, [t._v("短视频")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/buy",
            icon: "shopping-cart-o",
            name: "已购"
          }
        }, [t._v("已购")])], 1), a("Modal", {
          attrs: {
            closable: !1,
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            src: t.ds_img
          }
        }), a("img", {
          staticStyle: {
            position: "relative",
            bottom: "70%"
          },
          attrs: {
            src: t.player_img
          },
          on: {
            click: function(e) {
              return t.sb()
            }
          }
        })]), a("span", {
          staticStyle: {
            "text-align": "left",
            "font-weight": "bold",
            display: "block",
            position: "relative",
            top: "10px"
          }
        }, [t._v(t._s(t.ds_title))]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              long: ""
            },
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          })
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg1",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: ""
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("我在想想 ")])], 1)]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.shoucang,
            callback: function(e) {
              t.shoucang = e
            },
            expression: "shoucang"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v("网站通知")])]), a("div", {
          staticClass: "qrcode",
          staticStyle: {
            "text-align": "center"
          }
        }, [a("p", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: 0 == t.zbkg,
            expression: "zbkg == 0"
          }],
          staticStyle: {
            color: "#f74550",
            "line-height": "25px",
            "margin-top": "10px",
            "text-align": "center"
          }
        }, [t._v(" 当前时间段主播不在线，未开启直播状态 ")]), a("img", {
          staticStyle: {
            display: "inline-block",
            "max-height": "350px"
          },
          attrs: {
            src: t.fav.url
          }
        })]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.shoucang = !1
            }
          }
        }, [t._v("关闭 ")])], 1)]), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.model
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      L = [function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", {
          staticClass: "collect-btn",
          staticStyle: {
            "font-size": "0.8rem",
            "font-weight": "500"
          }
        }, [t._v("直播"), a("i")])
      }],
      R = a("b45a"),
      Q = a.n(R),
      D = a("0ca1"),
      T = a.n(D),
      K = a("b9af"),
      q = a.n(K),
      N = a("c2e4"),
      W = a.n(N),
      j = a("1105"),
      H = a.n(j),
      V = a("9310"),
      F = a.n(V),
      Y = a("2175"),
      G = a.n(Y),
      Z = a("34bf"),
      J = a.n(Z),
      X = a("f8bf"),
      $ = a.n(X),
      tt = a("15b3"),
      et = a.n(tt),
      at = a("5d8c"),
      it = a.n(at),
      st = a("a549"),
      ot = a.n(st),
      nt = a("03c5"),
      lt = a.n(nt),
      rt = a("4369"),
      ct = a.n(rt),
      dt = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            murmur: localStorage.getItem("fingerprint"),
            model: "",
            player_img: ct.a,
            menu: "首页",
            active: 1,
            lunbo: !0,
            value2: 1,
            zbkg: localStorage.getItem("zbkg"),
            l1: ot.a,
            l2: lt.a,
            ico: [T.a, q.a, W.a, H.a, F.a, G.a, J.a, $.a, et.a, it.a],
            fav: {
              url: ""
            },
            shoucang: !1,
            footerActiveClass: 0,
            allImg: Q.a,
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            user: [],
            value8: !1,
            url: "",
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 9,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          doRate: function() {
            return parseInt(10 * Math.random() + 90)
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.activeClass = -1, o.footerActiveClass = 1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (o
                .footerActiveClass = 3, this.dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "",
                t.payed = "1"), "string" == n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num =
                1, t.cid = "", t.key = o.params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage
              .getItem("fingerprint"), this.$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length), e.removeEmpty()
                  }))
              })).catch((function() {
                s.$Spin.hide(), e.endErr()
              }))
          },
          sb: function() {
            this.$Message.warning("请先购买后观看哦。")
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$axios.post(e.domain +
              "/index/index/pays/", {
                f: e.f,
                vid: t.id,
                money: t.money,
                murmur: localStorage.getItem("fingerprint")
              }).then((function(t) {
              e.pay = t.data.pay, e.modal2 = !0, e.user = t.data.user
            })) : this.$router.push("/v/" + t.id)
          },
          dsp: function(t) {
            return 1 == t ? 1 == localStorage.getItem("zbkg") ? (this.$router.push({
              name: "zb"
            }), !1) : (this.$Message.warning("暂未开启"), !1) : "短视频" == t ? (this.$router.push({
              name: "site"
            }), !1) : void 0
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.model = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }), 1500)) : this.$Message.error("暂未开通该支付渠道")
          },
          linkTo: function(t) {
            var e = this;
            if (this.url = t, "-" != this.user.pay_model && "-" != this.user.pay_model1) return this.url = t,
              void(this.value8 = !0);
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0,
                i = 0,
                s = 0,
                o = 0,
                n = 0;
              void 0 != e.hezi && e.hezi, t.tops.top = a + i + s + o + 45 + n + "px"
            }))
          },
          getHezi: function() {
            this.lunbo = !1, this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          },
          doFav: function() {
            var t = localStorage.getItem("zbkg");
            if (1 == t) return this.$router.push({
              name: "zb"
            }), !1;
            this.fav.url = localStorage.getItem("zb_t_img"), this.shoucang = !0
          },
          search: function() {},
          tousu: function() {
            location.href = this.domain + "/tousu?id=" + this.f
          }
        },
        watch: {
          cat: function() {
            this.changeHeight()
          },
          hezi: function(t) {
            this.changeHeight(), this.getHezi(), void 0 != t && null != t && "" != t || (this.lunbo = !0)
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      ut = dt,
      pt = (a("a418"), Object(y["a"])(ut, M, L, !1, null, "8ba79aae", null)),
      mt = pt.exports,
      ht = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          ref: "header",
          staticClass: "headers"
        }, [a("li", {
          staticClass: "agency",
          on: {
            click: t.doFav
          }
        }, [t._m(0)]), a("router-link", {
          attrs: {
            to: "/cat"
          }
        }, [a("li", {
          staticClass: "search"
        }, [t._v("搜索关键字.......")])]), a("li", {
          staticClass: "request"
        }, [a("router-link", {
          attrs: {
            to: "/tousu"
          }
        }, [a("span", {
          staticClass: "Request-btn",
          staticStyle: {
            "font-size": "0.8rem",
            color: "#FFFFFF",
            "font-weight": "500"
          }
        }, [t._v("投诉")])])], 1)], 1), a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp,
            hardwareClass: "asd"
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "4"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div"), a("div", {
          ref: "channel",
          staticClass: "channel"
        }, [a("ul", [a("li", {
          staticClass: "licat",
          attrs: {
            "data-cid": "0"
          }
        }, [a("a", {
          key: "-1",
          class: -1 == t.activeClass ? "active" : "",
          staticStyle: {
            color: "black"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [a("div", {
          staticClass: "thumb"
        }, [a("img", {
          attrs: {
            src: t.allImg,
            width: "48",
            height: "48"
          }
        })]), a("span", [t._v("全部")])])]), t._l(t.cat, (function(e, i) {
          return a("li", {
            key: i,
            staticClass: "licat",
            attrs: {
              "data-cid": "0"
            }
          }, [a("a", {
            staticStyle: {
              color: "black"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [a("img", {
            attrs: {
              src: t.ico[i],
              width: "48",
              height: "48"
            }
          }), a("span", [t._v(t._s(e.title))])])])
        })), a("div", {
          staticStyle: {
            clear: "both"
          }
        })], 2)]), a("div", {
          ref: "NoticeBox",
          staticClass: "NoticeBox"
        }, [a("div", {
          staticClass: "NoticeContentBox",
          staticStyle: {
            overflow: "hidden",
            position: "relative"
          }
        }, [a("marquee", [a("span", {
          staticStyle: {
            color: "orange"
          }
        }, [t._v("温馨提示：如果付款没有跳转，请到已购买里观看。保存链接或二维码，长期免费观看")])])], 1)]), t._l(t.dataList, (function(
          e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))]), a("span", {
            staticClass: "desc"
          }, [t._v(t._s(e.read_num) + "人观看  好评:" + t._s(e.read_num1) + "%")])])])
        }))], 2)], 1)]), a("van-tabbar", {
          attrs: {
            route: "",
            "active-color": "#ee0a24",
            fixed: !0,
            "inactive-color": "#000"
          },
          on: {
            change: t.dsp
          },
          model: {
            value: t.footerActiveClass,
            callback: function(e) {
              t.footerActiveClass = e
            },
            expression: "footerActiveClass"
          }
        }, [a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/",
            icon: "home-o",
            name: "首页"
          }
        }, [t._v("首页")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/cat",
            icon: "apps-o",
            name: "分类"
          }
        }, [t._v("分类")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/buy",
            icon: "shopping-cart-o",
            name: "已购"
          }
        }, [t._v("已购")])], 1), a("Modal", {
          attrs: {
            closable: !1,
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            src: t.ds_img
          }
        }), a("img", {
          staticStyle: {
            position: "relative",
            bottom: "70%"
          },
          attrs: {
            src: t.player_img
          },
          on: {
            click: function(e) {
              return t.sb()
            }
          }
        })]), a("span", {
          staticStyle: {
            "text-align": "left",
            "font-weight": "bold",
            display: "block",
            position: "relative",
            top: "10px"
          }
        }, [t._v(t._s(t.ds_title))]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              long: ""
            },
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          })
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg1",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: ""
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("我在想想 ")])], 1)]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.shoucang,
            callback: function(e) {
              t.shoucang = e
            },
            expression: "shoucang"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v("网站通知")])]), a("div", {
          staticClass: "qrcode",
          staticStyle: {
            "text-align": "center"
          }
        }, [a("p", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: 0 == t.zbkg,
            expression: "zbkg == 0"
          }],
          staticStyle: {
            color: "#f74550",
            "line-height": "25px",
            "margin-top": "10px",
            "text-align": "center"
          }
        }, [t._v(" 当前时间段主播不在线，未开启直播状态 ")]), a("img", {
          staticStyle: {
            display: "inline-block",
            "max-height": "350px"
          },
          attrs: {
            src: t.fav.url
          },
          on: {
            click: function(e) {
              return t.dsp(1)
            }
          }
        })]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.shoucang = !1
            }
          }
        }, [t._v("关闭 ")])], 1)]), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.model
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      gt = [function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", {
          staticClass: "collect-btn",
          staticStyle: {
            "font-size": "0.8rem",
            "font-weight": "500"
          }
        }, [t._v("直播"), a("i")])
      }],
      ft = a("d285"),
      vt = a.n(ft),
      _t = a("85f2"),
      yt = a.n(_t),
      At = a("8c57"),
      Ct = a.n(At),
      bt = a("7062"),
      wt = a.n(bt),
      kt = a("c80d"),
      xt = a.n(kt),
      St = a("32cd"),
      Et = a.n(St),
      It = a("f15b"),
      Bt = a.n(It),
      Pt = a("cb99"),
      zt = a.n(Pt),
      Ut = a("50ed"),
      Ot = a.n(Ut),
      Mt = a("e83c"),
      Lt = a.n(Mt),
      Rt = a("9366"),
      Qt = a.n(Rt),
      Dt = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            murmur: localStorage.getItem("fingerprint"),
            model: "",
            player_img: ct.a,
            menu: "首页",
            active: 1,
            lunbo: !0,
            value2: 1,
            zbkg: localStorage.getItem("zbkg"),
            l1: ot.a,
            l2: lt.a,
            ico: [yt.a, Ct.a, wt.a, xt.a, Et.a, Bt.a, zt.a, Ot.a, Lt.a, Qt.a],
            fav: {
              url: ""
            },
            shoucang: !1,
            footerActiveClass: 0,
            allImg: vt.a,
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            user: [],
            value8: !1,
            url: "",
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 9,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          doRate: function() {
            return parseInt(10 * Math.random() + 90)
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.activeClass = -1, o.footerActiveClass = 1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (o
                .footerActiveClass = 3, this.dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "",
                t.payed = "1"), "string" == n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num =
                1, t.cid = "", t.key = o.params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage
              .getItem("fingerprint"), this.$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length), e.removeEmpty()
                  }))
              })).catch((function() {
                s.$Spin.hide(), e.endErr()
              }))
          },
          sb: function() {
            this.$Message.warning("请先购买后观看哦。")
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$axios.post(e.domain +
              "/index/index/pays/", {
                f: e.f,
                vid: t.id,
                money: t.money,
                murmur: localStorage.getItem("fingerprint")
              }).then((function(t) {
              e.pay = t.data.pay, e.modal2 = !0, e.user = t.data.user
            })) : this.$router.push("/v/" + t.id)
          },
          dsp: function(t) {
            return 1 == t ? 1 == localStorage.getItem("zbkg") ? (this.$router.push({
              name: "zb"
            }), !1) : (this.$Message.warning("暂未开启"), !1) : "短视频" == t ? (this.$router.push({
              name: "site"
            }), !1) : void 0
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.model = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }), 1500)) : this.$Message.error("暂未开通该支付渠道")
          },
          linkTo: function(t) {
            var e = this;
            if (this.url = t, "-" != this.user.pay_model && "-" != this.user.pay_model1) return this.url = t,
              void(this.value8 = !0);
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0,
                i = 0,
                s = 0,
                o = 0,
                n = 0;
              void 0 != e.hezi && e.hezi, t.tops.top = a + i + s + o + 45 + n + "px"
            }))
          },
          getHezi: function() {
            this.lunbo = !1, this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          },
          doFav: function() {
            var t = localStorage.getItem("zbkg");
            if (1 == t) return this.$router.push({
              name: "zb"
            }), !1;
            this.fav.url = localStorage.getItem("zb_t_img"), this.shoucang = !0
          },
          search: function() {},
          tousu: function() {
            location.href = this.domain + "/tousu?id=" + this.f
          }
        },
        watch: {
          cat: function() {
            this.changeHeight()
          },
          hezi: function(t) {
            this.changeHeight(), this.getHezi(), void 0 != t && null != t && "" != t || (this.lunbo = !0)
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      Tt = Dt,
      Kt = (a("c8d3"), Object(y["a"])(Tt, ht, gt, !1, null, "935260a2", null)),
      qt = Kt.exports,
      Nt = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("换一批 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item",
            class: t.activeClass == e.id ? "active" : "",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        }))], 2), a("div", {
          staticClass: "type-search"
        }, [a("div", {
          key: "-1",
          staticClass: "type-search-type-item",
          staticStyle: {
            margin: "0",
            height: "100%",
            "padding-top": "5px"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已付款 ")]), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        }), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("点我搜索 ▹")])])]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-time",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("span", {
            staticClass: "img-tips-left"
          }, [t._v(t._s(e.rand) + "人已购买")]), a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))])])])
        }))], 2)], 1), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticStyle: {
            "background-image": "linear-gradient(to right, #ff0030, #c000ff)",
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      Wt = [],
      jt = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            scrollTop: 0,
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: " 0px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "打赏后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              onScroll: this.onScroll,
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        activated: function() {
          this.$refs.mescroll.mescroll.scrollTo(this.scrollTop, 0)
        },
        methods: {
          onScroll: function(t, e) {
            this.scrollTop = e
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage.getItem("fingerprint"), this
              .$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function() {
            var t = this;
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              t.$Spin.hide()
            }), 5e3), setTimeout((function() {
              t.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      Ht = jt,
      Vt = (a("f1a1"), Object(y["a"])(Ht, Nt, Wt, !1, null, "6e1cd12e", null)),
      Ft = Vt.exports,
      Yt = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("换一批 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item",
            class: t.activeClass == e.id ? "active" : "",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        }))], 2), a("div", {
          staticClass: "type-search"
        }, [a("div", {
          key: "-1",
          staticClass: "type-search-type-item",
          staticStyle: {
            margin: "0",
            height: "80%",
            "padding-top": "5px"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已付款 ")]), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        }), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("点我搜索 ▹")])])]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-time",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("span", {
            staticClass: "img-tips-left"
          }, [t._v(t._s(e.rand) + "人已购买")]), a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))])])])
        }))], 2)], 1), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticStyle: {
            "background-image": "linear-gradient(to right, #ff0030, #c000ff)",
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      Gt = [],
      Zt = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            scrollTop: 0,
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: " 0px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              onScroll: this.onScroll,
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        activated: function() {
          this.$refs.mescroll.mescroll.scrollTo(this.scrollTop, 0)
        },
        methods: {
          onScroll: function(t, e) {
            this.scrollTop = e
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, localStorage.getItem("fingerprint"), this.$axios.post(
                o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function(t) {
            var e = this;
            this.url = t;
            var a = this;
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), console.log(a.domain + t), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      Jt = Zt,
      Xt = (a("caa7"), Object(y["a"])(Jt, Yt, Gt, !1, null, "b5e4428c", null)),
      $t = Xt.exports,
      te = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item",
            class: t.activeClass == e.id ? "active" : "",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        })), a("div", {
          key: "99",
          staticClass: "type-item ",
          class: 99 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])], 2), a("div", {
          staticClass: "type-search mt20"
        }, [a("span", {
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("今日更新")]), a("div", {
          staticStyle: {
            position: "relative",
            left: "13px"
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })]), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")])]), a("div", {
          staticClass: "menu-list mt20"
        }, [a("span", {
          staticClass: "menu-list-btn",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("日播放榜")]), a("span", {
          staticClass: "menu-list-btn",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("月播放榜")]), a("span", {}, [a("img", {
          staticStyle: {
            position: "relative",
            left: "43%",
            top: "7px"
          },
          attrs: {
            width: "24",
            height: "24",
            src: t.menu_img
          },
          on: {
            click: function(e) {
              return t.menu_qiehuan()
            }
          }
        })])])]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            class: {
              menu_active: t.is_kk,
              cc_panel_detail: t.is_on
            },
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            class: {
              cc_panel_detail_image_wapper_active: t.is_kk,
              cc_panel_detail_image_wapper: t.is_on
            }
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left"
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("已有" + t._s(e.read_num) + "人进行播放")])]), a("span", {
            staticClass: "img-tips-time",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))])])])
        }))], 2)], 1), a("div", {
          staticClass: "foot",
          staticStyle: {
            "margin-left": "-5px"
          }
        }, [a("div", {
          staticClass: "type-item foot-item",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [a("img", {
          staticStyle: {
            "margin-right": "5px"
          },
          attrs: {
            width: "20",
            height: "20",
            src: "data:image/png;base64,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"
          }
        }), t._v("热门推荐")]), a("div", [a("div", {
          staticClass: "foot-item ",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [a("img", {
          staticStyle: {
            "margin-right": "5px"
          },
          attrs: {
            width: "20",
            height: "20",
            src: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAADuUlEQVR4Xu2bOaxNURSGv/WMUZBIDIWhQEGESiExd2iJKRJREMkrSCR4GhUhMRQSNBIRc+91hkeiUBFDgeLdKAyJhELEtGRxHif33TPcM9xzbqzT3j1+599r/2fftQV/YgmI84kn4IASFOKAHFC+IOIKcgW5gvIRcAXl4+cxyBXkCspHIElBqjoe6AV2ADPb7O0dsA+4KCI/WtVV1RHAVuAYMKnN9qsq3gDOAadFVfuAQ8CojKN5AqwVEWt02KOq04GbwPyM7VdV7ZtxMUCDwIwcozDlHBSRoxGATGGHgZ4cfVRVtWGAtIDeDdI2oB/4GLRnS3cNcKFL4fyeRlGArK0vwB3gqbULzANWAGMLeAGVNVEkoHYmYar9GarQ7Mei/FnHfVscoB4RGbb8VHUpcB2Y2g6RUNm3wDoRuZ+xfinVokJNJCARafm2VHU0sAmwoDylzdEaHAval0XEdonaPIUBshmpqlmCxcBZYG7KWT4DdgEP6gYnmFPLzaptBYVhBCZzD7AhBtRz4BpwUkQ+pYTZ8WKFKqh59Kpq8egVMK7pt8/ALBF50/EZt9lhqYACid4CVjaN67aIrGpzrJUU7wSgRcBVYFoww9fARhF5mDRjZfV4+NwLujOnq4/qqgFyDsadFvpbLvNOALJPiQXAMovjwD3gsYiE/U7LCSjL+0DzfA8mvQP7/RvIIeGuffYMe0oHlGaEUWWUZXm/B9N23xAGWp5YOKA/CKsDpKpDS8yctpnMAV9iIWGrqgfpuIWuqr7NJwCy49fmI9X3IjI5bQStslzpQdoVlPB6axKDEg1hpNWIOFnN9bEa7iy0i1VtFGMNYWWA8sSPEoxipN9xQAmGsDJANTKK9VxiHqSTdzE3im4UQwSi/tWICXKuoAQF1eFj1Y1iCr9Vz10sxcAji7hRTNoBiz9yrZ+TdqOYpIJ6nCjWOkj7Nh/jgfyv51ZwPHkhQjKh9JczQapdml3+/0h/CeBszphAZVke+4ErIvI1DdVOlSns0D5IwbuRIbtsaK4GaX3Xp+CV/OaSkjjD3ReRuJm5jSKzXG3JWEbHo2B2C4ElwJiSYZfafJGAdgOXQonkE4AtwKlSZ1By40VcRbDlckJE9kZYgeOA5TFmlnnJDOKaHyziMssLu3IgIi8jAM0OLrPMqXCiWbr+e5ll6DpUlvS3D8AB4LyIfI8ANBLYDhwBJmYZaQV1/l2HqqDzruqyG+NCRwE7oATcDsgB5VuRriBXkCsoHwFXUD5+HoNcQa6gfAQSav8CjwaxfkW4nOoAAAAASUVORK5CYII="
          }
        }), t._v(" 已购")])]), a("div", {
          staticClass: "foot-item",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [a("img", {
          staticStyle: {
            "margin-right": "5px"
          },
          attrs: {
            width: "20",
            height: "20",
            src: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAADuUlEQVR4Xu2bOaxNURSGv/WMUZBIDIWhQEGESiExd2iJKRJREMkrSCR4GhUhMRQSNBIRc+91hkeiUBFDgeLdKAyJhELEtGRxHif33TPcM9xzbqzT3j1+599r/2fftQV/YgmI84kn4IASFOKAHFC+IOIKcgW5gvIRcAXl4+cxyBXkCspHIElBqjoe6AV2ADPb7O0dsA+4KCI/WtVV1RHAVuAYMKnN9qsq3gDOAadFVfuAQ8CojKN5AqwVEWt02KOq04GbwPyM7VdV7ZtxMUCDwIwcozDlHBSRoxGATGGHgZ4cfVRVtWGAtIDeDdI2oB/4GLRnS3cNcKFL4fyeRlGArK0vwB3gqbULzANWAGMLeAGVNVEkoHYmYar9GarQ7Mei/FnHfVscoB4RGbb8VHUpcB2Y2g6RUNm3wDoRuZ+xfinVokJNJCARafm2VHU0sAmwoDylzdEaHAval0XEdonaPIUBshmpqlmCxcBZYG7KWT4DdgEP6gYnmFPLzaptBYVhBCZzD7AhBtRz4BpwUkQ+pYTZ8WKFKqh59Kpq8egVMK7pt8/ALBF50/EZt9lhqYACid4CVjaN67aIrGpzrJUU7wSgRcBVYFoww9fARhF5mDRjZfV4+NwLujOnq4/qqgFyDsadFvpbLvNOALJPiQXAMovjwD3gsYiE/U7LCSjL+0DzfA8mvQP7/RvIIeGuffYMe0oHlGaEUWWUZXm/B9N23xAGWp5YOKA/CKsDpKpDS8yctpnMAV9iIWGrqgfpuIWuqr7NJwCy49fmI9X3IjI5bQStslzpQdoVlPB6axKDEg1hpNWIOFnN9bEa7iy0i1VtFGMNYWWA8sSPEoxipN9xQAmGsDJANTKK9VxiHqSTdzE3im4UQwSi/tWICXKuoAQF1eFj1Y1iCr9Vz10sxcAji7hRTNoBiz9yrZ+TdqOYpIJ6nCjWOkj7Nh/jgfyv51ZwPHkhQjKh9JczQapdml3+/0h/CeBszphAZVke+4ErIvI1DdVOlSns0D5IwbuRIbtsaK4GaX3Xp+CV/OaSkjjD3ReRuJm5jSKzXG3JWEbHo2B2C4ElwJiSYZfafJGAdgOXQonkE4AtwKlSZ1By40VcRbDlckJE9kZYgeOA5TFmlnnJDOKaHyziMssLu3IgIi8jAM0OLrPMqXCiWbr+e5ll6DpUlvS3D8AB4LyIfI8ANBLYDhwBJmYZaQV1/l2HqqDzruqyG+NCRwE7oATcDsgB5VuRriBXkCsoHwFXUD5+HoNcQa6gfAQSav8CjwaxfkW4nOoAAAAASUVORK5CYII="
          }
        }), t._v("返回顶部")])]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticStyle: {
            "background-image": "linear-gradient(to right, #ff0030, #c000ff)",
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      ee = [],
      ae = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            scrollTop: 0,
            menu_img: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII=",
            is_on: !0,
            is_kk: !1,
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "打赏后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              onScroll: this.onScroll,
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        activated: function() {
          this.$refs.mescroll.mescroll.scrollTo(this.scrollTop, 0)
        },
        methods: {
          onScroll: function(t, e) {
            this.scrollTop = e
          },
          menu_qiehuan: function() {
            this.is_on ? (this.is_on = !1, this.is_kk = !0, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAAC6klEQVR4Xu2aPWzUQBCF33MkOpCgIAUpiUSFBPRp+GmhBlFTARIkdZIaiARU1BHU0PLT0BMkqkhQQgEFSNAhnR9auKCLc/Fszjaco+fWu+PdzzOznvEjfNUSoPnUEzCgwEMMyICaJRF7kD3IHtSMQBceJOkogHMA5gEc6nSFzY1/B/AewCuSn/dqLjsHSUpjjwG4KekagIN7fdh/Hv+D5CMA9wF8Iqmc9WQBGsJZkLQG4HSO4WkdQ/INgNsAXudAygU0J+kZgFMAsuZMKyAAIrkB4BLJj9E6szYr6Y6kxchYn+6TvEtyKVpzCCglZEkfqjmH5GZZli8AfC2KIiueo8W0fb8sSxZFcVjSBQAnKvZTTpqPEncOoMuSHo8aT3AAXAXwjuTPtjfWpj1JBwCclLRehUTyCskndc/LAbQsaWXUiKSHRVEsTjucrTUPIaU0caPyoldIrjYFdE/SrQqg1ZmZmW3Q2nzrXdgaDAYJxnIF0BrJdKLteuV40A5AJEPyXWyyiU1JOyKBpAGNhJkB1XmYPSiIPwMyoCYpGrAH2YN65EGS/KEYfD88ALDUp1KjLMtUvV/votTYUawC2CTZq2IVwLqkbRV9W8XqrKTU0622WBOk52VZfpvmdgeAI0VRnK/CAZDaHcdJfmlUi6XJbpjFR+QcgKeSUj86LHCbnTedz07NvbckL7bZck1QFgCkyv5M51vo9gEbJFP7pr2m/TDM/Nsn98VJmgVwtmc/Dl9GCXnc/vueT3Lf6cTjDChAZ0AGNHF0/Z44kQdZ3TEGutUdNZ5odUdGqWF1R70HWd2xGx+rO+Lwsrpjr38kre4YISbJ6o7Ag6zuMKDdCYSlxrgQsz4oyEEGZEB/CTjErFH84wyWv8QVwb/TKFrdsT1JjyNvdccWI0lWdwRf0lZ3RH1/qzviE8DqjiDMrO7ICDOrOyJIIyeb1R25sPb7uLBY3e8Aov0ZUEDIgAwoCqL6+/Yge5A9qBmBYPYvluHiZ6eIxCwAAAAASUVORK5CYII="
              ) : (this.is_on = !0, this.is_kk = !1, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII="
              )
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage.getItem("fingerprint"), this
              .$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function(t) {
            var e = this;
            this.url = t, this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      ie = ae,
      se = (a("6e1e"), Object(y["a"])(ie, te, ee, !1, null, "99213272", null)),
      oe = se.exports,
      ne = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item",
            class: t.activeClass == e.id ? "active" : "",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        })), a("div", {
          key: "99",
          staticClass: "type-item ",
          class: 99 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购 ")])], 2), a("div", {
          staticClass: "type-search mt20"
        }, [a("span", {
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("今日更新")]), a("div", {
          staticStyle: {
            position: "relative",
            left: "13px"
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })]), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")])]), a("div", {
          staticClass: "menu-list mt20"
        }, [a("span", {
          staticClass: "menu-list-btn",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("日播放榜")]), a("span", {
          staticClass: "menu-list-btn",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("月播放榜")]), a("span", {}, [a("img", {
          staticStyle: {
            position: "relative",
            left: "43%",
            top: "7px"
          },
          attrs: {
            width: "24",
            height: "24",
            src: t.menu_img
          },
          on: {
            click: function(e) {
              return t.menu_qiehuan()
            }
          }
        })])])]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            class: {
              menu_active: t.is_kk,
              cc_panel_detail: t.is_on
            },
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            class: {
              cc_panel_detail_image_wapper_active: t.is_kk,
              cc_panel_detail_image_wapper: t.is_on
            }
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left"
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("已有" + t._s(e.read_num) + "人进行播放")])]), a("span", {
            staticClass: "img-tips-time",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))])])])
        }))], 2)], 1), a("div", {
          staticClass: "foot",
          staticStyle: {
            "margin-left": "-5px"
          }
        }, [a("div", {
          staticClass: "type-item foot-item",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [a("img", {
          staticStyle: {
            "margin-right": "5px"
          },
          attrs: {
            width: "20",
            height: "20",
            src: "data:image/png;base64,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"
          }
        }), t._v("热门推荐")]), a("div", [a("div", {
          staticClass: "foot-item ",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [a("img", {
          staticStyle: {
            "margin-right": "5px"
          },
          attrs: {
            width: "20",
            height: "20",
            src: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAAD6klEQVR4Xu2bPYwVVRTHf2eVj1CsCS5CAVioBcRAZXATIUCJrUbRmBgLNyZbSGICrg0VBBORYhPYhsQQP9BeOzFqshgqjGKhFruhUFlJtDCExT3mvMyTl/fm483cOzNvs2faN/fj/OZ/z/znvnMFv3IJiPPJJ+CAChTigBxQWBJxBbmCXEFhBFxBYfw8B7mCXEFhBIoUpKrjwDTwOvBoydH+AI4Dl0Tk37S2qvoA8ArwLrClZP9t3b4IzAGzoqozwElgXcXZ/AA8KyLW6cClqjuAz4EnK/bfVrNl42KAFoCdAbMw5bwjImcyAJnCTgFjAWO01XTRAGmE0Q3Sq8AXwF9Jf7Z0jwAfrFI4nTBiAbK+7gBfAT9av8Bu4CCwMcIDaK2LmIDKBGGqXelp0O/HsvxZ474tD9CYiAwsP1XdD3wKbCtDpOfe34HnROTbiu1raZaVajIBiUjq01LV9cBRwJLy1pKzNTiWtD8SEXtLjMwVDZBFpKpmCSaBC8CuIaO8AbwBzI8anCSm1JdVaQX1wkhM5jHghRxQPwGXgfdF5O8hYTZ+W1QF9c9eVS0f/Qps6vvtH+AxEfmt8YhLDlgroESiXwKH+uZ1RUQOl5xrK7c3Aegp4BNgexLhTeBFEblWGPHVP8cRnWaFKURDXH36UNr5DJpDZJanH05d5k0Ask+JPcABy+PAN8D3ItLrd9ID+G5pBrXvwU7yr+mS5c4YkxP22TNw1Q4oKKr5pYValDOQLGWRyYnUHQsH1EmULQJS1e4SM6dtJvProZfY/NIM0sASszH2tbTEVDUsSavapl19SXqMObTdJO2v+bxErKq2/dq/pXpLRB4JSuANNa49SauqK6hAQWE5KIZRHMIQZsXQhIJGxCjmG8LWAAWlithGMcfvOKACQ9gaoNExirLcMZ0ZhrBNQGFJOoZRtKVVYAjbBOSveTeKPQSy/tXIkagryI1imILcKAaZwbzGbhQL0K4FQG4UC0QwEjuKbhRry4JWbxD/v/nudP2v54wH58UL2WC65S/nk1K7YbS/NspfktqglyoWUFmVxwngYxG5OwzVpu6JloOSErzPKlSXdWM1SM+v+hK8mp9cURFn7/AxCjcr9xGzytWWjFV0XE+i2ws8A2yoGXat3ccE9CbwYU8h+UPAy8C5WiOoufMYRxFsuZwVkbfS5qqq7wFWx1hZ5jUzyOt+IcZhlp/tyIGI/JIB6PHkMMsTLQZaZej/D7N0j0NNVTjUcht4G7goIvcyAD0IvAacBjZXmWkLbe4fh2ph8FU15GrMC40CdkAFuB2QAwpbka4gV5ArKIyAKyiMn+cgV5ArKIxAQev/AHdp0n6MtPRhAAAAAElFTkSuQmCC"
          }
        }), t._v(" 已购")])]), a("div", {
          staticClass: "foot-item",
          on: {
            click: function(e) {
              return t.dingbu()
            }
          }
        }, [a("img", {
          staticStyle: {
            "margin-right": "5px"
          },
          attrs: {
            width: "20",
            height: "20",
            src: "data:image/png;base64,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"
          }
        }), t._v("返回顶部")])]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticStyle: {
            "background-image": "linear-gradient(to right, #ff0030, #c000ff)",
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      le = [],
      re = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            scrollTop: 0,
            menu_img: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII=",
            is_on: !0,
            is_kk: !1,
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              onScroll: this.onScroll,
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        activated: function() {
          this.$refs.mescroll.mescroll.scrollTo(this.scrollTop, 0)
        },
        methods: {
          onScroll: function(t, e) {
            this.scrollTop = e
          },
          menu_qiehuan: function() {
            this.is_on ? (this.is_on = !1, this.is_kk = !0, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAAC6klEQVR4Xu2aPWzUQBCF33MkOpCgIAUpiUSFBPRp+GmhBlFTARIkdZIaiARU1BHU0PLT0BMkqkhQQgEFSNAhnR9auKCLc/Fszjaco+fWu+PdzzOznvEjfNUSoPnUEzCgwEMMyICaJRF7kD3IHtSMQBceJOkogHMA5gEc6nSFzY1/B/AewCuSn/dqLjsHSUpjjwG4KekagIN7fdh/Hv+D5CMA9wF8Iqmc9WQBGsJZkLQG4HSO4WkdQ/INgNsAXudAygU0J+kZgFMAsuZMKyAAIrkB4BLJj9E6szYr6Y6kxchYn+6TvEtyKVpzCCglZEkfqjmH5GZZli8AfC2KIiueo8W0fb8sSxZFcVjSBQAnKvZTTpqPEncOoMuSHo8aT3AAXAXwjuTPtjfWpj1JBwCclLRehUTyCskndc/LAbQsaWXUiKSHRVEsTjucrTUPIaU0caPyoldIrjYFdE/SrQqg1ZmZmW3Q2nzrXdgaDAYJxnIF0BrJdKLteuV40A5AJEPyXWyyiU1JOyKBpAGNhJkB1XmYPSiIPwMyoCYpGrAH2YN65EGS/KEYfD88ALDUp1KjLMtUvV/votTYUawC2CTZq2IVwLqkbRV9W8XqrKTU0622WBOk52VZfpvmdgeAI0VRnK/CAZDaHcdJfmlUi6XJbpjFR+QcgKeSUj86LHCbnTedz07NvbckL7bZck1QFgCkyv5M51vo9gEbJFP7pr2m/TDM/Nsn98VJmgVwtmc/Dl9GCXnc/vueT3Lf6cTjDChAZ0AGNHF0/Z44kQdZ3TEGutUdNZ5odUdGqWF1R70HWd2xGx+rO+Lwsrpjr38kre4YISbJ6o7Ag6zuMKDdCYSlxrgQsz4oyEEGZEB/CTjErFH84wyWv8QVwb/TKFrdsT1JjyNvdccWI0lWdwRf0lZ3RH1/qzviE8DqjiDMrO7ICDOrOyJIIyeb1R25sPb7uLBY3e8Aov0ZUEDIgAwoCqL6+/Yge5A9qBmBYPYvluHiZ6eIxCwAAAAASUVORK5CYII="
              ) : (this.is_on = !0, this.is_kk = !1, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII="
              )
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage.getItem("fingerprint"), this
              .$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function(t) {
            var e = this;
            this.url = t, this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      ce = re,
      de = (a("acc9"), Object(y["a"])(ce, ne, le, !1, null, "10d83628", null)),
      ue = de.exports,
      pe = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item active",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        })), t.sliceCat.length > 0 ? a("div", {
          staticClass: "mt20"
        }, [a("div", {
          staticClass: "type-item active ",
          staticStyle: {
            "margin-right": "27%"
          },
          attrs: {
            "data-cid": "0"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, t.sliceCat[0])
            }
          }
        }, [t._v(t._s(t.sliceCat[0].title) + " ")]), a("div", {
          staticClass: "type-item active ",
          staticStyle: {
            margin: "0 auto"
          },
          attrs: {
            "data-cid": "0"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, t.sliceCat[1])
            }
          }
        }, [t._v(t._s(t.sliceCat[1].title) + " ")]), a("div", {
          staticClass: "type-item active ",
          staticStyle: {
            float: "right",
            "margin-left": "1px",
            position: "relative",
            right: "4px"
          },
          attrs: {
            "data-cid": "0"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, t.sliceCat[2])
            }
          }
        }, [t._v(t._s(t.sliceCat[2].title) + " ")])]) : t._e()], 2), a("div", {
          staticClass: "mt20",
          staticStyle: {
            "background-color": "red",
            color: "#000000",
            "font-weight": "600",
            "margin-top": "10px"
          }
        }, [a("div", {
          staticClass: "cc-left",
          staticStyle: {
            float: "left",
            width: "49%"
          },
          on: {
            click: function(e) {
              return t.dsp("短视频")
            }
          }
        }, [a("img", {
          attrs: {
            width: "25",
            height: "25",
            src: "data:image/png;base64,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"
          }
        }), t._v("短视频")]), a("div", {
          staticClass: "cc-right",
          staticStyle: {
            width: "49%"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v(" 已购")])]), a("div", {
          ref: "NoticeBox",
          staticClass: "NoticeBox",
          staticStyle: {
            clear: "both"
          }
        }, [a("div", {
          staticClass: "NoticeContentBox",
          staticStyle: {
            overflow: "hidden",
            position: "relative",
            "background-color": "#fffbe6"
          }
        }, [a("marquee", [a("span", {
          staticStyle: {
            color: "orange"
          }
        }, [t._v("温馨提示：如果付款没有跳转，请到已购买里观看。保存链接或二维码，长期免费观看")])])], 1)])]), t._l(t.dataList, (function(e,
          i) {
          return a("div", {
            key: i,
            class: {
              menu_active: t.is_kk,
              cc_panel_detail: t.is_on
            },
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            class: {
              cc_panel_detail_image_wapper_active: t.is_kk,
              cc_panel_detail_image_wapper: t.is_on
            }
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-time",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px",
              "border-top-left-radius": "15px",
              "padding-left": "5px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))]), a("h4", {
            staticClass: "title"
          }, [a("p", {
            staticStyle: {
              "font-size": "12px",
              "font-weight": "bold",
              "text-align": "center"
            }
          }, [a("Icon", {
            staticStyle: {
              display: "inline-block",
              color: "#ffb800",
              "font-size": "20px"
            },
            attrs: {
              type: "ios-star"
            }
          }), t._v("已有" + t._s(e.read_num) + "人进行播放")], 1)])])])
        }))], 2)], 1), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticStyle: {
            "background-image": "linear-gradient(to right, #ff0030, #c000ff)",
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.shoucang,
            callback: function(e) {
              t.shoucang = e
            },
            expression: "shoucang"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v("保存本站")])]), a("div", {
          staticClass: "qrcode",
          staticStyle: {
            "text-align": "center"
          }
        }, [a("p", {
          staticStyle: {
            color: "#f74550",
            "line-height": "25px",
            "margin-top": "10px",
            "text-align": "center"
          }
        }, [t._v(" 长按保存二维码或复制链接收藏本站 ")])]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#000000",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.shoucang = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      me = [],
      he = (a("bee9"), a("d73d"), {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            fav: {
              url: null
            },
            scrollTop: 0,
            shoucang: !1,
            menu_img: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII=",
            is_on: !0,
            is_kk: !1,
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: "0px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "打赏后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            sliceCat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              onScroll: this.onScroll,
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            alert("4444"), t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          console.log(this.$refs.mescroll), this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        activated: function() {
          this.$refs.mescroll.mescroll.scrollTo(this.scrollTop, 0)
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          onScroll: function(t, e) {
            this.scrollTop = e, console.log(this.scrollTop)
          },
          doFav: function() {
            var t = localStorage.getItem("domain") + "/index/index/index?view_id=" + localStorage.getItem(
              "view_id") + "&f=" + this.f;
            this.fav.url = t, this.shoucang = !0
          },
          menu_qiehuan: function() {
            this.is_on ? (this.is_on = !1, this.is_kk = !0, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAAC6klEQVR4Xu2aPWzUQBCF33MkOpCgIAUpiUSFBPRp+GmhBlFTARIkdZIaiARU1BHU0PLT0BMkqkhQQgEFSNAhnR9auKCLc/Fszjaco+fWu+PdzzOznvEjfNUSoPnUEzCgwEMMyICaJRF7kD3IHtSMQBceJOkogHMA5gEc6nSFzY1/B/AewCuSn/dqLjsHSUpjjwG4KekagIN7fdh/Hv+D5CMA9wF8Iqmc9WQBGsJZkLQG4HSO4WkdQ/INgNsAXudAygU0J+kZgFMAsuZMKyAAIrkB4BLJj9E6szYr6Y6kxchYn+6TvEtyKVpzCCglZEkfqjmH5GZZli8AfC2KIiueo8W0fb8sSxZFcVjSBQAnKvZTTpqPEncOoMuSHo8aT3AAXAXwjuTPtjfWpj1JBwCclLRehUTyCskndc/LAbQsaWXUiKSHRVEsTjucrTUPIaU0caPyoldIrjYFdE/SrQqg1ZmZmW3Q2nzrXdgaDAYJxnIF0BrJdKLteuV40A5AJEPyXWyyiU1JOyKBpAGNhJkB1XmYPSiIPwMyoCYpGrAH2YN65EGS/KEYfD88ALDUp1KjLMtUvV/votTYUawC2CTZq2IVwLqkbRV9W8XqrKTU0622WBOk52VZfpvmdgeAI0VRnK/CAZDaHcdJfmlUi6XJbpjFR+QcgKeSUj86LHCbnTedz07NvbckL7bZck1QFgCkyv5M51vo9gEbJFP7pr2m/TDM/Nsn98VJmgVwtmc/Dl9GCXnc/vueT3Lf6cTjDChAZ0AGNHF0/Z44kQdZ3TEGutUdNZ5odUdGqWF1R70HWd2xGx+rO+Lwsrpjr38kre4YISbJ6o7Ag6zuMKDdCYSlxrgQsz4oyEEGZEB/CTjErFH84wyWv8QVwb/TKFrdsT1JjyNvdccWI0lWdwRf0lZ3RH1/qzviE8DqjiDMrO7ICDOrOyJIIyeb1R25sPb7uLBY3e8Aov0ZUEDIgAwoCqL6+/Yge5A9qBmBYPYvluHiZ6eIxCwAAAAASUVORK5CYII="
              ) : (this.is_on = !0, this.is_kk = !1, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII="
              )
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"),
              "string" == n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "",
                t.key = o.params.key, t.payed = ""), t.page = t.num, t.murmur = window.murmur, this.$axios.post(
                o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList =
                  s.dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o),
                l = n.length - 3;
              t.sliceCat = n.slice(-3), t.cat = n.splice(0, l)
            }))
          },
          dingbu: function() {
            location.reload()
          },
          dsp: function(t) {
            return 1 == t ? 1 == localStorage.getItem("zbkg") ? (this.$router.push({
              name: "zb"
            }), !1) : (this.$Message.warning("暂未开启"), !1) : "短视频" == t ? (this.$router.push({
              name: "site"
            }), !1) : void 0
          },
          linkTo: function(t) {
            var e = this;
            this.url = t;
            var a = this;
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), console.log(a.domain + t), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      }),
      ge = he,
      fe = (a("7764"), Object(y["a"])(ge, pe, me, !1, null, "6ca08cf4", null)),
      ve = fe.exports,
      _e = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item active",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        }))], 2), a("div", [a("Button", {
          staticClass: "tanchuang",
          style: {
            backgroundImage: t.backgroundImage
          },
          attrs: {
            type: "default",
            shape: "circle",
            long: ""
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("今日热门")]), a("Button", {
          staticClass: "tanchuang",
          style: {
            backgroundImage: t.backgroundImage
          },
          attrs: {
            type: "default",
            shape: "circle",
            long: ""
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("今日更新")]), a("Button", {
          staticClass: "tanchuang",
          style: {
            backgroundImage: t.backgroundImage
          },
          attrs: {
            type: "default",
            shape: "circle",
            long: ""
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("今日视频")])], 1)]), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            class: {
              menu_active: t.is_kk,
              cc_panel_detail: t.is_on
            },
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            class: {
              cc_panel_detail_image_wapper_active: t.is_kk,
              cc_panel_detail_image_wapper: t.is_on
            }
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-time",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px",
              "border-top-left-radius": "15px",
              "padding-left": "5px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))]), a("h4", {
            staticClass: "title",
            staticStyle: {
              height: "20px"
            }
          }, [a("p", {
            staticStyle: {
              "font-size": "12px",
              "font-weight": "bold",
              color: "#fa436a"
            }
          }, [t._v(t._s(e.read_num) + "人购买")])])])])
        }))], 2)], 1), a("van-tabbar", {
          attrs: {
            route: "",
            "active-color": "#ee0a24",
            fixed: !0,
            "inactive-color": "#000"
          },
          model: {
            value: t.footerActiveClass,
            callback: function(e) {
              t.footerActiveClass = e
            },
            expression: "footerActiveClass"
          }
        }, [a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/",
            icon: "home-o"
          }
        }, [t._v("首页")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/buy",
            icon: "shopping-cart-o"
          }
        }, [t._v("已购")])], 1), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticStyle: {
            "background-image": "linear-gradient(to right, #ff0030, #c000ff)",
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      ye = [],
      Ae = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            scrollTop: 0,
            footerActiveClass: 0,
            backgroundImage: "linear-gradient(-334.372deg, rgb(26, 0, 255), rgb(255, 0, 165))",
            menu_img: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII=",
            is_on: !0,
            is_kk: !1,
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: "50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "打赏后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            sliceCat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              onScroll: this.onScroll,
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi();
          var t = 760 * Math.random(),
            e = 40,
            a = this;
          setInterval((function() {
            var i = 360 * Math.sin(2 * t * Math.PI / 360),
              s = 360 * Math.sin(2 * (t + 20) * Math.PI / 360);
            t++;
            var o = "linear-gradient(" + e + "deg,hsl(" + i + ",100%, 50%) ,hsl(" + s + ",100%, 50%))";
            a.backgroundImage = o
          }), 120)
        },
        activated: function() {
          this.$refs.mescroll.mescroll.scrollTo(this.scrollTop, 0)
        },
        methods: {
          onScroll: function(t, e) {
            this.scrollTop = e
          },
          menu_qiehuan: function() {
            this.is_on ? (this.is_on = !1, this.is_kk = !0, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAAC6klEQVR4Xu2aPWzUQBCF33MkOpCgIAUpiUSFBPRp+GmhBlFTARIkdZIaiARU1BHU0PLT0BMkqkhQQgEFSNAhnR9auKCLc/Fszjaco+fWu+PdzzOznvEjfNUSoPnUEzCgwEMMyICaJRF7kD3IHtSMQBceJOkogHMA5gEc6nSFzY1/B/AewCuSn/dqLjsHSUpjjwG4KekagIN7fdh/Hv+D5CMA9wF8Iqmc9WQBGsJZkLQG4HSO4WkdQ/INgNsAXudAygU0J+kZgFMAsuZMKyAAIrkB4BLJj9E6szYr6Y6kxchYn+6TvEtyKVpzCCglZEkfqjmH5GZZli8AfC2KIiueo8W0fb8sSxZFcVjSBQAnKvZTTpqPEncOoMuSHo8aT3AAXAXwjuTPtjfWpj1JBwCclLRehUTyCskndc/LAbQsaWXUiKSHRVEsTjucrTUPIaU0caPyoldIrjYFdE/SrQqg1ZmZmW3Q2nzrXdgaDAYJxnIF0BrJdKLteuV40A5AJEPyXWyyiU1JOyKBpAGNhJkB1XmYPSiIPwMyoCYpGrAH2YN65EGS/KEYfD88ALDUp1KjLMtUvV/votTYUawC2CTZq2IVwLqkbRV9W8XqrKTU0622WBOk52VZfpvmdgeAI0VRnK/CAZDaHcdJfmlUi6XJbpjFR+QcgKeSUj86LHCbnTedz07NvbckL7bZck1QFgCkyv5M51vo9gEbJFP7pr2m/TDM/Nsn98VJmgVwtmc/Dl9GCXnc/vueT3Lf6cTjDChAZ0AGNHF0/Z44kQdZ3TEGutUdNZ5odUdGqWF1R70HWd2xGx+rO+Lwsrpjr38kre4YISbJ6o7Ag6zuMKDdCYSlxrgQsz4oyEEGZEB/CTjErFH84wyWv8QVwb/TKFrdsT1JjyNvdccWI0lWdwRf0lZ3RH1/qzviE8DqjiDMrO7ICDOrOyJIIyeb1R25sPb7uLBY3e8Aov0ZUEDIgAwoCqL6+/Yge5A9qBmBYPYvluHiZ6eIxCwAAAAASUVORK5CYII="
              ) : (this.is_on = !0, this.is_kk = !1, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII="
              )
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = window.murmur, this.$axios.post(o.domain +
                "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function(t) {
            var e = this;
            this.url = t;
            var a = this;
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), console.log(a.domain + t), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      Ce = Ae,
      be = (a("6a69"), Object(y["a"])(Ce, _e, ye, !1, null, "76591a10", null)),
      we = be.exports,
      ke = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: this.hezi,
            expression: "this.hezi"
          }],
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "1"
          }
        }, [a("div", {
          attrs: {
            id: "mse"
          }
        })]), a("div", {
          ref: "video-type",
          staticClass: "video-type"
        }, [a("div", {
          ref: "type-row",
          staticClass: "type-row"
        }, [a("div", {
          key: "-1",
          staticClass: "type-item ",
          class: -1 == t.activeClass ? "active" : "",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("全部 ")]), t._l(t.cat, (function(e) {
          return a("div", {
            key: e.id,
            staticClass: "type-item active",
            attrs: {
              "data-cid": "0"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [t._v(t._s(e.title) + " ")])
        }))], 2), a("div", {
          staticClass: "mt20 type-search",
          staticStyle: {
            color: "#FFFFFF",
            "font-weight": "600",
            "margin-top": "10px"
          }
        }, [a("div", {
          staticClass: "cc-left",
          staticStyle: {
            float: "left",
            width: "24%"
          },
          on: {
            click: function(e) {
              return t.dsp("短视频")
            }
          }
        }, [a("img", {
          attrs: {
            width: "25",
            height: "25",
            src: "data:image/png;base64,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"
          }
        }), t._v("短视频")]), a("div", {
          staticStyle: {
            position: "relative",
            left: "13px"
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticClass: "input-text color-ff",
          attrs: {
            type: "text",
            placeholder: "输入搜索关键词"
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })]), a("div", {
          staticClass: "btn-search",
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")])]), a("div", {
          staticClass: "mt20 type-search",
          staticStyle: {
            "font-size": "18px",
            "font-weight": "bold"
          }
        }, [a("div", {
          staticClass: "cc-left",
          staticStyle: {
            float: "left",
            width: "50%",
            "text-align": "right",
            "padding-right": "20%"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "all")
            }
          }
        }, [t._v("今日更新")]), a("div", {
          staticClass: "cc-left",
          staticStyle: {
            width: "50%",
            "text-align": "center"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "yigou")
            }
          }
        }, [t._v("已购视频")])]), a("div", {
          ref: "NoticeBox",
          staticClass: "NoticeBox",
          staticStyle: {
            clear: "both"
          }
        }, [a("div", {
          staticClass: "NoticeContentBox",
          staticStyle: {
            overflow: "hidden",
            position: "relative"
          }
        }, [a("marquee", [a("span", {
          staticStyle: {
            color: "#FFFFFF",
            "font-size": "12px"
          }
        }, [t._v("温馨提示：如果付款没有跳转，请到已购买里观看。保存链接或二维码，长期免费观看")])])], 1)])]), t._l(t.dataList, (function(e,
          i) {
          return a("div", {
            key: i,
            class: {
              menu_active: t.is_kk,
              cc_panel_detail: t.is_on
            },
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            class: {
              cc_panel_detail_image_wapper_active: t.is_kk,
              cc_panel_detail_image_wapper: t.is_on
            }
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-time",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title",
            staticStyle: {
              color: "#FFFFFF",
              "font-size": "13px"
            }
          }, [t._v(t._s(e.title))]), a("h4", {
            staticClass: "title",
            staticStyle: {
              height: "24px"
            }
          }, [a("p", {
            staticStyle: {
              "font-size": "12px",
              "font-weight": "bold",
              "text-align": "center"
            }
          }, [a("Icon", {
            staticStyle: {
              display: "inline-block",
              color: "#ffb800",
              "font-size": "20px"
            },
            attrs: {
              type: "ios-star"
            }
          }), t._v("已有" + t._s(e.read_num) + "人付费观看")], 1)])])])
        }))], 2)], 1), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticStyle: {
            "background-image": "linear-gradient(to right, #ff0030, #c000ff)",
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.shoucang,
            callback: function(e) {
              t.shoucang = e
            },
            expression: "shoucang"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v("保存本站")])]), a("div", {
          staticClass: "qrcode",
          staticStyle: {
            "text-align": "center"
          }
        }, [a("p", {
          staticStyle: {
            color: "#f74550",
            "line-height": "25px",
            "margin-top": "10px",
            "text-align": "center"
          }
        }, [t._v(" 长按保存二维码或复制链接收藏本站 ")])]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#000000",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.shoucang = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      xe = [],
      Se = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            scrollTop: 0,
            fav: {
              url: null
            },
            shoucang: !1,
            menu_img: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII=",
            is_on: !0,
            is_kk: !1,
            playerOptions: {
              preload: "auto",
              language: "zh-CN",
              sources: [{
                type: "",
                src: "http://www.html5videoplayer.net/videos/madagascar3.mp4"
              }]
            },
            tops: {
              top: "0px",
              bottom: "0px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            sliceCat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: this.f,
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 910,
              f: this.f
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: this.f,
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              onScroll: this.onScroll,
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        beforeCreate: function() {
          document.querySelector("body").setAttribute("style", "background-color:#000000")
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        activated: function() {
          this.$refs.mescroll.mescroll.scrollTo(this.scrollTop, 0)
        },
        methods: {
          onScroll: function(t, e) {
            this.scrollTop = e
          },
          dsp: function(t) {
            return 1 == t ? 1 == localStorage.getItem("zbkg") ? (this.$router.push({
              name: "zb"
            }), !1) : (this.$Message.warning("暂未开启"), !1) : "短视频" == t ? (this.$router.push({
              name: "site"
            }), !1) : void 0
          },
          doFav: function() {
            var t = localStorage.getItem("domain") + "/index/index/index?view_id=" + localStorage.getItem(
              "view_id") + "&f=" + this.f;
            this.fav.url = t, this.shoucang = !0
          },
          menu_qiehuan: function() {
            this.is_on ? (this.is_on = !1, this.is_kk = !0, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAAC6klEQVR4Xu2aPWzUQBCF33MkOpCgIAUpiUSFBPRp+GmhBlFTARIkdZIaiARU1BHU0PLT0BMkqkhQQgEFSNAhnR9auKCLc/Fszjaco+fWu+PdzzOznvEjfNUSoPnUEzCgwEMMyICaJRF7kD3IHtSMQBceJOkogHMA5gEc6nSFzY1/B/AewCuSn/dqLjsHSUpjjwG4KekagIN7fdh/Hv+D5CMA9wF8Iqmc9WQBGsJZkLQG4HSO4WkdQ/INgNsAXudAygU0J+kZgFMAsuZMKyAAIrkB4BLJj9E6szYr6Y6kxchYn+6TvEtyKVpzCCglZEkfqjmH5GZZli8AfC2KIiueo8W0fb8sSxZFcVjSBQAnKvZTTpqPEncOoMuSHo8aT3AAXAXwjuTPtjfWpj1JBwCclLRehUTyCskndc/LAbQsaWXUiKSHRVEsTjucrTUPIaU0caPyoldIrjYFdE/SrQqg1ZmZmW3Q2nzrXdgaDAYJxnIF0BrJdKLteuV40A5AJEPyXWyyiU1JOyKBpAGNhJkB1XmYPSiIPwMyoCYpGrAH2YN65EGS/KEYfD88ALDUp1KjLMtUvV/votTYUawC2CTZq2IVwLqkbRV9W8XqrKTU0622WBOk52VZfpvmdgeAI0VRnK/CAZDaHcdJfmlUi6XJbpjFR+QcgKeSUj86LHCbnTedz07NvbckL7bZck1QFgCkyv5M51vo9gEbJFP7pr2m/TDM/Nsn98VJmgVwtmc/Dl9GCXnc/vueT3Lf6cTjDChAZ0AGNHF0/Z44kQdZ3TEGutUdNZ5odUdGqWF1R70HWd2xGx+rO+Lwsrpjr38kre4YISbJ6o7Ag6zuMKDdCYSlxrgQsz4oyEEGZEB/CTjErFH84wyWv8QVwb/TKFrdsT1JjyNvdccWI0lWdwRf0lZ3RH1/qzviE8DqjiDMrO7ICDOrOyJIIyeb1R25sPb7uLBY3e8Aov0ZUEDIgAwoCqL6+/Yge5A9qBmBYPYvluHiZ6eIxCwAAAAASUVORK5CYII="
              ) : (this.is_on = !0, this.is_kk = !1, this.menu_img =
              "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAYAAABV7bNHAAABx0lEQVR4Xu2aPU4DQQxGP0ei5QhwPBpOQEXFCWg4HhyBFmmNggJSQmbH3tmfifJSOxPrzWd7YtvEZ5SAwWecAIAqCgEQgNqSCAqaqiB3v5X0KOnB3e/a7uH/t83sQ9KbpFcz+zx3fg8+FBXk7k+Snt39Zm44v+eZ2df+N8zspQBocx/GAL0voZxTEHslmdl9AdDmPhQBDcPgSynn9NzdbnfWjx58ANDhtkqXBKC5AZVIR8KxFDLZEFvTh7SC1nQuC3SJSwIQIXasq6wqURAKQkGR3PxnQ4hVcAEIQNKabzGqGFWMKkYVi/aPIqSoYltXscgtZW2y7Y7s+RF7GmYVSgCaG5C7bz5R6MEH5mKV2dwYICarkpjNV3ITgAAUeQWVbVAQCkJBbQSmKqiH5aUefOCh2PBQ5K/G2EOxh+WlHnxIN+2XyIj0gya2Gi5SQWvOpLL944i6s2emQwxAh2vIkl7i9nrwAQUxWWWyGolu9oOilLJ5jRxEDiIHRaPrx44Q23p5gZc0L+kjDVLFqGIrV7FUyQga0zC7poZZUBQps4tUUA/LSz34wFysYS7GAhULVPVUyfrL1OWFOtvrsEBBKKhN6Siowu8b/5MYdovT4c8AAAAASUVORK5CYII="
              )
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.footerActiveClass = 1, o.activeClass = -1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (this
                .dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "", t.payed = "1"), "string" ==
              n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num = 1, t.cid = "", t.key = o
                .params.key, t.payed = ""), t.page = t.num, t.murmur = window.murmur, this.$axios.post(o.domain +
                "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function(t) {
            var e = this;
            this.url = t;
            var a = this;
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), console.log(a.domain + t), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0;
              void 0 != e.hezi && "" != e.hezi && (a = 230), t.tops.top = t.$refs["video-type"]
                .offsetHeight + 7 + a + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          }
        },
        watch: {
          cat: function() {},
          hezi: function() {
            this.getHezi()
          }
        },
        props: {
          f: String,
          domain: String,
          hezi: String
        }
      },
      Ee = Se,
      Ie = (a("ec63"), Object(y["a"])(Ee, ke, xe, !1, null, "0f3cfa0f", null)),
      Be = Ie.exports,
      Pe = {
        name: "Home",
        data: function() {
          return {
            view_id: localStorage.getItem("view_id"),
            code: localStorage.getItem("f"),
            f: localStorage.getItem("f"),
            domain: localStorage.getItem("domain"),
            hezis: localStorage.getItem("hezi")
          }
        },
        methods: {
          tousu: function() {
            this.$router.push({
              name: "tousu",
              query: {
                f: localStorage.getItem("f"),
                v: 0
              }
            })
          }
        },
        mounted: function() {},
        components: {
          HelloWorld: C,
          mobanTwo: E,
          mobanSan: O,
          love: mt,
          loveTwo: qt,
          liu: Ft,
          qi: $t,
          ba: oe,
          jiu: ue,
          shi: ve,
          shiyi: we,
          shier: Be
        }
      },
      ze = Pe,
      Ue = (a("cccb"), Object(y["a"])(ze, o, n, !1, null, null, null)),
      Oe = Ue.exports,
      Me = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticStyle: {
            position: "absolute",
            "z-index": "1",
            top: "17px",
            "font-size": "14px",
            height: "25px",
            "line-height": "25px",
            "text-align": "center",
            width: "100%",
            color: "rgb(105,106,108)"
          }
        }, [t._v(" 此网页由  weixin110.qq.com  提供 ")]), a("div", {
          staticStyle: {
            position: "absolute",
            "z-index": "2",
            "background-color": "rgb(255, 255, 255)",
            width: "100%",
            height: "100%",
            transition: "all 0s",
            transform: "translate(0px, 0px)"
          },
          attrs: {
            id: "container"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "100%"
          }
        }, [a("header", [t._v("请选择投诉该网页的原因:")]), a("p", [a("a", {
          on: {
            click: function(e) {
              return t.t2(2)
            }
          }
        }, [t._v("网页包含欺诈信息 （如：假红包）"), a("img", {
          attrs: {
            src: t.right
          }
        })]), a("a", {
          on: {
            click: function(e) {
              return t.t2(3)
            }
          }
        }, [t._v("网页包含色情信息"), a("img", {
          attrs: {
            src: t.right
          }
        })]), a("a", {
          on: {
            click: function(e) {
              return t.t2(6)
            }
          }
        }, [t._v("网页包含暴力恐怖信息"), a("img", {
          attrs: {
            src: t.right
          }
        })]), a("a", {
          on: {
            click: function(e) {
              return t.t2(5)
            }
          }
        }, [t._v("网页包含政治敏感信息"), a("img", {
          attrs: {
            src: t.right
          }
        })]), a("a", {
          on: {
            click: function(e) {
              return t.t2(2)
            }
          }
        }, [t._v("网页在收集个人隐私信息（如:钓鱼链接）"), a("img", {
          attrs: {
            src: t.right
          }
        })]), a("a", {
          on: {
            click: function(e) {
              return t.t2(4)
            }
          }
        }, [t._v("网页包含诱导分享/关注性质的内容"), a("img", {
          attrs: {
            src: t.right
          }
        })]), a("a", {
          on: {
            click: function(e) {
              return t.t2(2)
            }
          }
        }, [t._v("网页可能包含谣言信息"), a("img", {
          attrs: {
            src: t.right
          }
        })])]), a("a", {
          staticStyle: {
            color: "#0d7aff",
            "margin-top": "30px",
            "margin-left": "18px",
            "z-index": "1"
          },
          attrs: {
            href: "http://mp.weixin.qq.com/s/TbX1CcZhQNReneXVc3At9Q"
          }
        }, [t._v("遇到网页流量被劫持怎么办")]), a("a", {
          staticClass: "footer",
          attrs: {
            href: "https://weixin110.qq.com/security/readtemplate?t=w_security_center_website/report_agreement&lang=zh_CN"
          }
        }, [t._v("投诉须知")])])])])
      },
      Le = [],
      Re = a("5712"),
      Qe = a.n(Re),
      De = {
        name: "Tousu",
        data: function() {
          return {
            right: Qe.a
          }
        },
        methods: {
          t2: function(t) {
            var e = 0 | this.$route.query.v;
            this.$router.push({
              name: "submit",
              query: {
                f: localStorage.getItem("f"),
                c: t,
                v: e
              }
            })
          }
        }
      },
      Te = De,
      Ke = (a("ac55"), Object(y["a"])(Te, Me, Le, !1, null, "aa87141a", null)),
      qe = Ke.exports,
      Ne = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticStyle: {
            position: "absolute",
            "z-index": "1",
            top: "17px",
            "font-size": "14px",
            height: "25px",
            "line-height": "25px",
            "text-align": "center",
            width: "100%",
            color: "rgb(105,106,108)"
          }
        }, [t._v(" 此网页由  weixin110.qq.com  提供 ")]), a("div", {
          staticStyle: {
            position: "absolute",
            "z-index": "2",
            "background-color": "#fff",
            width: "100%",
            height: "100%"
          },
          attrs: {
            id: "container"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "100%"
          }
        }, [a("header", [t._v("你可以:")]), a("p", {
          on: {
            click: t.t2
          }
        }, [a("a", [t._v("提交给我们审核"), a("img", {
          attrs: {
            src: t.right
          }
        })])]), a("a", {
          staticClass: "footer",
          attrs: {
            href: "https://weixin110.qq.com/security/readtemplate?t=w_security_center_website/report_agreement&lang=zh_CN"
          }
        }, [t._v("投诉须知")])])])])
      },
      We = [],
      je = {
        name: "Tousu",
        data: function() {
          return {
            right: Qe.a,
            domain: localStorage.getItem("domain")
          }
        },
        methods: {
          t2: function() {
            var t = this.$route.query.v,
              e = this.$route.query.f,
              a = this.$route.query.c;
            console.log(t, e, a);
            var i = this;
            this.$axios.get(i.domain + "/tousu/yitijiao.php/", {
              params: {
                v: t,
                f: e,
                c: a
              }
            }).then((function(t) {
              1 == t.data.code && i.$router.push({
                name: "res",
                query: {}
              })
            }))
          }
        }
      },
      He = je,
      Ve = (a("dfa9"), Object(y["a"])(He, Ne, We, !1, null, "b58b338e", null)),
      Fe = Ve.exports,
      Ye = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", {
          staticClass: "about"
        }, [a("van-notice-bar", {
          attrs: {
            "left-icon": "volume-o",
            text: "正在检测是否支付成功,支付成功后,请等待3-10秒钟，自动跳转播放页"
          }
        }), a("div", {
          staticStyle: {
            height: "70vh",
            display: "flex",
            "flex-direction": "column",
            "justify-content": "center"
          }
        }, [a("div", {
          staticClass: "item",
          staticStyle: {
            "margin-bottom": "5px"
          }
        }, [a("van-button", {
          attrs: {
            color: "linear-gradient(to right, #ff6034, #ee0a24)",
            block: ""
          },
          on: {
            click: t.jc
          }
        }, [t._v(" 已支付长时间没反应请点击已购查看 ")])], 1), a("div", {
          staticClass: "item"
        }, [a("van-button", {
          attrs: {
            type: "primary",
            to: "/",
            block: ""
          }
        }, [t._v("返回首页")])], 1)])], 1)
      },
      Ge = [],
      Ze = (a("9d96"), {
        data: function() {
          return {
            domain: localStorage.getItem("domain"),
            set: 0
          }
        },
        mounted: function() {
          var t = this,
            e = setInterval((function() {
              t.jc()
            }), 1e3);
          this.set = e
        },
        methods: {
          params: function() {
            var t = window.location.hash;
            t = unescape(t), t = t.replace(/&amp;/g, "&");
            var e = new Object;
            if (-1 != t.indexOf("?"))
              for (var a = t.substr(t.indexOf("?") + 1), i = a.split("&"), s = 0; s < i.length; s++) e[i[s]
                .split("=")[0]] = decodeURI(i[s].split("=")[1]);
            return e
          },
          jc: function() {
            var t = this.$route.query.transact,
              e = this.$route.query.f;
            "" != e && void 0 != e || (e = this.params().f), "" != t && void 0 != t || (t = this.params()
              .transact);
            var a = this;
            this.$axios.get(a.domain + "/index/trading/checkOrderStatus", {
              params: {
                f: e,
                transact: t
              }
            }).then((function(t) {
              console.log(t.data.data.status), 1 == t.data.data.status && (a.$Message.success("支付成功!"),
                clearInterval(a.set), "dsp" == t.data.data.is_dsp ? a.$router.push({
                  name: "site",
                  params: {
                    id: t.data.data.vid
                  }
                }) : a.$router.push({
                  name: "Video",
                  params: {
                    id: t.data.data.vid
                  }
                }))
            }))
          }
        }
      }),
      Je = Ze,
      Xe = Object(y["a"])(Je, Ye, Ge, !1, null, null, null),
      $e = Xe.exports,
      ta = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "done"
        }, [a("img", {
          attrs: {
            src: t.done
          }
        })]), a("h3", [t._v("投诉已提交")]), a("p", [t._v("微信团队会尽快核实，并通过“微信团队”通知你审核结果。感谢你的支持。")]), a("a", {
          on: {
            click: function(e) {
              return t.closeWindow()
            }
          }
        }, [a("span", [t._v("关闭")])])])
      },
      ea = [],
      aa = a("406d"),
      ia = a.n(aa),
      sa = {
        name: "Tousu",
        data: function() {
          return {
            done: ia.a
          }
        },
        methods: {
          closeWindow: function() {
            "undefined" != typeof WeixinJSBridge ? (WeixinJSBridge.call("closeWindow"), WeixinJSBridge.call(
                "closeWindow")) : navigator.userAgent.indexOf("MSIE") > 0 ? navigator.userAgent.indexOf(
                "MSIE 6.0") > 0 ? (window.opener = null, window.close()) : (window.open("", "_top"), window.top
                .close()) : navigator.userAgent.indexOf("Firefox") > 0 ? window.location.href = "about:blank " :
              window.location.href =
              "https://weixin110.qq.com/cgi-bin/mmspamsupport-bin/newredirectconfirmcgi?main_type=2&evil_type=0&source=2"
          }
        }
      },
      oa = sa,
      na = (a("5fff"), Object(y["a"])(oa, ta, ea, !1, null, "bd76caec", null)),
      la = na.exports,
      ra = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll channelbox",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          staticClass: "channellist-box"
        }, [a("ul", {
          staticClass: "channellist"
        }, t._l(t.cat, (function(e, i) {
          return a("div", {
            key: e.id
          }, [i < 10 ? a("li", {
            attrs: {
              "data-cid": "81"
            },
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [a("a", {
            staticStyle: {
              color: "black"
            }
          }, [a("img", {
            attrs: {
              src: t.ico[i]
            }
          }), a("span", [t._v(t._s(e.title) + " ")])])]) : t._e()])
        })), 0)]), a("div", {
          staticClass: "weui-search-bar",
          attrs: {
            id: "searchBar"
          }
        }, [a("form", {
          staticClass: "weui-search-bar__form"
        }, [a("div", {
          staticClass: "weui-search-bar__box"
        }, [a("i", {
          staticClass: "weui-icon-search"
        }), a("input", {
          staticClass: "weui-search-bar__input",
          attrs: {
            type: "text",
            id: "searchInput",
            placeholder: "搜索",
            required: ""
          }
        }), a("a", {
          staticClass: "weui-icon-clear",
          attrs: {
            href: "javascript:",
            id: "searchClear"
          }
        })]), a("label", {
          staticClass: "weui-search-bar__label",
          attrs: {
            id: "searchText"
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticStyle: {
            width: "98%",
            height: "100%",
            border: "0",
            outline: "none",
            "text-align": "center"
          },
          attrs: {
            type: "text",
            id: "keys",
            placeholder: "请输入搜索内容",
            required: ""
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })])]), a("a", {
          staticClass: "weui-search-bar__cancel-btn1",
          staticStyle: {
            color: "#fa436a"
          },
          attrs: {
            href: "javascript:",
            id: "searchSubmit1"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")])]), a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: t.catShow,
            expression: "catShow"
          }],
          staticClass: "search-keywords"
        }, [a("h3", {
          staticClass: "his",
          staticStyle: {
            display: "none"
          }
        }, [t._v("历史搜索"), a("i", {
          staticClass: "fa fa-trash",
          attrs: {
            onclick: "javascript:ClearHis();"
          }
        })]), a("ul", {
          staticClass: "his hiswords",
          staticStyle: {
            display: "none"
          }
        }), a("h3", [t._v("热门分类"), a("i", {
          staticClass: "fa fa-eye"
        })]), a("ul", [t._l(t.cat, (function(e) {
          return a("li", {
            key: e.id,
            on: {
              click: function(a) {
                return t.upCallback(t.mescrollUp.page, t.mescroll, e)
              }
            }
          }, [a("a", {
            staticStyle: {
              color: "black"
            }
          }, [t._v(t._s(e.title))])])
        })), a("div", {
          staticStyle: {
            clear: "both"
          }
        })], 2)]), a("div"), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          }), a("span", {
            staticClass: "img-tips-left",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))]), a("span", {
            staticClass: "desc"
          }, [t._v(t._s(t.doRates()) + "人观看  好评:" + t._s(t.doRate()) + "%")])])])
        }))], 2)], 1), a("van-tabbar", {
          attrs: {
            route: "",
            "active-color": "#ee0a24",
            fixed: !0,
            "inactive-color": "#000"
          },
          model: {
            value: t.footerActiveClass,
            callback: function(e) {
              t.footerActiveClass = e
            },
            expression: "footerActiveClass"
          }
        }, [a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/",
            icon: "home-o"
          }
        }, [t._v("首页")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/cat",
            icon: "apps-o"
          }
        }, [t._v("分类")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/buy",
            icon: "shopping-cart-o"
          }
        }, [t._v("已购")])], 1), a("Modal", {
          attrs: {
            closable: !1,
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            src: t.ds_img
          }
        }), a("img", {
          staticStyle: {
            position: "relative",
            bottom: "70%"
          },
          attrs: {
            src: t.player_img
          },
          on: {
            click: function(e) {
              return t.sb()
            }
          }
        })]), a("span", {
          staticStyle: {
            "text-align": "left",
            "font-weight": "bold",
            display: "block",
            position: "relative",
            top: "10px"
          }
        }, [t._v(t._s(t.ds_title))]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              long: ""
            },
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          })
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg1",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: ""
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("我在想想 ")])], 1)]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.shoucang,
            callback: function(e) {
              t.shoucang = e
            },
            expression: "shoucang"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v("保存本站")])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("p", {
          staticStyle: {
            color: "#f74550",
            "line-height": "25px",
            "margin-top": "10px",
            "text-align": "center"
          }
        }, [t._v(" 长按保存二维码或链接收藏本站 ")]), a("img", {
          staticStyle: {
            display: "block",
            margin: "auto"
          },
          attrs: {
            width: "100%",
            src: t.domain + "/index/index/qrcode?text=" + t.fav.url
          }
        })]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.shoucang = !1
            }
          }
        }, [t._v("关闭 ")])], 1)]), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.model
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      ca = [],
      da = a("c4b2"),
      ua = a.n(da),
      pa = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            murmur: localStorage.getItem("fingerprint"),
            model: "",
            player_img: ct.a,
            ico: [yt.a, vt.a, wt.a, xt.a, Et.a, Bt.a, zt.a, Ot.a, Lt.a, Qt.a],
            emptyShow: !1,
            catShow: !0,
            em: ua.a,
            domain: localStorage.getItem("domain"),
            f: localStorage.getItem("f"),
            fav: {
              url: null
            },
            shoucang: !1,
            footerActiveClass: 2,
            allImg: Q.a,
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            user: [],
            value8: !1,
            url: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: localStorage.getItem("f"),
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 100,
              f: localStorage.getItem("f")
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: localStorage.getItem("f"),
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          doRate: function() {
            return parseInt(10 * Math.random() + 90)
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.activeClass = -1, o.footerActiveClass = 1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (o
                .footerActiveClass = 3, this.dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "",
                t.payed = "1"), "string" == n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num =
                1, t.cid = "", t.key = o.params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage
              .getItem("fingerprint"), this.$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                if (s.$Spin.hide(), 0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var o = i.data.data;
                o = o.split("").reverse().join("");
                var n = a("e18e").Base64,
                  l = n.decode(o),
                  r = JSON.parse(l);
                0 == r.length && s.$Message.warning("暂无数据!"), 1 === t.num && (s.dataList = []), s.dataList = s
                  .dataList.concat(r), s.$nextTick((function() {
                    e.endSuccess(r.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$axios.post(e.domain +
              "/index/index/pays/", {
                f: e.f,
                vid: t.id,
                money: t.money,
                murmur: localStorage.getItem("fingerprint")
              }).then((function(t) {
              e.pay = t.data.pay, e.modal2 = !0, e.user = t.data.user
            })) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          sb: function() {
            this.$Message.warning("请先购买后观看哦。")
          },
          dingbu: function() {
            location.reload()
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.model = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }), 1500)) : this.$Message.error("暂未开通该支付渠道")
          },
          linkTo: function(t) {
            var e = this;
            if (this.url = t, "-" != this.user.pay_model && "-" != this.user.pay_model1) return this.url = t,
              void(this.value8 = !0);
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0,
                i = 0,
                s = 0,
                o = 0,
                n = 0;
              void 0 != e.hezi && "" != e.hezi && (n = 170), t.tops.top = a + i + s + o + 0 + n + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          },
          doFav: function() {
            var t = this.domain + "/index/index/lists/?f=" + localStorage.getItem("f") + "&view_id=" +
              localStorage.getItem("view_id");
            this.fav.url = t, this.shoucang = !0
          },
          search: function() {
            this.footerActiveClass = 2, location.href = this.domain + "/index/index/pagecat/?f=" + localStorage
              .getItem("f")
          },
          tousu: function() {
            location.href = this.domain + "/tousu?id=" + localStorage.getItem("f")
          }
        },
        watch: {
          footerActiveClass: function(t) {
            2 == t && (this.mescroll.setPageNum(1), this.mescrollUp.page.page = 1, this.mescrollUp.page.num = 1,
              this.mescrollUp.page.payed = "", this.mescrollUp.page.cid = "", this.mescroll.triggerDownScroll())
          },
          cat: function() {
            this.changeHeight()
          },
          hezi: function() {
            this.changeHeight(), this.getHezi()
          }
        },
        props: {
          hezi: String
        }
      },
      ma = pa,
      ha = (a("6423"), Object(y["a"])(ma, ra, ca, !1, null, "ac98fee8", null)),
      ga = ha.exports,
      fa = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          staticClass: "cc_panel_wapper mescroll channelbox",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("div", {
          staticClass: "weui-search-bar",
          attrs: {
            id: "searchBar"
          }
        }, [a("form", {
          staticClass: "weui-search-bar__form"
        }, [a("div", {
          staticClass: "weui-search-bar__box"
        }, [a("i", {
          staticClass: "weui-icon-search"
        }), a("input", {
          staticClass: "weui-search-bar__input",
          attrs: {
            type: "text",
            id: "searchInput",
            placeholder: "搜索",
            required: ""
          }
        }), a("a", {
          staticClass: "weui-icon-clear",
          attrs: {
            href: "javascript:",
            id: "searchClear"
          }
        })]), a("label", {
          staticClass: "weui-search-bar__label",
          attrs: {
            id: "searchText"
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.params.key,
            expression: "params.key"
          }],
          staticStyle: {
            width: "98%",
            height: "100%",
            border: "0",
            outline: "none",
            "text-align": "center"
          },
          attrs: {
            type: "text",
            id: "keys",
            placeholder: "请输入搜索内容",
            required: ""
          },
          domProps: {
            value: t.params.key
          },
          on: {
            input: function(e) {
              e.target.composing || t.$set(t.params, "key", e.target.value)
            }
          }
        })])]), a("a", {
          staticClass: "weui-search-bar__cancel-btn1",
          staticStyle: {
            color: "#fa436a"
          },
          attrs: {
            href: "javascript:",
            id: "searchSubmit1"
          },
          on: {
            click: function(e) {
              return t.upCallback(t.mescrollUp.page, t.mescroll, "search")
            }
          }
        }, [t._v("搜索")])]), a("div", {
          directives: [{
            name: "show",
            rawName: "v-show",
            value: t.emptyShow,
            expression: "emptyShow"
          }],
          staticClass: "empty",
          staticStyle: {
            "text-align": "center",
            "margin-top": "10%"
          }
        }, [a("img", {
          attrs: {
            src: t.em
          }
        }), a("span", {
          staticStyle: {
            display: "block"
          }
        }, [t._v("空空如也, "), a("router-link", {
          attrs: {
            to: "/"
          }
        }, [a("span", {
          staticStyle: {
            color: "#fa436a",
            "text-align": "center"
          }
        }, [t._v("随便逛逛>")])])], 1)]), a("div"), a("div"), t._l(t.dataList, (function(e, i) {
          return a("div", {
            directives: [{
              name: "show",
              rawName: "v-show",
              value: t.catShow,
              expression: "catShow"
            }],
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            attrs: {
              src: e.img,
              alt: "预览图",
              width: "186",
              height: "139"
            }
          }), a("span", {
            staticClass: "img-tips-left",
            staticStyle: {
              top: "0",
              height: "15px",
              "line-height": "15px"
            }
          }, [a("p", {
            staticStyle: {
              color: "#f9f8fb",
              "font-weight": "bold"
            }
          }, [t._v("时长:" + t._s(e.time))])])]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))]), a("span", {
            staticClass: "desc"
          }, [t._v(t._s(t.doRates()) + "人观看  好评:" + t._s(t.doRate()) + "%")])])])
        }))], 2)], 1), a("van-tabbar", {
          attrs: {
            route: "",
            "active-color": "#ee0a24",
            fixed: !0,
            "inactive-color": "#000"
          },
          model: {
            value: t.footerActiveClass,
            callback: function(e) {
              t.footerActiveClass = e
            },
            expression: "footerActiveClass"
          }
        }, [a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/",
            icon: "home-o"
          }
        }, [t._v("首页")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/cat",
            icon: "apps-o"
          }
        }, [t._v("分类")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/buy",
            icon: "shopping-cart-o"
          }
        }, [t._v("已购")])], 1), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v(t._s(t.ds_title))])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%"
          },
          attrs: {
            src: t.ds_img
          }
        })]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              icon: "md-cart",
              long: ""
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          }, [t._v(t._s(e.name) + " ")])
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("关闭 ")])], 1)]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.shoucang,
            callback: function(e) {
              t.shoucang = e
            },
            expression: "shoucang"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v("保存本站")])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("p", {
          staticStyle: {
            color: "#f74550",
            "line-height": "25px",
            "margin-top": "10px",
            "text-align": "center"
          }
        }, [t._v(" 长按保存二维码或复制链接收藏本站 ")]), a("img", {
          staticStyle: {
            display: "block",
            margin: "auto"
          },
          attrs: {
            width: "100%",
            src: t.domain + "/index/index/qrcode?text=" + t.fav.url
          }
        })]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.shoucang = !1
            }
          }
        }, [t._v("关闭 ")])], 1)])], 1)
      },
      va = [],
      _a = {
        components: {
          MescrollVue: d["a"]
        },
        data: function() {
          return {
            emptyShow: !1,
            catShow: !0,
            em: ua.a,
            domain: localStorage.getItem("domain"),
            f: "asdasd",
            fav: {
              url: null
            },
            shoucang: !1,
            footerActiveClass: 3,
            allImg: Q.a,
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "支付后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: localStorage.getItem("f"),
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 100,
              f: localStorage.getItem("f")
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: localStorage.getItem("f"),
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: 1
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getCat(), void 0 != this.hezi && "" != this.hezi && this.getHezi()
        },
        methods: {
          doRate: function() {
            return parseInt(10 * Math.random() + 90)
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.activeClass = -1, o.footerActiveClass = 1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (o
                .footerActiveClass = 3, this.dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "",
                t.payed = "1"), "string" == n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num =
                1, t.cid = "", t.key = o.params.key, t.payed = ""), t.page = t.num, t.murmur = localStorage
              .getItem("fingerprint"), this.$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                s.$Spin.hide();
                var n = i.data.total;
                if (0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var l = i.data.data;
                l = l.split("").reverse().join("");
                var r = a("e18e").Base64,
                  c = r.decode(l),
                  d = JSON.parse(c);
                0 == n ? (s.dataList = [], o.emptyShow = !0, o.catShow = !1) : (o.emptyShow = !1, o
                    .catShow = !0), 1 === t.num && (s.dataList = []), s.dataList = s.dataList.concat(d), s
                  .$nextTick((function() {
                    e.endSuccess(d.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$router.push("/p/" + t.id +
              "?m=" + t.money) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function(t) {
            var e = this;
            this.url = t, this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = 0,
                i = 0,
                s = 0,
                o = 0,
                n = 0;
              void 0 != e.hezi && "" != e.hezi && (n = 170), t.tops.top = a + i + s + o + 0 + n + "px"
            }))
          },
          getHezi: function() {
            this.Player = new f.a({
              el: document.querySelector("#mse"),
              url: localStorage.getItem("h_url"),
              width: "100%",
              height: "230px",
              volume: .6,
              autoplay: !1,
              playbackRate: [.5, .75, 1, 1.5, 2],
              defaultPlaybackRate: 1,
              playsinline: !0
            })
          },
          doFav: function() {
            var t = this.domain + "/index/index/lists/?f=" + localStorage.getItem("f") + "&view_id=" +
              localStorage.getItem("view_id");
            this.fav.url = t, this.shoucang = !0
          },
          search: function() {
            this.footerActiveClass = 2, location.href = this.domain + "/index/index/pagecat/?f=" + localStorage
              .getItem("f")
          },
          tousu: function() {
            location.href = this.domain + "/tousu?id=" + localStorage.getItem("f")
          }
        },
        watch: {
          footerActiveClass: function(t) {
            2 == t && (this.mescroll.setPageNum(1), this.mescrollUp.page.page = 1, this.mescrollUp.page.num = 1,
              this.mescrollUp.page.payed = "", this.mescrollUp.page.cid = "", this.mescroll.triggerDownScroll())
          },
          cat: function() {
            this.changeHeight()
          },
          hezi: function() {
            this.changeHeight(), this.getHezi()
          }
        },
        props: {
          hezi: String
        }
      },
      ya = _a,
      Aa = (a("4b0d"), Object(y["a"])(ya, fa, va, !1, null, "43c35640", null)),
      Ca = Aa.exports,
      ba = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", [a("div", {
          ref: "videoPlayer",
          staticClass: "hezi",
          attrs: {
            data: "4"
          }
        }, [t.configMp4.url ? a("VueXgplayer", {
          key: t.v_id,
          attrs: {
            config: t.configMp4
          },
          on: {
            player: function(e) {
              t.Player = e
            }
          }
        }) : t._e()], 1), a("div", {
          staticClass: "cc_panel_wapper mescroll channelbox",
          style: t.tops
        }, [a("mescroll-vue", {
          ref: "mescroll",
          attrs: {
            down: t.mescrollDown,
            up: t.mescrollUp
          },
          on: {
            init: t.mescrollInit
          }
        }, [a("van-tabbar", {
          attrs: {
            fixed: !1,
            "active-color": "#ee0a24",
            "inactive-color": "#000"
          },
          on: {
            change: t.onChange
          },
          model: {
            value: t.active,
            callback: function(e) {
              t.active = e
            },
            expression: "active"
          }
        }, [a("van-tabbar-item", {
          attrs: {
            icon: "replay"
          }
        }, [t._v("点我刷新")]), a("van-tabbar-item", {
          attrs: {
            icon: "refund-o"
          }
        }, [t._v("已购视频")]), a("van-tabbar-item", {
          attrs: {
            icon: "share-o"
          }
        }, [t._v("投诉视频")])], 1), a("div", {
          staticClass: "van-hairline--top",
          staticStyle: {
            "text-align": "center",
            color: "#fa436a",
            "font-weight": "600",
            "font-size": "16px"
          }
        }, [t._v("高清文件较大 稍后缓冲 即可播放 ")]), a("div", {
          staticClass: "van-hairline--top",
          staticStyle: {
            "text-align": "center",
            color: "#fa436a",
            "font-weight": "600",
            "font-size": "16px"
          }
        }, [t._v("如播放器空白请刷新页面 ")]), a("div", {
          staticClass: "contents",
          staticStyle: {
            "padding-left": "4%",
            "margin-top": "4%"
          }
        }, [a("div", {
          staticClass: "van-ellipsis"
        }, [t._v(t._s(t.doRates()) + "播放 " + t._s(t.doRate()) + "(金币) " + t._s(t.doRates()) +
          "次打赏")]), a("div", {
          staticClass: "van-ellipsis",
          staticStyle: {
            color: "#fa436a"
          }
        }, [a("van-icon", {
          attrs: {
            name: "edit"
          }
        }), t._v(" " + t._s(t.v_title) + " ")], 1), a("div", {
          staticClass: "van-ellipsis"
        }, [a("van-icon", {
          attrs: {
            name: "like-o"
          }
        }), t._v(" " + t._s(t.doRates()) + "喜欢 ")], 1)]), a("Divider", {
          staticStyle: {
            "margin-top": "0"
          },
          attrs: {
            plain: ""
          }
        }, [t._v("精彩推荐")]), a("div"), t._l(t.dataList, (function(e, i) {
          return a("div", {
            key: i,
            staticClass: "cc_panel_detail",
            on: {
              click: function(a) {
                return t.doPay(e)
              }
            }
          }, [a("div", {
            staticClass: "cc_panel_detail_image_wapper"
          }, [a("img", {
            directives: [{
              name: "lazy",
              rawName: "v-lazy",
              value: e.img,
              expression: "item.img"
            }],
            staticClass: "image",
            attrs: {
              alt: "预览图",
              width: "250",
              height: "188"
            }
          })]), a("div", {
            staticClass: "cc_panel_detail_info"
          }, [a("h4", {
            staticClass: "title"
          }, [t._v(t._s(e.title))]), a("span", {
            staticClass: "desc"
          }, [t._v(t._s(t.doRates()) + "人观看  好评:" + t._s(t.doRate()) + "%")])])])
        }))], 2)], 1), a("van-tabbar", {
          attrs: {
            route: "",
            "active-color": "#ee0a24",
            fixed: !0,
            "inactive-color": "#000"
          },
          model: {
            value: t.footerActiveClass,
            callback: function(e) {
              t.footerActiveClass = e
            },
            expression: "footerActiveClass"
          }
        }, [a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/",
            icon: "home-o"
          }
        }, [t._v("首页")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/cat",
            icon: "apps-o"
          }
        }, [t._v("分类")]), a("van-tabbar-item", {
          attrs: {
            replace: "",
            to: "/buy",
            icon: "shopping-cart-o"
          }
        }, [t._v("已购")])], 1), a("Modal", {
          attrs: {
            closable: !1,
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            src: t.ds_img
          }
        }), a("img", {
          staticStyle: {
            position: "relative",
            bottom: "70%"
          },
          attrs: {
            src: t.player_img
          },
          on: {
            click: function(e) {
              return t.sb()
            }
          }
        })]), a("span", {
          staticStyle: {
            "text-align": "left",
            "font-weight": "bold",
            display: "block",
            position: "relative",
            top: "10px"
          }
        }, [t._v(t._s(t.ds_title))]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              long: ""
            },
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.linkTo(e.url)
              }
            }
          })
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg1",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: ""
          },
          on: {
            click: function(e) {
              t.modal2 = !1
            }
          }
        }, [t._v("我在想想 ")])], 1)]), a("Modal", {
          attrs: {
            transfer: !0,
            styles: {
              top: "50px"
            },
            width: "90%"
          },
          model: {
            value: t.shoucang,
            callback: function(e) {
              t.shoucang = e
            },
            expression: "shoucang"
          }
        }, [a("p", {
          staticStyle: {
            "text-align": "center"
          },
          attrs: {
            slot: "header"
          },
          slot: "header"
        }, [a("span", [t._v("保存本站")])]), a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("p", {
          staticStyle: {
            color: "#f74550",
            "line-height": "25px",
            "margin-top": "10px",
            "text-align": "center"
          }
        }, [t._v(" 长按保存二维码或复制链接收藏本站 ")]), a("img", {
          staticStyle: {
            display: "block",
            margin: "auto"
          },
          attrs: {
            width: "100%",
            src: t.domain + "/index/index/qrcode?text=" + t.fav.url
          }
        })]), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: "",
            shape: "circle"
          },
          on: {
            click: function(e) {
              t.shoucang = !1
            }
          }
        }, [t._v("关闭 ")])], 1)]), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.model
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      wa = [],
      ka = (a("aae1"), a("9df8")),
      xa = {
        components: {
          MescrollVue: d["a"],
          VueXgplayer: ka["a"]
        },
        data: function() {
          return {
            murmur: localStorage.getItem("fingerprint"),
            model: "",
            player_img: ct.a,
            configMp4: {
              id: "vs",
              poster: null,
              width: "100%",
              height: "230px",
              playsinline: !1,
              url: "",
              "x5-video-player-type": "h5",
              "x5-video-player-fullscreen": "false"
            },
            Mp4Player: null,
            active: null,
            v_id: this.$route.params.id,
            v_title: null,
            emptyShow: !1,
            catShow: !0,
            em: ua.a,
            domain: localStorage.getItem("domain"),
            f: localStorage.getItem("f"),
            fav: {
              url: null
            },
            shoucang: !1,
            footerActiveClass: null,
            allImg: Q.a,
            tops: {
              top: "0px",
              bottom: " 50px",
              height: "auto",
              right: "0px",
              position: "fixed",
              padding: "0"
            },
            user: [],
            value8: !1,
            url: "",
            loading2: !1,
            modal2: !1,
            modal_loading: !1,
            ds_title: "打赏后观影",
            ds_img: "",
            vid: 0,
            cat: [],
            pay: [],
            activeClass: -1,
            params: {
              f: localStorage.getItem("f"),
              page: 1,
              row: 50,
              cid: "",
              key: "",
              payed: ""
            },
            catParam: {
              limit: 100,
              f: localStorage.getItem("f")
            },
            mescroll: null,
            mescrollDown: {},
            mescrollUp: {
              callback: this.upCallback,
              page: {
                num: 0,
                size: 10,
                f: localStorage.getItem("f"),
                page: 1,
                row: 50,
                cid: "",
                key: "",
                payed: ""
              },
              htmlNodata: '<p class="upwarp-nodata">-- 没有更多了.. --</p>',
              hardwareClass: "21",
              noMoreSize: 5,
              toTop: {
                src: p.a,
                offset: 600
              },
              empty: {
                icon: h.a,
                tip: "暂无相关数据~"
              }
            },
            dataList: []
          }
        },
        beforeRouteEnter: function(t, e, a) {
          a((function(t) {
            t.$refs.mescroll && t.$refs.mescroll.beforeRouteEnter()
          }))
        },
        beforeRouteLeave: function(t, e, a) {
          this.$refs.mescroll && this.$refs.mescroll.beforeRouteLeave(), a()
        },
        mounted: function() {
          this.getHezi("init"), this.changeHeight()
        },
        methods: {
          onChange: function(t) {
            0 == t && window.location.reload(), 1 == t && this.$router.push("/buy"), 2 == t && this.tousu()
          },
          doRate: function() {
            return parseInt(10 * Math.random() + 90)
          },
          doRates: function() {
            return parseInt(8e3 * Math.random() + 30)
          },
          mescrollInit: function(t) {
            this.mescroll = t
          },
          upCallback: function(t, e, i) {
            var s = this,
              o = this,
              n = Object(c["a"])(i);
            "object" == n && (o.activeClass = i.id, t.num = 1, t.cid = i.id, t.key = "", t.payed = ""),
              "string" == n && "all" == i && (o.activeClass = -1, o.footerActiveClass = 1, this.dataList = [], t
                .cid = "", t.num = 1, t.key = "", t.payed = ""), "string" == n && "yigou" == i && (o
                .footerActiveClass = 3, this.dataList = [], o.activeClass = 99, t.num = 1, t.cid = "", t.key = "",
                t.payed = "1"), "string" == n && "search" == i && (this.dataList = [], o.activeClass = -2, t.num =
                1, t.cid = "", t.key = o.params.key, t.payed = ""), t.page = t.num, t.murmur = window.murmur, this
              .$axios.post(o.domain + "/index/index/vlist", t).then((function(i) {
                s.$Spin.hide();
                var n = i.data.total;
                if (0 == i.data.code) return s.$Message.warning(i.data.msg), !1;
                var l = i.data.data;
                l = l.split("").reverse().join("");
                var r = a("e18e").Base64,
                  c = r.decode(l),
                  d = JSON.parse(c);
                0 == n ? (s.dataList = [], o.emptyShow = !0, o.catShow = !1) : (o.emptyShow = !1, o
                    .catShow = !0), 1 === t.num && (s.dataList = []), s.dataList = s.dataList.concat(d), s
                  .$nextTick((function() {
                    e.endSuccess(d.length)
                  }))
              })).catch((function() {
                e.endErr(), s.$Spin.hide()
              }))
          },
          doPay: function(t) {
            var e = this;
            e.vid = t.id, e.ds_img = t.img, e.ds_title = t.title, 1 != t.pay ? this.$axios.post(e.domain +
              "/index/index/pays/", {
                f: e.f,
                vid: t.id,
                money: t.money,
                murmur: localStorage.getItem("fingerprint")
              }).then((function(t) {
              e.pay = t.data.pay, e.modal2 = !0, e.user = t.data.user
            })) : this.$router.push("/v/" + t.id)
          },
          getCat: function() {
            var t = this;
            this.$axios.post(t.domain + "/index/index/cat", t.catParam).then((function(e) {
              var i = e.data.data;
              i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.cat = n
            }))
          },
          dingbu: function() {
            location.reload()
          },
          linkTo: function(t) {
            var e = this;
            if (this.url = t, "-" != this.user.pay_model && "-" != this.user.pay_model1) return console.log(t),
              this.url = t, void(this.value8 = !0);
            this.$Spin.show({
              render: function(t) {
                return t("div", [t("Icon", {
                  class: "demo-spin-icon-load",
                  props: {
                    type: "ios-loading",
                    size: 18
                  }
                }), t("div", "正在吊起支付,请稍后!")])
              }
            }), setTimeout((function() {
              e.$Spin.hide()
            }), 5e3), setTimeout((function() {
              e.$refs.forms.submit()
            }), 1500)
          },
          changeHeight: function() {
            var t = this,
              e = this;
            this.$nextTick((function() {
              var a = t.$refs["videoPlayer"].offsetHeight,
                i = 0,
                s = 0,
                o = 0,
                n = 0;
              void 0 != e.hezi && "" != e.hezi && (n = 270), t.tops.top = a + i + s + o + 15 + n + "px"
            }))
          },
          getHezi: function() {
            this.configMp4.id = "xgplayer";
            var t = this,
              e = t.v_id;
            this.$axios.post(t.domain + "/index/index/video", {
              vid: e,
              f: localStorage.getItem("f"),
              murmur: localStorage.getItem("fingerprint")
            }).then((function(e) {
              if (0 == e.data.code) return t.$Message.error({
                content: e.data.msg,
                duration: 10
              }), !1;
              var i = e.data.data.data;
              console.log(i), i = i.split("").reverse().join("");
              var s = a("e18e").Base64,
                o = s.decode(i),
                n = JSON.parse(o);
              t.v_title = n.link.title, t.configMp4.poster = n.link.img, t.configMp4.url = n.link.url,
                void 0 != t.Player && (t.Player.poster = n.link.img, t.Player.src = n.link.url, t.Player
                  .src = n.link.url, t.Player.start(n.link.url), t.Player.pause())
            }))
          },
          doFav: function() {
            var t = this.domain + "/index/index/lists/?f=" + localStorage.getItem("f") + "&view_id=" +
              localStorage.getItem("view_id");
            this.fav.url = t, this.shoucang = !0
          },
          search: function() {
            this.footerActiveClass = 2, location.href = this.domain + "/index/index/pagecat/?f=" + localStorage
              .getItem("f")
          },
          tousu: function() {
            this.$router.push({
              name: "tousu",
              query: {
                f: localStorage.getItem("f"),
                v: 0
              }
            })
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.model = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }))) : this.$Message.error("暂未开通该支付渠道")
          },
          sb: function() {
            this.$Message.warning("请先购买后观看哦。")
          }
        },
        watch: {
          $route: function(t) {
            this.v_id = t.params.id
          },
          v_id: function() {
            this.getHezi("reload")
          },
          footerActiveClass: function(t) {
            2 == t && (this.mescroll.setPageNum(1), this.mescrollUp.page.page = 1, this.mescrollUp.page.num = 1,
              this.mescrollUp.page.payed = "", this.mescrollUp.page.cid = "", this.mescroll.triggerDownScroll())
          },
          cat: function() {
            this.changeHeight()
          },
          hezi: function() {
            this.changeHeight(), this.getHezi()
          }
        }
      },
      Sa = xa,
      Ea = (a("ca5e"), Object(y["a"])(Sa, ba, wa, !1, null, "25e47a74", null)),
      Ia = Ea.exports,
      Ba = function() {
        var t = this,
          e = t.$createElement,
          a = t._self._c || e;
        return a("div", {
          staticStyle: {
            "background-color": "#000000"
          }
        }, [a("div", {
          staticStyle: {
            height: "100vh"
          }
        }), a("Modal", {
          attrs: {
            "mask-closable": !1,
            closable: !1,
            transfer: !0,
            styles: {
              top: "100px"
            },
            width: "90%"
          },
          model: {
            value: t.modal2,
            callback: function(e) {
              t.modal2 = e
            },
            expression: "modal2"
          }
        }, [a("div", {
          staticStyle: {
            "text-align": "center"
          }
        }, [a("div", {
          staticStyle: {
            width: "100%",
            height: "200px"
          }
        }, [a("img", {
          staticStyle: {
            width: "100%",
            height: "100%",
            "border-top-left-radius": "20px",
            "border-top-right-radius": "20px"
          },
          attrs: {
            src: t.stock.ds_img
          }
        }), a("img", {
          staticStyle: {
            position: "relative",
            bottom: "70%"
          },
          attrs: {
            src: t.player_img
          },
          on: {
            click: function(e) {
              return t.sb()
            }
          }
        })]), a("span", {
          staticStyle: {
            "text-align": "left",
            "font-weight": "bold",
            display: "block",
            position: "relative",
            top: "10px"
          }
        }, [t._v(t._s(t.stock.title))]), t._l(t.pay, (function(e, i) {
          return a("Button", {
            key: i,
            staticClass: "tanchuang",
            attrs: {
              type: "default",
              shape: "circle",
              long: ""
            },
            domProps: {
              innerHTML: t._s(e.name)
            },
            on: {
              click: function(a) {
                return t.zf(e)
              }
            }
          })
        }))], 2), a("div", {
          attrs: {
            slot: "footer"
          },
          slot: "footer"
        }, [a("Button", {
          staticClass: "bg1",
          staticStyle: {
            color: "#f9f8fb",
            "font-weight": "bold"
          },
          attrs: {
            type: "default",
            size: "large",
            long: ""
          },
          on: {
            click: function(e) {
              return t.go()
            }
          }
        }, [t._v("更多视频 ")])], 1)]), a("Drawer", {
          attrs: {
            title: "请选择支付方式",
            height: "200",
            placement: "bottom",
            closable: !1,
            "class-name": "tp"
          },
          model: {
            value: t.value8,
            callback: function(e) {
              t.value8 = e
            },
            expression: "value8"
          }
        }, [a("div", {
          staticClass: "pays d-wechat",
          on: {
            click: function(e) {
              return t.submit("wechat")
            }
          }
        }), a("div", {
          staticClass: "payss d-alipay",
          on: {
            click: function(e) {
              return t.submit("alipay")
            }
          }
        })]), a("form", {
          ref: "forms",
          staticStyle: {
            display: "none",
            position: "absolute",
            top: "1px",
            "z-index": "99999999"
          },
          attrs: {
            method: "post",
            action: t.url
          }
        }, [a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.f,
            expression: "f"
          }],
          attrs: {
            name: "f"
          },
          domProps: {
            value: t.f
          },
          on: {
            input: function(e) {
              e.target.composing || (t.f = e.target.value)
            }
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.murmur,
            expression: "murmur"
          }],
          attrs: {
            name: "murmur"
          },
          domProps: {
            value: t.murmur
          },
          on: {
            input: function(e) {
              e.target.composing || (t.murmur = e.target.value)
            }
          }
        }), a("input", {
          attrs: {
            name: "model"
          },
          domProps: {
            value: t.switchPay
          }
        }), a("input", {
          directives: [{
            name: "model",
            rawName: "v-model",
            value: t.vid,
            expression: "vid"
          }],
          attrs: {
            name: "vid"
          },
          domProps: {
            value: t.vid
          },
          on: {
            input: function(e) {
              e.target.composing || (t.vid = e.target.value)
            }
          }
        })])], 1)
      },
      Pa = [],
      za = {
        components: {},
        data: function() {
          return {
            player_img: ct.a,
            modal2: !0,
            f: localStorage.getItem("f"),
            modelll: "",
            caonima: null,
            vid: null,
            pay: [],
            user: [],
            switchPay: null,
            url: null,
            value8: !1,
            title: null,
            stock: [],
            murmur: localStorage.getItem("fingerprint"),
            domain: localStorage.getItem("domain")
          }
        },
        mounted: function() {
          this.doPay(this.$route.params.id)
        },
        methods: {
          go: function() {
            this.modal2 = !1, this.$router.back()
          },
          sb: function() {
            this.$Message.warning("请先购买后观看哦。")
          },
          submit: function(t) {
            var e = this,
              a = null;
            "wechat" == t && (a = this.user.pay_model), "alipay" == t && (a = this.user.pay_model1), null != a ? (
              this.switchPay = a, this.modelll = a, this.caonima = a, this.$Spin.show({
                render: function(t) {
                  return t("div", [t("Icon", {
                    class: "demo-spin-icon-load",
                    props: {
                      type: "ios-loading",
                      size: 18
                    }
                  }), t("div", "正在前往支付请稍后!")])
                }
              }), setTimeout((function() {
                e.$Spin.hide()
              }), 3e3), setTimeout((function() {
                e.$refs.forms.submit()
              }), 1e3)) : this.$Message.error("暂未开通该支付渠道")
          },
          zf: function(t) {
            this.value8 = !0, this.url = t.url
          },
          shouye: function() {
            this.$router.push("/")
          },
          doPay: function(t) {
            var e = this;
            e.vid = t, this.$axios.get(e.domain + "/index/index/pays/", {
              params: {
                f: localStorage.getItem("f"),
                vid: t,
                money: this.$route.query.m,
                murmur: localStorage.getItem("fingerprint")
              }
            }).then((function(t) {
              e.title = t.data.stock.title, e.pay = t.data.pay, e.user = t.data.user, e.stock = t.data.stock
            }))
          }
        },
        watch: {}
      },
      Ua = za,
      Oa = (a("eb60"), Object(y["a"])(Ua, Ba, Pa, !1, null, "7cf7775a", null)),
      Ma = Oa.exports;
    i["default"].use(s["a"]);
    var La = s["a"].prototype.push;
    s["a"].prototype.push = function(t) {
      return La.call(this, t).catch((function(t) {
        return t
      }))
    };
    var Ra = [{
        path: "/",
        name: "Home",
        component: Oe,
        keepAlive: !0,
        isBack: !1
      }, {
        path: "/zb",
        name: "zb",
        component: function() {
          return a.e("about").then(a.bind(null, "01c5"))
        },
        keepAlive: !0,
        isBack: !1
      }, {
        path: "/callback",
        name: "Callback",
        component: $e,
        keepAlive: !0,
        isBack: !1
      }, {
        path: "/tousu",
        name: "tousu",
        component: qe,
        keepAlive: !0,
        isBack: !1
      }, {
        path: "/submit",
        name: "submit",
        component: Fe,
        keepAlive: !0,
        isBack: !1
      }, {
        path: "/res",
        name: "res",
        component: la,
        keepAlive: !0,
        isBack: !1
      }, {
        path: "/cat",
        name: "Cat",
        component: ga
      }, {
        path: "/buy",
        name: "Buy",
        component: Ca
      }, {
        path: "/v/:id",
        name: "Video",
        component: Ia
      }, {
        path: "/p/:id",
        name: "Page",
        component: Ma
      }, {
        path: "/site",
        name: "site",
        component: function() {
          return a.e("about").then(a.bind(null, "f820"))
        }
      }, {
        path: "/test",
        name: "test",
        component: function() {
          return a.e("about").then(a.bind(null, "2762"))
        }
      }, {
        path: "/my",
        name: "my",
        component: function() {
          return a.e("about").then(a.bind(null, "0c6f"))
        }
      }],
      Qa = new s["a"]({
        routes: Ra
      });
    e["a"] = Qa
  },
  a418: function(t, e, a) {
    "use strict";
    a("5ea6")
  },
  a549: function(t, e, a) {
    t.exports = a.p + "img/l1.4307c7a0.jpg"
  },
  ac55: function(t, e, a) {
    "use strict";
    a("86ad")
  },
  acc9: function(t, e, a) {
    "use strict";
    a("f27d")
  },
  b45a: function(t, e, a) {
    t.exports = a.p + "img/icon1.19ec8631.png"
  },
  b9af: function(t, e, a) {
    t.exports = a.p + "img/2.dc64fd12.png"
  },
  be3b: function(t, e, a) {
    "use strict";
    a("6a08");
    var i = a("430a"),
      s = a("f753"),
      o = a.n(s),
      n = a("e2b4"),
      l = n.JSEncrypt,
      r = function(t) {
        var e = new l,
          a =
          "\n-----BEGIN PRIVATE KEY-----\nMIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKsrZ4WkEU0ryBaT\nTlpND/b05512nIzdAFrA5QLC7CvgFRy6Bu3+vvMf8YU3Lh1blnqZiIymLEleMaQL\nVoNrcGIY9AeCNIdvXwlnGkIm7XsR/gIIM0z11clFHq8IDz8RGhaVmph3kcAyhCYC\nEpJnFrPxToO9woulNSIDzOyfv0xDAgMBAAECgYAiyrLDFkMyBWkpNY3iivFoyATg\nG8DcXPt6duTVt9sbW/POn/1SmMolTuPfqQSlkP04NEbuF8S+sPe0GV3fXpI7+Stl\nBR8Ff6aNTXwWI24NTK4qE+AOXnlVi5AKInNOmcrr00qnSHjCVMyi2Q8zF4igvWyk\nNEmfztaPOTRcHwnOKQJBANVbELPIEPnrYBkoLjq7JgzDcWL4/FuU94AFOJC+lbg5\nxNrTfXkRs6LsxSqFXmfJJS0G53VhkCTYvC3snYyNS68CQQDNYcRZilWfNgfkx0l9\n692gImTKKWrwn6O54l8Hump74YmNm1Xk66c2/3zeC9hpFT6M+5x+2GZc0fiRVZuh\nhgmtAkBOtHlE2NjqWNnqbdgf8knnC3IYgKEXZ6ylnUdwnd29SBJGZx4yO0V5JL7X\nILvirWD5a0KXGpaCATHp/w9fegAhAkBzM+jodOEMOkl5OZPurxQU09YHU+4pZNJ4\n3RKDCjzamisHJF+s1cZo4iyPfMN6RjFc8XHZ8NaSMDEmjIeMtdvNAkEAoRWB5C5/\nWvqdD2fnobHrC25Qq+1AyZc04qqj5JTBMbY7XdbyVMbNWsdSg0aOXPXrAFSEtBNI\nOR0FBkYPqReKxQ==\n-----END PRIVATE KEY-----\n";
        e.setPrivateKey(a);
        var i = e.decryptLong(t);
        return i
      },
      c = {},
      d = o.a.create(c);
    d.interceptors.request.use((function(t) {
      return t
    }), (function(t) {
      return Promise.reject(t)
    })), d.interceptors.response.use((function(t) {
      if (1 == t.data.e || t.config.url.indexOf("/index/index/vlist") >= 0) {
        var e = t.data.data;
        return e = r(e), t.data.data = e, t
      }
      return t
    }), (function(t) {
      return Promise.reject(t)
    })), window.axios = d, i["default"].prototype.$axios = window.axios
  },
  c074: function(t, e, a) {},
  c199: function(t, e, a) {},
  c2e4: function(t, e, a) {
    t.exports = a.p + "img/3.211989ae.png"
  },
  c4b2: function(t, e, a) {
    t.exports = a.p + "img/emptyCart.74f402c8.jpeg"
  },
  c514: function(t, e, a) {},
  c80d: function(t, e, a) {
    t.exports = a.p + "img/4.2d340215.png"
  },
  c8d3: function(t, e, a) {
    "use strict";
    a("c199")
  },
  ca5e: function(t, e, a) {
    "use strict";
    a("0298")
  },
  caa7: function(t, e, a) {
    "use strict";
    a("cc82")
  },
  cb12: function(t, e, a) {
    "use strict";
    a("c514")
  },
  cb99: function(t, e, a) {
    t.exports = a.p + "img/7.9246382b.png"
  },
  cc82: function(t, e, a) {},
  cccb: function(t, e, a) {
    "use strict";
    a("c074")
  },
  d285: function(t, e, a) {
    t.exports = a.p + "img/11.e4cef80c.png"
  },
  dfa9: function(t, e, a) {
    "use strict";
    a("f434")
  },
  e533: function(t, e, a) {},
  e726: function(t, e, a) {},
  e75f: function(t, e, a) {},
  e83c: function(t, e, a) {
    t.exports = a.p + "img/9.73d01bf6.png"
  },
  eb60: function(t, e, a) {
    "use strict";
    a("e75f")
  },
  ec63: function(t, e, a) {
    "use strict";
    a("3c89")
  },
  f15b: function(t, e, a) {
    t.exports = a.p + "img/6.82bf7e11.png"
  },
  f1a1: function(t, e, a) {
    "use strict";
    a("56d0")
  },
  f27d: function(t, e, a) {},
  f434: function(t, e, a) {},
  f8bf: function(t, e, a) {
    t.exports = a.p + "img/8.f5cdcd2f.png"
  }
});
