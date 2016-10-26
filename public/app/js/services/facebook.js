'use strict'
var wgg = wgg || {};
wgg.services = wgg.services || {};
wgg.services.facebook = (function(logger, globalSettings) {
    function init() {
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        window.fbAsyncInit = function() {
            FB.init({
                appId: globalSettings.facebook.appId,
                xfbml: true,
                version: 'v2.8',
                cookie: true
            });

            FB.AppEvents.logPageView();
        };
    }

    function login(redirectUrl) {
        FB.login(function(response) {
            if (response.authResponse) {
                var loginUrl = globalSettings.routes.login.facebook;
                if (redirectUrl) {
                    loginUrl += '?success_redirect=' + redirectUrl;
                }
                window.location = loginUrl;
            } else {
                logger.error('login', response);
            }
        }, {scope: 'user_location,email'});
    }

    return {
        login: login,
        init: init
    };
})(Logger, globalSettings);
