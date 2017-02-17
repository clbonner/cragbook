/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/functions.js
 * Javascript functions for Cragbook.
 */

// global variables
var routes, crags, areas, returnurl, map, marker, auth;
const defaultCenter = {lat: 53.815474, lng: -4.632684};

// info window for markers
var infowindow = new google.maps.InfoWindow();

// check authentication
auth_check();

// returns true if a user is logged in
function auth_check() {
    var url = "include/auth_json.php";
    
    $.getJSON(url, function (data, status, xhr) {
        auth = data;
    });
}

// sorts routes by orderid and updates DOM element
function showRouteOrder() {
    var x, table, buttons;
    
    // sort array objects by orderid
    routes.sort(function(a, b){return a.orderid - b.orderid});
    
    // build table of routes
    table = '<table>';
    table += '<tr><th>Order</th><th>Name</th><th>Grade</th><th>Sector</th></tr>';
    for (x in routes) {
        table += '<tr>';
        table += '<td><button id=' + routes[x].routeid + ' class=\"fa fa-arrow-up btn-edit\" onclick=\"routeUp(this.id)\"></button>';
        table += '<button id=' + routes[x].routeid + ' class=\"fa fa-arrow-down btn-edit\" onclick=\"routeDown(this.id)\"></button></td>';
        table += '<td id=\"route\">' + routes[x].name + '</td>';
        table += '<td>' + routes[x].grade + '</td><td>' + routes[x].sector + '</td>';
        table += '</tr>';
    }
    table += "</table>";
    
    // add the buttons
    buttons = '<br><button class="btn-save" onclick="updateRouteOrder()">Save</button>';
    buttons += '<button class="btn-cancel" onclick="window.location.assign(\'' + returnurl + '\')">Cancel</button>';
    
    // display table and buttons
    $("#routes").html(table);
    $("#buttons").html(buttons);
}

// send route order data back to database
function updateRouteOrder() {
    var url = "../include/route_json.php";
    var data = "routes=" + encodeURIComponent(JSON.stringify(routes));

    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    $("#buttons").hide();
    
    $.post(url, data, function (data, status, xhr){
        window.location.assign(returnurl);
    });
}

// moves a route down in the table
function routeDown(routeid) {
    var x;
    
    for (x in routes) {
        if (routeid == routes[x].routeid && routes[x].orderid < routes.length){
            routes[x].orderid = ++routes[x].orderid;
            routes[++x].orderid = --routes[x].orderid;
            break;
        }
    }
    
    showRouteOrder();
}

// moves a route up in the table
function routeUp(routeid) {
    var x;

    for (x in routes) {
        if (routeid == routes[x].routeid && routes[x].orderid > 1){
            routes[x].orderid = --routes[x].orderid;
            routes[--x].orderid = ++routes[x].orderid;
            break;
        }
    }
    
    showRouteOrder();
}

/* AJAX functions */
/* -------------- */

// gets routes from route_update.php as JSON and stores in routes
function getRouteOrder(crag) {
    var url = "../include/route_json.php?cragid=" + crag;
    returnurl = '../crag_info.php?cragid=' + crag;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
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

// get routes for area
function getAreaRoutes(areaid) {
    var url = "include/route_json.php?areaid=" + areaid;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        routes = data;
        viewAreaRoutes(routes);
    });
}

// get routes for crag
function getCragRoutes(cragid) {
    var url = "include/route_json.php?cragid=" + cragid;

    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        routes = data;
        viewCragRoutes(routes);
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
        viewCragList();
    });
}

// get JSON data for crag info
function getCragInfo(id) {
    var url = "include/crag_json.php?cragid=" + id;
    
    $.getJSON(url, function (data, status, xhr) {
        crags = data;
        viewCragInfo();
    });
}

// get JSON data on areas
function getAreas() {
    var url = "include/area_json.php";
    
    $.getJSON(url, function (data, status, xhr) {
        areas = data;
        viewAreaList();
    });
}

// gets JDON data for a route and display route info
function getRouteInfo(routeid) {
    var url = "include/route_json.php?routeid=" + routeid;
    var div = '<div id="routeinfowindow">';
    div += '<i class="fa fa-circle-o-notch fa-spin fa-5x"></i></div>';
    
    $("#routeinfo").html(div);
    $("#routeinfo").show();
    
    $.getJSON(url, function (data, status, xhr) {
        var route = data;
        
        div = '<h3>' + route.name + ' ' + route.stars + '</h3>';
        div += '<p>' + route.description + '</p>';
        div += '<p><b>First Ascent: </b>' + route.firstascent + '</p>';
        div += '<p><b>Grade: </b>' + route.grade + '</p>';
        div += '<p><b>Length: </b>' + route.length + 'm</p>';
        div += '<p><b>Crag Sector: </b>' + route.sector + '</p>';
        div += '<button class="btn-edit margin-15" onclick="$(\'#routeinfo\').hide()">Close</button>';
        
        $("#routeinfowindow").html(div);
    });
}


/* DOM manipulation functions */
/* -------------------------- */


// display list of crags for area page
function viewCragList() {
    var i, view;
    
    if (crags.length != 0) {
        
        // build list of crags
        view = '<div id="list">';
        
        for (i in crags) {
            view += '<a class="btn" href="crag_info.php?cragid=' + crags[i].cragid + '">';
            view += crags[i].name + '</a>';
        }
        
        view += '</div>';
    }
    else
        view = '<div id="nocrags">No crags</div>';
    
    // show list of crags
    $('#view').html(view);
}

// display list of areas for climbing areas page
function viewAreaList() {
    var i, view;
    
    if (areas.length != 0) {
        
        // build list of crags
        view = '<div id="list">';
        
        for (i in areas) {
            view += '<a class="btn" href="crags.php?areaid=' + areas[i].areaid + '">';
            view += areas[i].name + '</a>';
        }
        
        view += '</div>';
    }
    else
        view = '<div id="noareas">No areas</div>';
    
    // show list of crags
    $('#view').html(view);
}

// display list of areas for climbing areas page
function viewCragInfo() {
    var view;
    
    // build crag info
    view = '<div id="craginfo">';
    view += '<div class="heading">' + crags[0].description + '</div>';
    view += '<p><b>Access: </b>' + crags[0].access + '</p>';
    view += '<p><b>Policy on fixed gear: </b>' + crags[0].policy + '</p>';
    view += '<p><b>Approach </b>' + crags[0].approach + '</p>';
    view += '</div>';
    
    // show crag info
    $('#view').html(view);
}

// shows routes on area pages
function viewAreaRoutes(routes) {
    var table, x, y;
    
    // build table
    if (routes.length > 0) {
        table = "<table>";
        table += "<tr>";
        table += "<th></th>";
        table += "<th>Name</th>";
        table += "<th>Grade</th>";
        table += "<th>Stars</th>";
        table += "<th>Crag</th>";
        table += "</tr>";
        
        for (x in routes) {
            table += "<tr>";
            table += "<td><div>";
            table += "<button class=\"fa fa-info btn-border margin-side-5\" onclick=\"getRouteInfo(" + routes[x].routeid + ")\"></button></div></td>";
            table += "<td>" + routes[x].name + "</td>";
            table += "<td>" + routes[x].grade + "</td>";
            table += "<td>" + routes[x].stars + "</td>";
            table += "<td>";
            
            for (y in crags) {
                if(crags[y].cragid == routes[x].cragid) {
                    table += "<a class=\"pointer\" onclick=\"window.location.assign('crag_info.php?cragid=" + crags[y].cragid + "')\">" + crags[y].name + "</a>";
                    break;
                }
            }
            
            table += "</td>";
            table += "</tr>";
        }
        
        table += "</table>";
    }
    else
        table = "<p>No routes</p>";
    
    // show table
    $('#routes').html(table);
}

// shows routes on crag pages
function viewCragRoutes(routes) {
    var table, x;
    
    if (routes.length > 0) {
        table = "<table>";
        table += "<tr>";
        table += "<th></th>";
        table += "<th>Name</th>";
        table += "<th>Grade</th>";
        table += "<th>Stars</th>";
        table += "<th>Length</th>";
        table += "<th>Sector</th>";
        table += "</tr>";
        
        // show editing options if user logged in
        if (auth == true) {
            for (x  in routes) {
                table += "<tr>";
                table += "<td>";
                table += "<button class=\"fa fa-edit btn-edit\" onclick=\"window.location.assign('admin/route.php?action=edit&routeid=" + routes[x].routeid + "')\"></button>";
                table += "<button class=\"fa fa-trash-o btn-edit\" onclick=\"window.location.assign('admin/route.php?action=delete&routeid=" + routes[x].routeid + "')\"></button>";
                table += "<button class=\"fa fa-info btn-border\" onclick=\"getRouteInfo(" + routes[x].routeid + ")\"></button></td>";
                table += "<td><a href=\"admin/route.php?action=edit&routeid=" + routes[x].routeid + "\">" + routes[x].name + "</a></td>";
                table += "<td>" + routes[x].grade + "</td>";
                table += "<td>" + routes[x].stars + "</td>";
                table += "<td>" + routes[x].length + "m</td>";
                table += "<td>" + routes[x].sector + "</td>";
                table += "</tr>";
            }
        }
        
        // not logged in
        else {
            for (x in routes) {
                table += "<tr>";
                table += "<td>";
                table += "<button class=\"fa fa-info btn-border margin-side-5\" onclick=\"getRouteInfo(" + routes[x].routeid + ")\"></button></td>";
                table += "<td>" + routes[x].name + "</td>";
                table += "<td>" + routes[x].grade + "</td>";
                table += "<td>" + routes[x].stars + "</td>";
                table += "<td>" + routes[x].length + "m</td>";
                table += "<td>" + routes[x].sector + "</td>";
                table += "</tr>";
            }
        }
        
        table += "</table>";
    }
    else
        table = "<p>No routes</p>";

    // show table
    $('#routes').html(table);
}

// filters and sorts routes for british grading
function britishFilter(page) {
    var x, britishRoutes = [];
    
    for (x in routes) {
        if (/^E$/.test(routes[x].grade)) britishRoutes.push(routes[x]);
        else if (/^M/.test(routes[x].grade)) britishRoutes.push(routes[x]);
        else if (/^D/.test(routes[x].grade)) britishRoutes.push(routes[x]);
        else if (/^VD/.test(routes[x].grade)) britishRoutes.push(routes[x]);
        else if (/^S/.test(routes[x].grade)) britishRoutes.push(routes[x]);
        else if (/^VS/.test(routes[x].grade)) britishRoutes.push(routes[x]);
        else if (/^HVS/.test(routes[x].grade)) britishRoutes.push(routes[x]);
        else if (/^E[0-9]/.test(routes[x].grade)) britishRoutes.push(routes[x]);
    }
    
    britishRoutes.sort(function (a, b) {
        
        var gradeA = a.grade.split(" ");
        var gradeB = b.grade.split(" ");
        
        if (gradeA[0] == gradeB[0]) {
            if (gradeA[1] < gradeB[1]) return -1;
            else if (gradeA[1] > gradeB[1]) return 1;
            else return 0;
        } 
        else {
            a = britishGrade(a.grade);
            b = britishGrade(b.grade);
            
            if (a < b) return -1;
            else if (a > b) return 1;
            else return 0;
        }
    });
    
    if (page == 'crag') viewCragRoutes(britishRoutes);
    else if (page == 'area') viewAreaRoutes(britishRoutes);
}

// helper function for sorting british grades
function britishGrade(grade) {
    if (/^E$/.test(grade)) grade = 0;
    else if (/^M/.test(grade)) grade = 1;
    else if (/^D/.test(grade)) grade = 2;
    else if (/^VD/.test(grade)) grade = 3;
    else if (/^S/.test(grade)) grade = 4;
    else if (/^HS/.test(grade)) grade = 5;
    else if (/^VS/.test(grade)) grade = 6;
    else if (/^HVS/.test(grade)) grade = 7;
    else if (/^E1/.test(grade)) grade = 8;
    else if (/^E2/.test(grade)) grade = 9;
    else if (/^E3/.test(grade)) grade = 10;
    else if (/^E4/.test(grade)) grade = 11;
    else if (/^E5/.test(grade)) grade = 12;
    else if (/^E6/.test(grade)) grade = 13;
    else if (/^E7/.test(grade)) grade = 14;
    else if (/^E8/.test(grade)) grade = 15;
    
    return grade;
}

// filters and sorts routes for UIAA grading
function uiaaFilter (page) {
    var x, uiaaRoutes = [];
    
    for (x in routes) {
        if (/^I/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^II/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^III/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^IV/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^V$/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^V[+-]/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^VI/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^VII/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^VIII/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^IX/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^X/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^XI/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^XII/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
        else if (/^XIII/.test(routes[x].grade)) uiaaRoutes.push(routes[x]);
    }
    
    uiaaRoutes.sort(function (a, b) {
        console.log(a.grade,b.grade);
        a = uiaaGrade(a.grade);
        b = uiaaGrade(b.grade);
        console.log(a,b);
        if (a < b) return -1;
        else if (a > b) return 1;
        else return 0;
    });
    
    if (page == 'crag') viewCragRoutes(uiaaRoutes);
    else if (page == 'area') viewAreaRoutes(uiaaRoutes);
}

// helper function for sorting UIAA grades
function uiaaGrade(grade) {
    if (/^I-/.test(grade)) grade = 0;
    else if (/^I$/.test(grade)) grade = 1;
    else if (/^I[+]/.test(grade)) grade = 2;
    else if (/^II-/.test(grade)) grade = 3;
    else if (/^II$/.test(grade)) grade = 4;
    else if (/^II[+]/.test(grade)) grade = 5;
    else if (/^III-/.test(grade)) grade = 6;
    else if (/^III$/.test(grade)) grade = 7;
    else if (/^III[+]/.test(grade)) grade = 8;
    else if (/^IV-/.test(grade)) grade = 9;
    else if (/^IV$/.test(grade)) grade = 10;
    else if (/^IV[+]/.test(grade)) grade = 11;
    else if (/^V-/.test(grade)) grade = 12;
    else if (/^V$/.test(grade)) grade = 13;
    else if (/^V[+]/.test(grade)) grade = 14;
    else if (/^VI-/.test(grade)) grade = 15;
    else if (/^VI$/.test(grade)) grade = 16;
    else if (/^VI[+]/.test(grade)) grade = 17;
    else if (/^VII-/.test(grade)) grade = 18;
    else if (/^VII$/.test(grade)) grade = 19;
    else if (/^VII[+]/.test(grade)) grade = 20;
    else if (/^VIII-/.test(grade)) grade = 21;
    else if (/^VIII$/.test(grade)) grade = 22;
    else if (/^VIII[+]/.test(grade)) grade = 23;
    else if (/^IX-/.test(grade)) grade = 24;
    else if (/^IX$/.test(grade)) grade = 25;
    else if (/^IX[+]/.test(grade)) grade = 26;
    else if (/^X-/.test(grade)) grade = 27;
    else if (/^X$/.test(grade)) grade = 28;
    else if (/^X[+]/.test(grade)) grade = 29;
    else if (/^XI-/.test(grade)) grade = 30;
    else if (/^XI$/.test(grade)) grade = 31;
    else if (/^XI[+]/.test(grade)) grade = 32;
    
    return grade;
}

// filters and sorts routes for french grading
function frenchFilter(page) {
    var x, frenchRoutes = [];
    
    for (x in routes) {
        if (/^F/.test(routes[x].grade)) {
            frenchRoutes.push(routes[x]);
        }
    }
    
    frenchRoutes.sort(function (a, b) {
        if (a.grade < b.grade) return -1;
        else if (a.grade > b.grade) return 1;
        else return 0;
    })
    
    if (page == 'crag') viewCragRoutes(frenchRoutes);
    else if (page == 'area') viewAreaRoutes(frenchRoutes);
}

// filters and sorts routes for YDS grading
function ydsFilter(page) {
    var x, ydsRoutes = [];
    
    for (x in routes) {
        if (/^5./.test(routes[x].grade)) {
            ydsRoutes.push(routes[x]);
        }
    }
    
    ydsRoutes.sort(function (a, b) {
        if (a.grade < b.grade) return -1;
        else if (a.grade > b.grade) return 1;
        else return 0;
    })
    
    if (page == 'crag') viewCragRoutes(ydsRoutes);
    else if (page == 'area') viewAreaRoutes(ydsRoutes);
}

// filters and sorts routes for font grading
function fontFilter(page) {
    var x, fontRoutes = [];
    
    for (x in routes) {
        if (/^f/.test(routes[x].grade)) {
            fontRoutes.push(routes[x]);
        }
    }
    
    fontRoutes.sort(function (a, b) {
        if (a.grade < b.grade) return -1;
        else if (a.grade > b.grade) return 1;
        else return 0;
    })
    
    if (page == 'crag') viewCragRoutes(fontRoutes);
    else if (page == 'area') viewAreaRoutes(fontRoutes);
}

// filters and sorts routes for V grading
function vGradeFilter(page) {
    var x, vGradeRoutes = [];
    
    for (x in routes) {
        if (/^V[0-9]/.test(routes[x].grade)) {
            vGradeRoutes.push(routes[x]);
        }
    }
    
    vGradeRoutes.sort(function (a, b) {
        if (a.grade < b.grade) return -1;
        else if (a.grade > b.grade) return 1;
        else return 0;
    })
    
    if (page == 'crag') viewCragRoutes(vGradeRoutes);
    else if (page == 'area') viewAreaRoutes(vGradeRoutes);
}

/* Google Maps Functions */
/* --------------------- */

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
    $('#view').html('<div id="map" class="panel" style="height: ' + height + 'px; width: 100%"></div>');
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
            contentString = '<div><a href="crag_info.php?cragid=' + crags[i].cragid + '"><b><h3>' + crags[i].name + '</h3></b></a></div>';
            contentString += '<div>' + crags[i].description + '</div>';
            
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
    $('#view').html('<div id="map" class="panel" style="height: 500px; width: 100%"></div>');
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
            contentString = '<div><a href="crags.php?areaid=' + areas[i].areaid + '"><b><h3>' + areas[i].name + '</h3></b></a></div>';
            contentString += '<div>' + areas[i].description + '</div>';
            
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