<div id="Map" style="height:250px"></div>
<script src="{{ asset('js/OpenLayers.js') }}"></script>
<script>
    var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
    var toProjection = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection


    map = new OpenLayers.Map("Map");
    var mapnik = new OpenLayers.Layer.OSM();
    map.addLayer(mapnik);

    var markers = new OpenLayers.Layer.Markers("Markers");
    map.addLayer(markers);

    @foreach($stations as $station)

    @if ($station->position_json != null)
        var lat = {{ $station->position_json['x'] }};
        var lon = {{ $station->position_json['y'] }};
        var position = new OpenLayers.LonLat(lon, lat).transform(fromProjection, toProjection);

        markers.addMarker(new OpenLayers.Marker(position));
    @endif

    @endforeach

    var zoom = 5;
    var position = new OpenLayers.LonLat(23.098, 38.064).transform(fromProjection, toProjection);

    map.setCenter(position, zoom);
</script>
