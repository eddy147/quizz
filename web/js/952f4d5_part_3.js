/**
 * @fileoverview This file is the entry point for the compiler.
 *
 * You can compile this script by running (assuming you have JMSGoogleClosureBundle installed):
 *
 *    php app/console plovr:build @FOSJsRoutingBundle/compile.js
 */

goog.require('fos.Router');

goog.exportSymbol('fos.Router', fos.Router);
goog.exportSymbol('fos.Router.setData', function(data) {
    var router = fos.Router.getInstance();
    router.setBaseUrl(/** @type {string} */ (data['base_url']));
    router.setRoutes(/** @type {Object.<string, fos.Router.Route>} */ (data['routes']));
    if ('prefix' in data) {
        router.setPrefix(/** @type {string} */ (data['prefix']));
    }
    router.setHost(/** @type {string} */ (data['host']));
    router.setScheme(/** @type {string} */ (data['scheme']));
});
goog.exportProperty(fos.Router, 'getInstance', fos.Router.getInstance);
goog.exportProperty(fos.Router.prototype, 'setRoutes', fos.Router.prototype.setRoutes);
goog.exportProperty(fos.Router.prototype, 'getRoutes', fos.Router.prototype.getRoutes);
goog.exportProperty(fos.Router.prototype, 'setBaseUrl', fos.Router.prototype.setBaseUrl);
goog.exportProperty(fos.Router.prototype, 'getBaseUrl', fos.Router.prototype.getBaseUrl);
goog.exportProperty(fos.Router.prototype, 'generate', fos.Router.prototype.generate);
goog.exportProperty(fos.Router.prototype, 'setPrefix', fos.Router.prototype.setPrefix);
goog.exportProperty(fos.Router.prototype, 'getRoute', fos.Router.prototype.getRoute);

window['Routing'] = fos.Router.getInstance();

/**
 * @fileoverview This file contains some properties which we don't
 * want the compiler to rename.
 */
var externs = {
    tokens: '',
    defaults: '',
    requirements: '',
    hosttokens: ''
};

goog.provide('fos.Router');

goog.require('goog.structs.Map');
goog.require('goog.array');
goog.require('goog.object');
goog.require('goog.uri.utils');

/**
 * @constructor
 * @param {fos.Router.Context=} opt_context
 * @param {Object.<string, fos.Router.Route>=} opt_routes
 */
fos.Router = function(opt_context, opt_routes) {
    this.context_ = opt_context || {base_url: '', prefix: '', host: '', scheme: ''};
    this.setRoutes(opt_routes || {});
};
goog.addSingletonGetter(fos.Router);

/**
 * @typedef {{
 *     tokens: (Array.<Array.<string>>),
 *     defaults: (Object.<string, string>),
 *     requirements: Object,
 *     hosttokens: (Array.<string>)
 * }}
 */
fos.Router.Route;

/**
 * @typedef {{
 *     base_url: (string)
 * }}
 */
fos.Router.Context;

/**
 * @param {Object.<string, fos.Router.Route>} routes
 */
fos.Router.prototype.setRoutes = function(routes) {
    this.routes_ = new goog.structs.Map(routes);
};

/**
 * @return {Object.<string, fos.Router.Route>} routes
 */
fos.Router.prototype.getRoutes = function() {
    return this.routes_;
};

/**
 * @param {string} baseUrl
 */
fos.Router.prototype.setBaseUrl = function(baseUrl) {
    this.context_.base_url = baseUrl;
};

/**
 * @return {string}
 */
fos.Router.prototype.getBaseUrl = function() {
    return this.context_.base_url;
};

/**
 * @param {string} prefix
 */
fos.Router.prototype.setPrefix = function(prefix) {
    this.context_.prefix = prefix;
};

/**
 * @param {string} scheme
 */
fos.Router.prototype.setScheme = function(scheme) {
    this.context_.scheme = scheme;
};

/**
 * @return {string}
 */
fos.Router.prototype.getScheme = function() {
    return this.context_.scheme;
};

/**
 * @param {string} host
 */
fos.Router.prototype.setHost = function(host) {
    this.context_.host = host;
};

/**
 * @return {string}
 */
fos.Router.prototype.getHost = function() {
    return this.context_.host;
};


/**
 * Builds query string params added to a URL.
 * Port of jQuery's $.param() function, so credit is due there.
 *
 * @param {string} prefix
 * @param {Array|Object|string} params
 * @param {Function} add
 */
fos.Router.prototype.buildQueryParams = function(prefix, params, add) {
    var self = this;
    var name;
    var rbracket = new RegExp(/\[\]$/);

    if (params instanceof Array) {
        goog.array.forEach(params, function(val, i) {
            if (rbracket.test(prefix)) {
                add(prefix, val);
            } else {
                self.buildQueryParams(prefix + '[' + (typeof val === 'object' ? i : '') + ']', val, add);
            }
        });
    } else if (typeof params === 'object') {
        for (name in params) {
            this.buildQueryParams(prefix + '[' + name + ']', params[name], add);
        }
    } else {
        add(prefix, params);
    }
};

/**
 * Returns a raw route object.
 *
 * @param {string} name
 * @return {fos.Router.Route}
 */
fos.Router.prototype.getRoute = function(name) {
    var prefixedName = this.context_.prefix + name;
    if (!this.routes_.containsKey(prefixedName)) {
        // Check first for default route before failing
        if (!this.routes_.containsKey(name)) {
            throw new Error('The route "' + name + '" does not exist.');
        }
    } else {
        name = prefixedName;
    }

    return (this.routes_.get(name));
};


/**
 * Generates the URL for a route.
 *
 * @param {string} name
 * @param {Object.<string, string>} opt_params
 * @param {boolean} absolute
 * @return {string}
 */
fos.Router.prototype.generate = function(name, opt_params, absolute) {
    var route = (this.getRoute(name)),
        params = opt_params || {},
        unusedParams = goog.object.clone(params),
        url = '',
        optional = true,
        host = '';

    goog.array.forEach(route.tokens, function(token) {
        if ('text' === token[0]) {
            url = token[1] + url;
            optional = false;

            return;
        }

        if ('variable' === token[0]) {
            var hasDefault = goog.object.containsKey(route.defaults, token[3]);
            if (false === optional || !hasDefault || (goog.object.containsKey(params, token[3]) && params[token[3]] != route.defaults[token[3]])) {
                    var value;

                    if (goog.object.containsKey(params, token[3])) {
                        value = params[token[3]];
                        goog.object.remove(unusedParams, token[3]);
                    } else if (hasDefault) {
                        value = route.defaults[token[3]];
                    } else if (optional) {
                        return;
                    } else {
                        throw new Error('The route "' + name + '" requires the parameter "' + token[3] + '".');
                    }

                    var empty = true === value || false === value || '' === value;

                    if (!empty || !optional) {
                        var encodedValue = encodeURIComponent(value).replace(/%2F/g, '/');

                        if ('null' === encodedValue && null === value) {
                            encodedValue = '';
                        }

                        url = token[1] + encodedValue + url;
                    }

                    optional = false;
                } else if (hasDefault) {
                    goog.object.remove(unusedParams, token[3]);
                }

                return;
        }

        throw new Error('The token type "' + token[0] + '" is not supported.');
    });

    if (url === '') {
        url = '/';
    }

    goog.array.forEach(route.hosttokens, function (token) {
        var value;

        if ('text' === token[0]) {
            host = token[1] + host;

            return;
        }

        if ('variable' === token[0]) {
            if (goog.object.containsKey(params, token[3])) {
                value = params[token[3]];
                goog.object.remove(unusedParams, token[3]);
            } else if (goog.object.containsKey(route.defaults, token[3])) {
                value = route.defaults[token[3]];
            }

            host = token[1] + value + host;
        }
    });

    url = this.context_.base_url + url;
    if (goog.object.containsKey(route.requirements, "_scheme") && this.getScheme() != route.requirements["_scheme"]) {
        url = route.requirements["_scheme"] + "://" + (host || this.getHost()) + url;
    } else if (host && this.getHost() !== host) {
        url = this.getScheme() + "://" + host + url;
    } else if (absolute === true) {
        url = this.getScheme() + "://" + this.getHost() + url;
    }

    if (goog.object.getCount(unusedParams) > 0) {
        var prefix;
        var queryParams = [];
        var add = function(key, value) {
            // if value is a function then call it and assign it's return value as value
            value = (typeof value === 'function') ? value() : value;

            // change null to empty string
            value = (value === null) ? '' : value;

            queryParams.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
        };

        for (prefix in unusedParams) {
            this.buildQueryParams(prefix, unusedParams[prefix], add);
        }

        url = url + '?' + queryParams.join('&').replace(/%20/g, '+');
    }

    return url;
};

goog.require('goog.testing.jsunit');

function testGenerate() {
    var router = new fos.Router({base_url: ''}, {
        literal: {
            tokens: [['text', '/homepage']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/homepage', router.generate('literal'));
}

function testGenerateWithParams() {
    var router = new fos.Router({base_url: ''}, {
        blog_post: {
            tokens: [['variable', '/', '[^/]+?', 'slug'], ['text', '/blog-post']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/blog-post/foo', router.generate('blog_post', {slug: 'foo'}));
}

function testGenerateUsesBaseUrl() {
    var router = new fos.Router({base_url: '/foo'}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/foo/bar', router.generate('homepage'));
}

function testGenerateUsesSchemeRequirements() {
    var router = new fos.Router({base_url: '/foo', host: "localhost"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {"_scheme": "https"},
            hosttokens: []
        }
    });

    assertEquals('https://localhost/foo/bar', router.generate('homepage'));
}

function testGenerateUsesHost() {
    var router = new fos.Router({base_url: '/foo', host: "localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {},
            hosttokens: [['text', 'otherhost']]
        }
    });

    assertEquals('http://otherhost/foo/bar', router.generate('homepage'));
}

function testGenerateUsesHostWhenTheSameSchemeRequirementGiven() {
    var router = new fos.Router({base_url: '/foo', host: "localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {"_scheme": "http"},
            hosttokens: [['text', 'otherhost']]
        }
    });

    assertEquals('http://otherhost/foo/bar', router.generate('homepage'));
}

function testGenerateUsesHostWhenAnotherSchemeRequirementGiven() {
    var router = new fos.Router({base_url: '/foo', host: "localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {"_scheme": "https"},
            hosttokens: [['text', 'otherhost']]
        }
    });

    assertEquals('https://otherhost/foo/bar', router.generate('homepage'));
}

function testGenerateSupportsHostPlaceholders() {
    var router = new fos.Router({base_url: '/foo', host: "localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {},
            hosttokens: [
                ['text', '.localhost'],
                ['variable', '', '', 'subdomain']
            ]
        }
    });

    assertEquals('http://api.localhost/foo/bar', router.generate('homepage', {subdomain: 'api'}));
}

function testGenerateSupportsHostPlaceholdersDefaults() {
    var router = new fos.Router({base_url: '/foo', host: "localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {subdomain: 'api'},
            requirements: {},
            hosttokens: [
                ['text', '.localhost'],
                ['variable', '', '', 'subdomain']
            ]
        }
    });

    assertEquals('http://api.localhost/foo/bar', router.generate('homepage'));
}

function testGenerateGeneratesRelativePathWhenTheSameHostGiven() {
    var router = new fos.Router({base_url: '/foo', host: "api.localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {},
            hosttokens: [
                ['text', '.localhost'],
                ['variable', '', '', 'subdomain']
            ]
        }
    });

    assertEquals('/foo/bar', router.generate('homepage', {subdomain: 'api'}));
}

function testGenerateUsesAbsoluteUrl() {
    var router = new fos.Router({base_url: '/foo', host: "localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('http://localhost/foo/bar', router.generate('homepage', [], true));
}

function testGenerateUsesAbsoluteUrlWhenSchemeRequirementGiven() {
    var router = new fos.Router({base_url: '/foo', host: "localhost", scheme: "http"}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {"_scheme": "http"},
            hosttokens: []
        }
    });

    assertEquals('http://localhost/foo/bar', router.generate('homepage', [], true));
}

function testGenerateWithOptionalTrailingParam() {
    var router = new fos.Router({base_url: ''}, {
        posts: {
            tokens: [['variable', '.', '', '_format'], ['text', '/posts']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/posts', router.generate('posts'));
    assertEquals('/posts.json', router.generate('posts', {'_format': 'json'}));
}

function testGenerateQueryStringWithoutDefaults() {
    var router = new fos.Router({base_url: ''}, {
        posts: {
            tokens: [['variable', '/', '[1-9]+[0-9]*', 'page'], ['text', '/blog-posts']],
            defaults: {'page' : 1},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/blog-posts?extra=1', router.generate('posts', {page: 1, extra: 1}));
}

function testAllowSlashes() {
    var router = new fos.Router({base_url: ''}, {
        posts: {
            tokens: [['variable', '/', '.+', 'id'], ['text', '/blog-post']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/blog-post/foo/bar', router.generate('posts', {id: 'foo/bar'}));
}

function testGenerateWithExtraParams() {
    var router = new fos.Router(undefined, {
        foo: {
            tokens: [['variable', '/', '', 'bar']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/baz?foo=bar', router.generate('foo', {
        bar: 'baz',
        foo: 'bar'
    }));
}

function testGenerateWithExtraParamsDeep() {
    var router = new fos.Router(undefined, {
        foo: {
            tokens: [['variable', '/', '', 'bar']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/baz?foo%5B%5D=1&foo%5B1%5D%5B%5D=1&foo%5B1%5D%5B%5D=2&foo%5B1%5D%5B%5D=3&foo%5B1%5D%5B%5D=foo&foo%5B%5D=3&foo%5B%5D=4&foo%5B%5D=bar&foo%5B5%5D%5B%5D=1&foo%5B5%5D%5B%5D=2&foo%5B5%5D%5B%5D=3&foo%5B5%5D%5B%5D=baz&baz%5Bfoo%5D=bar+foo&baz%5Bbar%5D=baz&bob=cat', router.generate('foo', {
        bar: 'baz', // valid param, not included in the query string
        foo: [1, [1, 2, 3, 'foo'], 3, 4, 'bar', [1, 2, 3, 'baz']],
        baz: {
            foo : 'bar foo',
            bar : 'baz'
        },
        bob: 'cat'
    }));
}

function testGenerateThrowsErrorWhenRequiredParameterWasNotGiven() {
    var router = new fos.Router({base_url: ''}, {
        foo: {
            tokens: [['text', '/moo'], ['variable', '/', '', 'bar']],
            defaults: {},
            requirements: {}
        }
    });

    try {
        router.generate('foo');
        fail('generate() was expected to throw an error, but has not.');
    } catch (e) {
        assertEquals('The route "foo" requires the parameter "bar".', e.message);
    }
}

function testGenerateThrowsErrorForNonExistentRoute() {
    var router = new fos.Router({base_url: ''}, {});

    try {
        router.generate('foo');
        fail('generate() was expected to throw an error, but has not.');
    } catch (e) { }
}

function testGetBaseUrl() {
    var router = new fos.Router({base_url: '/foo'}, {
        homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {}
        }
    });

    assertEquals('/foo', router.getBaseUrl());
}

function testGeti18n() {
    var router = new fos.Router({base_url: '/foo', prefix: 'en__RG__'}, {
        en__RG__homepage: {
            tokens: [['text', '/bar']],
            defaults: {},
            requirements: {},
            hosttokens: []
        },
        es__RG__homepage: {
            tokens: [['text', '/es/bar']],
            defaults: {},
            requirements: {},
            hosttokens: []
        },
        _admin: {
            tokens: [['text', '/admin']],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/foo/bar', router.generate('homepage'));
    assertEquals('/foo/admin', router.generate('_admin'));

    router.setPrefix('es__RG__');
    assertEquals('/foo/es/bar', router.generate('homepage'));
}

function testGetRoute() {
    var router = new fos.Router({base_url: ''}, {
        blog_post: {
            tokens: [['variable', '/', '[^/]+?', 'slug'], ['text', '/blog-post']],
            defaults: {},
            requirements: {"_scheme": "http"}
        }
    });

    var expected = {
        'defaults': {},
        'tokens' : [
            ['variable', '/', '[^/]+?', 'slug'],
            ['text', '/blog-post']
        ],
        'requirements': {"_scheme": "http"}
    };

    assertObjectEquals(expected, router.getRoute('blog_post'));
}

function testGetRoutes() {
    var router = new fos.Router({base_url: ''}, {
        blog_post: 'test',
        blog: 'test'
    });

    var expected = new goog.structs.Map({
        blog_post: 'test',
        blog: 'test'
    });

    assertObjectEquals(expected, router.getRoutes());
}

function testGenerateWithNullValue() {
    var router = new fos.Router({base_url: ''}, {
        posts: {
            tokens: [
                ['variable', '/', '.+', 'id'],
                ['variable', '/', '.+', 'page'],
                ['text', '/blog-post']
            ],
            defaults: {},
            requirements: {},
            hosttokens: []
        }
    });

    assertEquals('/blog-post//10', router.generate('posts', { page: null, id: 10 }));
}

function waitFor(testFx, onReady, timeOutMillis) {
    var maxtimeOutMillis = timeOutMillis ? timeOutMillis : 3001, //< Default Max Timout is 3s
        start = new Date().getTime(),
        condition = false,
        interval = setInterval(function() {
            if ( (new Date().getTime() - start < maxtimeOutMillis) && !condition ) {
                // If not time-out yet and condition not yet fulfilled
                condition = (typeof(testFx) === "string" ? eval(testFx) : testFx()); //< defensive code
            } else {
                if(!condition) {
                    // If condition still not fulfilled (timeout but condition is 'false')
                    console.log("'waitFor()' timeout");
                    phantom.exit(1);
                } else {
                    // Condition fulfilled (timeout and/or condition is 'true')
                    console.log("'waitFor()' finished in " + (new Date().getTime() - start) + "ms.");
                    typeof(onReady) === "string" ? eval(onReady) : onReady(); //< Do what it's supposed to do once the condition is fulfilled
                    clearInterval(interval); //< Stop this interval
                }
            }
        }, 100); //< repeat check every 250ms
}

if (phantom.args.length === 0) {
    console.log('Usage: phantomjs run_jsunit.js <filepath>');
    phantom.exit();
} else {
    var page = new WebPage();

    page.onConsoleMessage = function(msg) {
        console.log(msg);
    };

    page.open(phantom.args[0], function(status) {
        if (status === 'success') {
            waitFor(function() {
                return page.evaluate(function() {
                    return G_testRunner.isFinished();
                });
            }, function() {
                var exitCode = page.evaluate(function() {
                    return G_testRunner.isSuccess() ? 0 : 1;
                });
                phantom.exit(exitCode);
            });
        } else {
            console.log("phantomjs: Unable to load page. [" + address + ']');
            phantom.exit();
        }
    });
}

/**
 * Portions of this code are from the Google Closure Library,
 * received from the Closure Authors under the Apache 2.0 license.
 *
 * All other code is (C) FriendsOfSymfony and subject to the MIT license.
 */
(function() {var f=!1,i,k=this;function l(a,c){var b=a.split("."),d=k;!(b[0]in d)&&d.execScript&&d.execScript("var "+b[0]);for(var e;b.length&&(e=b.shift());)!b.length&&void 0!==c?d[e]=c:d=d[e]?d[e]:d[e]={}};var m=Array.prototype,n=m.forEach?function(a,c,b){m.forEach.call(a,c,b)}:function(a,c,b){for(var d=a.length,e="string"==typeof a?a.split(""):a,g=0;g<d;g++)g in e&&c.call(b,e[g],g,a)};function q(a,c){this.c={};this.a=[];var b=arguments.length;if(1<b){if(b%2)throw Error("Uneven number of arguments");for(var d=0;d<b;d+=2)this.set(arguments[d],arguments[d+1])}else if(a){var e;if(a instanceof q){r(a);d=a.a.concat();r(a);e=[];for(b=0;b<a.a.length;b++)e.push(a.c[a.a[b]])}else{var b=[],g=0;for(d in a)b[g++]=d;d=b;b=[];g=0;for(e in a)b[g++]=a[e];e=b}for(b=0;b<d.length;b++)this.set(d[b],e[b])}}q.prototype.f=0;q.prototype.p=0;
function r(a){if(a.f!=a.a.length){for(var c=0,b=0;c<a.a.length;){var d=a.a[c];t(a.c,d)&&(a.a[b++]=d);c++}a.a.length=b}if(a.f!=a.a.length){for(var e={},b=c=0;c<a.a.length;)d=a.a[c],t(e,d)||(a.a[b++]=d,e[d]=1),c++;a.a.length=b}}q.prototype.get=function(a,c){return t(this.c,a)?this.c[a]:c};q.prototype.set=function(a,c){t(this.c,a)||(this.f++,this.a.push(a),this.p++);this.c[a]=c};function t(a,c){return Object.prototype.hasOwnProperty.call(a,c)};var u,v,w,x;function y(){return k.navigator?k.navigator.userAgent:null}x=w=v=u=f;var C;if(C=y()){var D=k.navigator;u=0==C.indexOf("Opera");v=!u&&-1!=C.indexOf("MSIE");w=!u&&-1!=C.indexOf("WebKit");x=!u&&!w&&"Gecko"==D.product}var E=v,F=x,G=w;var I;if(u&&k.opera){var J=k.opera.version;"function"==typeof J&&J()}else F?I=/rv\:([^\);]+)(\)|;)/:E?I=/MSIE\s+([^\);]+)(\)|;)/:G&&(I=/WebKit\/(\S+)/),I&&I.exec(y());function K(a,c){this.b=a||{e:"",prefix:"",host:"",scheme:""};this.h(c||{})}K.g=function(){return K.j?K.j:K.j=new K};i=K.prototype;i.h=function(a){this.d=new q(a)};i.o=function(){return this.d};i.k=function(a){this.b.e=a};i.n=function(){return this.b.e};i.l=function(a){this.b.prefix=a};
function L(a,c,b,d){var e,g=RegExp(/\[\]$/);if(b instanceof Array)n(b,function(b,e){g.test(c)?d(c,b):L(a,c+"["+("object"===typeof b?e:"")+"]",b,d)});else if("object"===typeof b)for(e in b)L(a,c+"["+e+"]",b[e],d);else d(c,b)}i.i=function(a){var c=this.b.prefix+a;if(t(this.d.c,c))a=c;else if(!t(this.d.c,a))throw Error('The route "'+a+'" does not exist.');return this.d.get(a)};
i.m=function(a,c,b){var d=this.i(a),e=c||{},g={},z;for(z in e)g[z]=e[z];var h="",s=!0,j="";n(d.tokens,function(b){if("text"===b[0])h=b[1]+h,s=f;else if("variable"===b[0]){var c=b[3]in d.defaults;if(f===s||!c||b[3]in e&&e[b[3]]!=d.defaults[b[3]]){if(b[3]in e){var c=e[b[3]],p=b[3];p in g&&delete g[p]}else if(c)c=d.defaults[b[3]];else{if(s)return;throw Error('The route "'+a+'" requires the parameter "'+b[3]+'".');}if(!(!0===c||f===c||""===c)||!s)p=encodeURIComponent(c).replace(/%2F/g,"/"),"null"===p&&
null===c&&(p=""),h=b[1]+p+h;s=f}else c&&(b=b[3],b in g&&delete g[b])}else throw Error('The token type "'+b[0]+'" is not supported.');});""===h&&(h="/");n(d.hosttokens,function(a){var b;if("text"===a[0])j=a[1]+j;else if("variable"===a[0]){if(a[3]in e){b=e[a[3]];var c=a[3];c in g&&delete g[c]}else a[3]in d.defaults&&(b=d.defaults[a[3]]);j=a[1]+b+j}});h=this.b.e+h;"_scheme"in d.requirements&&this.b.scheme!=d.requirements._scheme?h=d.requirements._scheme+"://"+(j||this.b.host)+h:j&&this.b.host!==j?h=
this.b.scheme+"://"+j+h:!0===b&&(h=this.b.scheme+"://"+this.b.host+h);var c=0,A;for(A in g)c++;if(0<c){var B,H=[];A=function(a,b){b="function"===typeof b?b():b;H.push(encodeURIComponent(a)+"="+encodeURIComponent(null===b?"":b))};for(B in g)L(this,B,g[B],A);h=h+"?"+H.join("&").replace(/%20/g,"+")}return h};l("fos.Router",K);l("fos.Router.setData",function(a){var c=K.g();c.k(a.base_url);c.h(a.routes);"prefix"in a&&c.l(a.prefix);c.b.host=a.host;c.b.scheme=a.scheme});K.getInstance=K.g;K.prototype.setRoutes=K.prototype.h;K.prototype.getRoutes=K.prototype.o;K.prototype.setBaseUrl=K.prototype.k;K.prototype.getBaseUrl=K.prototype.n;K.prototype.generate=K.prototype.m;K.prototype.setPrefix=K.prototype.l;K.prototype.getRoute=K.prototype.i;window.Routing=K.g();})();