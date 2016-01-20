'use strict';

/* Controllers */

var app = angular.module('front', [
	'ngRoute',
	'ngAnimate'
]);

// manage routes
app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
    // Routing system
    $routeProvider
        .when('/login', {
            templateUrl: Routing.generate('login'),
            controller: 'AuthController'
        })
        .when('/logout', {
            templateUrl: Routing.generate('ard_backend_checkout'),
            controller: 'LogoutController'
        })
        .when('/', {
            templateUrl: Routing.generate('ard_backend_checkout'),
            controller: 'CheckoutController'
        })
        .otherwise({
            redirectTo: '/login'
        });
        $locationProvider.html5Mode(true);
}]);

// GlobalController
app.controller('GlobalController', ['$scope', '$http', function GlobalController($scope, $http) {
    
}
]);

// AuthController
app.controller('AuthController', ['$scope', '$http', function AuthController($scope, $http) {
    
}
]);

// LogoutController
app.controller('LogoutController', ['$scope', '$http', function LogoutController($scope, $http) {
	console.log("logout");
    window.location = '/logout';
}
]);

// CheckoutController
app.controller('CheckoutController', ['$scope', '$http', function CheckoutController($scope, $http) {
    
}
]);