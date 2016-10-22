'use strict';
var MyApp = (function($, logger, globalSettings) {
    var settings = globalSettings.application;

    var user = {
        isLoggedIn: false
    };

    var elements = {
        typeahead: {
            city: $('.js-typeahead-city')
        },
        datepicker: {
            generic: $('.js-datepicker')
        }
    };

    function init() {
        bindDatepickers();
        bindTypeaheads();
    }

    function bindDatepickers() {
        elements.datepicker.generic.datepicker({
            todayHighlight: true,
            autoclose: true
        });
    }

    function bindTypeaheads() {
        elements.typeahead.city.typeahead({
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
                    logger.info('AutocompleteService', results, status);
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        asyncResults(results);
                    }
                });
            }
        }).on('typeahead:selected', function (obj, datum) {
            var $this = $(this);

            // Clear previous latitude and longitude
            if ($this.data('bind-field-lat')) {
                $($this.data('bind-field-lat')).val('');
            }
            if ($this.data('bind-field-lng')) {
                $($this.data('bind-field-lng')).val('');
            }

            // Get new latitude and longitude
            settings.google.maps.services.places.getDetails({
                placeId: datum.place_id
            }, function(place, status) {
                logger.info('PlacesService', place.geometry.location.lat(), place.geometry.location.lng());
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    if ($this.data('bind-field-lat')) {
                        $($this.data('bind-field-lat')).val(place.geometry.location.lat());
                    }
                    if ($this.data('bind-field-lng')) {
                        $($this.data('bind-field-lng')).val(place.geometry.location.lng());
                    }
                }
            });
        });
    }

    return {
        settings: settings,
        user: user,
        init: init
    }
}(jQuery, Logger, globalSettings));
