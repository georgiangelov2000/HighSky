define([], function () {
    'use strict';

    var scriptPromise;
    var initialized = false;

    function loadScript(scriptUrl) {
        if (scriptPromise) {
            return scriptPromise;
        }

        scriptPromise = new Promise(function (resolve, reject) {
            var script = document.createElement('script');

            script.async = true;
            script.src = scriptUrl;
            script.onload = resolve;
            script.onerror = reject;

            document.head.appendChild(script);
        });

        return scriptPromise;
    }

    function initWidget(config) {
        if (initialized) {
            return;
        }

        if (!window.ChatWidget || typeof window.ChatWidget.init !== 'function') {
            return;
        }

        var initConfig = Object.assign({}, config.init || {}, {
            chat_init_url: window.location.href
        });

        window.ChatWidget.init(initConfig);
        initialized = true;
    }

    return function (config) {
        if (!config || !config.scriptUrl) {
            return;
        }

        loadScript(config.scriptUrl)
            .then(function () {
                initWidget(config);
            })
            .catch(function () {
                // Fail silently so storefront rendering is not disrupted by widget issues.
            });
    };
});
