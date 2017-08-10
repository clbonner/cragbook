/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/interface.js
 * Javascript functions relating to changing the DOM/user interface.
 */
 
function sortByName(page) {
    viewRoutes(page.data, Cragbook.routes.sort('name')); 
    $("th:contains('Name')").append($("<i>").addClass('fa fa-sort-desc'));
}

function sortByGrade(page) {
    viewRoutes(page.data, Cragbook.routes.sort('grade')); 
    $("th:contains('Grade')").append($("<i>").addClass('fa fa-sort-desc'));
}

function sortByStars(page) {
    viewRoutes(page.data, Cragbook.routes.sort('stars')); 
    $("th:contains('Stars')").append($("<i>").addClass('fa fa-sort-desc'));
}

function sortByLength(page) {
    viewRoutes(page.data, Cragbook.routes.sort('length')); 
    $("th:contains('Length')").append($("<i>").addClass('fa fa-sort-desc'));
}

function sortBySector(page) {
    viewRoutes(page.data, Cragbook.routes.sort('sector')); 
    $("th:contains('Sector')").append($("<i>").addClass('fa fa-sort-desc'));
}

function sortByCrag(page) {
    viewRoutes(page.data, Cragbook.routes.sort('crag')); 
    $("th:contains('Crag')").append($("<i>").addClass('fa fa-sort-desc'));
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

// gets JDON data for a route and display route info
function viewRouteInfo(route) {
    var x, discipline, seriousness, url = "include/route_json.php?routeid=" + route.data.id;
    var div = $("<div>").attr("id", "routeinfowindow");

    $("#modal").html(div);
    
    for (x in Cragbook.routes.view) {
        if (Cragbook.routes.view[x].routeid == route.data.id) {
            switch (Cragbook.routes.view[x].discipline) {
                case '1':
                    discipline = $("<i>").addClass("fa fa-circle blue").html("&nbsp");
                    break;
                case '2':
                    discipline = $("<i>").addClass("fa fa-circle green").html("&nbsp");
                    break;
                case '3':
                    discipline = $("<i>").addClass("fa fa-circle amber").html("&nbsp");
            }
            
            $('#routeinfowindow').append($("<h3>").text(Cragbook.routes.view[x].name + ' ' + Cragbook.routes.view[x].stars).prepend(discipline));
            $('#routeinfowindow').append($("<p>").text(Cragbook.routes.view[x].description));
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
    
    div = $("<div>").attr("id", "craginfo").addClass("heading");
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
        row.append($("<th>").append($("<i>").addClass("fa fa-sort-amount-asc")));
        row.append($("<th>").text("Name").click("area", sortByName));
        row.append($("<th>").text("Grade").click("area", sortByGrade));
        row.append($("<th>").text("Stars").click("area", sortByStars));
        row.append($("<th>").text("Length").click("area", sortByLength));
        row.append($("<th>").text("Crag").click("area", sortByCrag));
        table.append(row);
        
        for (x in routes) {
            row = $("<tr>").addClass("pointer").attr("id", routes[x].routeid);
            data= $("<td>").click( { "id" : routes[x].routeid }, viewRouteInfo);
            
            switch(routes[x].discipline) {
                case "1":
                    data.append($("<i>").addClass("fa fa-circle fa-lg blue"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-circle fa-lg green"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-circle fa-lg amber"));
            }
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            
            data.text(routes[x].name);
            row.append(data);

            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
                
            data.text(routes[x].grade);
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
        row.append($("<th>").append($("<i>").addClass("fa fa-sort-amount-asc")));
        row.append($("<th>").text("Name").click("crag", sortByName));
        row.append($("<th>").text("Grade").click("crag", sortByGrade));
        row.append($("<th>").text("Stars").click("crag", sortByStars));
        row.append($("<th>").text("Length").click("crag", sortByLength));
        row.append($("<th>").text("Sector").click("crag", sortBySector));

        // show editing options if user logged in
        if (Cragbook.auth === true)
            row.append($("<th>"));
        
        table.append(row);
            
        for (x in routes) {
            row = $("<tr>").addClass("pointer").attr("id", routes[x].routeid);
            
            data = $("<td>").click( { "id" : routes[x].routeid }, viewRouteInfo);
            
            switch(routes[x].discipline) {
                case "1":
                    data.append($("<i>").addClass("fa fa-circle fa-lg blue"));
                    break;
                case "2":
                    data.append($("<i>").addClass("fa fa-circle fa-lg green"));
                    break;
                case "3":
                    data.append($("<i>").addClass("fa fa-circle fa-lg amber"));
            }
            row.append(data);
            
            data = $("<td>").click({ id : routes[x].routeid }, viewRouteInfo);
            
            data.text(routes[x].name);
            row.append(data);
            
            data = $("<td>").click({ id : routes[x].routeid }, viewRouteInfo);
            
            data.text(routes[x].grade);
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.text(routes[x].stars);
            row.append(data);
            
            data = $("<td>").click( { id: routes[x].routeid }, viewRouteInfo);
            data.text(routes[x].length + "m");
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

    div = $("<h6>").text("Cragbook");
    
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
    else if (page == 'grades') {
        div.append($("<p>").append($("#title").html()));
    }
    
    printWindow.document.write(head);
    printWindow.document.write(div.html());

    printWindow.document.write($("#routes").html());
    printWindow.document.write("</body><script>window.print();window.close();</script></html>");
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
    var div = $("<div>"), page = { data : "area" };
    
    if (search.area == "") search.area = "None";
    if (search.crag == "") search.crag = "None";
    if (search.route == "") search.route = "None";
    if (search.grade == "") search.grade = "None";

    div.append($("<div>").addClass("heading").text("Search Results")
        .append($("<p>").attr("id", "searchoptions").text("Area: " + search.area + " / Crag: " + 
        search.crag + " / Route: " + search.route + " / Grade: " + search.grade)));

    div.append($("<div>").addClass("panel").attr("id", "routes"));
    div.append($("<div>").addClass("modal").attr("id", "modal"));
    
    $("#searchresults").addClass("panel").html(div);
    
    Cragbook.routes.getAllRoutes();
    sortByCrag(page);
}


function gradeFilter(page) {
    var div, all, trad, sport, bouldering, filter;
    
    div = ($("<div>"));
    all = $("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.getAllRoutes());").text("All");
    trad = $("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.getTradRoutes());").html('<i class="fa fa-circle blue"></i> Trad');
    sport = $("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.getSportRoutes());").html('<i class="fa fa-circle green"></i> Sport');
    bouldering = $("<button>").addClass("btn").attr("onclick", "viewRoutes('" + page + "', Cragbook.routes.getBoulderProblems());").html('<i class="fa fa-circle amber"></i> Bouldering');
    filter = $("<div>").attr("id", "filter");
    
    div.append(all);
    div.append(trad);
    div.append(sport);
    div.append(bouldering);
    div.append(filter);
    return div;
}