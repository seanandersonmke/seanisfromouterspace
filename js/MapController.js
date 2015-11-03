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

    if (navigator.geolocation) {
      $scope.showSpinner = true;
       navigator.geolocation.getCurrentPosition(showPosition, showError);
    }
  }
function showPosition(position) {
  var mapPos = {lat: position.coords.latitude, lng: position.coords.longitude };
  $scope.$apply(function() {
    $scope.showSpinner = false;
  });
  //angular.element('.loader').hide();
 // services.getMap(mapPos).success(function(data){
 // console.log(data);
  setMap(mapPos);
 //})
 // console.log(map); 
}
function showError(error){
 // console.log(error);
 $scope.$apply(function() {
    $scope.showSpinner = false;
  });
  var mapPos = {lat: 43.0500, lng: -87.9500};
//console.log($scope.map);
  //console.log(map);
  angular.element('body').prepend("<h4 class='well well-sm'>You did not allow your location to be used, so the map defaults to Milwaukee.</h4>");
//  $scope.showSpinner = false;

  setMap(mapPos);
}
function setMap(mapPos){
  $scope.showSpinner = true;
  var myLatlng = new google.maps.LatLng(mapPos.lat, mapPos.lng);
  var styles = [{"featureType":"administrative","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#AB2323"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#0066ff"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"hue":"#F8E0C8"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#0066ff"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#0066ff"},{"lightness":4}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#AB2323"}]}];
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
  $scope.$apply(function() {
    $scope.showSpinner = false;
  }); 
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    angular.forEach(results, function(value, key){
      addMarker(value);
    });
  }
}

function addMarker(place) {
   var request = {
    placeId: place.place_id
  };
  
  //var infowindow = new google.maps.InfoWindow();
  var iwContent = "<div class='location_popup'><h4>"+place.name+"</h4><p>"+place.vicinity+"</p></div>";
  var url;
  switch(place.types[0]){
    case 'atm':
      url = 'img/atm-2.png';
      break;
    case 'bank':
      url = 'img/bank.png';
      break;
    case 'bar':
      url = 'img/bar.png';
      break;
    case 'gas_station':
      url = 'img/gas_cylinder1.png';
      break;
    case 'grocery_or_supermarket':
      url = 'img/grocery.png';
      break;
    case 'hospital':
      url = 'img/hospital-building.png';
      break;
    case 'restaurant':
      url = 'img/restaurant.png';
      break;
    default:
      url = 'img/bigcity.png';
  }
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    title: place.name,
    icon: {
      url: url,
    //  anchor: new google.maps.Point(10, 10),
     // scaledSize: new google.maps.Size(10, 17)
    }
  });
  google.maps.event.addListener(marker, 'click', function () {
     // infowindow.setContent(iwContent);
     // infowindow.open(map, marker);
        populateData(request);
    });
}
  function populateData(request){
    $scope.review_index = 0;
     service.getDetails(request, function (detail, status) {
      $scope.$apply(function() {
        $scope.location_name = detail.name;
        $scope.location_address = detail.formatted_address;
        $scope.location_phone = detail.formatted_phone_number;
        $scope.location_reviews = detail.reviews;
        $scope.review_count = $scope.location_reviews.length;
        $scope.change_class = {fadeIn:true};
        if(angular.isDefined($scope.location_reviews) == false){
          $scope.change_review_class = {hide: true};
        }else{
          $scope.change_review_class = {fadeIn:true};
          $timeout(function(){
            $scope.change_class = {fadeIn:false};
            $scope.change_review_class = {fadeIn:false};
          }, 400);
        } 
    });
  }); 
}
  $scope.getLocation();

$scope.next = function() {
  $scope.change_review_class = {fadeIn:true};
  
  $timeout(function(){
    $scope.change_review_class = {fadeIn:false};
  }, 400);
  if ($scope.review_index >= $scope.location_reviews.length -1) {
    $scope.review_index = $scope.location_reviews.length - 1;
  }else {
    $scope.review_index ++;
    }
};
$scope.previous = function() {
  $scope.change_review_class = {fadeIn:true};
    $timeout(function(){
      $scope.change_review_class = {fadeIn:false};
    }, 400);
    if ($scope.review_index <= 0) {
      $scope.review_index = 0;
    }else {
      $scope.review_index --;
    }
  };
}]);

app.controller('FormController', ['$scope', 'services', function($scope, services) {
  $scope.pass = {};
  $scope.user = {};
  $scope.user.message = '<div>These tags will be removed by PHP</div>';
  $scope.passSubmit = true;
  if(angular.isDefined($scope.pwHash) == true){
    $scope.showPassArea = true;
  }else{
    $scope.showPassArea = false;
  }
  $scope.passwordVerify = function(){     
    if($scope.user.password == $scope.user.password_verify){
      $scope.valColor = true;
      $scope.passSubmit = false;
    }else{
      $scope.valColor = false;
      $scope.passSubmit = true;
    } 
  }
  $scope.formSubmit = function(data){
    data.fn = 'submit_form';
    services.getData(data).success(function(data){
      $scope.errors = data.errors;
      $scope.pass.pwHash = data.e_pass;
      $scope.pass.message = data.message;
      if(angular.isDefined($scope.errors) == false){
        $scope.showPassArea = true;
      }
    
    });
  }
  $scope.passSubmitFn = function(data){
    data.fn = 'pass_submit';
    services.getData(data).success(function(data){
      if(data.pwcheck == 1){
        $scope.checkColor = true;
      }else{
        $scope.checkColor = false;
      }
    });
  }
}]);
