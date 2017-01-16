/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/crag_maps.js
 * Javascript functions for displaying crags using Google Maps API.
 */
 
//new google.maps.LatLng(crag.location));

// globals
var crags, markers = [];
var infoWindow;

// get JSON data on crags
function getCrags(areaid) {
    var url = "../include/crag_json.php?areaid=" + areaid;
    
    $.getJSON(url, function (data, status, xhr){
        crags = data;
    });
}

function setMarkers() {
  
}
function initMap() {
  

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 4,
    center: uluru
  });

  var contentString = '';

  var infowindow = new google.maps.InfoWindow({
    content: contentString
  });

  var marker = new google.maps.Marker({
    position: uluru,
    map: map,
    title: 'Uluru (Ayers Rock)'
  });
  marker.addListener('click', function() {
    infowindow.open(map, marker);
  });
}

    