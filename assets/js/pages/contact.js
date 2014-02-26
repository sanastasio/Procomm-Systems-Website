var Contact = function () {

    return {

        //Map
        initMap: function () {
            var map;
            $(document).ready(function(){
                map = new GMaps({
                    div: '#map',
                    lat: 40.71947,
                    lng: -75.14023
                });
                var marker = map.addMarker({
                    lat: 40.71947,
                    lng: -75.14023,
                    title: 'Procomm Systems, Inc.'
                });
            });
        }

    };
}();