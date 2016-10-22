'use strict';
MyApp.services = MyApp.services || {};
MyApp.services.facebook = (function($, logger, globalSettings) {
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
                appId: globalSettings.application.facebook.appId,
                xfbml: true,
                version: 'v2.8',
                cookie: true
            });

            FB.AppEvents.logPageView();
        };
    }

    function login() {
        FB.login(function(response) {
            if (response.authResponse) {
                window.location = globalSettings.application.routes.login;
            } else {
                logger.error('login', response);
            }
        }, {scope: 'user_location,email'});
    }

    return {
        init: init,
        login: login
    };
})(jQuery, Logger, globalSettings);
