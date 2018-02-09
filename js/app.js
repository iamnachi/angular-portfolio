//declare main module and its dependencies
var app = angular.module("portfolio", [ 'ngAnimate','ngRoute','angularUtils.directives.dirPagination','ui.bootstrap']);

app.provider('sampleProvider', function() { // provider can't recieve primitives, it can be used in the config phase
	//return "hello"; it will return error
	this.$get = function () {
		return 'testing provider';		
	}		
	/*
	return {
		$get: function() {
			return 'test'
		}
	}
	*/
});

app.config(['$routeProvider' ,'$locationProvider', function($routeProvider, $locationProvider, sampleProviderProvider) {
	
	//console.log(sampleProviderProvider);
	
	$routeProvider.when('/About', {
        templateUrl:'partials/about.html'
	}).when('/Experience', {
		templateUrl:'partials/experience.html'
	}).when('/Education', {
		templateUrl:'partials/education.html'
	}).when('/Library', {
		templateUrl:'partials/library.html'
	}).when('/Cart', {
		templateUrl:'partials/cart.html'
	}).when('/Checkout', {
		templateUrl:'partials/checkout.html'
	}).when('/Orders', {
		templateUrl:'partials/orders.html'
	}).when('/Broadcast', {
		templateUrl:'partials/broadcast.html'
	}).when('/Quiz', {
		templateUrl:'partials/quiz.html'
	}).otherwise({
        redirectTo: '/About'
    });
	
	// use the HTML5 History API
    $locationProvider.html5Mode(true);
}]);



