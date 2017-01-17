/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/functions.js
 * Javascript functions for Cragbook.
 */

// global variables
var routes, crags, returnurl, map, markers = [];

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
function getCrags(areaid) {
    var url = "include/crag_json.php?areaid=" + areaid;
    var i, contentString;
    
    $.getJSON(url, function (data, status, xhr) {
        crags = data;
        
        // set crag markers on map
        for (i in crags) {
            if (crags[i].location == "");
                // skip
            else {
                var location = crags[i].location.split(",");
                var latlng = new google.maps.LatLng(location[0], location[1]);
                
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: crags[i].name
                });
                
                // set info window content
                contentString = '<div class="w3-container"><a href="crag_info.php?cragid=' + crags[i].cragid + '"><b><h6>' + crags[i].name + '</h6></b></a></div>';
                contentString += '<div class="w3-container w3-small">' + crags[i].description + '</div>';
                
                marker.info = contentString;
                
                marker.addListener('click', function() {
                    infowindow.setContent(this.info);
                    infowindow.open(map, this);
                });
                
                markers.push(marker);
            }
        }
        
        // don't show the map if no markers were placed
        if (markers.length == 0)
            $("#map").hide();
    });
}

function initMap() {
    var canvas = $("#map").get(0);
    
    map = new google.maps.Map(canvas, {
        zoom: 10,
        center: {lat: 51.237045, lng: -2.569498}
    });
}