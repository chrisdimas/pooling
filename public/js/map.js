(function($) {
  // function initMap() {
      var longlats = [], markers = [], location, search_area, in_area = [];
      var markersO = JSON.parse(pooling_map_global.users_longlats);
      pooling_map_global.center = JSON.parse(pooling_map_global.center);

      // The location
      var uluru = {lat: pooling_map_global.center.lat, lng: pooling_map_global.center.lng};
      // The map, centered at location
      var map = new google.maps.Map(
          document.getElementById('map'),
          {
            zoom: 1,
            center: uluru,
            sensor: false
          }
      );
      search_area = {
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        center : uluru,
        radius : JSON.parse(pooling_map_global.pooling_radius),
        map: map
      }
      search_area = new google.maps.Circle(search_area);
      map.fitBounds(search_area.getBounds());
      // The marker, positioned at location
      var pinColor = "FFE641";
      var pingImage = {
          url: "/wp-content/plugins/pooling/public/img/marker.svg",
          scaledSize: new google.maps.Size(32, 32),
          size: new google.maps.Size(480, 480),
          anchor: new google.maps.Point(0, 32)
      };
      var marker = new google.maps.Marker({position: uluru, map: map, icon: pingImage, zIndex: 999});

      for(var mark in markersO){
        longlats.push(new google.maps.LatLng(markersO[mark].lat, markersO[mark].lng));
      }

      for(var mark in longlats){
        markers.push(new google.maps.Marker({position: longlats[mark], map: map}));
      }
    // }
})(jQuery);