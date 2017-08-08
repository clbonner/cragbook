/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/db.js
 * Javascript functions relating to database requests (aka AJAX).
 */
 


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
    var page = { data : "area" }, url = "include/route_json.php?areaid=" + areaid;

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
        Cragbook.routes.getAllRoutes();
        sortByCrag(page)
    });
}

// get routes for crag
function getCragRoutes(cragid) {
    var page = { data : "crag" }, url = "include/route_json.php?cragid=" + cragid;
    
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x center\"></i>");
    
    $.getJSON(url, function (data, status, xhr){
        Cragbook.routes = new Cragbook.RouteList(data);
        
        $('#gradefilter').html(gradeFilter('crag'));
        Cragbook.routes.getAllRoutes();
        sortBySector(page);
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


// get JSON data on areas and show map
function getAllAreasMap() {
    var url = "include/area_json.php";
    
    $.getJSON("include/auth_json.php", function (data, status, xhr) {
        Cragbook.auth = data;
        $.getJSON(url, function (data, status, xhr) {
            Cragbook.areaList = data;
            viewAreaMap();
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

// gets all routes from the database at a given grade
function getGrades(grade) {
    var page = { data : "area" }, viewpicker, routes, filter, modal, data, div, url = "include/search_json.php";

    // get search results
    div = $("<div>");
    div.append($("<i>").addClass("fa fa-print btn btn-border").attr("onclick", "printRoutes('search')"));

    div.append($("<div>").addClass("center"));
    div.append($("<i>").addClass("fa fa-circle-o-notch fa-spin fa-5x"));
    $("#searchresults").html(div);

    search = { "grade" : grade };
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
            
            div = $("<div>").addClass("heading").attr("id", "title").text("Showing all grades at " + grade);
            
            viewpicker = $("<div>").attr("id","viewpicker");
            viewpicker.append($("<button>").attr("id","printview").addClass("fa fa-print btn-picker").attr("onclick", "printRoutes('grades')"));
            routes = $("<div>").addClass("panel").attr("id", "routes");
            modal = $("<div>").addClass("modal").attr("id", "modal");
            
            
            $("#grades").addClass("panel").html(div);
            $("#grades").append(viewpicker);
            $("#grades").append(routes);
            $("#grades").append(modal);
            
            Cragbook.routes.getAllRoutes();
            sortByGrade(page)
        });
    });
}