    {{-- googleMap --}}
    <script type="text/javascript"
        src='https://maps.google.com/maps/api/js?language={{ app()->getLocale() }}&key={{ googleMapKey() }}&libraries=places'>
    </script>
    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map, marker;
            var lat = document.getElementById('lat');
            var lng = document.getElementById('lng');
            var address = document.getElementById('address');
            var geocoder = new google.maps.Geocoder();
            var infowindow = new google.maps.InfoWindow();

            /**
             * تخصيص محتوى InfoWindow باستخدام CSS مدمج
             */
            function setCustomInfoWindowContent(text) {
                return `
            <div style="background-color: gray; color: white; padding: 8px; border-radius: 5px; font-size: 14px; max-width: 200px;">
                ${text}
            </div>
        `;
            }

            /**
             * تهيئة الخريطة وإضافة الماركر
             */
            function initMap(locationlat, locationlng) {
                var myLatlng = (locationlat && locationlng) ?
                    new google.maps.LatLng(locationlat, locationlng) :
                    new google.maps.LatLng(30, 30);

                var mapOptions = {
                    zoom: 15,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                map = new google.maps.Map(document.getElementById("map"), mapOptions);
                marker = new google.maps.Marker({
                    map: map,
                    position: myLatlng,
                    draggable: true
                });

                geocoder.geocode({
                    'latLng': myLatlng
                }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK && results[0]) {
                        updateLocationInfo(results[0]);
                    }
                });

                google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({
                        'latLng': marker.getPosition()
                    }, function(results, status) {
                        if (status === google.maps.GeocoderStatus.OK && results[0]) {
                            updateLocationInfo(results[0]);
                        }
                    });
                });
            }

            /**
             * تحديث الحقول والمعلومات بناءً على الموقع
             */
            function updateLocationInfo(result) {
                address.value = result.formatted_address;
                lat.value = marker.getPosition().lat();
                lng.value = marker.getPosition().lng();
                infowindow.setContent(setCustomInfoWindowContent(result.formatted_address));
                infowindow.open(map, marker);
            }

            /**
             * محاولة الحصول على الموقع الحالي باستخدام Geolocation API
             */
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    initMap(position.coords.latitude, position.coords.longitude);
                },
                function() {
                    console.warn("{{ __('admin.current_location_not_available') }}");
                    initMap(lat.value || 30, lng.value || 30); // {{ __('admin.if_coordinates_exist_use_them') }}
                }
            );

            /**
             * تفعيل خاصية الإكمال التلقائي للعنوان
             */
            function initializeAutocomplete() {
                var input = document.getElementById('address');
                var autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['(cities)']
                });

                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    var place = autocomplete.getPlace();
                    if (place.geometry) {
                        lat.value = place.geometry.location.lat();
                        lng.value = place.geometry.location.lng();
                        initMap(place.geometry.location.lat(), place.geometry.location.lng());
                    }
                });
            }

            initializeAutocomplete();
        });
    </script>
    {{-- googleMap --}}
