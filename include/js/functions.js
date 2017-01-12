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
    var x, table;
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
    
    // display table
    $("#routes").html(table);
}

// gets routes from route_update.php as JSON and stores in routes
function getRoutes(crag) {
    var url = "route_update.php?cragid=" + crag;
    
    // get data from server then update page
    $.getJSON(url, function (data,status,xhr){
        routes = data;
        
        // convert orderid to int
        for (var x in routes) {
            routes[x].orderid = parseInt(routes[x].orderid);
        }
        
        showRoutes();
    });
}

// send route order data back to database
function updateRoutes(returnurl){
    var url = "route_update.php";
    
    // inform the user something is happening
    $("#routes").html("<i class=\"fa fa-circle-o-notch fa-spin fa-5x w3-display-middle\"></i>");
    
    // send data back to server
    $.getJSON(url, JSON.stringify(routes), function (data, status, xhr){
        console.log(returnurl);
        window.location.assign(returnurl);
    });
}

// moves a route down in the table
function routeDown(dom) {
    var x, routeName = dom[1].firstChild.innerText;

    // swap orderid of routes
    for (x in routes) {
        if (routeName == routes[x].name && routes[x].orderid < routes.length){
            routes[x].orderid = ++routes[x].orderid;
            routes[++x].orderid = --routes[x].orderid;
            break;
        }
    }
    
    // update table
    showRoutes();
}

// moves a route up in the table
function routeUp(dom) {
    var x, routeName = dom[1].firstChild.innerText;

    // swap orderid of routes
    for (x in routes) {
        if (routeName == routes[x].name && routes[x].orderid > 1){
            routes[x].orderid = --routes[x].orderid;
            routes[--x].orderid = ++routes[x].orderid;
            break;
        }
    }
    
    // update table
    showRoutes();
}