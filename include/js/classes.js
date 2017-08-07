/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/js/prototypes.js
 * Class prototypes for Cragbook.
 */


var Cragbook = {
    
    // class prototype for Routes objects
    RouteList : function(jsonData) {
        this.all = jsonData;
        this.view;
        this.discipline = 'all';

        // sorts routes by field
        this.sort = function(sort) {
            switch (sort) {
                
                case 'name':
                    this.view.sort(function (a, b) {
                        var x = a.name.toLowerCase();
                        var y = b.name.toLowerCase();
                        if (x < y) return -1;
                        if (x > y) return 1;
                        return 0;
                    });
                    break;
                
                case 'grade':
                    var tradRoutes, sportRoutes, boulderProblems, x;
                    
                    switch(this.discipline) {
                        
                        case "all" :
                            tradRoutes = this.getTradRoutes(this.all);
                            sportRoutes = this.getSportRoutes(this.all);
                            boulderProblems = this.getBoulderProblems(this.all);
                            
                            tradRoutes = this.sortTradRoutes(tradRoutes);
                            sportRoutes = this.sortSportRoutes(sportRoutes);
                            boulderProblems = this.sortBoulderProblems(boulderProblems);
                            
                            this.view = [];
                            
                            for (x in tradRoutes)
                                this.view.push(tradRoutes[x]);
                                
                            for (x in sportRoutes)
                                this.view.push(sportRoutes[x]);
                                
                            for (x in boulderProblems)
                                this.view.push(boulderProblems[x]);
                            
                            this.discipline = "all";
                            return this.view;

                        case "trad" :
                            tradRoutes = this.sortTradRoutes(this.view);
                            
                            return this.view = tradRoutes;

                        case "sport" :
                            sportRoutes = this.sortSportRoutes(this.view);
                            
                            return this.view = sportRoutes;

                        case "boulder" :
                            boulderProblems = this.sortBoulderProblems(this.view);
                            
                            return this.view = boulderProblems;
                    }
                    break;
                    
                case 'stars':
                    this.view.sort(function(a, b) { return b.stars.length - a.stars.length });
                    break;        
                
                case 'length':
                    this.view.sort(function(a, b) { return a.length - b.length });
                    break;
                
                case 'firstascent':
                    this.view.sort(function(a, b) {
                        var x = a.firstascent.toLowerCase();
                        var y = b.firstascent.toLowerCase();
                        if (x < y) return -1;
                        if (x > y) return 1;
                        return 0;
                    });
                    break;
                
                case 'sector':
                    this.view.sort(function(a, b) { return a.orderid - b.orderid });
                    break;
                
                case 'crag':
                    this.view.sort(function(a, b) {
                        var x = a.cragName.toLowerCase();
                        var y = b.cragName.toLowerCase();
                        if (x < y) return -1;
                        if (x > y) return 1;
                        return 0;
                    });
                    break;
            }
            
            return this.view;
        }
        
        // return all routes
        this.getAllRoutes = function() {
            var x;
            
            this.discipline = "all";
            
            if (this.all == 0)
                return this.view = 0;
            else
                return this.view = this.all.slice();
        }
        
        // extracts trad routes from array of all routes
        this.getTradRoutes = function() {
            var x;
            
            this.view = [];
            
            for (x in this.all) {
                if (this.all[x].discipline == 1) 
                    this.view.push(this.all[x]);
            }
            
            this.discipline = "trad";
            return this.view;
        }
        
        // extracts sport routes from a given arrary of routes
        this.getSportRoutes = function() {
            var x;
            
            this.view = [];
            
            for (x in this.all) {
                if (this.all[x].discipline == 2 || this.all[x].discipline == 4) {
                    this.view.push(this.all[x]);
                }
            }
            
            this.discipline = "sport";
            return this.view;
        }
        
        // extracts boulder problems from a given arrary of routes
        this.getBoulderProblems = function() {
            var x;
            
            this.view = [];
            
            for (x in this.all) {
                if (this.all[x].discipline == 3) {
                    this.view.push(this.all[x]);
                }
            }
            
            this.discipline = "boulder";
            return this.view;
        }
        
        this.sortTradRoutes = function(tradRoutes) {
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
            
            return tradRoutes;
            
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
        }
        
        this.sortSportRoutes = function(sportRoutes) {
            sportRoutes.sort(function(a, b) { 
                if (a.grade < b.grade) return -1;
                else if (a.grade > b.grade) return 1;
                else return 0;
            });
            
            return sportRoutes;
        }
        
        this.sortBoulderProblems = function(boulderProblems) {
            boulderProblems.sort(function(a, b) {
                if (a.grade == "VB") return -1;
                else if (b.grade == "VB") return 1;
                else if (a.grade < b.grade) return -1;
                else if (a.grade > b.grade) return 1;
                else return 0;
            });
            
            return boulderProblems;
        }
        
        this.gradeFilter = function(filter) {
            var x, routes = [];
            var pattern = new RegExp("^" + filter);

            switch(this.discipline) {
                case "trad" :
                    var tradRoutes = this.getTradRoutes(this.all);
                    tradRoutes = this.sortTradRoutes(tradRoutes);
                    
                    this.view = tradRoutes;
                    break;
                    
                case "sport" :
                    var sportRoutes = this.getSportRoutes(this.all);
                    sportRoutes = this.sortSportRoutes(sportRoutes);
                    
                    this.view = sportRoutes;
                    break;
                    
                case "boulder" :
                    var boulderProblems = this.getBoulderProblems(this.all);
                    boulderProblems = this.sortBoulderProblems(boulderProblems);
                    
                    this.view = boulderProblems;
                    break;
            }
            
            for (x in this.view) {
                if (pattern.test(this.view[x].grade)) {
                    routes.push(this.view[x]);
                }
            }
            
            return this.view = routes;
        }
    }
}

