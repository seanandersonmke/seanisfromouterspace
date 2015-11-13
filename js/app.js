var app = angular.module('sample_app', ['ngRoute', 'ngTouch']).config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/maps', {
        templateUrl: 'templates/maps.html',
        controller: 'MapController'
      }).
      when('/', {
        templateUrl: 'templates/home.html',
      }).
      when('/portfolio', {
        templateUrl: 'templates/main.html',
        controller: 'MainController'
      }).
      when('/forms', {
        templateUrl: 'templates/forms.php',
        controller: 'FormController'
      }).
      when('/twitterfeed', {
        templateUrl: 'templates/twitterfeed.php',
       // controller: 'TwitterController'
      }).
      when('/filmsearch', {
        templateUrl: 'templates/filmsearch.html',
        controller: 'FilmController'
      }).
      when('/freelance-info', {
        templateUrl: 'templates/rates.html',
        controller: 'ContactFormController'
      })
  }]);

