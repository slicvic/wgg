'use strict';
var wgg = wgg || {};
wgg.app = (function($, logger, Vue, google, globalSettings) {
    var viewModels = {};

    function init() {
        initServices();
        initViewModels();
        setjQueryBindings();
    }

    function initServices() {
        wgg.services.facebook.init();
        wgg.services.googleMaps.init();
    }

    function initViewModels() {
        // Nav
        viewModels.nav = new Vue({
            el: '#nav',
            methods: {
                login: function() {
                    wgg.services.facebook.login();
                },
                createEvent: function() {
                    if (globalSettings.user.isLoggedIn) {
                        window.location = globalSettings.routes.events.create;
                    } else {
                        wgg.services.facebook.login(globalSettings.routes.events.create);
                    }
                }
            }
        });

        // Events -- Create/Edit Form
        viewModels.events = {};
        viewModels.events.form = new Vue({
            el: '#events--create-edit-form',
            data: {
                errors: '',
                submitted: false,
                submitButtonText: 'Save'
            },
            watch: {
                submitted: function(val) {
                    if (val) {
                        this.submitButtonText = 'Saving...';
                        this.errors = '';
                    } else {
                        this.submitButtonText = 'Save';
                    }
                }
            }
        });

        // Events -- My Events
        viewModels.events.myEvents = new Vue({
            el: '#events--my-events',
            methods: {
                cancelEvent: function(e) {
                    if (!confirm('Are you sure you want to cancel this game?')) {
                        e.preventDefault();
                    }
                }
            }
        });
    }

    function setjQueryBindings() {
        // Bind form validation
        $('.js-validate-form').parsley();

        // Bind form submit
        $('#events--create-edit-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);

            viewModels.events.form.submitted = true;

            $.post(form.attr('action'), form.serialize())
                .done(function(response) {
                    window.location = globalSettings.routes.account.events;
                }).fail(function(response) {
                    if (response.responseJSON.error) {
                        if (response.responseJSON.error.message_format === 'html') {
                            viewModels.events.form.errors = response.responseJSON.error.message;
                        } else {
                            toastr.error(response.responseJSON.error.message, 'Whoops!');
                        }
                    } else {
                        toastr.error('Looks like Something went wrong, please try again.', 'Whoops!');
                    }
                }).always(function() {
                    viewModels.events.form.submitted = false;
                });
        });

        // Bind date pickers
        $('.js-datepicker').datepicker({
            todayHighlight: true,
            autoclose: true
        });

        // Bind datetime pickers
        $('.js-datetimepicker').datetimepicker({
            minDate: Date.now(),
            sideBySide: true,
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right'
            }
        });

        // Bind city autocomplete
        $('.js-typeahead-city').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            displayKey: function(datum) {
                return datum.description;
            },
            limit: 10,
            templates: {
                empty: [
                    '<div>Nothing Found.</div>'
                ],
                suggestion: function (datum) {
                    return ['<a href="#">', datum.description, '</a>'].join('');
                }
            },
            source: function(query, syncResults, asyncResults) {
                globalSettings.google.maps.services.autocomplete.getPlacePredictions({
                    types: ['(cities)'],
                    input: query
                }, function(results, status) {
                    logger.info('AutocompleteService.getPlacePredictions', results, status);
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        asyncResults(results);
                    }
                });
            }
        }).on('typeahead:selected', function (obj, datum) {
            var el = $(this);

            // Get new latitude and longitude
            globalSettings.google.maps.services.places.getDetails({
                placeId: datum.place_id
            }, function(place, status) {
                logger.info('PlacesService.getDetails', place);
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    $(el.data('bind-field-lat')).val(place.geometry.location.lat());
                    $(el.data('bind-field-lng')).val(place.geometry.location.lng());
                }
            });
        }).on('change', function (e) {
            var el = $(this);

            // Clear previous latitude and longitude
            $(el.data('bind-field-lat')).val('');
            $(el.data('bind-field-lng')).val('');
        });

        // Bind venue autocomplete
        $('.js-typeahead-venue').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            displayKey: function(datum) {
                return datum.name;
            },
            limit: 10,
            templates: {
                empty: [
                    '<div>Nothing Found.</div>'
                ],
                suggestion: function (datum) {
                    return ['<a href="#">', datum.name + ' (' + datum.formatted_address + ')', '</a>'].join('');
                }
            },
            source: function(query, syncResults, asyncResults) {
                globalSettings.google.maps.services.places.textSearch({
                    query: query
                }, function(results, status) {
                    logger.info('PlacesService.textSearch', results, status);
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        asyncResults(results);
                    }
                });
            }
        }).on('typeahead:selected', function (obj, datum) {
            var el = $(this);
            // Get new latitude and longitude
            globalSettings.google.maps.services.places.getDetails({
                placeId: datum.place_id
            }, function(place, status) {
                logger.info('PlacesService.getDetails', place);
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    $(el.data('bind-field-lat')).val(place.geometry.location.lat());
                    $(el.data('bind-field-lng')).val(place.geometry.location.lng());
                    $(el.data('bind-field-address')).val(place.formatted_address);
                    $(el.data('bind-field-url')).val(place.url);
                }
            });
        }).on('change', function(e) {
            var el = $(this);

            // Clear previous latitude and longitude
            $(el.data('bind-field-lat')).val('');
            $(el.data('bind-field-lng')).val('');
            $(el.data('bind-field-address')).val('');
            $(el.data('bind-field-url')).val('');
        });
    }

    return {
        init: init,
        viewModels: viewModels
    }
}(jQuery, Logger, Vue, google, globalSettings));

wgg.app.init();
