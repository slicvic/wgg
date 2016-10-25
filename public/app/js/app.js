'use strict';
var wgg = wgg || {};
wgg.globalSettings = globalSettings;
wgg.app = (function($, logger, globalSettings) {
    var settings = globalSettings.application;

    var vue = new Vue({
        el: '#app',
        data: {
            events: {
                form: {
                    validationErrors: '',
                    submitted: false,
                    submitButtonText: 'Save'
                }
            }
        },
        methods: {
            login: function() {
                wgg.services.facebook.login();
            },
            cancelEvent: function(e) {
                if (confirm('Are you sure you want to cancel this event?')) {
                    //
                } else {
                    e.preventDefault();
                }
            }
        },
        mounted: function() {
            initServices();
            setjQueryBindings();
        }
    });

    function initServices() {
        wgg.services.facebook.init();
    }

    function setjQueryBindings() {
        // Bind form validation
        $('.js-validate-form').parsley();
        Parsley.addMessages('en', {
            required:  'Please put something here.',
        });

        // Bind form submit
        $('#events--add-edit-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);

            vue.events.form.validationErrors = '';
            vue.events.form.submitted = true;
            vue.events.form.submitButtonText = 'Saving...';

            $.post(form.attr('action'), form.serialize())
                .done(function(response) {
                    window.location = globalSettings.application.routes.account.events;
                }).fail(function(response) {
                    if (response.responseJSON.error) {
                        toastr.error(response.responseJSON.error, 'Whoops!');
                    } else if (response.responseJSON.errors) {
                        vue.events.form.validationErrors = response.responseJSON.errors.html;
                    } else {
                        toastr.error('Something went wrong, please try again', 'Whoops!');
                    }
                }).always(function() {
                    vue.events.form.submitted = false;
                    vue.events.form.submitButtonText = 'Save';
                });
        });

        // Bind date pickers
        /*$('.js-datepicker').datepicker({
            todayHighlight: true,
            autoclose: true
        });*/

        // Bind datetime pickers
        $('.js-datetimepicker').datetimepicker({
            minDate: Date.now(),
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
                settings.google.maps.services.autocomplete.getPlacePredictions({
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
            settings.google.maps.services.places.getDetails({
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
                settings.google.maps.services.places.textSearch({
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
            settings.google.maps.services.places.getDetails({
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

    return vue;
}(jQuery, Logger, globalSettings));
