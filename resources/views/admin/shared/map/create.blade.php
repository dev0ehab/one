    {{-- map --}}
    <script type="text/javascript"
        src='https://maps.google.com/maps/api/js?language={{ app()->getLocale() }}&libraries=places&key={{ googleMapKey() }}'>
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map;
            var marker;
            var lat = document.getElementById('lat');
            var lng = document.getElementById('lng');
            var address = document.getElementById('address');
            var geocoder = new google.maps.Geocoder();
            var infowindow = new google.maps.InfoWindow();

            function initMap(locationlat, locationlng) {
                var myLatlng;
                if (locationlat && locationlng) {
                    myLatlng = new google.maps.LatLng(locationlat, locationlng);
                } else {
                    myLatlng = new google.maps.LatLng(21.52, 39.16);
                }

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

                function setCustomInfoWindowContent(text) {
                    return `
                <div style="background-color: gray; color: white; padding: 8px; border-radius: 5px; font-size: 14px; max-width: 200px;">
                    ${text}
                </div>
            `;
                }

                geocoder.geocode({
                    'latLng': myLatlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            address.value = results[0].formatted_address;
                            lat.value = marker.getPosition().lat();
                            lng.value = marker.getPosition().lng();
                            infowindow.setContent(setCustomInfoWindowContent(results[0].formatted_address));
                            infowindow.open(map, marker);
                        }
                    }
                });

                google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({
                        'latLng': marker.getPosition()
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                address.value = results[0].formatted_address;
                                lat.value = marker.getPosition().lat();
                                lng.value = marker.getPosition().lng();
                                infowindow.setContent(setCustomInfoWindowContent(results[0]
                                    .formatted_address));
                                infowindow.open(map, marker);

                            }
                        }
                    });
                });
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    if (lat.value && lng.value) {
                        initMap(lat.value, lng.value);
                    } else {
                        initMap(position.coords.latitude, position.coords.longitude);
                    }
                },
                function errorCallback(error) {
                    initMap();
                }
            );
        });
    </script>
    {{-- map --}}