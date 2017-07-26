/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/functions.js
 * Javascript functions for Cragbook.
 */

// global variables
var map, marker, btn;
const defaultCenter = {lat: 51.4490382, lng: -2.5943542};

// info window for markers
var infowindow = new google.maps.InfoWindow();

// sorts routes by orderid and updates DOM element
function viewRouteOrder() {
    var x, table, row, data, buttons;
    
    // sort array objects by orderid
    Cragbook.routes.all.sort(function(a, b){return a.orderid - b.orderid});
    
    // build table of routes
    table = $("<table>");
    row = $("<tr>")
        .append($("<th>").text("Order"))
        .append($("<th>").text("Name"))
        .append($("<th>").text("Grade"))
        .append($("<th>").text("Sector"));
    table.append(row);
            
    for (x in Cragbook.routes.all) {
        row = $("<tr>");
        data = $("<td>");
        data.append($("<button>").attr("id", Cragbook.routes.all[x].routeid).addClass("fa fa-arrow-up btn-border").attr("onclick", "routeUp(this.id)"));
        data.append($("<button>").attr("id", Cragbook.routes.all[x].routeid).addClass("fa fa-arrow-down btn-border").attr("onclick", "routeDown(this.id)"));
        row.append(data);
        
        data = $("<td>").attr("id", "route").text(Cragbook.routes.all[x].name);
        row.append(data);
        
        data = $("<td>").text(Cragbook.routes.all[x].grade);
        row.append(data);
        
        data = $("<td>").text(Cragbook.routes.all[x].sector);
        row.append(data);
        
        table.append(row);
    }
    
    // add the buttons
    $('#buttons').html($("<button>").addClass("btn-save").click(updateRouteOrder).text("Save"));
    $('#buttons').append($("<button>").addClass("btn-cancel").attr("onclick", "window.location.assign('" + Cragbook.returnurl + "')").text("Cancel"));
    
    // display table and buttons
    $("#routes").html(table);
}

// send route order data back to database
function updateRouteOrder() {
    var url = "../include/route_json.php";
    var data = "routes=" + encodeURIComponent(JSON.stringify(Cragbook.routes.all));

    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    $("#buttons").hide();
    
    $.post(url, data, function (data, status, xhr){
        window.location.assign(Cragbook.returnurl);
    });
}

// moves a route down in the table
function routeDown(routeid) {
    var x;
    
    for (x in Cragbook.routes.all) {
        if (routeid == Cragbook.routes.all[x].routeid && Cragbook.routes.all[x].orderid < Cragbook.routes.all.length){
            Cragbook.routes.all[x].orderid = ++Cragbook.routes.all[x].orderid;
            Cragbook.routes.all[++x].orderid = --Cragbook.routes.all[x].orderid;
            break;
        }
    }
    
    viewRouteOrder();
}

// moves a route up in the table
function routeUp(routeid) {
    var x;

    for (x in Cragbook.routes.all) {
        if (routeid == Cragbook.routes.all[x].routeid && Cragbook.routes.all[x].orderid > 1){
            Cragbook.routes.all[x].orderid = --Cragbook.routes.all[x].orderid;
            Cragbook.routes.all[--x].orderid = ++Cragbook.routes.all[x].orderid;
            break;
        }
    }
    
    viewRouteOrder();
}

/* AJAX functions */
/* -------------- */

// gets routes from route_update.php as JSON and stores in routes
function getRouteOrder(crag) {
    var i = 1, x, url = "../include/route_json.php?cragid=" + crag;
    Cragbook.returnurl = '../crag.php?cragid=' + crag;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        Cragbook.routes = new Cragbook.RouteList(data);
        
        // assign orderid to each route
        for (x in Cragbook.routes.all) {
            Cragbook.routes.all[x].orderid = i++;
        }
        
        viewRouteOrder();
    });
}

// get routes for area
function getAreaRoutes(areaid) {
    var url = "include/route_json.php?areaid=" + areaid;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        Cragbook.routes = new Cragbook.RouteList(data);
    
        // assign crag name for each route
        for (x in Cragbook.routes.all) {
            for (y in Cragbook.cragList) {
                if(Cragbook.cragList[y].cragid == Cragbook.routes.all[x].cragid) {
                    Cragbook.routes.all[x].cragName = Cragbook.cragList[y].name;
                }
            }
        }
        
        $('#gradefilter').html(gradeFilter('area'));
        viewAreaRoutes(Cragbook.routes.getAllRoutes());
    });
}

// get routes for crag
function getCragRoutes(cragid) {
    var url = "include/route_json.php?cragid=" + cragid;

    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        Cragbook.routes = new Cragbook.RouteList(data);
        
        $('#gradefilter').html(gradeFilter('crag'));
        viewCragRoutes(Cragbook.routes.getAllRoutes());
    });
}

// get JSON data on crags for area
function getArea(id) {
    var url = "include/crag_json.php?areaid=" + id;

    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        Cragbook.auth = data;
        
        // get crags in area
        $.getJSON(url, function (data, status, xhr) {
            Cragbook.cragList = data;
            viewCragList();
            
            if (id != 'all)') {
                getAreaRoutes(id);
            }
        });
        
        // get area info
        $.getJSON("include/area_json.php?areaid=" + id, function (data, status, xhr) {
            Cragbook.area = data;
            
            $("#name").text(Cragbook.area.name);
            $("#description").text(Cragbook.area.description);
        });
    });
}

// get JSON data for crag info
function getCrag(id) {
    var url = "include/crag_json.php?cragid=" + id;
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        Cragbook.auth = data;
        $.getJSON(url, function (data, status, xhr) {
            Cragbook.crag = data[0];
            
            $("#name").text(Cragbook.crag.name);
            
            viewCragInfo();
            getCragRoutes(id);
        });
    });
}

// get JSON data on areas
function getAllAreas() {
    var url = "include/area_json.php";
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        Cragbook.auth = data;
        $.getJSON(url, function (data, status, xhr) {
            Cragbook.areaList = data;
            viewAreaList();
        });
    });
}

// get JSON data for crag info
function getAllCrags() {
    var url = "include/crag_json.php";
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        Cragbook.auth = data;
        $.getJSON(url, function (data, status, xhr) {
            Cragbook.cragList = data;
            viewCragList();
        });
    });
}

// gets JDON data for a route and display route info
function viewRouteInfo(route) {
    var x, discipline, seriousness, url = "include/route_json.php?routeid=" + route.data.id;
    var div = $("<div>").attr("id", "routeinfowindow");

    $("#modal").html(div);
    
    for (x in Cragbook.routes.view) {
        if (Cragbook.routes.view[x].routeid == route.data.id) {
            switch (Cragbook.routes.view[x].discipline) {
                case '1':
                    discipline = $("<i>").addClass("fa fa-circle-thin").html("&nbsp");
                    break;
                case '2':
                    discipline = $("<i>").addClass("fa fa-circle yellow").html("&nbsp");
                    break;
                case '3':
                    discipline = $("<i>").addClass("fa fa-circle").html("&nbsp");
                    break;
            }
            
            switch (Cragbook.routes.view[x].seriousness) {
                case '1':
                    seriousness = $("<i>").addClass("fa fa-smile-o green").html("&nbsp");
                    break;
                case '2':
                    seriousness = $("<i>").addClass("fa fa-meh-o amber").html("&nbsp");
                    break;
                case '3':
                    seriousness = $("<i>").addClass("fa fa-frown-o red").html("&nbsp");
                    break;
            }
            
            $('#routeinfowindow').append($("<h3>").text(Cragbook.routes.view[x].name + ' ' + Cragbook.routes.view[x].stars).prepend(discipline));
            $('#routeinfowindow').append($("<p>").text(Cragbook.routes.view[x].description).prepend(seriousness));
            $('#routeinfowindow').append($("<p>").text(Cragbook.routes.view[x].grade).prepend($("<b>").text("Grade: ")));
            $('#routeinfowindow').append($("<p>").text(Cragbook.routes.view[x].length + "m").prepend($("<b>").text("Length: ")));
            $('#routeinfowindow').append($("<p>").text(Cragbook.routes.view[x].sector).prepend($("<b>").text("Sector: ")));
            $('#routeinfowindow').append($("<p>").text(Cragbook.routes.view[x].firstascent).prepend($("<b>").text("First Ascent: ")));
            $('#routeinfowindow').append($("<button>").addClass("btn-edit margin-15").attr("onclick","$('#modal').hide()").text("Close"));
            
            $("#modal").show();
        }
    }
}


/* DOM manipulation functions */
/* -------------------------- */


// display list of crags for area page
function viewCragList() {
    var i, div;
    
    $(btn).removeClass("btn-border");
    $("#listview").addClass("btn-border");
    btn = "#listview";
    
    if (Cragbook.cragList.length != 0) {
        div = $("<div>").attr("id", "list");
        $('#view').html(div);
        
        for (i in Cragbook.cragList) {
            if (Cragbook.cragList[i].public == 1 && Cragbook.auth == true)
                $('#list').append($("<a>").addClass("btn-public").attr("href", "crag.php?cragid=" + Cragbook.cragList[i].cragid).text(Cragbook.cragList[i].name));
            else
                $('#list').append($("<a>").addClass("btn").attr("href", "crag.php?cragid=" + Cragbook.cragList[i].cragid).text(Cragbook.cragList[i].name));
        }
    }
    else
        $('#view').append("<div>").attr("id", "nocrags").text("No crags");
}

// display list of areas for climbing areas page
function viewAreaList() {
    var i, div;
    
    $(btn).removeClass("btn-border");
    $("#listview").addClass("btn-border");
    btn = "#listview";
    
    if (Cragbook.areaList.length != 0) {
        div = $("<div>").attr("id", "list");
        $('#view').html(div);
        
        for (i in Cragbook.areaList) {
            if (Cragbook.areaList[i].public == 1 && Cragbook.auth == true)
                $('#list').append($("<a>").addClass("btn-public").attr("href", "area.php?areaid=" + Cragbook.areaList[i].areaid).text(Cragbook.areaList[i].name));
            else
                $('#list').append($("<a>").addClass("btn").attr("href", "area.php?areaid=" + Cragbook.areaList[i].areaid).text(Cragbook.areaList[i].name));
        }
    }
    else
        $('#view').append("<div>").attr("id", "noareas").text("No areas");
}

// display list of areas for climbing areas page
function viewCragInfo() {
    var div;
    
    $(btn).removeClass("btn-border");
    $("#infoview").addClass("btn-border");
    btn = "#infoview";
    
    div = $("<div>").attr("id", "craginfo");
    $('#view').html(div);
    
    $('#craginfo').append($("<p>").addClass("heading").text(Cragbook.crag.description));
    $('#craginfo').append($("<p>").text(Cragbook.crag.access).prepend($("<b>").text("Access: ")));
    $('#craginfo').append($("<p>").text(Cragbook.crag.policy).prepend($("<b>").text("Fixed gear policy: ")));
    $('#craginfo').append($("<p>").text(Cragbook.crag.approach).prepend($("<b>").text("Approach: ")));
}

// shows routes on area pages
function viewAreaRoutes(routes) {
    var x, y, row, data;
    var table = $("<table></table>");
    
    // build table
    if (routes.length > 0) {
        row = $("<tr>");
        row.append($("<th>"));
        row.append($("<th>").text("Name").attr("onclick", "viewRoutes('area', Cragbook.routes.sort('name'))"));
        row.append($("<th>").text("Grade").attr("onclick", "viewRoutes('area', Cragbook.routes.sort('grade'))"));
        row.append($("<th>"));
        row.append($("<th>").text("Stars").attr("onclick", "viewRoutes('area', Cragbook.routes.sort('stars'))"));
        row.append($("<th>").text("Length").attr("onclick", "viewRoutes('area', Cragbook.routes.sort('length'))"));
        row.append($("<th>").text("Crag").attr("onclick", "viewRoutes('area', Cragbook.routes.sort('crag'))"));
        row.append($("<th>").text("First Ascent").attr("onclick", "viewRoutes('area', Cragbook.routes.sort('firstascent'))"));
        table.append(row);
        
        for (x in routes) {
            row = $("<tr>").addClass("pointer").attr("id", routes[x].routeid);
            data= $("<td>").click( { "id" : routes[x].routeid }, viewRouteInfo);
            
            switch(routes[x].discipline) {
                case "1":
                    data.append($("<i>").addClass("fa fa-circle-thin fa-lg"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-circle fa-lg yellow"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-circle fa-lg"));
                    break;
                case "4":
                    data.append($("<i>").addClass("fa fa-circle fa-lg yellow"));
            }
            row.append(data);
            
            if (routes[x].discipline == 4)
                data = $("<td>").addClass("hybrid").click( { id: routes[x].routeid }, viewRouteInfo);
            else
                data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            
            data.text(routes[x].name);
            row.append(data);
            
            if (routes[x].discipline == 4)
                data = $("<td>").addClass("hybrid").click( { id: routes[x].routeid }, viewRouteInfo);
            else
                data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
                
            data.text(routes[x].grade);
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            
            switch(routes[x].seriousness) {
                case "1":
                    data.append($("<i>").addClass("fa fa-smile-o green"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-meh-o amber"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-frown-o red"));
                    break;
                case "4":
                    data.append($("<i>").addClass("fa fa-times-circle"));
            }
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.text(routes[x].stars);
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.text(routes[x].length + "m");
            row.append(data);

            data = $("<td>");
            data.append($("<a>").attr("href", "crag.php?cragid=" + routes[x].cragid).text(routes[x].cragName));
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.append($("<div>").addClass("firstascent").text(routes[x].firstascent));
            row.append(data);
            
            table.append(row);
        }
    }
    else
        table.append($("<p>").text("No routes"));
    
    // show table
    $('#routes').html(table);
}

// shows routes on crag pages
function viewCragRoutes(routes) {
    var x, row, data;
    var table = $("<table></table");
    
    // build table
    if (routes.length > 0) {
        row = $("<tr>");
        row.append($("<th>"));
        row.append($("<th>").text("Name").attr("onclick", "viewRoutes('crag', Cragbook.routes.sort('name'))"));
        row.append($("<th>").text("Grade").attr("onclick", "viewRoutes('crag', Cragbook.routes.sort('grade'))"));
        row.append($("<th>"));
        row.append($("<th>").text("Stars").attr("onclick", "viewRoutes('crag', Cragbook.routes.sort('stars'))"));
        row.append($("<th>").text("Length").attr("onclick", "viewRoutes('crag', Cragbook.routes.sort('length'))"));
        row.append($("<th>").text("First Ascent").attr("onclick", "viewRoutes('crag', Cragbook.routes.sort('firstascent'))"));
        row.append($("<th>").text("Sector").attr("onclick", "viewRoutes('crag', Cragbook.routes.sort('sector'))"));
        
        
        // show editing options if user logged in
        if (Cragbook.auth === true)
            row.append($("<th>"));
        
        table.append(row);
            
        for (x in routes) {
            row = $("<tr>").addClass("pointer").attr("id", routes[x].routeid);
            
            data = $("<td>").click( { "id" : routes[x].routeid }, viewRouteInfo);
            
            switch(routes[x].discipline) {
                case "1":
                    data.append($("<i>").addClass("fa fa-circle-thin fa-lg"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-circle fa-lg yellow"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-circle fa-lg"));
                    break;
                case "4":
                    data.append($("<i>").addClass("fa fa-circle fa-lg yellow"));
            }
            row.append(data);
            
            if (routes[x].discipline == 4)
                data = $("<td>").addClass("hybrid").click({ id : routes[x].routeid }, viewRouteInfo);
            else
                data = $("<td>").click({ id : routes[x].routeid }, viewRouteInfo);
            
            data.text(routes[x].name);
            row.append(data);
            
            if (routes[x].discipline == 4)
                data = $("<td>").addClass("hybrid").click({ id : routes[x].routeid }, viewRouteInfo);
            else
                data = $("<td>").click({ id : routes[x].routeid }, viewRouteInfo);
            
            data.text(routes[x].grade);
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            switch(routes[x].seriousness) {
                case "1":
                    data.append($("<i>").addClass("fa fa-smile-o green"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-meh-o amber"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-frown-o red"));
                    break;
                case "4":
                    data.append($("<i>").addClass("fa fa-times-circle"));
            }
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.text(routes[x].stars);
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.text(routes[x].length + "m");
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.append($("<div>").addClass("firstascent").text(routes[x].firstascent));
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.text(routes[x].sector);
            row.append(data);
            
            if (Cragbook.auth === true) {
                data = $("<td>");
                data.append($("<a>").addClass("fa fa-edit fa-lg").attr("href", "admin/route.php?action=edit&routeid=" + routes[x].routeid))
                data.append(" ");
                data.append($("<a>").addClass("fa fa-trash-o fa-lg").attr("href", "admin/route.php?action=delete&routeid=" + routes[x].routeid))
                row.append(data);
            }
            
            table.append(row);
        }
    }
    else
        table.append($("<p>").text("No routes"));
    
    $('#routes').html(table);
}

function viewRoutes(page, routes) {
    var div;
    
    div = $("<div>").addClass("center");
    div.append($("<i>").addClass("fa fa-circle-o-notch fa-spin fa-5x"));
    $("#routes").html(div);
    
    if (page == 'area') viewAreaRoutes(routes);
    else if (page == 'crag') viewCragRoutes(routes);
}


/* Google Maps Functions */
/* --------------------- */

// show map on area page with drop pins for crags in the area
function viewCragMap(location) {
    var i, contentString;
    
    $(btn).removeClass("btn-border");
    $("#mapview").addClass("btn-border");
    btn = "#mapview";
    
    // set map options for all crags
    if (location == 'all') {
        var latlng = new google.maps.LatLng(defaultCenter);
        var zoom = 9;
        var height = 500;
    }
    
    // set map options for single crag
    else if (location == 'crag') {
        Cragbook.cragList = [Cragbook.crag];
        location = Cragbook.crag.location.split(",");
        var latlng = new google.maps.LatLng(location[0], location[1]);
        var zoom = 15;
        var height = 300;
    }
    
    // set map options for area
    else {
        location = Cragbook.area.location.split(",");
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
    for (i in Cragbook.cragList) {
        if (Cragbook.cragList[i].location === "") {
            // skip
        }
        else {
            // get crag location
            var location = Cragbook.cragList[i].location.split(",");
            var latlng = new google.maps.LatLng(location[0], location[1]);
            
            // set marker
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: Cragbook.cragList[i].name
            });
            
            // set marker info window content
            contentString = '<div><a href="crag.php?cragid=' + Cragbook.cragList[i].cragid + '"><b><h3>' + Cragbook.cragList[i].name + '</h3></b></a></div>';
            contentString += '<div>' + Cragbook.cragList[i].description + '</div>';
            
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
    
    $(btn).removeClass("btn-border");
    $("#mapview").addClass("btn-border");
    btn = "#mapview";
    
    // set and get map canvas
    $('#view').html('<div id="map" class="panel" style="height: 500px; width: 100%"></div>');
    var canvas = $("#map").get(0);
    
    // create map
    map = new google.maps.Map(canvas, {
        zoom: 9,
        center: defaultCenter,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });
    
    // add markers
    for (i in Cragbook.areaList) {
        if (Cragbook.areaList[i].location === "") {
            // skip
        }
        else {
            
            // get area location
            var location = Cragbook.areaList[i].location.split(",");
            var latlng = new google.maps.LatLng(location[0], location[1]);
            
            // set marker
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: Cragbook.areaList[i].name
            });
            
            // set marker info window content
            contentString = '<div><a href="area.php?areaid=' + Cragbook.areaList[i].areaid + '"><b><h3>' + Cragbook.areaList[i].name + '</h3></b></a></div>';
            contentString += '<div>' + Cragbook.areaList[i].description + '</div>';
            
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


function viewCragDownloads() {
    $(btn).removeClass("btn-border");
    $("#photoview").addClass("btn-border");
    btn = "#photoview";
}

function viewAreaDownloads() {
    $(btn).removeClass("btn-border");
    $("#photoview").addClass("btn-border");
    btn = "#photoview";
}

function printRoutes(page) {
    var printWindow, div, head;
    
    printWindow = window.open();
    
    head = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
    head += '<title>Print</title>';
    head += '<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>';
    head += '<script src="https://maps.googleapis.com/maps/api/js?key=<?= $googlemaps_apikey ?>"></script>';
    head += '<script src="include/js/functions.js"></script>';
    head += '<link rel="stylesheet" href="css/cragbook.css">';
    head += '<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">';
    head += '</head><body>';

    div = $("<h6>").text("Great Western Rock");
    
    if (page == 'area') {
        div.append($("<p>").append($("<h2>").text(Cragbook.area.name)));
        div.append($("<p>").append($("<h4>").text(Cragbook.area.description)));
        div.append($("<p>").append($("<h4>").text("Routes")));
    }
    
    else if (page == 'crag') {
        div.append($("<p>").append($("<h2>").text(Cragbook.crag.name)));
        div.append($("<p>").append($("#view").html()));
        div.append($("<p>").append($("<h4>").text("Routes")));
    }
    
    else if (page == 'search') {
        div.append($("<p>").append($("<h2>").text("Search Results")));
        div.append($("<p>").append($("#searchoptions").html()));
    }
    printWindow.document.write(head);
    printWindow.document.write(div.html());

    printWindow.document.write($("#routes").html());
    printWindow.document.write("</body><script>window.print();window.close();</script></html>");
}

function getSearch() {
    var data, search, div, url = "include/search_json.php";
    
    var area = $("#area").val(), crag = $("#crag").val(), route = $("#route").val(), grade = $("#grade").val();
    
    // input validation
    if (area !== "" && !area.match(/^[0-9a-zA-Z ]+$/)) {
        $("#error").html($("<div>").addClass("red")
            .text("Invalid input for area. Must only contain letters or numbers."));
        return;
    }
    if (crag !== "" && !crag.match(/^[0-9a-zA-Z ]+$/)) {
        $("#error").html($("<div>").addClass("red")
            .text("Invalid input for crag. Must only contain letters or numbers."));
        return;
    }
    if (route !== "" && !route.match(/^[0-9a-zA-Z ]+$/)) {
        $("#error").html($("<div>").addClass("red")
            .text("Invalid input for route. Must only contain letters or numbers."));
        return;
    }
    if (grade !== "" && !grade.match(/^[0-9a-zA-Z \+\-]+$/)) {
        $("#error").html($("<div>").addClass("red")
            .text("Invalid input for grade. Must only contain letters, numbers and +/-."));
        return;
    }
    if (area == "" && crag == "" && route == "" && grade == "") {
        $("#error").html($("<div>").addClass("red")
            .text("Please enter at least one search item."));
        return;
    }
    
    // get search results
    div = $("<div>");
    div.append($("<i>").addClass("fa fa-search btn btn-border").click(showSearchForm));
    div.append($("<i>").addClass("fa fa-print btn btn-border").attr("onclick", "printRoutes('search')"));
    $("#searchform").html(div);
    
    div = $("<div>").addClass("center");
    div.append($("<i>").addClass("fa fa-circle-o-notch fa-spin fa-5x"));
    $("#searchresults").html(div);

    search = { "area" : area, "crag" : crag, "route" : route, "grade" : grade };
    data = "search=" + encodeURIComponent(JSON.stringify(search));
    
    $.post(url, data, function (data, status, xhr) {
        Cragbook.routes = new Cragbook.RouteList(JSON.parse(data));
        $.getJSON("include/crag_json.php", function (data) {
            Cragbook.cragList = data;
            
            // assign crag name for each route
            for (x in Cragbook.routes.all) {
                for (y in Cragbook.cragList) {
                    if(Cragbook.cragList[y].cragid == Cragbook.routes.all[x].cragid) {
                        Cragbook.routes.all[x].cragName = Cragbook.cragList[y].name;
                    }
                }
            }
            
            showSearchResults(search);
        });
    });
}

function showSearchForm() {
    var div = $("<div>");
    div.append($("<div>").addClass("heading").text("Search for routes"));
    div.append($("<input>").attr("type", "text").attr("id", "area").attr("placeholder", "Area..."));
    div.append($("<input>").attr("type", "text").attr("id", "crag").attr("placeholder", "Crag..."));
    div.append($("<input>").attr("type", "text").attr("id", "route").attr("placeholder", "Route..."));
    div.append($("<input>").attr("type", "text").attr("id", "grade").attr("placeholder", "Grade..."));
    div.append($("<button>").addClass("btn-edit").click(getSearch).text("Search"));
    div.append($("<div>").attr("id", "error"));
    
    $("#searchform").addClass("panel").html(div);
}

function showSearchResults(search) {
    var div = $("<div>");
    
    if (search.area == "") search.area = "None";
    if (search.crag == "") search.crag = "None";
    if (search.route == "") search.route = "None";
    if (search.grade == "") search.grade = "None";

    div.append($("<div>").addClass("heading").text("Search Results")
        .append($("<p>").attr("id", "searchoptions").text("Area: " + search.area + " / Crag: " + 
        search.crag + " / Route: " + search.route + " / Grade: " + search.grade)));

    div.append($("<div>").attr("id", "gradefilter").html(gradeFilter('area')));
    
    div.append($("<div>").addClass("panel").attr("id", "routes"));
    div.append($("<div>").addClass("modal").attr("id", "modal"));
    
    $("#searchresults").addClass("panel").html(div);
    
    viewAreaRoutes(Cragbook.routes.getAllRoutes());
}


function allRoutesFilter(page) {
    viewRoutes(page, Cragbook.routes.getAllRoutes());
    $("#filter").hide();
}

function tradRoutesFilter(page) {
    var div;
    
    div = $("<div>");
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('M'))").text("M"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('D'))").text("D"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('VD'))").text("VD"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('S'))").text("S"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('VS'))").text("VS"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('HVS'))").text("HVS"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('E'))").text("Extreme"));

    viewRoutes(page, Cragbook.routes.getTradRoutes());
    
    $("#filter").html(div).show();
}


function sportRoutesFilter(page) {
    var div;
    
    div = $("<div>");
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F1'))").text("F1"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F2'))").text("F2"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F3'))").text("F3"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F4'))").text("F4"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F5'))").text("F5"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F6'))").text("F6"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F7'))").text("F7"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F8'))").text("F8"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('F9'))").text("F9"));

    
    viewRoutes(page, Cragbook.routes.getSportRoutes());
    
    $("#filter").html(div).show();
}

function boulderProblemsFilter(page) {
    var div;
    
    div = $("<div>");
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f1'))").text("f1"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f2'))").text("f2"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f3'))").text("f3"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f4'))").text("f4"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f5'))").text("f5"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f6'))").text("f6"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f7'))").text("f7"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f8'))").text("f8"));
    div.append($("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.gradeFilter('f9'))").text("f9"));
    
    viewRoutes(page, Cragbook.routes.getBoulderProblems());
    
    $("#filter").html(div).show();
}

function gradeFilter(page) {
    var div, all, trad, sport, bouldering, filter;
    
    div = ($("<div>"));
    all = $("<button>").addClass("btn").attr("onclick", "allRoutesFilter('" + page +"')").text("All");
    trad = $("<button>").addClass("btn").attr("onclick", "tradRoutesFilter('" + page +"')").html('<i class="fa fa-circle-o"></i> Trad');
    sport = $("<button>").addClass("btn").attr("onclick", "sportRoutesFilter('" + page +"')").html('<i class="fa fa-circle yellow"></i> Sport');
    bouldering = $("<button>").addClass("btn").attr("onclick", "boulderProblemsFilter('" + page +"')").html('<i class="fa fa-circle"></i> Bouldering');
    filter = $("<div>").attr("id", "filter");
    
    div.append(all);
    div.append(trad);
    div.append(sport);
    div.append(bouldering);
    div.append(filter);
    return div;
}