/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * Javascript functions for Cragbook
 */

// global variables
var routes, returnurl;

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
    var url = "route_update.php?cragid=" + crag;
    returnurl = '../crag_info.php?cragid=' + crag;
    
    $.getJSON(url, function (data,status,xhr){
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
    var url = "route_update.php";
    
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