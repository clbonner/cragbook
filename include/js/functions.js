/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/functions.js
 * Javascript functions for Cragbook.
 */

// global variables
var routes, crags, areas, returnurl, map, markers = [];

// info window for markers
infowindow = new google.maps.InfoWindow;

// gets routes from database by parsing JSON data
function showRoutes() {
    var x, table, buttons;
    
    // sort array objects by orderid
    routes.sort(function(a, b){return a.orderid - b.orderid});
    
    // build table of routes
    table = '<table class="w3-table-all w3-small w3-margin-bottom">';
    table += '<tr class="w3-blue"><th>Name</th><th>Order</th><th>Grade</th><th>Sector</th></tr>';
    for (x in routes) {
        table += '<tr><td id=\"route\">' + routes[x].name + '</td>';
        table += '<td><i id=' + routes[x].routeid + ' class=\"fa fa-arrow-down w3-btn w3-round w3-red w3-hover-red w3-small w3-margin-right\" onclick=\"routeDown($(this).parents())\"></i>';
        table += '<i id=' + routes[x].routeid + ' class=\"fa fa-arrow-up w3-btn w3-round w3-red w3-hover-red w3-small\" onclick=\"routeUp($(this).parents())\"></i></td>';
        table += '<td>' + routes[x].grade + '</td><td>' + routes[x].sector + '</td></tr>';
    }
    table += "</table>";
    
    // add the buttons
    buttons = '<input class="w3-btn w3-round w3-green" style="margin-right:4px" type="button" onclick="updateRoutes()" value="Save">';
    buttons += '<input class="w3-btn w3-round w3-red" type="button" onclick="window.location.assign(\'' + returnurl + '\')" value="Cancel">';
    
    // display table and buttons
    $("#routes").html(table);
    $("#buttons").html(buttons);
}

// gets routes from route_update.php as JSON and stores in routes
function getRoutes(crag) {
    var url = "../include/route_json.php?cragid=" + crag;
    returnurl = '../crag_info.php?cragid=' + crag;
    
    $.getJSON(url, function (data, status, xhr){
        routes = data;
        
        var i = 1;
        // assign orderid to each route
        for (var x in routes) {
            routes[x].orderid = i++;
        }
        
        showRoutes();
    });
}



// send route order data back to database
function updateRoutes() {
    var url = "../include/route_json.php";
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x w3-display-middle\"></i>");
    $("#buttons").hide();
    
    $.getJSON(url, JSON.stringify(routes), function (data, status, xhr){
        console.log(returnurl);
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
    
    showRoutes();
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
    
    showRoutes();
}

// get JSON data on crags
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

// get JSON data on crags
function getCrag(id) {
    // get crags in area
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
        var latlng = new google.maps.LatLng(53.815474, -4.632684);
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
        var zoom = 10
        var height = 300;
    }
    
    // set and get map canvas
    $('#view').html('<div id="map" class="w3-card-2 w3-margin-top" style="height: ' + height + 'px; width: 100%"></div>');
    var canvas = $("#map").get(0);
    
    // create map
    map = new google.maps.Map(canvas, {
        zoom: zoom,
        center: latlng,
        scroll: false
    });
    
    // add markers
    for (i in crags) {
        if (crags[i].location == "");
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
            
            markers.push(marker);
        }
    }
}

// shows map for areas in the climbing areas page
function showMapAreas() {
    var i, contentString;
    
    // set and get map canvas
    $('#view').html('<div id="map" class="w3-card-2 w3-margin-top" style="height: 500px; width: 100%"></div>');
    var canvas = $("#map").get(0);
    
    // create map
    map = new google.maps.Map(canvas, {
        zoom: 5,
        center: {lat: 53.815474, lng: -4.632684}, // somewhere in the Irish Sea
        scroll: false
    });
    
    // add markers
    for (i in areas) {
        if (areas[i].location == "");
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
            
            markers.push(marker);
        }
    }
}

// display list of crags for area page
function listViewCrags() {
    var i, view;
    
    // build list of crags
    view = '<div id="list" class="w3-margin-top w3-margin-bottom">';
    
    for (i in crags) {
        view += '<a class="w3-btn w3-round w3-white w3-hover-red" style="box-shadow: none" href="crag_info.php?cragid=' + crags[i].cragid + '">';
        view += crags[i].name + '</a>';
    }
    
    view += '</div>';
    
    // show list of crags
    $('#view').html(view);
}

// display list of areas for climbing areas page
function listViewAreas() {
    var i, view;
    
    // build list of crags
    view = '<div id="list" class="w3-margin-top w3-margin-bottom">';
    
    for (i in areas) {
        view += '<a class="w3-btn w3-round w3-white w3-hover-red" style="box-shadow: none" href="crags.php?areaid=' + areas[i].areaid + '">';
        view += areas[i].name + '</a>';
    }
    
    view += '</div>';
    
    // show list of crags
    $('#view').html(view);
}


// display list of areas for climbing areas page
function viewCragInfo() {
    var i, view;
    
    // build crag info
    view = '<div id="info" class="w3-margin-top w3-margin-bottom">';
    view += '<h6>' + crags[0].description + '</h6>';
    view += '<p><b>Access: </b>' + crags[0].access + '</p>';
    view += '<p><b>Policy on fixed gear: </b>' + crags[0].policy + '</p>';
    view += '<p><b>Approach: </b>' + crags[0].approach + '</p>';
    view += '</div>';
    
    // show crag info
    $('#view').html(view);
}
