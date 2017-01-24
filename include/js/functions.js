/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/functions.js
 * Javascript functions for Cragbook.
 */

// global variables
var routes, crags, areas, returnurl, map, marker;
const defaultCenter = {lat: 53.815474, lng: -4.632684};

// info window for markers
var infowindow = new google.maps.InfoWindow();

// sorts routes by orderid and updates DOM element
function showRouteOrder() {
    var x, table, buttons;
    
    // sort array objects by orderid
    routes.sort(function(a, b){return a.orderid - b.orderid});
    
    // build table of routes
    table = '<table class="w3-table-all w3-small w3-margin-bottom">';
    table += '<tr class="w3-blue"><th>Name</th><th>Order</th><th>Grade</th><th>Sector</th></tr>';
    for (x in routes) {
        table += '<tr><td id=\"route\">' + routes[x].name + '</td>';
        table += '<td><i id=' + routes[x].routeid + ' class=\"fa fa-arrow-up w3-btn w3-round w3-red w3-hover-red w3-small w3-margin-right\" onclick=\"routeUp($(this).parents())\"></i>';
        table += '<i id=' + routes[x].routeid + ' class=\"fa fa-arrow-down w3-btn w3-round w3-red w3-hover-red w3-small \" onclick=\"routeDown($(this).parents())\"></i></td>';
        table += '<td>' + routes[x].grade + '</td><td>' + routes[x].sector + '</td></tr>';
    }
    table += "</table>";
    
    // add the buttons
    buttons = '<input class="w3-btn w3-round w3-green" style="margin-right:4px" type="button" onclick="updateRouteOrder()" value="Save">';
    buttons += '<input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign(\'' + returnurl + '\')" value="Cancel">';
    
    // display table and buttons
    $("#routes").html(table);
    $("#buttons").html(buttons);
}

// gets routes from route_update.php as JSON and stores in routes
function getRouteOrder(crag) {
    var url = "../include/route_json.php?cragid=" + crag;
    returnurl = '../crag_info.php?cragid=' + crag;
    
    $.getJSON(url, function (data, status, xhr){
        routes = data;
        
        // assign orderid to each route
        var i = 1;
        for (var x in routes) {
            routes[x].orderid = i++;
        }
        
        showRouteOrder();
    });
}

// send route order data back to database
function updateRouteOrder() {
    var url = "../include/route_json.php";
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x w3-display-middle\"></i>");
    $("#buttons").hide();
    
    $.getJSON(url, JSON.stringify(routes), function (data, status, xhr){
        window.location.assign(returnurl);
    });
}

// moves a route down in the table
function routeDown(dom) {
    var x, routeName = dom[1].firstChild.innerText;

    for (x in routes) {
        if (routeName == routes[x].name && routes[x].orderid < routes.length){
            routes[x].orderid = ++routes[x].orderid;
            routes[++x].orderid = --routes[x].orderid;
            break;
        }
    }
    
    showRouteOrder();
}

// moves a route up in the table
function routeUp(dom) {
    var x, routeName = dom[1].firstChild.innerText;
    
    for (x in routes) {
        if (routeName == routes[x].name && routes[x].orderid > 1){
            routes[x].orderid = --routes[x].orderid;
            routes[--x].orderid = ++routes[x].orderid;
            break;
        }
    }
    
    showRouteOrder();
}

// get routes for area
function getAreaRoutes(areaid, filter) {
    var url = "crags.php?areaid=" + areaid + "&filter=" + filter;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x \"></i>");
    
    $.get(url, function (data) {
        $('#routes').html(data);
    });
}

// get routes for crag
function getCragRoutes(cragid, filter) {
    var url = "crag_info.php?cragid=" + cragid + "&filter=" + filter;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x \"></i>");
    
    $.get(url, function (data) {
        $('#routes').html(data);
    });
}

// get JSON data on crags for area
function getCrags(id) {
    
    // get all crags
    if (id == 'all')
        var url = "include/crag_json.php";
    
    // get crags in area
    else
        var url = "include/crag_json.php?areaid=" + id;
    
    $.getJSON(url, function (data, status, xhr) {
        crags = data;
    });
}

// get JSON data for crag info
function getCragInfo(id) {
    var url = "include/crag_json.php?cragid=" + id;
    
    $.getJSON(url, function (data, status, xhr) {
        crags = data;
    });
}

// get JSON data on areas
function getAreas() {
    var url = "include/area_json.php";
    
    $.getJSON(url, function (data, status, xhr) {
        areas = data;
    });
}

// show map on area page with drop pins for crags in the area
function viewCragMap(location) {
    var i, contentString;
    
    // set map options for all crags
    if (location == 'all') {
        var latlng = new google.maps.LatLng(defaultCenter);
        var zoom = 5;
        var height = 500;
    }
    
    // set map options for single crag
    else if (location == 'crag') {
        location = crags[0].location.split(",");
        var latlng = new google.maps.LatLng(location[0], location[1]);
        var zoom = 15;
        var height = 300;
    }
    
    // set map options for area
    else {
        location = location.split(",");
        var latlng = new google.maps.LatLng(location[0], location[1]);
        var zoom = 10;
        var height = 300;
    }
    
    // set and get map canvas
    $('#view').html('<div id="map" class="w3-card-2 w3-margin-top" style="height: ' + height + 'px; width: 100%"></div>');
    var canvas = $("#map").get(0);
    
    // create map
    map = new google.maps.Map(canvas, {
        zoom: zoom,
        center: latlng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });
    
    // add markers
    for (i in crags) {
        if (crags[i].location === "");
            // skip
        else {
            // get crag location
            var location = crags[i].location.split(",");
            var latlng = new google.maps.LatLng(location[0], location[1]);
            
            // set marker
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: crags[i].name
            });
            
            // set marker info window content
            contentString = '<div class="w3-container"><a href="crag_info.php?cragid=' + crags[i].cragid + '"><b><h6>' + crags[i].name + '</h6></b></a></div>';
            contentString += '<div class="w3-container w3-small">' + crags[i].description + '</div>';
            
            marker.info = contentString;
            
            // add event listener for pin click
            marker.addListener('click', function() {
                infowindow.setContent(this.info);
                infowindow.open(map, this);
            });
        }
    }
}

// shows map for areas in the climbing areas page
function viewAreaMap() {
    var i, contentString;
    
    // set and get map canvas
    $('#view').html('<div id="map" class="w3-card-2 w3-margin-top" style="height: 500px; width: 100%"></div>');
    var canvas = $("#map").get(0);
    
    // create map
    map = new google.maps.Map(canvas, {
        zoom: 5,
        center: defaultCenter,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });
    
    // add markers
    for (i in areas) {
        if (areas[i].location === "");
            // skip
        else {
            
            // get area location
            var location = areas[i].location.split(",");
            var latlng = new google.maps.LatLng(location[0], location[1]);
            
            // set marker
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: areas[i].name
            });
            
            // set marker info window content
            contentString = '<div class="w3-container"><a href="crags.php?areaid=' + areas[i].areaid + '"><b><h6>' + areas[i].name + '</h6></b></a></div>';
            contentString += '<div class="w3-container w3-small">' + areas[i].description + '</div>';
            
            marker.info = contentString;
            
            // add event listener for pin click
            marker.addListener('click', function() {
                infowindow.setContent(this.info);
                infowindow.open(map, this);
            });
        }
    }
}

// map for setting area location on area form
function setAreaMap(location) {
    
    // set current location if editing area
    if (location === "") {
        var center = defaultCenter;
        var zoom = 5;
    }
    else {
        location = location.split(",");
        var center = new google.maps.LatLng(location[0], location[1]);
        var zoom = 10;
    }
    
    // get map canvas
    var canvas = $("#map").get(0);
    
    // create map
    map = new google.maps.Map(canvas, {
        zoom: zoom,
        center: center,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });
    
    // get center location on map when moved
    map.addListener('center_changed', function() {
        var center = map.getCenter();
        $("#latlng").val(center.lat() + ',' + center.lng());
    });
}

// map for setting crag location on crag form
function setCragMap(location) {
    
    // set crag location if editing crag
    if (location === "") {
        var center = defaultCenter;
        var zoom = 5;
    }
    else {
        location = location.split(",");
        var center = new google.maps.LatLng(location[0], location[1]);
        var zoom = 12;
    }
    
    // get map canvas
    var canvas = $("#map").get(0);
    
    // create map
    map = new google.maps.Map(canvas, {
        zoom: zoom,
        center: center,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });
    
    // set current location of marker if editing
    if (location !== "") {
        marker = new google.maps.Marker({
            position: center,
            map: map
        });
    }
    
    // set marker to null if new crag
    else
        marker = null;
        
    // right click to drop a pin
    map.addListener('rightclick', function(location) {
        
        // remove current marker from map
        if (marker !== null)
            marker.setMap(null);
        
        // add new marker
        marker = new google.maps.Marker({
            position: location.latLng,
            map: map
        });
        
        // center map on marker
        map.panTo(location.latLng);
        
        // set lat and lng values for location
        $("#latlng").val(location.latLng.lat() + ',' + location.latLng.lng());
    });
}

// display list of crags for area page
function viewCragList() {
    var i, view;
    
    if (crags.length != 0) {
        
        // build list of crags
        view = '<div id="list" class="w3-margin-top w3-margin-bottom">';
        
        for (i in crags) {
            view += '<a class="w3-btn w3-round w3-white w3-hover-red" style="box-shadow: none" href="crag_info.php?cragid=' + crags[i].cragid + '">';
            view += crags[i].name + '</a>';
        }
        
        view += '</div>';
    }
    else
        view = "<div class=\"w3-center w3-margin-top w3-margin-bottom\">No crags</div>";
    
    // show list of crags
    $('#view').html(view);
}

// display list of areas for climbing areas page
function viewAreaList() {
    var i, view;
    
    if (areas.length != 0) {
        
        // build list of crags
        view = '<div id="list" class="w3-margin-top w3-margin-bottom">';
        
        for (i in areas) {
            view += '<a class="w3-btn w3-round w3-white w3-hover-red" style="box-shadow: none" href="crags.php?areaid=' + areas[i].areaid + '">';
            view += areas[i].name + '</a>';
        }
        
        view += '</div>';
    }
    else
        view = "<div class=\"w3-center w3-margin-top w3-margin-bottom\">No areas</div>";
    
    // show list of crags
    $('#view').html(view);
}

// display list of areas for climbing areas page
function viewCragInfo() {
    var view;
    
    // build crag info
    view = '<div id="info" class="w3-margin-top w3-margin-bottom">';
    view += '<h6>' + crags[0].description + '</h6>';
    view += '<p><b>Access: </b>' + crags[0].access + '</p>';
    view += '<p><b>Policy on fixed gear: </b>' + crags[0].policy + '</p>';
    view += '<p><b>Approach </b>' + crags[0].approach + '</p>';
    view += '</div>';
    
    // show crag info
    $('#view').html(view);
}
