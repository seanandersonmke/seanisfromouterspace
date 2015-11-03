app.controller('MapController', ['$scope', '$timeout', '$compile', 'services', '$swipe', function($scope, $timeout, $compile, services, $swipe) {
 var map;
 $scope.review_index = 0;
 $scope.hidden = false;
 $scope.location_reviews = {};
 var service;
 $scope.mapType = {
  restaurant:'Restaurant', bar:'Bar', grocery_or_supermarket: 'Grocery or supermarket', hospital: 'Hospital', atm: 'ATM', bank:'Bank', gas_station: 'Gas station'
 };
 $scope.theType = [];
 
 $scope.getLocation = function(){
    $scope.showSpinner = true;
    if (navigator.geolocation) {
       navigator.geolocation.getCurrentPosition(showPosition, showError);
       
    } else {

        
    }
  }
function showPosition(position) {
  
  var mapPos = {lat: position.coords.latitude, lng: position.coords.longitude };
  $scope.showSpinner = false;
 // services.getMap(mapPos).success(function(data){
 // console.log(data);
  setMap(mapPos);
 //})
 // console.log(map); 
}
function showError(error){
 // console.log(error);
var mapPos = {lat: 43.0500, lng: -87.9500};
//console.log($scope.map);
  //console.log(map);
  angular.element('body').prepend("<h4 class='well well-sm'>You did not allow your location to be used, so the map defaults to Milwaukee.</h4>");
  $scope.showSpinner = false;

  setMap(mapPos);
}
function setMap(mapPos){
  //console.log(map);
  var myLatlng = new google.maps.LatLng(mapPos.lat, mapPos.lng);

  var styles =[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]
  var mapOptions = {
    zoom: 13,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    styles:styles,
  };

  map = new google.maps.Map(document.getElementById("map"), mapOptions);
  var request = {
      location: myLatlng,
      radius: '500',
      types: [$scope.theType]
    };
  service = new google.maps.places.PlacesService(map);
  service.nearbySearch(request, callback);
   
}
function callback(results, status) {
 // console.log(results);
 // console.log(status);
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    angular.forEach(results, function(value, key){
      addMarker(value);

    });
  }
}

function addMarker(place) {
 // console.log(place.name);
   var request = {
    placeId: place.place_id
  };
  
  //var infowindow = new google.maps.InfoWindow();
  var iwContent = "<div class='location_popup'><h4>"+place.name+"</h4><p>"+place.vicinity+"</p></div>";
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    title: place.name,
    icon: {
      url: 'img/icon.png',
    //  anchor: new google.maps.Point(10, 10),
     // scaledSize: new google.maps.Size(10, 17)
    }
  });
  google.maps.event.addListener(marker, 'click', function () {
     // infowindow.setContent(iwContent);
     // infowindow.open(map, marker);
        populateData(request);
    
      
      //$scope.getSingleLocation();
    });
}
  function populateData(request){
     service.getDetails(request, function (detail, status) {
      $scope.$apply(function() {
            $scope.location_name = detail.name;
            $scope.location_address = detail.formatted_address;
            $scope.location_phone = detail.formatted_phone_number;
            $scope.location_reviews = detail.reviews;
            $scope.change_class = {slideInRight:true};
            $scope.change_review_class = {slideInLeft:true};
            $timeout(function(){
              $scope.change_class = {slideInRight:false};
              $scope.change_review_class = {slideInLeft:false};
            }, 800);
        });
    }); 
  }
  $scope.getLocation();
$scope.next = function() {

         $scope.change_review_class = {slideOutLeft:true};
 $timeout(function(){
  $scope.change_review_class = {slideOutLeft:false, slideInRight:true};
  }, 700);
      $timeout(function(){
        $scope.change_review_class = {slideInRight:false};
      }, 1600);
  
        if ($scope.review_index >= $scope.location_reviews.length -1) {
            $scope.review_index = 0;
        }
        else {
            $scope.review_index ++;
        }
  };
  $scope.previous = function() {
    $scope.change_review_class = {slideInLeft:true};
      $timeout(function(){
        $scope.change_review_class = {slideInLeft:false};
      }, 800);
        if ($scope.review_index >= $scope.location_reviews.length -1) {
            $scope.review_index = 0;
        }
        else {
            $scope.review_index --;
        }
  };
}]);