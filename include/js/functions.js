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
    var i = 1, x, url = "../include/route_json.php?cragid=" + crag;
    returnurl = '../crag_info.php?cragid=' + crag;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        routes = data;
        
        // assign orderid to each route
        for (x in routes) {
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
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        auth = data;
        $.getJSON(url, function (data, status, xhr) {
            crags = data;
            viewCragList();
            
            if (id != 'all)') {
                getAreaRoutes(id);
            }
        });
    });
}

// get JSON data for crag info
function getCragInfo(id) {
    var url = "include/crag_json.php?cragid=" + id;
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        auth = data;
        $.getJSON(url, function (data, status, xhr) {
            crags = data;
            viewCragInfo();
            getCragRoutes(id);
        });
    });
}

// get JSON data on areas
function getAreas() {
    var url = "include/area_json.php";
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        auth = data;
        $.getJSON(url, function (data, status, xhr) {
            areas = data;
            viewAreaList();
        });
    });
}

// gets JDON data for a route and display route info
function getRouteInfo(routeid) {
    var url = "include/route_json.php?routeid=" + routeid;
    var div = '<div id="routeinfowindow"></div>';
    var x;

    $("#routeinfo").html(div);
    
    for (x in routes) {
        if (routes[x].routeid == routeid) {
            div = '<h3>' + routes[x].name + ' ' + routes[x].stars + '</h3>';
            div += '<p>' + routes[x].description + '</p>';
            div += '<p><b>First Ascent: </b>' + routes[x].firstascent + '</p>';
            div += '<p><b>Grade: </b>' + routes[x].grade + '</p>';
            div += '<p><b>Length: </b>' + routes[x].length + 'm</p>';
            div += '<p><b>Crag Sector: </b>' + routes[x].sector + '</p>';
            div += '<button class="btn-edit margin-15" onclick="$(\'#routeinfo\').hide()">Close</button>';
            
            $("#routeinfowindow").html(div);
            $("#routeinfo").show();
        }
    }
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
            if (crags[i].public == 1 && auth == true) {
                view += '<a class="btn-public" href="crag_info.php?cragid=' + crags[i].cragid + '">';
                view += crags[i].name + '</a>';
            }
            else {
                view += '<a class="btn" href="crag_info.php?cragid=' + crags[i].cragid + '">';
                view += crags[i].name + '</a>';
            }
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
            if (areas[i].public == 1 && auth == true) {
                view += '<a class="btn-public" href="crags.php?areaid=' + areas[i].areaid + '">';
                view += areas[i].name + '</a>';
            }
            else {
                view += '<a class="btn" href="crags.php?areaid=' + areas[i].areaid + '">';
                view += areas[i].name + '</a>';
            }
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
    view += '<p><b>Approach: </b>' + crags[0].approach + '</p>';
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
        table += "<th></td>";
        table += "<th>Stars</th>";
        table += "<th>Length</th>";
        table += "<th>Crag</th>";
        table += "<th>First Ascent</th>";
        table += "</tr>";
        
        for (x in routes) {
            table += "<tr class=\"pointer\">";
            table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">";
            
            switch(routes[x].discipline) {
                case "1":
                    table += "<i class=\"fa fa-circle-thin fa-lg\"></i></td>";
                    break;
                case "2":
                    table += "<i class=\"fa fa-circle-thin fa-lg yellow\"></i></td>";
                    break;
                case "3":
                    table += "<i class=\"fa fa-circle fa-lg\"></i></td>";
                    break;
                default:
                    table += "</td>";
            }
                
            table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].name + "</td>";
            table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].grade + "</td>";
            
            switch(routes[x].seriousness) {
                case "1":
                    table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><i class=\"fa fa-smile-o green\"></i></td>";
                    break;
                case "2":
                    table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><i class=\"fa fa-meh-o amber\"></i></td>";
                    break;
                case "3":
                    table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><i class=\"fa fa-frown-o red\"></i></td>";
                    break;
                default:
                    table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"></td>";
            }
                
            table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].stars + "</td>";
            table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].length + "m</td>";
            table += "<td>";
            
            for (y in crags) {
                if(crags[y].cragid == routes[x].cragid) {
                    table += "<a onclick=\"window.location.assign('crag_info.php?cragid=" + crags[y].cragid + "')\">" + crags[y].name + "</a>";
                    break;
                }
            }
            table += "</td>";
            table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><div class=\"firstascent\">" + routes[x].firstascent + "</div></td>";
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
    var table, x, i;
    
    if (routes.length > 0) {
        table = "<table>";
        table += "<tr>";
        table += "<th></th>";
        table += "<th>Name</th>";
        table += "<th>Grade</th>";
        table += "<th></th>";
        table += "<th>Stars</th>";
        table += "<th>Length</th>";
        table += "<th>First Ascent</th>";
        table += "<th>Sector</th>";
        
        // show editing options if user logged in
        if (auth === true) {
            
            table += "<th></th></tr>";
            
            for (x  in routes) {
                table += "<tr class=\"pointer\">";
                table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">";
                
                switch(routes[x].discipline) {
                    case "1":
                        table += "<i class=\"fa fa-circle-thin fa-lg\"></i></td>";
                        break;
                    case "2":
                        table += "<i class=\"fa fa-circle-thin fa-lg yellow\"></i></td>";
                        break;
                    case "3":
                        table += "<i class=\"fa fa-circle fa-lg\"></i></td>";
                        break;
                    default:
                        table += "</td>";
                }
                    
                table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].name + "</td>";
                table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].grade + "</td>";
                
                switch(routes[x].seriousness) {
                    case "1":
                        table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><i class=\"fa fa-smile-o green\"></i></td>";
                        break;
                    case "2":
                        table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><i class=\"fa fa-meh-o amber\"></i></td>";
                        break;
                    case "3":
                        table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><i class=\"fa fa-frown-o red\"></i></td>";
                        break;
                    default:
                        table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"></td>";
                }
                
                table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].stars + "</td>";
                table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].length + "m</td>";
                table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\"><div class=\"firstascent\">" + routes[x].firstascent + "</div></td>";
                table += "<td onclick=\"getRouteInfo(" + routes[x].routeid + ")\">" + routes[x].sector + "</td>";
                
                
                table += "<td>";
                table += "<i class=\"fa fa-edit fa-lg\" onclick=\"window.location.assign('admin/route.php?action=edit&routeid=" + routes[x].routeid + "')\"></i>&nbsp";
                table += "<i class=\"fa fa-trash-o fa-lg\" onclick=\"window.location.assign('admin/route.php?action=delete&routeid=" + routes[x].routeid + "')\"></i>";
                table += "</td>";
                table += "</tr>";
            }
        }
        
        // not logged in
        else {
            
            table += "</tr>";
            
            for (x in routes) {
                table += "<tr class=\"pointer\" onclick=\"getRouteInfo(" + routes[x].routeid + ")\">";
                table += "<td>";
                
                switch(routes[x].discipline) {
                    case "1":
                        table += "<i class=\"fa fa-circle-thin fa-lg\"></i></td>";
                        break;
                    case "2":
                        table += "<i class=\"fa fa-circle-thin fa-lg yellow\"></i></td>";
                        break;
                    case "3":
                        table += "<i class=\"fa fa-circle fa-lg\"></i></td>";
                        break;
                    default:
                        table += "</td>";
                }
                
                table += "<td>" + routes[x].name + "</td>";
                table += "<td>" + routes[x].grade + "</td>";
                
                switch(routes[x].seriousness) {
                    case "1":
                        table += "<td><i class=\"fa fa-smile-o green\"></i></td>";
                        break;
                    case "2":
                        table += "<td><i class=\"fa fa-meh-o amber\"></i></td>";
                        break;
                    case "3":
                        table += "<td><i class=\"fa fa-frown-o red\"></i></td>";
                        break;
                    default:
                        table += "<td></td>";
                }
                
                table += "<td>" + routes[x].stars + "</td>";
                table += "<td>" + routes[x].length + "m</td>";
                table += "<td><div class=\"firstascent\">" + routes[x].firstascent + "</div></td>";
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

// filters and sorts routes for british trad grading
function trad(page) {
    var x, tradRoutes = [];
    
    for (x in routes) {
        if (/^E$/.test(routes[x].grade)) tradRoutes.push(routes[x]);
        else if (/^M/.test(routes[x].grade)) tradRoutes.push(routes[x]);
        else if (/^D/.test(routes[x].grade)) tradRoutes.push(routes[x]);
        else if (/^VD/.test(routes[x].grade)) tradRoutes.push(routes[x]);
        else if (/^S/.test(routes[x].grade)) tradRoutes.push(routes[x]);
        else if (/^VS/.test(routes[x].grade)) tradRoutes.push(routes[x]);
        else if (/^HVS/.test(routes[x].grade)) tradRoutes.push(routes[x]);
        else if (/^E[0-9]/.test(routes[x].grade)) tradRoutes.push(routes[x]);
    }
    
    tradRoutes.sort(function (a, b) {
        
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
    
    if (page == 'crag') viewCragRoutes(tradRoutes);
    else if (page == 'area') viewAreaRoutes(tradRoutes);
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




// filters and sorts routes for french grading
function sport(page) {
    var x, sportRoutes = [];
    
    for (x in routes) {
        if (/^F/.test(routes[x].grade)) {
            sportRoutes.push(routes[x]);
        }
    }
    
    sportRoutes.sort(function (a, b) {
        if (a.grade < b.grade) return -1;
        else if (a.grade > b.grade) return 1;
        else return 0;
    })
    
    if (page == 'crag') viewCragRoutes(sportRoutes);
    else if (page == 'area') viewAreaRoutes(sportRoutes);
}

// filters and sorts routes for font grading
function bouldering(page) {
    var x, boulderProblems = [];
    
    for (x in routes) {
        if (/^f/.test(routes[x].grade)) {
            boulderProblems.push(routes[x]);
        }
    }
    
    for (x in routes) {
        if (/^V[0-9]/.test(routes[x].grade)) {
            boulderProblems.push(routes[x]);
        }
    }
    
    boulderProblems.sort(function (a, b) {
        if (a.grade < b.grade) return -1;
        else if (a.grade > b.grade) return 1;
        else return 0;
    })
    
    if (page == 'crag') viewCragRoutes(boulderProblems);
    else if (page == 'area') viewAreaRoutes(boulderProblems);
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