/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/functions.js
 * Javascript functions for Cragbook.
 */

// global variables
var allRoutes, routes, crag, area, cragList, areaList, returnurl, map, marker, auth, btn;
const defaultCenter = {lat: 53.815474, lng: -4.632684};

// cragbook namespace
var cragbook = {
    defaultCenter : {lat: 53.815474, lng: -4.632684},
    infowindow : new google.maps.InfoWindow()
};

// info window for markers
var infowindow = new google.maps.InfoWindow();

// sorts routes by orderid and updates DOM element
function showRouteOrder() {
    var x, table, row, data, buttons;
    
    // sort array objects by orderid
    allRoutes.sort(function(a, b){return a.orderid - b.orderid});
    
    // build table of routes
    table = $("<table>");
    row = $("<tr>")
        .append($("<th>").text("Order"))
        .append($("<th>").text("Name"))
        .append($("<th>").text("Grade"))
        .append($("<th>").text("Sector"));
    table.append(row);
            
    for (x in allRoutes) {
        row = $("<tr>");
        data = $("<td>");
        data.append($("<button>").attr("id", allRoutes[x].routeid).addClass("fa fa-arrow-up btn-edit").attr("onclick", "routeUp(this.id)"));
        data.append($("<button>").attr("id", allRoutes[x].routeid).addClass("fa fa-arrow-down btn-edit").attr("onclick", "routeDown(this.id)"));
        row.append(data);
        
        data = $("<td>").attr("id", "route").text(allRoutes[x].name);
        row.append(data);
        
        data = $("<td>").text(allRoutes[x].grade);
        row.append(data);
        
        data = $("<td>").text(allRoutes[x].sector);
        row.append(data);
        
        table.append(row);
    }
    
    // add the buttons
    $('#buttons').html($("<button>").addClass("btn-save").click(updateRouteOrder).text("Save"));
    $('#buttons').append($("<button>").addClass("btn-cancel").attr("onclick", "window.location.assign('" + returnurl + "')").text("Cancel"));
    
    // display table and buttons
    $("#routes").html(table);
}

// send route order data back to database
function updateRouteOrder() {
    var url = "../include/route_json.php";
    var data = "routes=" + encodeURIComponent(JSON.stringify(allRoutes));

    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    $("#buttons").hide();
    
    $.post(url, data, function (data, status, xhr){
        window.location.assign(returnurl);
    });
}

// moves a route down in the table
function routeDown(routeid) {
    var x;
    
    for (x in allRoutes) {
        if (routeid == allRoutes[x].routeid && allRoutes[x].orderid < allRoutes.length){
            allRoutes[x].orderid = ++allRoutes[x].orderid;
            allRoutes[++x].orderid = --allRoutes[x].orderid;
            break;
        }
    }
    
    showRouteOrder();
}

// moves a route up in the table
function routeUp(routeid) {
    var x;

    for (x in allRoutes) {
        if (routeid == allRoutes[x].routeid && allRoutes[x].orderid > 1){
            allRoutes[x].orderid = --allRoutes[x].orderid;
            allRoutes[--x].orderid = ++allRoutes[x].orderid;
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
    returnurl = '../crag.php?cragid=' + crag;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        allRoutes = data;
        
        // assign orderid to each route
        for (x in allRoutes) {
            allRoutes[x].orderid = i++;
        }
        
        showRouteOrder();
    });
}

// get routes for area
function getAreaRoutes(areaid) {
    var url = "include/route_json.php?areaid=" + areaid;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        allRoutes = data;
    
        // assign crag name for each route
        for (x in allRoutes) {
            for (y in cragList) {
                if(cragList[y].cragid == allRoutes[x].cragid) {
                    allRoutes[x].cragName = cragList[y].name;
                }
            }
        }
        
        routes = allRoutes.slice();
        viewAreaRoutes(routes);
    });
}

// get routes for crag
function getCragRoutes(cragid) {
    var url = "include/route_json.php?cragid=" + cragid;

    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        allRoutes = data;
        routes = allRoutes.slice();
        
        viewCragRoutes(routes);
    });
}

// get JSON data on crags for area
function getArea(id) {
    var url = "include/crag_json.php?areaid=" + id;

    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        auth = data;
        
        // get crags in area
        $.getJSON(url, function (data, status, xhr) {
            cragList = data;
            viewCragList();
            
            if (id != 'all)') {
                getAreaRoutes(id);
            }
        });
        
        // get area info
        $.getJSON("include/area_json.php?areaid=" + id, function (data, status, xhr) {
            area = data;
            
            $("#name").text(area.name);
            $("#description").text(area.description);
        });
    });
}

// get JSON data for crag info
function getCrag(id) {
    var url = "include/crag_json.php?cragid=" + id;
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        auth = data;
        $.getJSON(url, function (data, status, xhr) {
            crag = data[0];
            
            $("#name").text(crag.name);
            
            viewCragInfo();
            getCragRoutes(id);
        });
    });
}

// get JSON data on areas
function getAllAreas() {
    var url = "include/area_json.php";
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        auth = data;
        $.getJSON(url, function (data, status, xhr) {
            areaList = data;
            viewAreaList();
        });
    });
}

// get JSON data for crag info
function getAllCrags() {
    var url = "include/crag_json.php";
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        auth = data;
        $.getJSON(url, function (data, status, xhr) {
            cragList = data;
            viewCragList();
        });
    });
}

// gets JDON data for a route and display route info
function viewRouteInfo(route) {
    var x, discipline, seriousness, url = "include/route_json.php?routeid=" + route.data.id;
    var div = $("<div>").attr("id", "routeinfowindow");

    $("#modal").html(div);
    
    for (x in routes) {
        if (routes[x].routeid == route.data.id) {
            switch (routes[x].discipline) {
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
            
            switch (routes[x].seriousness) {
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
            
            $('#routeinfowindow').append($("<h3>").text(routes[x].name + ' ' + routes[x].stars).prepend(discipline));
            $('#routeinfowindow').append($("<p>").text(routes[x].description).prepend(seriousness));
            $('#routeinfowindow').append($("<p>").text(routes[x].grade).prepend($("<b>").text("Grade: ")));
            $('#routeinfowindow').append($("<p>").text(routes[x].length).prepend($("<b>").text("Length: ")));
            $('#routeinfowindow').append($("<p>").text(routes[x].sector).prepend($("<b>").text("Crag Sector: ")));
            $('#routeinfowindow').append($("<p>").text(routes[x].firstascent).prepend($("<b>").text("First Ascent: ")));
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
    
    if (cragList.length != 0) {
        div = $("<div>").attr("id", "list");
        $('#view').html(div);
        
        for (i in cragList) {
            if (cragList[i].public == 1 && auth == true)
                $('#list').append($("<a>").addClass("btn-public").attr("href", "crag.php?cragid=" + cragList[i].cragid).text(cragList[i].name));
            else
                $('#list').append($("<a>").addClass("btn").attr("href", "crag.php?cragid=" + cragList[i].cragid).text(cragList[i].name));
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
    
    if (areaList.length != 0) {
        div = $("<div>").attr("id", "list");
        $('#view').html(div);
        
        for (i in areaList) {
            if (areaList[i].public == 1 && auth == true)
                $('#list').append($("<a>").addClass("btn-public").attr("href", "area.php?areaid=" + areaList[i].areaid).text(areaList[i].name));
            else
                $('#list').append($("<a>").addClass("btn").attr("href", "area.php?areaid=" + areaList[i].areaid).text(areaList[i].name));
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
    
    $('#craginfo').append($("<p>").addClass("heading").text(crag.description));
    $('#craginfo').append($("<p>").text(crag.access).prepend($("<b>").text("Access: ")));
    $('#craginfo').append($("<p>").text(crag.policy).prepend($("<b>").text("Fixed gear policy: ")));
    $('#craginfo').append($("<p>").text(crag.approach).prepend($("<b>").text("Approach: ")));
}

// shows routes on area pages
function viewAreaRoutes(areaRoutes) {
    var x, y, row, data;
    var table = $("<table></table>");
    
    // build table
    if (areaRoutes.length > 0) {
        row = $("<tr>");
        row.append($("<th>"));
        row.append($("<th>").text("Name").attr("onclick", "sortRoutes('area','name')"));
        row.append($("<th>").text("Grade").attr("onclick", "sortRoutes('area','grade')"));
        row.append($("<th>"));
        row.append($("<th>").text("Stars").attr("onclick", "sortRoutes('area','stars')"));
        row.append($("<th>").text("Length").attr("onclick", "sortRoutes('area','length')"));
        row.append($("<th>").text("Crag").attr("onclick", "sortRoutes('area','crag')"));
        row.append($("<th>").text("First Ascent").attr("onclick", "sortRoutes('area','firstascent')"));
        table.append(row);
        
        for (x in areaRoutes) {
            row = $("<tr>").addClass("pointer").attr("id", areaRoutes[x].routeid);
            data= $("<td>").click( { "id" : areaRoutes[x].routeid }, viewRouteInfo);
            
            switch(areaRoutes[x].discipline) {
                case "1":
                    data.append($("<i>").addClass("fa fa-circle-thin fa-lg"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-circle fa-lg yellow"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-circle fa-lg"));
            }
            row.append(data);
            
            data = $("<td>").click({ id : areaRoutes[x].routeid }, viewRouteInfo);
            data.text(areaRoutes[x].name);
            row.append(data);
            
            data = $("<td>").click( { id: areaRoutes[x].routeid }, viewRouteInfo);
            data.text(areaRoutes[x].grade);
            row.append(data);
            
            data = $("<td>").click( { id: areaRoutes[x].routeid }, viewRouteInfo);
            switch(areaRoutes[x].seriousness) {
                case "1":
                    data.append($("<i>").addClass("fa fa-smile-o green"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-meh-o amber"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-frown-o red"));
            }
            row.append(data);
            
            data = $("<td>").click( { id: areaRoutes[x].routeid }, viewRouteInfo);
            data.text(areaRoutes[x].stars);
            row.append(data);
            
            data = $("<td>").click( { id: areaRoutes[x].routeid }, viewRouteInfo);
            data.text(areaRoutes[x].length + "m");
            row.append(data);

            data = $("<td>");
            data.append($("<a>").attr("href", "crag.php?cragid=" + areaRoutes[x].cragid).text(areaRoutes[x].cragName));
            row.append(data);
            
            data = $("<td>").click( { id: areaRoutes[x].routeid }, viewRouteInfo);
            data.append($("<div>").addClass("firstascent").text(areaRoutes[x].firstascent));
            row.append(data);
            
            table.append(row);
        }
    }
    else
        table.append($("<p>").text("No routes"));
    
    // show table
    $('#routes').html(table);
    $('#gradefilter').html(gradeFilter('area'));
}

// shows routes on crag pages
function viewCragRoutes(cragRoutes) {
    var x, row, data;
    var table = $("<table></table");
    
    // build table
    if (cragRoutes.length > 0) {
        row = $("<tr>");
        row.append($("<th>"));
        row.append($("<th>").text("Name").attr("onclick", "sortRoutes('crag','name')"));
        row.append($("<th>").text("Grade").attr("onclick", "sortRoutes('crag','grade')"));
        row.append($("<th>"));
        row.append($("<th>").text("Stars").attr("onclick", "sortRoutes('crag','stars')"));
        row.append($("<th>").text("Length").attr("onclick", "sortRoutes('crag','length')"));
        row.append($("<th>").text("First Ascent").attr("onclick", "sortRoutes('crag','firstascent')"));
        row.append($("<th>").text("Sector").attr("onclick", "sortRoutes('crag','sector')"));
        
        
        // show editing options if user logged in
        if (auth === true)
            row.append($("<th>"));
        
        table.append(row);
            
        for (x in cragRoutes) {
            row = $("<tr>").addClass("pointer").attr("id", cragRoutes[x].routeid);
            data = $("<td>").click( { "id" : cragRoutes[x].routeid }, viewRouteInfo);
            
            switch(cragRoutes[x].discipline) {
                case "1":
                    data.append($("<i>").addClass("fa fa-circle-thin fa-lg"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-circle fa-lg yellow"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-circle fa-lg"));
            }
            row.append(data);
            
            data = $("<td>").click({ id : cragRoutes[x].routeid }, viewRouteInfo);
            data.text(cragRoutes[x].name);
            row.append(data);
            
            data = $("<td>").click( { id: cragRoutes[x].routeid }, viewRouteInfo);
            data.text(cragRoutes[x].grade);
            row.append(data);
            
            data = $("<td>").click( { id: cragRoutes[x].routeid }, viewRouteInfo);
            switch(cragRoutes[x].seriousness) {
                case "1":
                    data.append($("<i>").addClass("fa fa-smile-o green"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-meh-o amber"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-frown-o red"));
            }
            row.append(data);
            
            data = $("<td>").click( { id: cragRoutes[x].routeid }, viewRouteInfo);
            data.text(cragRoutes[x].stars);
            row.append(data);
            
            data = $("<td>").click( { id: cragRoutes[x].routeid }, viewRouteInfo);
            data.text(cragRoutes[x].length + "m");
            row.append(data);
            
            data = $("<td>").click( { id: cragRoutes[x].routeid }, viewRouteInfo);
            data.append($("<div>").addClass("firstascent").text(cragRoutes[x].firstascent));
            row.append(data);
            
            data = $("<td>").click( { id: cragRoutes[x].routeid }, viewRouteInfo);
            data.text(cragRoutes[x].sector);
            row.append(data);
            
            if (auth === true) {
                data = $("<td>");
                data.append($("<a>").addClass("fa fa-edit fa-lg").attr("href", "admin/route.php?action=edit&routeid=" + cragRoutes[x].routeid))
                data.append(" ");
                data.append($("<a>").addClass("fa fa-trash-o fa-lg").attr("href", "admin/route.php?action=delete&routeid=" + cragRoutes[x].routeid))
                row.append(data);
            }
            
            table.append(row);
        }
    }
    else
        table.append($("<p>").text("No routes"));
    
    $('#routes').html(table);
    $('#gradefilter').html(gradeFilter('crag'));
}

function viewAllRoutes(page) {
    var div;
    
    div = $("<div>").addClass("center");
    div.append($("<i>").addClass("fa fa-circle-o-notch fa-spin fa-5x"));
    $("#routes").html(div);
    
    routes = allRoutes.slice();
    
    if (page == 'area') viewAreaRoutes(routes);
    else if (page == 'crag') viewCragRoutes(routes);
}

// sort routes depending on column
function sortRoutes(page, sort) {
    switch (sort) {
        case 'name':
            routes.sort(function (a, b) {
                var x = a.name.toLowerCase();
                var y = b.name.toLowerCase();
                if (x < y) return -1;
                if (x > y) return 1;
                return 0;
            });
            break;
        
        case 'grade':
            var tradRoutes, sportRoutes, boulderProblems, x;
            
            tradRoutes = getTradRoutes(routes);
            sportRoutes = getSportRoutes(routes);
            boulderProblems = getBoulderProblems(routes);
            
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
            
            sportRoutes.sort(function(a, b) { 
                if (a.grade < b.grade) return -1;
                else if (a.grade > b.grade) return 1;
                else return 0;
            });
            
            boulderProblems.sort(function(a, b) {
                if (a.grade == "VB") return -1;
                else if (b.grade == "VB") return 1;
                else if (a.grade < b.grade) return -1;
                else if (a.grade > b.grade) return 1;
                else return 0;
            });
            
            routes = [];
            
            for (x in tradRoutes)
                routes.push(tradRoutes[x]);
                
            for (x in sportRoutes)
                routes.push(sportRoutes[x]);
                
            for (x in boulderProblems)
                routes.push(boulderProblems[x]);
            
            break;
            
        case 'stars':
            routes.sort(function(a, b) { return b.stars.length - a.stars.length });
            break;        
        
        case 'length':
            routes.sort(function(a, b) { return a.length - b.length });
            break;
        
        case 'firstascent':
            routes.sort(function(a, b) {
                var x = a.firstascent.toLowerCase();
                var y = b.firstascent.toLowerCase();
                if (x < y) return -1;
                if (x > y) return 1;
                return 0;
            });
            break;
        
        case 'sector':
            routes.sort(function(a, b) {
                var x = a.sector.toLowerCase();
                var y = b.sector.toLowerCase();
                if (x < y) return -1;
                if (x > y) return 1;
                return 0;
            });
            break;
        
        case 'crag':
            routes.sort(function(a, b) {
                var x = a.cragName.toLowerCase();
                var y = b.cragName.toLowerCase();
                if (x < y) return -1;
                if (x > y) return 1;
                return 0;
            });
            break;
    }

    if (page == 'area') viewAreaRoutes(routes);
    else if (page == 'crag') viewCragRoutes(routes);
}

// extracts trad routes from a given arrary of routes
function getTradRoutes(routes) {
    var x, tradRoutes = [];
    
    for (x in routes) {
        if (routes[x].discipline == 1) 
            tradRoutes.push(routes[x]);
    }
    
    return tradRoutes;
}

// extracts sport routes from a given arrary of routes
function getSportRoutes(routes) {
    var x, sportRoutes = [];
    
    for (x in routes) {
        if (routes[x].discipline == 2) {
            sportRoutes.push(routes[x]);
        }
    }
    
    return sportRoutes;
}

// extracts boulder problems from a given arrary of routes
function getBoulderProblems (routes) {
    var x, boulderProblems = [];
    
    for (x in routes) {
        if (routes[x].discipline == 3) {
            boulderProblems.push(routes[x]);
        }
    }
    
    return boulderProblems;
}

// filters routes for british trad grading
function trad(page) {
    routes = getTradRoutes(allRoutes);
    
    if (page == 'crag') viewCragRoutes(routes);
    else if (page == 'area') viewAreaRoutes(routes);
}

// filters routes for french grading
function sport(page) {
    routes = getSportRoutes(allRoutes);
    
    if (page == 'crag') viewCragRoutes(routes);
    else if (page == 'area') viewAreaRoutes(routes);
}

// filters routes for font grading
function bouldering(page) {
    routes = getBoulderProblems(allRoutes);
    
    if (page == 'crag') viewCragRoutes(routes);
    else if (page == 'area') viewAreaRoutes(routes);
}

// helper function for sorting british grades
function britishGrade(grade) {
    if (/^E$/.test(grade)) grade = 0;
    else if (/^M/.test(grade)) grade = 1;
    else if (/^D/.test(grade)) grade = 2;
    else if (/^HD/.test(grade)) grade = 3;
    else if (/^VD/.test(grade)) grade = 4;
    else if (/^HVD/.test(grade)) grade = 5;
    else if (/^MS/.test(grade)) grade = 6;
    else if (/^S/.test(grade)) grade = 7;
    else if (/^HS/.test(grade)) grade = 8;
    else if (/^MVS/.test(grade)) grade = 9;
    else if (/^VS/.test(grade)) grade = 10;
    else if (/^HVS/.test(grade)) grade = 11;
    else if (/^E1/.test(grade)) grade = 12;
    else if (/^E2/.test(grade)) grade = 13;
    else if (/^E3/.test(grade)) grade = 14;
    else if (/^E4/.test(grade)) grade = 15;
    else if (/^E5/.test(grade)) grade = 16;
    else if (/^E6/.test(grade)) grade = 17;
    else if (/^E7/.test(grade)) grade = 18;
    else if (/^E8/.test(grade)) grade = 19;
    else if (/^E9/.test(grade)) grade = 20;
    else if (/^E10/.test(grade)) grade = 21;
    else if (/^E11/.test(grade)) grade = 22;
    else if (/^MXS/.test(grade)) grade = 23;
    else if (/^XS/.test(grade)) grade = 24;
    else if (/^HXS/.test(grade)) grade = 25;
    return grade;
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
        var zoom = 5;
        var height = 500;
    }
    
    // set map options for single crag
    else if (location == 'crag') {
        cragList = [crag];
        location = crag.location.split(",");
        var latlng = new google.maps.LatLng(location[0], location[1]);
        var zoom = 15;
        var height = 300;
    }
    
    // set map options for area
    else {
        location = area.location.split(",");
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
    for (i in cragList) {
        if (cragList[i].location === "") {
            // skip
        }
        else {
            // get crag location
            var location = cragList[i].location.split(",");
            var latlng = new google.maps.LatLng(location[0], location[1]);
            
            // set marker
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: cragList[i].name
            });
            
            // set marker info window content
            contentString = '<div><a href="crag.php?cragid=' + cragList[i].cragid + '"><b><h3>' + cragList[i].name + '</h3></b></a></div>';
            contentString += '<div>' + cragList[i].description + '</div>';
            
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
        zoom: 5,
        center: defaultCenter,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });
    
    // add markers
    for (i in areaList) {
        if (areaList[i].location === "") {
            // skip
        }
        else {
            
            // get area location
            var location = areaList[i].location.split(",");
            var latlng = new google.maps.LatLng(location[0], location[1]);
            
            // set marker
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: areaList[i].name
            });
            
            // set marker info window content
            contentString = '<div><a href="area.php?areaid=' + areaList[i].areaid + '"><b><h3>' + areaList[i].name + '</h3></b></a></div>';
            contentString += '<div>' + areaList[i].description + '</div>';
            
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


function viewCragPhotos() {
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
        div.append($("<p>").append($("<h2>").text(area.name)));
        div.append($("<p>").append($("<h4>").text(area.description)));
        div.append($("<p>").append($("<h4>").text("Routes")));
    }
    
    else if (page == 'crag') {
        div.append($("<p>").append($("<h2>").text(crag.name)));
        div.append($("<p>").append($("<h4>").text(crag.description)));
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
    
    var area = $("#area").val();
    var crag = $("#crag").val();
    var route = $("#route").val();
    var grade = $("#grade").val();
    
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
        allRoutes = JSON.parse(data);
        $.getJSON("include/crag_json.php", function (data) {
            cragList = data;
            
            // assign crag name for each route
            for (x in allRoutes) {
                for (y in cragList) {
                    if(cragList[y].cragid == allRoutes[x].cragid) {
                        allRoutes[x].cragName = cragList[y].name;
                    }
                }
            }
            
            if (allRoutes !== 0) routes = allRoutes.slice();
            else routes = 0;
            
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
    
    viewAreaRoutes(allRoutes);
}

function gradeFilter(page) {
    var div, all, trad, sport, bouldering;
    
    div = ($("<div>"));
    all = $("<button>").addClass("btn").attr("onclick", "viewAllRoutes('" + page +"')").text("All");
    trad = $("<button>").addClass("btn").attr("onclick", "trad('" + page + "')").html('<i class="fa fa-circle-o"></i> Trad');
    sport = $("<button>").addClass("btn").attr("onclick", "sport('" + page + "')").html('<i class="fa fa-circle yellow"></i> Sport');
    bouldering = $("<button>").addClass("btn").attr("onclick", "bouldering('" + page + "')").html('<i class="fa fa-circle"></i> Bouldering');
    
    div.append(all);
    div.append(trad);
    div.append(sport);
    div.append(bouldering);
    return div;
}