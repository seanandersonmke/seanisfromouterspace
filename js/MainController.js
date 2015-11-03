function modal_function(src){
	$('.mainmodal .img').attr('src', src);
	$('.mainmodal').modal('show');
}
app.controller('MainController', ['$scope', '$timeout', 'services', '$sce', function($scope, $timeout, services, $sce) {
/*
 $timeout(function(){
 	angular.element('.home-background .home-title-a').removeClass('slideInDown');
 	angular.element('.home-background .home-title-a').addClass('slideOutUp');
 }, 1500);
  $timeout(function(){
 	angular.element('.home-background .home-title-a').addClass('hide');
 	angular.element('.home-background .home-title-b').removeClass('hide');
 	angular.element('.home-background .home-title-b').addClass('slideInDown');
 }, 2500);
  $timeout(function(){
 	angular.element('.home-background .home-title-b').removeClass('slideInDown');
 	angular.element('.home-background .home-title-b').addClass('slideOutUp');
 }, 4000);
  $timeout(function(){
 	angular.element('.home-background .home-title-b').addClass('hide');
 	angular.element('.home-background .moon').removeClass('hide');
 	angular.element('.home-background .moon').addClass('slideInRight');
 }, 4700);
*/	
	var data = {};
	data.fn = 'nasapic';
	$scope.img = '';
	$scope.title = '';
	$scope.details = '';
	services.getData(data).success(function(data){
		switch(data.media_type) {
		    case 'video':
		        $scope.img = $sce.trustAsResourceUrl(data.url);
				$scope.vid_show = true;
		        break;
		    case 'image':
		        $scope.img_show = true;
				$scope.img = data.url;
		        break;
		    default:
        	$scope.img_show = false;
        	$scope.vid_show = false;
		}
		$scope.title = data.title;
		$scope.details = data.explanation;
	});

}]);

app.controller('FilmController', ['$scope', '$timeout', '$interval', 'services', function($scope, $timeout, $interval, services) {
	$scope.movies = {};
	$scope.showing = 'true';
	$scope.searchterm = 'Star Wars';
	$scope.theSearch = function(){
		$scope.showing = 'true';
		services.getMovies($scope.searchterm).success(function(data){
			$scope.movies = data.results;
		});
	}
	$scope.theSearch();
	$scope.hideThis = function(id, film_id){
		$interval.cancel($scope.slider);
		var data = {};
		data.film_id = film_id;
		data.fn = 'single_movie';
		theSlider(id, data);
		$timeout(function(){
			$scope.showing = 'false';
			$scope.fadeout = 'false';
		}, 400);
		$timeout(function(){
			$scope.fadein = 'true';
			//$scope.fadeout = 'true';
		}, 400);
	}
	function theSlider(id, data){
		services.getData(data).success(function(data){
			$scope.single_movie = $scope.movies[id];
			$scope.image = data.backdrops;
			$scope.count_limit = data.backdrops.length;
			$scope.count = 1;
			$scope.slider = $interval(function(){
				$scope.count++; 
				if($scope.count >= $scope.count_limit){
					$scope.count = 0;
				}
			}, 4000);
		});
	}
	$scope.cancelSlider = function(){
		$interval.cancel($scope.slider);
	}
	$scope.startSlider = function(){
		$scope.slider = $interval(function(){
				$scope.count++; 
				if($scope.count >= $scope.count_limit){
					$scope.count = 0;
				}
			}, 4000);
	}
	$scope.countUp = function(){
		$scope.cancelSlider();
		$scope.count ++;
		if($scope.count >= $scope.count_limit){
			$scope.count = 0;
		}
		$scope.startSlider();
		console.log($scope.count_limit);
	}
	$scope.countDown = function(){
		$scope.cancelSlider();
		console.log($scope.count);
		$scope.count --;
		if($scope.count <= 0){
			$scope.count = 1;
		}
		$scope.startSlider();
	}
	$scope.theToggle = function(){
		$scope.fadeout = 'true';
		$scope.fadein = 'true';
		$timeout(function(){
			$scope.showing = 'true';
			$scope.fadein = 'false';
		
		}, 400);
	}
	$scope.inputFocus = function(){
		$scope.showing = 'true';
		$scope.fadeout = 'true';
		$scope.fadein = 'true';
	}
}]);
app.controller('NasaController', ['$scope', 'services', function($scope, services) {
	var data = {};
	data.fn = 'nasapic';
	services.getData(data).success(function(data){
		console.log(data);
		$scope.img = data.url;
		$scope.title = data.title;
		$scope.details = data.explanation;
	});
	var data = {};
	data.fn = 'twitter_live';

	var myLatlng = new google.maps.LatLng(data.reclat, data.reclong);

	services.getData(data).success(function(data){
		console.log(data);
//		var myLatlng = new google.maps.LatLng(data.reclat, data.reclong);

	});
/*
map = new google.maps.Map(document.getElementById("map"), mapOptions);
  var request = {
      location: myLatlng,
      radius: '500',
      types: [$scope.theType]
    };
  service = new google.maps.places.PlacesService(map);
  service.nearbySearch(request, callback);
   
}
*/

}]);