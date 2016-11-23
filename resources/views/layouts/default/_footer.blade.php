<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
<script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="/bower_components/typeahead.js/dist/bloodhound.min.js"></script>
<script src="/bower_components/typeahead.js/dist/typeahead.jquery.min.js"></script>
<script src="/bower_components/vue/dist/vue.min.js"></script>
<script src="/bower_components/parsleyjs/dist/parsley.min.js"></script>
<script src="/bower_components/toastr/toastr.min.js"></script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
<script type="text/javascript">
    var globalSettings = {
        csrfToken: '{{ csrf_token() }}',
        user: {
            isLoggedIn: {{ Auth::check() ? 'true' : 'false' }}
        },
        routes: {
            login: {
                facebook: '{{ route('login.facebook') }}'
            },
            user: {
                account: {
                    events: '{{ route('user.account.events.index') }}'
                }
            },
            events: {
                create: '{{ route('events.create') }}'
            }
        },
        facebook: {
            appId: {{ env('FACEBOOK_APP_ID') }}
        },
        google: {
            maps: {
                apiKey: '{{ env('GOOGLE_MAPS_API_KEY') }}',
                services: {
                    autocomplete: new google.maps.places.AutocompleteService(),
                    places: new google.maps.places.PlacesService(document.createElement('span')),
                }
            }
        }
    };
</script>
<script src="/app/js/utils/logger.js"></script>
<script src="/app/js/services/facebook.js"></script>
<script src="/app/js/services/googlemaps.js"></script>
<script src="/app/js/app.js"></script>
